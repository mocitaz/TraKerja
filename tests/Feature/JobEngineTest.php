<?php

namespace Tests\Feature;

use App\JobEngine\Ai\AiGateway;
use App\JobEngine\Http\DisallowedByRobots;
use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\Http\RobotsPolicy;
use App\JobEngine\Ingest\DiscoveryRunner;
use App\JobEngine\Ingest\IngestService;
use App\JobEngine\Normalize\CompanyResolver;
use App\JobEngine\ScrapedJob;
use App\Models\AiCall;
use App\Models\CrawlRun;
use App\Models\JobSource;
use App\Models\User;
use App\Models\Vacancy;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JobEngineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
        JobSource::query()->update(['min_delay_ms' => 0]);
    }

    private function pintarnyaPage(string $title, string $company, string $city, string $description = 'Tugas: melayani pelanggan.'): string
    {
        return '<html><script type="application/ld+json">'.json_encode([
            '@context' => 'https://schema.org', '@type' => 'JobPosting', 'title' => $title,
            'datePosted' => now()->toDateString(), 'validThrough' => now()->addMonth()->toDateString(),
            'employmentType' => 'FULL_TIME', 'description' => $description,
            'hiringOrganization' => ['@type' => 'Organization', 'name' => $company, 'sameAs' => 'https://www.contoh.co.id'],
            'jobLocation' => ['@type' => 'Place', 'address' => ['addressLocality' => $city, 'addressRegion' => 'Jawa Barat', 'addressCountry' => 'ID']],
            'baseSalary' => ['@type' => 'MonetaryAmount', 'currency' => 'IDR', 'value' => ['minValue' => 4500000, 'maxValue' => 5200000, 'unitText' => 'MONTH']],
        ]).'</script></html>';
    }

    public function test_robots_policy_follows_longest_match_and_wildcards(): void
    {
        $robots = app(RobotsPolicy::class);
        [$rules] = $robots->parse("User-agent: *\nDisallow: */job/\nDisallow: *?\nAllow: *?keywords\n\nUser-agent: GPTBot\nDisallow: /");
        $this->assertSame([['disallow', '*/job/'], ['disallow', '*?'], ['allow', '*?keywords']], $rules);

        Http::fake(['id.jobstreet.com/robots.txt' => Http::response("User-agent: *\nDisallow: */job/\nDisallow: *?\nAllow: *?keywords\nCrawl-delay: 3")]);
        $this->assertFalse($robots->allows('https://id.jobstreet.com/id/job/12345'));
        $this->assertFalse($robots->allows('https://id.jobstreet.com/id/jobs?page=2'));
        $this->assertTrue($robots->allows('https://id.jobstreet.com/id/jobs?keywords=admin'));
        $this->assertTrue($robots->allows('https://id.jobstreet.com/id/companies'));
        $this->assertSame(3.0, $robots->crawlDelay('https://id.jobstreet.com/x'));
    }

    public function test_polite_client_refuses_disallowed_urls_without_requesting_them(): void
    {
        Http::fake(['example.co.id/robots.txt' => Http::response("User-agent: *\nDisallow: /private/"), '*' => Http::response('ok')]);
        $client = new PoliteHttpClient(app(RobotsPolicy::class), 0);

        $this->expectException(DisallowedByRobots::class);
        try {
            $client->get('https://example.co.id/private/jobs');
        } finally {
            Http::assertNotSent(fn ($r) => str_contains($r->url(), '/private/'));
        }
    }

    public function test_discovery_stores_indonesian_vacancy_and_skips_unchanged_pages_on_rerun(): void
    {
        Http::fake([
            'pintarnya.com/robots.txt' => Http::response("User-agent: *\nAllow: /"),
            'pintarnya.com/sitemap/lowongan_1.xml' => Http::response('<urlset>'
                .'<url><loc>https://pintarnya.com/lowongan/admin-gudang-111</loc><lastmod>2026-11-17</lastmod></url>'
                .'<url><loc>https://pintarnya.com/lowongan/kasir-222</loc><lastmod>2026-11-16</lastmod></url>'
                .'<url><loc>https://pintarnya.com/blog/tips</loc></url></urlset>'),
            'pintarnya.com/lowongan/admin-gudang-111' => Http::response($this->pintarnyaPage(
                'Staff Admin Gudang', 'PT. Sumber Rejeki Abadi Tbk', 'Bekasi', 'Kirim CV ke WA 0812-3456-7890 atau rina@gmail.com')),
            'pintarnya.com/lowongan/kasir-222' => Http::response($this->pintarnyaPage('Kasir', 'Sumber Rejeki Abadi', 'Kota Bandung')),
        ]);
        $source = JobSource::where('slug', 'pintarnya')->firstOrFail();

        $run = app(DiscoveryRunner::class)->run($source, 10);

        $this->assertSame('success', $run->status);
        $this->assertSame(2, $run->new_listings);
        $vacancy = Vacancy::where('title', 'Staff Admin Gudang')->firstOrFail();
        $this->assertSame('Kota Bekasi', $vacancy->region->name);
        $this->assertSame('Sumber Rejeki Abadi', $vacancy->company->name);
        $this->assertSame('contoh.co.id', $vacancy->company->domain);
        $this->assertSame([4500000, 5200000, 'IDR', 'month'], [$vacancy->salary_min, $vacancy->salary_max, $vacancy->salary_currency, $vacancy->salary_period]);
        $this->assertStringNotContainsString('0812', $vacancy->summary);
        $this->assertStringNotContainsString('rina@gmail.com', $vacancy->summary);
        $this->assertSame('111', $vacancy->listings()->first()->external_id);
        $this->assertSame(1, DB::table('companies')->count(), 'both vacancies share one company');

        $before = Http::recorded()->count();
        $rerun = app(DiscoveryRunner::class)->run($source, 10);
        $pageRequests = Http::recorded()->slice($before)->filter(fn ($pair) => str_contains($pair[0]->url(), '/lowongan/'))->count();

        $this->assertSame(0, $rerun->new_listings);
        $this->assertSame(0, $pageRequests, 'known, unchanged job pages are not downloaded again');
    }

    public function test_kalibrr_expired_flag_closes_the_vacancy(): void
    {
        $nextData = json_encode(['props' => ['pageProps' => ['isExpired' => true, 'job' => [
            'id' => 9001, 'name' => 'Sales Trainee', 'tenure' => 'Full time', 'activationDate' => '2026-10-01',
            'companyInfo' => ['name' => 'Ruangguru'], 'googleLocation' => ['addressComponents' => ['city' => 'Jakarta Selatan', 'region' => 'DKI Jakarta', 'country' => 'Indonesia']],
            'description' => '<p>Menjual program belajar.</p>',
        ]]]]);
        Http::fake([
            'www.kalibrr.id/robots.txt' => Http::response("User-agent: *\nDisallow: /root"),
            'www.kalibrr.id/home/all-jobs' => Http::response('<a href="/c/ruangguru/jobs/9001/sales-trainee">x</a>'),
            'www.kalibrr.id/c/ruangguru/jobs/9001/sales-trainee' => Http::response('<script id="__NEXT_DATA__" type="application/json">'.$nextData.'</script>'),
        ]);
        $source = JobSource::where('slug', 'kalibrr')->firstOrFail();
        $source->update(['config' => ['discovery_paths' => ['/home/all-jobs']]]);

        app(DiscoveryRunner::class)->run($source, 5);

        $vacancy = Vacancy::firstOrFail();
        $this->assertSame('closed', $vacancy->status);
        $this->assertSame('source_expired', $vacancy->closed_reason);
        $this->assertTrue($vacancy->is_internship, 'trainee programs count as internships');
        $this->assertSame('Jakarta Selatan', $vacancy->region?->name);
    }

    public function test_ingest_detects_changed_content(): void
    {
        $source = JobSource::where('slug', 'dealls')->firstOrFail();
        $job = new ScrapedJob(source: 'dealls', url: 'https://dealls.com/loker/a?utm_source=x', externalId: 'A1', title: 'Data Analyst', company: 'Bank Jago');
        $ingest = app(IngestService::class);

        $this->assertSame('new', $ingest->ingest($source, $job));
        $this->assertSame('unchanged', $ingest->ingest($source, clone $job));
        $job->salaryMin = 9000000;
        $this->assertSame('updated', $ingest->ingest($source, $job));
        $this->assertSame(9000000, Vacancy::firstOrFail()->salary_min);
        $this->assertSame('skipped', $ingest->ingest($source, new ScrapedJob(source: 'dealls', url: 'https://dealls.com/loker/b')));
    }

    public function test_company_resolver_prefers_domain_over_similar_names(): void
    {
        $resolver = app(CompanyResolver::class);
        $flipId = $resolver->resolve('Flip', 'flip.id')->id;

        $this->assertSame($flipId, $resolver->resolve('PT Fliptech Lentera Inspirasi Pertiwi', 'flip.id')->id, 'same domain, same company');
        $this->assertNotSame($flipId, $resolver->resolve('Flip', 'flip.com')->id, 'same name, different domain: different company');
    }

    public function test_ai_gateway_caches_answers_and_respects_the_daily_budget(): void
    {
        config(['services.openai.jobs.key' => 'test-key', 'services.openai.jobs.daily_budget_usd' => 0.01]);
        Http::fake(['api.openai.com/*' => Http::response([
            'service_tier' => 'flex',
            'usage' => ['prompt_tokens' => 400, 'completion_tokens' => 100, 'prompt_tokens_details' => ['cached_tokens' => 0]],
            'choices' => [['message' => ['content' => '{"sector":"Sektor Konstruksi"}']]],
        ])]);
        $schema = ['type' => 'object', 'additionalProperties' => false, 'required' => ['sector'], 'properties' => ['sector' => ['type' => 'string']]];
        $ai = app(AiGateway::class);

        $this->assertSame(['sector' => 'Sektor Konstruksi'], $ai->structured('classify', 'Klasifikasikan.', 'Drafter sipil', $schema));
        $this->assertSame(['sector' => 'Sektor Konstruksi'], $ai->structured('classify', 'Klasifikasikan.', 'Drafter sipil', $schema));
        Http::assertSentCount(1);
        $this->assertSame('flex', Http::recorded()[0][0]['service_tier']);
        $this->assertEqualsWithDelta((400 * 0.05 + 100 * 0.40) / 1_000_000 / 2, (float) AiCall::first()->cost_usd, 1e-9);

        AiCall::query()->update(['cost_usd' => 0.02]); // over budget
        $this->assertNull($ai->structured('classify', 'Klasifikasikan.', 'Teller bank', $schema));
        Http::assertSentCount(1);
        $this->assertSame('budget_skipped', AiCall::latest('id')->first()->status);
    }

    public function test_legacy_job_postings_are_migrated_and_bookmarks_relinked(): void
    {
        $scraperSourceId = DB::table('scraper_sources')->insertGetId(['name' => 'Old', 'target_domain' => 'old.id', 'seed_url' => 'https://old.id', 'is_active' => true, 'selectors_config' => '{}']);
        $postingId = DB::table('job_postings')->insertGetId([
            'scraper_source_id' => $scraperSourceId, 'title' => 'Perawat', 'company_name' => 'RS Sehat', 'description' => 'Hubungi 081234567890',
            'raw_url' => 'https://old.id/perawat', 'unique_hash' => 'h1', 'status' => 'active', 'location' => 'Kota Bandung',
            'category_field' => 'Sektor Kesehatan Manusia dan Kegiatan Sosial', 'work_type' => 'Onsite', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $user = User::factory()->create();
        DB::table('job_bookmarks')->insert(['user_id' => $user->id, 'job_posting_id' => $postingId, 'created_at' => now(), 'updated_at' => now()]);

        $this->artisan('jobs:migrate-legacy')->assertSuccessful();
        $this->artisan('jobs:migrate-legacy')->assertSuccessful(); // idempotent

        $vacancy = Vacancy::sole();
        $this->assertSame('Perawat', $vacancy->title);
        $this->assertSame('Kota Bandung', $vacancy->region->name);
        $this->assertSame('Sektor Kesehatan Manusia dan Kegiatan Sosial', $vacancy->sector->name);
        $this->assertStringNotContainsString('081234567890', $vacancy->summary);
        $this->assertSame($vacancy->id, DB::table('job_bookmarks')->value('vacancy_id'));
        $this->assertFalse(JobSource::where('slug', 'legacy-'.$scraperSourceId)->value('is_active'));
    }
}
