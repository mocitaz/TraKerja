<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ScraperSource;
use App\Models\JobPosting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExploreJobsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected ScraperSource $source;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'is_premium' => false,
            'payment_status' => User::PAYMENT_STATUS_FREE,
        ]);

        $this->source = ScraperSource::create([
            'name' => 'LinkedIn Indonesia',
            'target_domain' => 'linkedin.com',
            'seed_url' => 'https://linkedin.com/jobs',
            'selectors_config' => ['title' => '.job-title'],
        ]);
    }

    public function test_explore_page_is_accessible_for_logged_in_users()
    {
        $response = $this->actingAs($this->user)->get('/explore');
        $response->assertStatus(200);
    }

    public function test_explore_page_redirects_admin_users_to_admin_index()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/explore');
        $response->assertRedirect(route('admin.index'));
    }

    public function test_user_can_search_active_job_listings()
    {
        JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Laravel Programmer',
            'company_name' => 'TechCorp',
            'description' => 'Great job description',
            'raw_url' => 'https://linkedin.com/jobs/view/100',
            'unique_hash' => md5('https://linkedin.com/jobs/view/100'),
            'status' => 'active',
        ]);

        JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Python Coder',
            'company_name' => 'PyCorp',
            'description' => 'Great python job description',
            'raw_url' => 'https://linkedin.com/jobs/view/101',
            'unique_hash' => md5('https://linkedin.com/jobs/view/101'),
            'status' => 'active',
        ]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->set('search', 'Laravel')
            ->assertSee('Laravel Programmer')
            ->assertDontSee('Python Coder');
    }

    public function test_user_can_filter_by_platform()
    {
        $jobStreetSource = ScraperSource::create([
            'name' => 'JobStreet Indonesia',
            'target_domain' => 'jobstreet.co.id',
            'seed_url' => 'https://jobstreet.co.id',
            'selectors_config' => ['title' => 'h1'],
        ]);

        JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'LinkedIn Job Listing',
            'company_name' => 'LinkedIn Inc',
            'description' => 'Description',
            'raw_url' => 'https://linkedin.com/jobs/view/200',
            'unique_hash' => md5('https://linkedin.com/jobs/view/200'),
            'status' => 'active',
        ]);

        JobPosting::create([
            'scraper_source_id' => $jobStreetSource->id,
            'title' => 'JobStreet Listing',
            'company_name' => 'JobStreet Inc',
            'description' => 'Description',
            'raw_url' => 'https://jobstreet.co.id/view/201',
            'unique_hash' => md5('https://jobstreet.co.id/view/201'),
            'status' => 'active',
        ]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->set('selectedPlatform', 'linkedin.com')
            ->assertSee('LinkedIn Job Listing')
            ->assertDontSee('JobStreet Listing');
    }

    public function test_user_reporting_expired_increments_report_count_and_archives_at_threshold()
    {
        $posting = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Vue Developer',
            'company_name' => 'VueCorp',
            'description' => 'Sample description',
            'raw_url' => 'https://linkedin.com/jobs/view/300',
            'unique_hash' => md5('https://linkedin.com/jobs/view/300'),
            'status' => 'active',
        ]);

        // First report
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->call('reportExpired', $posting->id)
            ->assertSee('Laporan Diterima!');

        $this->assertEquals(1, $posting->fresh()->report_dead_count);
        $this->assertEquals('active', $posting->fresh()->status);

        // Report 2 more times to trigger the threshold (= 3)
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->call('reportExpired', $posting->id)
            ->call('reportExpired', $posting->id);

        // Should be closed/archived immediately at 3 reports
        $this->assertEquals(3, $posting->fresh()->report_dead_count);
        $this->assertEquals('closed', $posting->fresh()->status);
    }

    public function test_user_can_save_scraped_job_to_job_tracker()
    {
        $posting = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Ruby Programmer',
            'company_name' => 'RubyCorp',
            'description' => 'Ruby job description',
            'raw_url' => 'https://linkedin.com/jobs/view/400',
            'unique_hash' => md5('https://linkedin.com/jobs/view/400'),
            'status' => 'active',
        ]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->call('trackJob', $posting->id)
            ->assertSee('Disimpan ke Tracker!');

        $this->assertDatabaseHas('job_applications', [
            'user_id' => $this->user->id,
            'company_name' => 'RubyCorp',
            'position' => 'Ruby Programmer',
            'platform_link' => 'https://linkedin.com/jobs/view/400',
        ]);
    }

    public function test_admin_can_trigger_manual_crawl_and_verify_active_listings()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $posting = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Closed Job Opportunity',
            'company_name' => 'ClosedCorp',
            'description' => 'Closed job description',
            'raw_url' => 'https://linkedin.com/jobs/view/99999',
            'unique_hash' => md5('https://linkedin.com/jobs/view/99999'),
            'status' => 'active',
        ]);

        \Illuminate\Support\Facades\Bus::fake();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\ScraperDashboard::class)
            ->call('triggerManualCrawl')
            ->assertHasNoErrors()
            ->assertStatus(200);

        \Illuminate\Support\Facades\Bus::assertDispatched(\App\Jobs\DiscoverLinksJob::class);

        $detectorMock = $this->createMock(\App\Services\DeadLinkDetector::class);
        $detectorMock->method('validate')->willReturn('closed');

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\ScraperDashboard::class)
            ->call('verifyActiveListings', $detectorMock)
            ->assertSee('Sinkronisasi selesai! 1 lowongan terdeteksi ditutup');
        $this->assertEquals('closed', $posting->fresh()->status);
    }

    public function test_user_can_filter_by_location()
    {
        JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Jakarta Senior Engineer',
            'company_name' => 'GoCorp',
            'description' => 'Great job description',
            'raw_url' => 'https://linkedin.com/jobs/view/500',
            'unique_hash' => md5('https://linkedin.com/jobs/view/500'),
            'status' => 'active',
            'location' => 'Jakarta',
        ]);

        JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Surabaya Senior Engineer',
            'company_name' => 'SuraCorp',
            'description' => 'Great job description',
            'raw_url' => 'https://linkedin.com/jobs/view/501',
            'unique_hash' => md5('https://linkedin.com/jobs/view/501'),
            'status' => 'active',
            'location' => 'Surabaya',
        ]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->set('selectedLocation', 'Jakarta')
            ->assertSee('Jakarta Senior Engineer')
            ->assertDontSee('Surabaya Senior Engineer');
    }

    public function test_location_classification_and_statistics()
    {
        // Test remote
        $resRemote = \App\Helpers\LocationHelper::classify('Remote / WFH', 'React Developer', 'Work from home');
        $this->assertEquals('Remote', $resRemote['city']);
        $this->assertEquals('Remote / WFH', $resRemote['province']);

        // Test specific city
        $resCity = \App\Helpers\LocationHelper::classify('Kota Bandung, Jawa Barat', 'Developer Bandung', 'Description');
        $this->assertEquals('Bandung', $resCity['city']);
        $this->assertEquals('Jawa Barat', $resCity['province']);

        // Test normalisation
        $this->assertEquals('Surakarta', \App\Helpers\LocationHelper::normalizeCity('Solo'));
        $this->assertEquals('Yogyakarta', \App\Helpers\LocationHelper::normalizeCity('Jogja'));
    }
}
