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
        \Illuminate\Support\Facades\Queue::fake();

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
        \App\Models\Setting::set('explore_maintenance', false);
    }

    public function test_explore_page_shows_maintenance_teaser_when_enabled()
    {
        \App\Models\Setting::set('explore_maintenance', true);

        $response = $this->actingAs($this->user)->get('/explore');
        $response->assertStatus(200);
        $response->assertSee('Something massive is cooking');
        $response->assertSee('A place to find your people.');
        $response->assertSee('Hey, welcome in!');
        $response->assertSee('New paths. Shared together.');
        $response->assertSee('Different journeys.');
        $response->assertSee('One community.');
        $response->assertSee('amber.svg');
        $response->assertSee('sage.svg');
        $response->assertSee('indigo.svg');
        $response->assertDontSee('Your main workspace is 100% active');
        $response->assertDontSee('NEXT-GEN EXPLORE');
        $response->assertSee('Stay tuned. Dropping sooner than you think.');
        $response->assertSee('Back to Dashboard');
        $response->assertSee('Open Job Tracker');
    }

    public function test_explore_maintenance_teaser_is_shown_for_both_free_and_premium_users()
    {
        \App\Models\Setting::set('explore_maintenance', true);

        $premiumUser = User::factory()->create([
            'is_premium' => true,
            'payment_status' => User::PAYMENT_STATUS_PAID,
        ]);

        // Free user sees maintenance teaser
        $freeResponse = $this->actingAs($this->user)->get('/explore');
        $freeResponse->assertStatus(200);
        $freeResponse->assertSee('Something massive is cooking');

        // Premium user also sees maintenance teaser
        $premiumResponse = $this->actingAs($premiumUser)->get('/explore');
        $premiumResponse->assertStatus(200);
        $premiumResponse->assertSee('Something massive is cooking');
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

    public function test_user_reporting_expired_is_deduplicated_without_archiving()
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
            ->assertSee('Report submitted.');

        $this->assertEquals(1, $posting->fresh()->report_dead_count);
        $this->assertEquals('active', $posting->fresh()->status);

        // Repeated reports from the same user add no evidence.
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->call('reportExpired', $posting->id)
            ->call('reportExpired', $posting->id);

        $this->assertEquals(1, $posting->fresh()->report_dead_count);
        $this->assertEquals('active', $posting->fresh()->status);
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

        $component = Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->set('applicationLocation', 'Jakarta')
            ->call('trackJob', $posting->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('job_applications', [
            'user_id' => $this->user->id,
            'company_name' => 'RubyCorp',
            'position' => 'Ruby Programmer',
            'platform_link' => 'https://linkedin.com/jobs/view/400',
            'application_status' => 'On Process',
            'recruitment_stage' => 'Applied',
        ]);
        $component->assertSee('Application recorded. Your saved jobs are kept separately.');
    }

    public function test_user_can_save_scraped_job_via_confirmation_modal()
    {
        $posting = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Swift Programmer',
            'company_name' => 'SwiftCorp',
            'description' => 'Swift job description',
            'raw_url' => 'https://linkedin.com/jobs/view/401',
            'unique_hash' => md5('https://linkedin.com/jobs/view/401'),
            'status' => 'active',
        ]);

        $component = \Livewire\Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->call('initiateTrackJob', $posting->id)
            ->assertSet('confirmingTrackJobId', $posting->id)
            ->set('applicationLocation', 'Jakarta')
            ->call('confirmTrackJob')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('job_applications', [
            'user_id' => $this->user->id,
            'company_name' => 'SwiftCorp',
            'position' => 'Swift Programmer',
            'application_status' => 'On Process',
            'recruitment_stage' => 'Applied',
        ]);
        $component->assertSee('Application recorded. Your saved jobs are kept separately.');
    }

    public function test_user_can_report_expired_job_via_confirmation_modal()
    {
        $posting = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Stale Tech Job',
            'company_name' => 'StaleCorp',
            'description' => 'Stale job description',
            'raw_url' => 'https://linkedin.com/jobs/view/405',
            'unique_hash' => md5('https://linkedin.com/jobs/view/405'),
            'status' => 'active',
        ]);

        \Livewire\Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->call('initiateReportExpired', $posting->id)
            ->assertSet('confirmingReportJobId', $posting->id)
            ->call('confirmReportExpired')
            ->assertSee('Report submitted.');

        $this->assertEquals(1, $posting->fresh()->report_dead_count);
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
            'location' => 'Jakarta Selatan',
        ]);

        JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Surabaya Senior Engineer',
            'company_name' => 'SuraCorp',
            'description' => 'Great job description',
            'raw_url' => 'https://linkedin.com/jobs/view/501',
            'unique_hash' => md5('https://linkedin.com/jobs/view/501'),
            'status' => 'active',
            'location' => 'Kota Surabaya',
        ]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->set('selectedProvince', 'DKI Jakarta')
            ->assertSee('Jakarta Senior Engineer')
            ->assertDontSee('Surabaya Senior Engineer')
            ->set('selectedLocation', 'Jakarta Selatan')
            ->assertSee('Jakarta Senior Engineer');
    }

    public function test_location_classification_and_statistics()
    {
        // Test remote
        $resRemote = \App\Helpers\LocationHelper::classify('Remote / WFH', 'React Developer', 'Work from home');
        $this->assertEquals('Remote', $resRemote['city']);
        $this->assertEquals('Remote / WFH', $resRemote['province']);

        // Test specific city
        $resCity = \App\Helpers\LocationHelper::classify('Kota Bandung, Jawa Barat', 'Developer Bandung', 'Description');
        $this->assertEquals('Kota Bandung', $resCity['city']);
        $this->assertEquals('Jawa Barat', $resCity['province']);

        // Test normalisation
        $this->assertEquals('Kota Surakarta', \App\Helpers\LocationHelper::normalizeCity('Solo'));
        $this->assertEquals('Kota Yogyakarta', \App\Helpers\LocationHelper::normalizeCity('Jogja'));
    }

    public function test_user_can_reset_all_filters()
    {
        $component = Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExploreJobs::class)
            ->set('search', 'Ruby')
            ->set('selectedPlatform', 'linkedin.com')
            ->set('selectedField', 'IT')
            ->set('selectedMajor', 'CS')
            ->set('selectedWorkType', 'Remote')
            ->set('selectedProvince', 'DKI Jakarta')
            ->set('selectedLocation', 'Jakarta Selatan')
            ->call('resetFilters');

        $this->assertEquals('', $component->get('search'));
        $this->assertEquals('', $component->get('selectedPlatform'));
        $this->assertEquals('', $component->get('selectedField'));
        $this->assertEquals('', $component->get('selectedMajor'));
        $this->assertEquals('', $component->get('selectedWorkType'));
        $this->assertEquals('', $component->get('selectedProvince'));
        $this->assertEquals('', $component->get('selectedLocation'));
    }
}
