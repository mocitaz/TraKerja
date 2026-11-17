<?php

namespace Tests\Feature;

use App\JobEngine\Ingest\IngestService;
use App\JobEngine\Intelligence\AiEnricher;
use App\JobEngine\Intelligence\VacancyMerger;
use App\JobEngine\ScrapedJob;
use App\Models\AiCall;
use App\Models\JobSource;
use App\Models\User;
use App\Models\Vacancy;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JobIntelligenceTest extends TestCase
{
    use RefreshDatabase;

    private const DESCRIPTION = 'Kami mencari frontend engineer berpengalaman membangun aplikasi web dengan React dan TypeScript untuk tim produk pembayaran kami di Jakarta Selatan. Minimal S1 Informatika.';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JobEngineReferenceSeeder::class);
        config(['services.openai.jobs.key' => 'test-key', 'services.openai.jobs.daily_budget_usd' => 1]);
    }

    private function job(string $source, string $id, string $title, array $extra = []): ScrapedJob
    {
        return new ScrapedJob(...array_merge([
            'source' => $source, 'url' => "https://{$source}.example/{$id}", 'externalId' => $id, 'title' => $title,
            'company' => 'PT Kredivo Group', 'companyDomain' => 'kredivo.com', 'city' => 'Jakarta Selatan',
            'locationText' => 'Jakarta Selatan', 'description' => self::DESCRIPTION, 'datePosted' => now()->toDateString(),
        ], $extra));
    }

    private function fakeAi(array ...$answers): void
    {
        $responses = array_map(fn ($a) => Http::response([
            'service_tier' => 'flex', 'usage' => ['prompt_tokens' => 500, 'completion_tokens' => 120],
            'choices' => [['message' => ['content' => json_encode($a)]]],
        ]), $answers);
        Http::fake(['api.openai.com/*' => Http::sequence($responses)]);
    }

    public function test_the_same_job_on_two_platforms_becomes_one_vacancy_with_the_ats_as_primary(): void
    {
        Http::fake();
        $ingest = app(IngestService::class);

        $this->assertSame('new', $ingest->ingest(JobSource::where('slug', 'dealls')->first(), $this->job('dealls', 'D1', 'Sr. Frontend Engineer', ['salaryMin' => 18000000])));
        $this->assertSame('merged', $ingest->ingest(JobSource::where('slug', 'greenhouse')->first(), $this->job('greenhouse', 'G1', 'Senior Frontend Engineer', ['validThrough' => now()->addMonth()->toDateString()])));

        $vacancy = Vacancy::sole();
        $this->assertSame(2, $vacancy->listings_count);
        $this->assertSame(18000000, $vacancy->salary_min, 'kept from the first platform');
        $this->assertNotNull($vacancy->valid_through, 'gap filled from the second platform');
        $this->assertSame('greenhouse', $vacancy->listings()->find($vacancy->primary_listing_id)->source->slug, 'company ATS is the apply target');
        Http::assertNothingSent();
    }

    public function test_a_different_level_stays_a_separate_vacancy(): void
    {
        Http::fake();
        $ingest = app(IngestService::class);
        $ingest->ingest(JobSource::where('slug', 'dealls')->first(), $this->job('dealls', 'D1', 'Backend Engineer'));
        $ingest->ingest(JobSource::where('slug', 'kalibrr')->first(), $this->job('kalibrr', 'K1', 'Senior Backend Engineer'));

        $this->assertSame(2, Vacancy::count());
    }

    /** Real wrong AI merge (Ajaib, 2026-11-17): "Engineer I" and "Engineer II" are different levels. */
    public function test_level_numbers_are_never_merged(): void
    {
        $matcher = app(\App\JobEngine\Intelligence\Matcher::class);
        $one = new Vacancy(['title' => 'Senior Backend Engineer I - Money']);
        $this->assertTrue($matcher->conflicts('Senior Backend Engineer II - Money', null, $one));
        $this->assertTrue($matcher->conflicts('Staff Admin 2', null, new Vacancy(['title' => 'Staff Admin'])));
        $this->assertFalse($matcher->conflicts('Senior Backend Engineer I - Money', null, $one));
        $this->assertFalse($matcher->conflicts('Staff Admin min. 2 tahun', null, new Vacancy(['title' => 'Staff Admin'])));
    }

    /** Real wrong merges seen on 2026-11-17: template postings that differ only by place, language or level. */
    public function test_hard_rules_keep_different_places_languages_and_levels_apart_even_if_the_ai_says_same(): void
    {
        // An over-eager AI that always says "same, high confidence".
        Http::fake(['api.openai.com/*' => Http::response([
            'service_tier' => 'flex', 'usage' => ['prompt_tokens' => 1, 'completion_tokens' => 1],
            'choices' => [['message' => ['content' => json_encode(['same_job' => true, 'confidence' => 'high', 'reason' => 'x'])]]],
        ])]);
        $ingest = app(IngestService::class);
        $template = ['company' => 'Erajaya', 'companyDomain' => 'erajaya.com', 'city' => null, 'locationText' => null];
        $pairs = [
            ['SPG/SPB Store - Tegal (EI)', 'SPG/SPB Store - Bantul (EI)'],
            ['Customer Service (Malang)', 'Customer Service (Palembang)'],
            ['Client Service Analyst - Livechat (Turkish required)', 'Client Service Analyst - Livechat (Czech required)'],
            ['Personal Trainer - FIT HUB Kota Tangerang', 'Personal Trainer - FIT HUB Kab. Tangerang'],
            ['Compliance Engineer', 'Compliance Engineer Intern'],
        ];
        foreach ($pairs as $i => [$a, $b]) {
            $ingest->ingest(JobSource::where('slug', 'dealls')->first(), $this->job('dealls', "A{$i}", $a, $template));
            $this->assertSame('new', $ingest->ingest(JobSource::where('slug', 'kalibrr')->first(), $this->job('kalibrr', "B{$i}", $b, $template)), "{$a} vs {$b}");
        }
        $this->assertSame(10, Vacancy::count());
        Http::assertNothingSent();
    }

    public function test_the_ai_is_not_asked_when_the_region_is_unknown(): void
    {
        Http::fake();
        $ingest = app(IngestService::class);
        $noCity = ['city' => null, 'locationText' => null];
        $ingest->ingest(JobSource::where('slug', 'dealls')->first(), $this->job('dealls', 'D1', 'Customer Service', $noCity));
        $ingest->ingest(JobSource::where('slug', 'kalibrr')->first(), $this->job('kalibrr', 'K1', 'Customer Service Officer', $noCity));

        Http::assertNothingSent();
        $this->assertSame(1, DB::table('dedup_reviews')->where('status', 'pending')->count(), 'goes to a person instead');
    }

    public function test_a_pair_is_queued_for_review_only_once(): void
    {
        Http::fake();
        $ingest = app(IngestService::class);
        $noCity = ['city' => null, 'locationText' => null];
        $ingest->ingest(JobSource::where('slug', 'dealls')->first(), $this->job('dealls', 'D1', 'Customer Service', $noCity));
        $ingest->ingest(JobSource::where('slug', 'kalibrr')->first(), $this->job('kalibrr', 'K1', 'Customer Service Officer', $noCity));
        $this->artisan('jobs:dedup', ['--no-ai' => true])->assertSuccessful(); // looks at the pair from the other side too

        $this->assertSame(1, DB::table('dedup_reviews')->where('status', 'pending')->count());
    }

    public function test_merges_are_audited_and_can_be_undone(): void
    {
        Http::fake();
        $ingest = app(IngestService::class);
        $ingest->ingest(JobSource::where('slug', 'dealls')->first(), $this->job('dealls', 'D1', 'Data Analyst', ['city' => null, 'locationText' => null]));
        $ingest->ingest(JobSource::where('slug', 'kalibrr')->first(), $this->job('kalibrr', 'K1', 'Data Scientist', ['salaryMin' => 9000000]));
        [$keep, $duplicate] = Vacancy::orderBy('id')->get()->all();

        app(VacancyMerger::class)->merge($duplicate, $keep, 'ai', 0.8);
        $this->assertNotNull($keep->fresh()->region_id, 'gap filled by the merge');
        $this->assertSame(1, DB::table('dedup_reviews')->where('status', 'merged')->count());

        $this->artisan('jobs:unmerge', ['ids' => [$duplicate->id]])->assertSuccessful();
        $this->assertSame(['active', null, 1], [$duplicate->fresh()->status, $duplicate->fresh()->merged_into_id, $duplicate->fresh()->listings_count]);
        $this->assertSame(1, $keep->fresh()->listings_count);
        $this->assertNull($keep->fresh()->region_id, 'values copied from the duplicate are cleared again');
        $this->assertNull($keep->fresh()->salary_min);
    }

    public function test_gray_zone_pairs_merge_only_on_a_confident_ai_verdict(): void
    {
        $ingest = app(IngestService::class);
        $ingest->ingest(JobSource::where('slug', 'dealls')->first(), $this->job('dealls', 'D1', 'Customer Service'));

        // One fake for the whole test: first verdict unsure, second confident.
        $this->fakeAi(['same_job' => true, 'confidence' => 'medium', 'reason' => 'mirip'], ['same_job' => true, 'confidence' => 'high', 'reason' => 'sama']);
        $this->assertSame('new', $ingest->ingest(JobSource::where('slug', 'kalibrr')->first(), $this->job('kalibrr', 'K1', 'Customer Service Officer')));
        $this->assertSame(1, DB::table('dedup_reviews')->where('status', 'pending')->count(), 'unsure pairs wait for a person');

        $this->assertSame('merged', $ingest->ingest(JobSource::where('slug', 'pintarnya')->first(), $this->job('pintarnya', 'P1', 'Customer Service Officer Kredivo')));
    }

    public function test_enricher_fills_only_empty_fields_validates_and_falls_back_once(): void
    {
        app(IngestService::class)->ingest(JobSource::where('slug', 'dealls')->first(), $this->job('dealls', 'D1', 'Frontend Engineer', ['employmentType' => 'contract']));
        $vacancy = Vacancy::sole();

        $base = ['sector' => 'Sektor Informasi dan Komunikasi (TIK)', 'seniority' => 'mid', 'education_min' => 's1', 'employment_type' => 'full_time',
            'work_mode' => 'hybrid', 'is_internship' => false, 'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'salary_currency' => 'IDR',
            'salary_period' => 'month', 'summary' => 'Membangun aplikasi web React. Hubungi 081234567890.', 'skills' => ['React', 'TypeScript'], 'scam_risk' => 'low'];
        // nano answers an impossible salary (min > max) -> invalid -> mini retried once
        $this->fakeAi($base + ['salary_min' => 25000000, 'salary_max' => 15000000], $base + ['salary_min' => 15000000, 'salary_max' => 25000000]);

        $this->assertSame('enriched', app(AiEnricher::class)->enrich($vacancy));

        $vacancy->refresh();
        $this->assertSame('contract', $vacancy->employment_type, 'source data is never overwritten');
        $this->assertSame(['mid', 's1', 'hybrid', 15000000, 25000000], [$vacancy->seniority, $vacancy->education_min, $vacancy->work_mode, $vacancy->salary_min, $vacancy->salary_max]);
        $this->assertSame('Sektor Informasi dan Komunikasi (TIK)', $vacancy->sector->name);
        $this->assertStringNotContainsString('081234567890', $vacancy->summary);
        $this->assertContains('sector_id', $vacancy->ai_filled);
        $this->assertSame(['gpt-5-nano', 'gpt-5-mini'], AiCall::orderBy('id')->pluck('model')->all());
    }

    public function test_enricher_flags_scams_even_without_ai(): void
    {
        config(['services.openai.jobs.key' => null]);
        app(IngestService::class)->ingest(JobSource::where('slug', 'pintarnya')->first(), $this->job('pintarnya', 'P9', 'Admin Online', [
            'description' => 'Kerja dari HP gaji harian 300rb tanpa pengalaman. Wajib transfer biaya pendaftaran Rp 50.000.', 'company' => 'Sukses Bersama',
        ]));

        $this->assertSame('suspected', Vacancy::sole()->scam_flag, 'rules run at ingest time');
        app(AiEnricher::class)->enrich(Vacancy::sole());
        $this->assertSame('suspected', Vacancy::sole()->scam_flag);
    }

    public function test_merging_keeps_user_bookmarks_and_tracker_links(): void
    {
        Http::fake();
        $ingest = app(IngestService::class);
        $ingest->ingest(JobSource::where('slug', 'dealls')->first(), $this->job('dealls', 'D1', 'Data Analyst', ['company' => 'Bank Jago', 'companyDomain' => 'jago.com']));
        $ingest->ingest(JobSource::where('slug', 'kalibrr')->first(), $this->job('kalibrr', 'K1', 'Data Scientist', ['company' => 'Bank Jago', 'companyDomain' => 'jago.com']));
        [$keep, $duplicate] = Vacancy::orderBy('id')->get()->all();
        $user = User::factory()->create();
        DB::table('job_bookmarks')->insert(['user_id' => $user->id, 'vacancy_id' => $duplicate->id, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('job_applications')->insert(['user_id' => $user->id, 'vacancy_id' => $duplicate->id, 'company_name' => 'Bank Jago', 'position' => 'Data Scientist',
            'platform' => 'Kalibrr', 'status' => 'On Process', 'application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'application_date' => now()->toDateString(),
            'platform_link' => '', 'notes' => '', 'location' => 'Jakarta', 'created_at' => now(), 'updated_at' => now()]);

        app(VacancyMerger::class)->merge($duplicate, $keep);

        $this->assertSame($keep->id, DB::table('job_bookmarks')->value('vacancy_id'));
        $this->assertSame($keep->id, DB::table('job_applications')->value('vacancy_id'));
        $this->assertSame(['archived', 'merged', $keep->id], [$duplicate->fresh()->status, $duplicate->fresh()->closed_reason, $duplicate->fresh()->merged_into_id]);
        $this->assertSame(2, $keep->fresh()->listings_count);
    }
}
