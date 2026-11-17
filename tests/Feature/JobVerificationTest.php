<?php

namespace Tests\Feature;

use App\JobEngine\Ingest\DiscoveryRunner;
use App\JobEngine\Ingest\IngestService;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Verify\VerificationService;
use App\Models\JobSource;
use App\Models\Vacancy;
use App\Models\VacancyListing;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use App\Jobs\DiscoverSourceJob;
use Tests\TestCase;

class JobVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
        JobSource::query()->update(['min_delay_ms' => 0]);
        Http::preventStrayRequests();
    }

    private function storeDealls(string $id, string $title = 'Staff Admin Gudang'): VacancyListing
    {
        app(IngestService::class)->ingest(JobSource::where('slug', 'dealls')->first(), new ScrapedJob(
            source: 'dealls', url: "https://dealls.com/loker/{$id}", externalId: $id, title: $title, company: 'Sumber Rejeki', city: 'Bekasi', extractionMethod: 'jsonld',
        ));
        $listing = VacancyListing::where('external_id', $id)->firstOrFail();
        $listing->update(['next_check_at' => now()->subMinute()]);

        return $listing;
    }

    private function page(array $posting): string
    {
        return '<script type="application/ld+json">'.json_encode(['@type' => 'JobPosting', 'hiringOrganization' => ['name' => 'Sumber Rejeki']] + $posting).'</script>';
    }

    public function test_strong_and_medium_signals_follow_the_state_machine(): void
    {
        // Distinct jobs (identical ones would be merged into one vacancy by the matcher).
        $gone = $this->storeDealls('gone', 'Kasir');
        $expired = $this->storeDealls('expired', 'Staff Admin Gudang');
        $removed = $this->storeDealls('removed', 'Sopir Pengiriman');
        $open = $this->storeDealls('open', 'Staff Admin Gudang Shift Malam');
        $blocked = $this->storeDealls('blocked', 'Teknisi Listrik');
        Http::fake([
            'dealls.com/robots.txt' => Http::response("User-agent: *\nAllow: /"),
            'dealls.com/loker/gone' => Http::response('Not found', 404),
            'dealls.com/loker/expired' => Http::response($this->page(['title' => 'Staff Admin Gudang', 'validThrough' => now()->subDays(3)->toDateString()])),
            'dealls.com/loker/removed' => Http::response('<html>Lowongan ini sudah tidak tersedia</html>'),
            'dealls.com/loker/open' => Http::response($this->page(['title' => 'Staff Admin Gudang Shift Malam', 'validThrough' => now()->addMonth()->toDateString()])),
            'dealls.com/loker/blocked' => Http::response('Forbidden', 403),
        ]);

        $counts = app(VerificationService::class)->run(10);

        $this->assertSame(['open' => 1, 'suspect' => 1, 'closed' => 2, 'blocked' => 1], $counts);
        $this->assertSame('closed', $gone->vacancy->fresh()->status);
        $this->assertSame('http_gone', $gone->vacancy->fresh()->closed_reason);
        $this->assertSame('closed', $expired->vacancy->fresh()->status);
        $this->assertSame('suspect', $removed->fresh()->status, 'one medium signal only makes it suspect');
        $this->assertSame('active', $open->vacancy->fresh()->status);
        $this->assertSame('active', $blocked->fresh()->status, 'a 403 is not evidence that a job closed');
        $this->assertTrue($blocked->fresh()->next_check_at->between(now()->addHours(5), now()->addHours(7)));
        $this->assertSame(5, DB::table('vacancy_checks')->count());

        // The suspect is re-checked: a second medium signal closes it.
        $removed->fresh()->update(['next_check_at' => now()->subMinute()]);
        app(VerificationService::class)->run(10);
        $this->assertSame('closed', $removed->fresh()->status);
        $this->assertSame('closed', $removed->vacancy->fresh()->status);
    }

    public function test_reused_urls_with_a_different_job_are_suspected(): void
    {
        $listing = $this->storeDealls('reused', 'Account Lead');
        Http::fake([
            'dealls.com/robots.txt' => Http::response("User-agent: *\nAllow: /"),
            'dealls.com/loker/reused' => Http::response($this->page(['title' => 'Marketing Strategist'])),
        ]);

        app(VerificationService::class)->run(5);

        $this->assertSame('suspect', $listing->fresh()->status);
        $this->assertContains('title_replaced', json_decode(DB::table('vacancy_checks')->value('signals'), true));
    }

    public function test_a_vacancy_stays_active_while_another_platform_still_lists_it(): void
    {
        $dealls = $this->storeDealls('dup', 'Senior Frontend Engineer');
        app(IngestService::class)->ingest(JobSource::where('slug', 'greenhouse')->first(), new ScrapedJob(
            source: 'greenhouse', url: 'https://job-boards.greenhouse.io/x/jobs/1', externalId: 'x-1', title: 'Sr. Frontend Engineer', company: 'Sumber Rejeki', city: 'Bekasi',
        ));
        $this->assertSame(1, Vacancy::count());
        Http::fake(['dealls.com/robots.txt' => Http::response(''), 'dealls.com/loker/dup' => Http::response('', 404)]);

        app(VerificationService::class)->run(5);

        $this->assertSame('closed', $dealls->fresh()->status);
        $this->assertSame('active', Vacancy::sole()->status, 'closed only when every source has closed it');
    }

    public function test_jobs_leaving_a_complete_feed_are_suspected_then_closed_and_reopen_if_they_return(): void
    {
        $feed = fn (array $ids) => Http::response(['jobs' => array_map(fn ($id) => [
            'id' => $id, 'title' => "Engineer {$id}", 'absolute_url' => "https://job-boards.greenhouse.io/xendit/jobs/{$id}",
            'location' => ['name' => 'Jakarta, Indonesia'], 'company_name' => 'Xendit',
        ], $ids)]);
        Http::fake([
            '*/robots.txt' => Http::response('', 404),
            'boards-api.greenhouse.io/v1/*' => Http::sequence()->pushResponse($feed([1, 2]))->pushResponse($feed([1]))->pushResponse($feed([1]))->pushResponse($feed([1, 2])),
        ]);
        $source = JobSource::where('slug', 'greenhouse')->first();
        $run = fn () => app(DiscoveryRunner::class)->run($source->fresh(), 50);
        $two = fn () => VacancyListing::where('external_id', 'xendit-2')->first();

        $run();
        $run();
        $this->assertSame('suspect', $two()->status);
        $run();
        $this->assertSame('closed', $two()->status);
        $this->assertSame('closed', $two()->vacancy->status);
        $run();
        $this->assertSame('active', $two()->status, 'reappearing in the feed reopens it');
    }

    public function test_source_health_and_adaptive_interval(): void
    {
        Http::fake([
            '*/robots.txt' => Http::response('', 404),
            'boards-api.greenhouse.io/v1/*' => Http::sequence()
                ->push(['jobs' => array_map(fn ($i) => ['id' => $i, 'title' => "Role {$i}", 'absolute_url' => "https://g.io/{$i}", 'location' => ['name' => 'Jakarta, Indonesia'], 'company_name' => 'Xendit'], range(1, 25))])
                ->push(['jobs' => []]),
        ]);
        $source = JobSource::where('slug', 'greenhouse')->first();

        app(DiscoveryRunner::class)->run($source, 100);
        $source->refresh();
        $this->assertSame(['ok', 360], [$source->health, $source->discovery_interval_minutes], '25 new jobs: crawl every 6 h');

        app(DiscoveryRunner::class)->run($source, 100);
        $source->refresh();
        $this->assertSame('degraded', $source->health);
        $this->assertStringContainsString('0 listings found', $source->health_reason);
        Http::assertNotSent(fn ($r) => str_contains($r->url(), 'api.telegram.org'));
    }

    public function test_tick_is_off_until_enabled(): void
    {
        Queue::fake();
        config(['job_sources.schedule_enabled' => false]);
        $this->artisan('jobs:tick')->expectsOutputToContain('nonaktif')->assertSuccessful();
        Queue::assertNothingPushed();

        config(['job_sources.schedule_enabled' => true]);
        $this->artisan('jobs:tick')->assertSuccessful();
        Queue::assertPushed(DiscoverSourceJob::class, fn ($job) => $job->queue === 'ingest');
    }
}
