<?php

namespace Tests\Feature;

use App\JobEngine\Promotion\PromoWriter as PromoWriterAlias;

use App\JobEngine\Ingest\IngestService;
use App\JobEngine\ScrapedJob;
use App\Jobs\DiscoverSourceJob;
use App\Livewire\Admin\JobEngine;
use App\Models\JobSource;
use App\Models\User;
use App\Models\Vacancy;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class JobEngineAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JobEngineReferenceSeeder::class);
        Http::fake();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_admin' => true]);
    }

    private function pendingPair(): int
    {
        $ingest = app(IngestService::class);
        $noCity = ['city' => null, 'locationText' => null, 'company' => 'Kredivo', 'companyDomain' => 'kredivo.com'];
        $ingest->ingest(JobSource::where('slug', 'dealls')->first(), new ScrapedJob(...['source' => 'dealls', 'url' => 'https://d/1', 'externalId' => 'D1', 'title' => 'Customer Service'] + $noCity));
        $ingest->ingest(JobSource::where('slug', 'kalibrr')->first(), new ScrapedJob(...['source' => 'kalibrr', 'url' => 'https://k/1', 'externalId' => 'K1', 'title' => 'Customer Service Officer'] + $noCity));

        return (int) DB::table('dedup_reviews')->where('status', 'pending')->value('id');
    }

    public function test_only_admins_can_open_it(): void
    {
        $this->actingAs(User::factory()->create())->get(route('admin.job-engine'))->assertForbidden();
        $this->actingAs($this->admin())->get(route('admin.job-engine'))->assertOk()->assertSee('Pantau Mesin');
        $this->actingAs($this->admin())->get(route('admin.vacancies'))->assertOk()->assertSee('Kelola Loker');
        $this->actingAs($this->admin())->get(route('admin.promotions'))->assertOk()->assertSee('Promosi Media Sosial');
        $this->actingAs(User::factory()->create())->get(route('admin.vacancies'))->assertForbidden();
    }

    public function test_reviewer_merges_or_separates_a_pending_pair(): void
    {
        $reviewId = $this->pendingPair();
        $this->assertSame(2, Vacancy::where('status', 'active')->count());

        Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\VacancyManager::class)
            ->set('tab', 'pairs')
            ->assertSee('Customer Service Officer')
            ->call('resolvePair', $reviewId, true);

        $this->assertSame(1, Vacancy::where('status', 'active')->count());
        $this->assertSame('merged_manual', DB::table('dedup_reviews')->where('id', $reviewId)->value('status'));
    }

    public function test_separating_keeps_both_vacancies(): void
    {
        $reviewId = $this->pendingPair();

        Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\VacancyManager::class)->call('resolvePair', $reviewId, false);

        $this->assertSame(2, Vacancy::where('status', 'active')->count());
        $this->assertSame('separate', DB::table('dedup_reviews')->where('id', $reviewId)->value('status'));
    }

    public function test_weekly_qa_sample_and_verdicts(): void
    {
        $this->pendingPair();
        $component = Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\VacancyManager::class)->set('tab', 'qa')->call('drawQaSample');

        $check = DB::table('vacancy_qa_checks')->first();
        $this->assertSame(now()->format('o-\WW'), $check->sample);
        $this->assertSame(2, DB::table('vacancy_qa_checks')->count());

        $component->set("qaNotes.{$check->id}", 'Lokasi salah')->call('judge', $check->id, 'wrong_data');
        $this->assertSame(['wrong_data', 'Lokasi salah'], [DB::table('vacancy_qa_checks')->where('id', $check->id)->value('verdict'), DB::table('vacancy_qa_checks')->where('id', $check->id)->value('note')]);
    }

    public function test_run_now_queues_discovery_and_toggle_switches_a_source(): void
    {
        Queue::fake();
        $source = JobSource::where('slug', 'pintarnya')->first();

        Livewire::actingAs($this->admin())->test(JobEngine::class)
            ->call('runNow', $source->id)
            ->call('toggle', $source->id);

        Queue::assertPushed(DiscoverSourceJob::class, fn ($job) => $job->sourceId === $source->id);
        $this->assertFalse($source->fresh()->is_active);
    }

    private function storeVacancy(string $id, string $title): \App\Models\Vacancy
    {
        app(IngestService::class)->ingest(JobSource::where('slug', 'dealls')->first(), new ScrapedJob(source: 'dealls', url: "https://dealls.com/loker/{$id}", externalId: $id, title: $title, company: 'Sumber Rejeki', city: 'Bekasi'));

        return \App\Models\Vacancy::whereHas('listings', fn ($q) => $q->where('external_id', $id))->firstOrFail();
    }

    public function test_overview_runs_and_vacancy_tabs_render(): void
    {
        $this->storeVacancy('a', 'Staff Admin Gudang');
        Livewire::actingAs($this->admin())->test(JobEngine::class)
            ->assertSee('Perlu perhatian')->assertSee('Kualitas data')->assertSee('Antrean worker')
            ->call('selectTab', 'runs')->assertSee('Riwayat run');

        Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\VacancyManager::class)
            ->call('selectTab', 'all')->assertSee('Staff Admin Gudang')
            ->set('search', 'tidak ada')->assertDontSee('Staff Admin Gudang');
    }

    public function test_review_queue_explains_reasons_and_check_clears_it(): void
    {
        $caps = $this->storeVacancy('caps', 'SALES INTERNET RAKYAT SURABAYA');
        $fine = $this->storeVacancy('fine', 'Staff Admin Gudang');
        $fine->update(['enriched_at' => now()]);
        DB::table('vacancy_reports')->insert(['vacancy_id' => $fine->id, 'user_id' => $this->admin()->id, 'reason' => 'closed', 'created_at' => now(), 'updated_at' => now()]);

        $component = Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\VacancyManager::class)
            ->assertSee('SALES INTERNET RAKYAT SURABAYA')->assertSee('Judul huruf kapital semua')
            ->assertSee('Staff Admin Gudang')->assertSee('Dilaporkan pengguna');

        $component->call('markChecked', $fine->id)->assertDontSee('Staff Admin Gudang');
        $this->assertNotNull(DB::table('vacancy_reports')->where('vacancy_id', $fine->id)->value('resolved_at'));

        $component->set('selected', [(string) $caps->id])->call('bulk', 'archive');
        $this->assertSame('archived', $caps->fresh()->status);
    }

    public function test_detail_panel_shows_listings_and_can_unmerge(): void
    {
        $reviewId = $this->pendingPair();
        $component = Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\VacancyManager::class)->call('resolvePair', $reviewId, true);
        $kept = Vacancy::where('status', 'active')->whereNull('merged_into_id')->first();
        $merged = Vacancy::whereNotNull('merged_into_id')->first();

        $component->call('open', $kept->id)->assertSee('Listing (2)')->assertSee('Digabung ke loker ini')
            ->call('unmerge', $merged->id);
        $this->assertNull($merged->fresh()->merged_into_id);
    }

    public function test_archive_restore_recheck_and_scam_flag(): void
    {
        $vacancy = $this->storeVacancy('a', 'Staff Admin Gudang');
        $vacancy->listings()->update(['next_check_at' => now()->addDays(3)]);
        $component = Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\VacancyManager::class);

        $component->call('recheck', $vacancy->id);
        $this->assertTrue($vacancy->listings()->first()->next_check_at->lte(now()));

        $component->call('archive', $vacancy->id);
        $this->assertSame('archived', $vacancy->fresh()->status);
        $this->assertFalse(\App\Models\Vacancy::visible()->whereKey($vacancy->id)->exists());

        $component->call('restore', $vacancy->id);
        $this->assertSame('active', $vacancy->fresh()->status, 'a restored vacancy takes its status from its listings');

        $component->call('toggleScam', $vacancy->id);
        $this->assertSame('suspected', $vacancy->fresh()->scam_flag);
        $component->call('toggleScam', $vacancy->id);
        $this->assertSame('none', $vacancy->fresh()->scam_flag);
    }

    public function test_bulk_archive_only_touches_long_closed_vacancies(): void
    {
        $old = $this->storeVacancy('old', 'Kasir');
        $recent = $this->storeVacancy('recent', 'Sopir Pengiriman');
        $old->update(['status' => 'closed', 'closed_at' => now()->subDays(40)]);
        $recent->update(['status' => 'closed', 'closed_at' => now()->subDays(5)]);

        Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\VacancyManager::class)->call('archiveOldClosed');
        $this->assertSame('archived', $old->fresh()->status);
        $this->assertSame('closed', $recent->fresh()->status);
    }

    public function test_promotion_drafts_with_fallback_copy_and_marks_posted(): void
    {
        config(['services.openai.jobs.key' => null]); // no AI: rule-based copy
        $vacancy = $this->storeVacancy('promo', 'Staff Admin Gudang');
        $vacancy->update(['skills' => ['Excel', 'Inventory'], 'education_min' => 'sma_smk']);

        $component = Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\Promotions::class, ['vacancyId' => $vacancy->id])
            ->call('generate')
            ->assertSet('headline', 'Staff Admin Gudang');
        $this->assertContains('Pendidikan minimal SMA/SMK', $component->get('highlights'));
        $captions = $component->get('captions');
        $this->assertSame(['threads', 'instagram', 'linkedin', 'x', 'whatsapp'], array_keys($captions));
        foreach (PromoWriterAlias::PLATFORMS as $key => [, $limit]) {
            $this->assertLessThanOrEqual($limit, mb_strlen($captions[$key]), $key);
        }
        $this->assertStringContainsString('utm_source=threads', $captions['threads']);
        $this->assertStringContainsString('utm_source=linkedin', $captions['linkedin']);
        $this->assertStringContainsString('utm_source=x', $captions['x']);
        $this->assertStringNotContainsString('http', $captions['instagram'], 'Instagram links are not clickable: point to the bio');
        $this->assertStringContainsString('bio', $captions['instagram']);

        $component->call('setPlatform', 'linkedin')->set('postUrls.linkedin', 'https://www.linkedin.com/posts/abc')->call('togglePosted');
        $row = DB::table('job_promotions')->first();
        $this->assertSame('posted', $row->status);
        $this->assertSame($vacancy->id, (int) $row->vacancy_id);
        $this->assertSame(['linkedin'], array_keys(json_decode($row->posted_on, true)));
        $this->assertSame('https://www.linkedin.com/posts/abc', $row->post_url);
        $this->assertSame($captions['x'], json_decode($row->captions, true)['x']);

        // Reopening the job restores every caption and the posted marks.
        $again = Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\Promotions::class, ['vacancyId' => $vacancy->id]);
        $this->assertSame($captions['whatsapp'], $again->get('captions')['whatsapp']);
        $this->assertArrayHasKey('linkedin', $again->get('postedOn'));
        $again->call('setPlatform', 'linkedin')->call('togglePosted');
        $this->assertSame('draft', DB::table('job_promotions')->value('status'));

        Livewire::actingAs($this->admin())->test(\App\Livewire\Admin\Promotions::class)->assertSee('Riwayat promosi')->assertSee('Staff Admin Gudang');
    }

    public function test_caption_keeps_link_within_threads_limit(): void
    {
        $caption = \App\JobEngine\Promotion\PromoWriter::withLink(str_repeat('kata ', 200).' https://contoh.com/hapus', 'https://trakerja.com/loker/abc?utm_source=threads');
        $this->assertLessThanOrEqual(500, mb_strlen($caption));
        $this->assertStringEndsWith('https://trakerja.com/loker/abc?utm_source=threads', $caption);
        $this->assertStringNotContainsString('contoh.com', $caption);

        $long = PromoWriterAlias::withLink(str_repeat('Kalimat panjang tentang loker. ', 30).'#lowongankerja #lokertangerang', 'https://trakerja.com/loker/x');
        $this->assertLessThanOrEqual(500, mb_strlen($long));
        $this->assertStringContainsString('#lowongankerja #lokertangerang', $long, 'hashtags survive trimming');

        // X counts a link as 23 characters, so the text may use the rest of the 280.
        $x = PromoWriterAlias::withLink(str_repeat('loker bagus ', 40).'#loker', 'https://trakerja.com/loker/'.str_repeat('a', 80), 280, 23);
        $this->assertLessThanOrEqual(280, mb_strlen(preg_replace('/https?:\/\/\S+/', '', $x)) + 23);
        $this->assertGreaterThan(240, mb_strlen(preg_replace('/https?:\/\/\S+/', '', $x)) + 23);
    }

    public function test_automation_pipeline_housekeeping_and_auto_pause(): void
    {
        Queue::fake();
        config(['job_sources.auto_pipeline' => true]);
        $vacancy = $this->storeVacancy('pipe', 'Kasir Toko');
        Queue::assertPushed(\App\Jobs\EnrichVacancyJob::class, fn ($job) => $job->vacancyId === $vacancy->id);

        $vacancy->update(['status' => 'closed', 'closed_at' => now()->subDays(40)]);
        $this->artisan('jobs:housekeeping')->assertSuccessful();
        $this->assertSame('archived', $vacancy->fresh()->status);

        $source = JobSource::where('slug', 'dealls')->first();
        foreach (range(1, 3) as $i) {
            $run = \App\Models\CrawlRun::create(['job_source_id' => $source->id, 'run_type' => 'discover', 'trigger' => 'schedule', 'started_at' => now(), 'finished_at' => now(), 'status' => 'failed', 'error_summary' => 'HTTP 500']);
            app(\App\JobEngine\Ingest\SourceHealth::class)->afterRun($source->fresh(), $run, 100);
        }
        $this->assertSame('paused', $source->fresh()->health);
        $this->assertTrue($source->fresh()->next_discovery_at->gt(now()->addHours(5)));

        $this->artisan('jobs:daily-report', ['--print' => true])->assertSuccessful();
    }
}
