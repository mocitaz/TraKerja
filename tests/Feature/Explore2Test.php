<?php

namespace Tests\Feature;

use App\JobEngine\Ingest\IngestService;
use App\JobEngine\ScrapedJob;
use App\Livewire\ExploreJobs;
use App\Models\JobApplication;
use App\Models\JobBookmark;
use App\Models\JobSource;
use App\Models\User;
use App\Models\Vacancy;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

/** Fase 5: Explore reads the vacancies table; public pages, JSON-LD and the sitemap. */
class Explore2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
    }

    private function vacancy(string $id, string $title, string $company = 'Sumber Rejeki', array $extra = []): Vacancy
    {
        app(IngestService::class)->ingest(JobSource::where('slug', 'dealls')->first(), new ScrapedJob(
            source: 'dealls', url: "https://dealls.com/loker/{$id}", externalId: $id, title: $title, company: $company, city: 'Bekasi', extractionMethod: 'jsonld',
        ));
        $vacancy = Vacancy::whereHas('listings', fn ($q) => $q->where('external_id', $id))->firstOrFail();
        $vacancy->update($extra + ['summary' => "Ringkasan {$title}."]);

        return $vacancy->fresh();
    }

    private function path(Vacancy $vacancy): string
    {
        return parse_url($vacancy->public_path, PHP_URL_PATH);
    }

    public function test_explore_lists_visible_vacancies_and_hides_closed_merged_and_scam(): void
    {
        $this->vacancy('a', 'Staff Admin Gudang', extra: ['salary_min' => 4500000, 'salary_max' => 5200000, 'salary_period' => 'month']);
        $this->vacancy('b', 'Kasir Toko', extra: ['status' => 'closed']);
        $this->vacancy('c', 'Sopir Pengiriman', extra: ['scam_flag' => 'suspected']);

        Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)
            ->assertSee('Staff Admin Gudang')
            ->assertSee('Rp4.5–5.2M/month')
            ->assertDontSee('Kasir Toko')
            ->assertDontSee('Sopir Pengiriman');
    }

    public function test_bookmark_track_and_report_use_the_vacancy_id(): void
    {
        $user = User::factory()->create();
        $vacancy = $this->vacancy('a', 'Staff Admin Gudang');
        $listing = $vacancy->listings()->first();
        $listing->update(['next_check_at' => now()->addDays(3)]);

        $component = Livewire::actingAs($user)->test(ExploreJobs::class)
            ->call('toggleBookmark', $vacancy->id);
        $this->assertTrue(JobBookmark::where('user_id', $user->id)->where('vacancy_id', $vacancy->id)->exists());

        $component->call('initiateTrackJob', $vacancy->id)
            ->set('applicationLocation', 'Bekasi')
            ->call('confirmTrackJob');
        $application = JobApplication::where('user_id', $user->id)->sole();
        $this->assertSame($vacancy->id, $application->vacancy_id);
        $this->assertSame('Staff Admin Gudang', $application->position);

        // Tracking twice does not create a second application.
        $component->call('initiateTrackJob', $vacancy->id)->set('applicationLocation', 'Bekasi')->call('confirmTrackJob');
        $this->assertSame(1, JobApplication::where('user_id', $user->id)->count());

        // A report queues the listing for an early check but does not close the vacancy.
        $component->call('reportExpired', $vacancy->id)->call('reportExpired', $vacancy->id);
        $this->assertSame(1, DB::table('vacancy_reports')->where('vacancy_id', $vacancy->id)->count());
        $this->assertTrue($listing->fresh()->next_check_at->lte(now()));
        $this->assertSame('active', $vacancy->fresh()->status);
    }

    public function test_public_page_has_job_posting_markup_only_while_open(): void
    {
        $vacancy = $this->vacancy('a', 'Staff Admin Gudang', extra: ['valid_through' => now()->addMonth()->toDateString()]);

        $this->get($this->path($vacancy))->assertOk()
            ->assertSee('"@type":"JobPosting"', false)
            ->assertSee('Lamar di Dealls')
            ->assertDontSee('noindex', false);

        $vacancy->update(['status' => 'closed']);
        $this->get($this->path($vacancy))->assertOk()
            ->assertDontSee('"@type":"JobPosting"', false)
            ->assertSee('<meta name="robots" content="noindex">', false)
            ->assertSee('Lowongan ini sudah tidak menerima lamaran')
            ->assertDontSee('Lamar di Dealls');
    }

    public function test_public_page_redirects_old_slugs_and_merged_vacancies_and_hides_scams(): void
    {
        $vacancy = $this->vacancy('a', 'Staff Admin Gudang');
        $other = $this->vacancy('b', 'Kasir Toko', 'Maju Jaya');

        $this->get('/loker/judul-lama-'.$vacancy->public_id)->assertRedirect($this->path($vacancy))->assertStatus(301);

        $oldPath = $this->path($other);
        $other->update(['merged_into_id' => $vacancy->id]);
        $this->get($oldPath)->assertStatus(301)->assertRedirect($this->path($vacancy));

        $vacancy->update(['scam_flag' => 'suspected']);
        $this->get($this->path($vacancy))->assertNotFound();
        $this->get('/loker/apa-saja-01m7ksfxkj8nvv2jhhd10m7cd7')->assertNotFound();
    }

    public function test_company_page_and_sitemap_list_only_visible_vacancies(): void
    {
        $open = $this->vacancy('a', 'Staff Admin Gudang');
        $closed = $this->vacancy('b', 'Kasir Toko', extra: ['status' => 'closed']);

        $this->get(route('perusahaan.show', $open->company->slug))->assertOk()
            ->assertSee('Staff Admin Gudang')->assertDontSee('Kasir Toko');

        $this->get('/sitemap-loker.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee($this->path($open), false)
            ->assertDontSee($this->path($closed), false)
            ->assertSee('/perusahaan/'.$open->company->slug, false);
    }

    public function test_salary_label_follows_the_ui_language(): void
    {
        $range = new \App\Models\Vacancy(['salary_min' => 4_500_000, 'salary_max' => 5_200_000, 'salary_period' => 'month', 'salary_currency' => 'IDR']);
        $usd = new \App\Models\Vacancy(['salary_min' => 3000, 'salary_max' => 4000, 'salary_period' => 'month', 'salary_currency' => 'USD']);
        $daily = new \App\Models\Vacancy(['salary_min' => 150_000, 'salary_period' => 'day']);

        app()->setLocale('en');
        $this->assertSame('Rp4.5–5.2M/month', $range->salary_label);
        $this->assertSame('USD 3,000–4,000/month', $usd->salary_label);
        $this->assertSame('Rp150,000/day', $daily->salary_label);
        // Public SEO pages and social posts always ask for Indonesian.
        $this->assertSame('Rp4,5–5,2 jt/bulan', $range->salaryLabel('id'));

        app()->setLocale('id');
        $this->assertSame('Rp4,5–5,2 jt/bulan', $range->salary_label);
        $this->assertSame('USD 3.000–4.000/bulan', $usd->salary_label);
        $this->assertSame('Rp150.000/hari', $daily->salary_label);
    }
}
