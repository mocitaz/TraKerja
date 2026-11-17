<?php

namespace Tests\Feature;

use App\JobEngine\Ingest\DiscoveryRunner;
use App\Models\JobSource;
use App\Models\Vacancy;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JobEngineApiSourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
        JobSource::query()->update(['min_delay_ms' => 0]);
    }

    public function test_remote_sources_keep_only_jobs_open_to_indonesia(): void
    {
        Http::fake([
            '*/robots.txt' => Http::response("User-agent: *\nAllow: /"),
            'remotive.com/api/remote-jobs' => Http::response(['jobs' => [
                ['id' => 1, 'url' => 'https://remotive.com/1', 'title' => 'Backend Engineer', 'company_name' => 'Acme', 'candidate_required_location' => 'Worldwide', 'job_type' => 'full_time', 'salary' => '$3,000 - $4,000 per month', 'publication_date' => '2026-11-16T10:00:00', 'description' => '<p>Go</p>'],
                ['id' => 2, 'url' => 'https://remotive.com/2', 'title' => 'Support', 'company_name' => 'Beta', 'candidate_required_location' => 'USA Only', 'job_type' => 'full_time'],
                ['id' => 3, 'url' => 'https://remotive.com/3', 'title' => 'Designer', 'company_name' => 'Gamma', 'candidate_required_location' => 'APAC', 'job_type' => 'contract'],
            ]]),
        ]);

        $run = app(DiscoveryRunner::class)->run(JobSource::where('slug', 'remotive')->firstOrFail(), 10);

        $this->assertSame(2, $run->new_listings);
        $this->assertEqualsCanonicalizing(['Backend Engineer', 'Designer'], Vacancy::pluck('title')->all());
        $backend = Vacancy::where('title', 'Backend Engineer')->first();
        $this->assertSame(['remote', 3000, 4000, 'USD', 'month'], [$backend->work_mode, $backend->salary_min, $backend->salary_max, $backend->salary_currency, $backend->salary_period]);
    }

    public function test_jooble_does_nothing_without_a_key_and_searches_indonesia_with_one(): void
    {
        Http::fake([
            '*/robots.txt' => Http::response("User-agent: *\nAllow: /"),
            'jooble.org/api/*' => Http::response(['jobs' => [
                ['id' => 77, 'link' => 'https://jooble.org/desc/77', 'title' => '<b>Admin</b> Gudang', 'company' => 'PT Maju', 'location' => 'Bekasi', 'salary' => 'Rp 4.500.000', 'type' => 'Full-time', 'updated' => '2026-11-16T00:00:00', 'snippet' => 'Admin gudang shift'],
            ]]),
        ]);
        $source = JobSource::where('slug', 'jooble')->firstOrFail();
        $source->update(['config' => ['keywords' => ['admin']]]);

        config(['services.jooble.key' => null]);
        $this->assertSame(0, app(DiscoveryRunner::class)->run($source, 5)->found);
        Http::assertNotSent(fn ($r) => str_contains($r->url(), 'jooble.org/api'));

        config(['services.jooble.key' => 'test-key']);
        app(DiscoveryRunner::class)->run($source, 5);

        Http::assertSent(fn ($r) => str_contains($r->url(), 'jooble.org/api/test-key') && $r['location'] === 'Indonesia');
        $vacancy = Vacancy::sole();
        $this->assertSame('Admin Gudang', $vacancy->title);
        $this->assertSame('Kota Bekasi', $vacancy->region->name);
        $this->assertSame(4500000, $vacancy->salary_min);
    }
}
