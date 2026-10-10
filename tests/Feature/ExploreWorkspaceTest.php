<?php

namespace Tests\Feature;

use App\Livewire\ExploreJobs;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\ScraperSource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class ExploreWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    private function posting(array $attributes = [], string $domain = 'linkedin.com'): JobPosting
    {
        $source = ScraperSource::create(['name' => $domain === 'linkedin.com' ? 'LinkedIn Indonesia' : 'Custom Source', 'target_domain' => $domain, 'seed_url' => 'https://'.$domain, 'selectors_config' => []]);
        $url = 'https://'.$domain.'/jobs/'.uniqid();
        return JobPosting::create(array_merge(['scraper_source_id' => $source->id, 'title' => 'Platform engineer', 'company_name' => 'Actual Corp', 'description' => 'Build reliable systems.', 'raw_url' => $url, 'unique_hash' => md5($url), 'status' => 'active'], $attributes));
    }

    public function test_saved_and_reported_states_persist_and_belong_to_the_current_user(): void
    {
        Queue::fake();
        $user = User::factory()->create();
        $posting = $this->posting();
        JobApplication::factory()->create(['user_id' => $user->id, 'company_name' => $posting->company_name, 'position' => $posting->title]);
        Livewire::actingAs($user)->test(ExploreJobs::class)->call('reportExpired', $posting->id);
        Livewire::actingAs($user)->test(ExploreJobs::class)->assertSee('In your tracker')->assertSee('Report submitted');
        Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)->assertDontSee('In your tracker')->assertDontSee('Report submitted');
        $this->assertSame('active', $posting->fresh()->status);
    }

    public function test_details_show_actual_content_and_never_render_html_or_unsafe_source_links(): void
    {
        $posting = $this->posting(['description' => '<p>Actual role description.</p><script>alert("unsafe")</script>', 'raw_url' => 'javascript:alert(1)']);
        Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)
            ->call('previewJob', $posting->id)->assertSet('previewJobId', $posting->id)
            ->assertSee('Actual role description.')->assertDontSee('<script>alert("unsafe")</script>', false)
            ->assertDontSee('href="javascript:', false)->call('closePreview')->assertSet('previewJobId', null);
    }

    public function test_unknown_source_is_not_mislabelled_as_kalibrr_when_tracking(): void
    {
        $user = User::factory()->create();
        $posting = $this->posting([], 'custom.example');
        Livewire::actingAs($user)->test(ExploreJobs::class)->call('initiateTrackJob', $posting->id)
            ->assertSet('confirmingJobPortal', 'Custom Source')->call('confirmTrackJob');
        $this->assertDatabaseHas('job_applications', ['user_id' => $user->id, 'platform' => 'Custom Source', 'recruitment_stage' => 'Applied']);
    }

    public function test_closed_jobs_cannot_be_previewed_or_added_through_stale_actions(): void
    {
        $posting = $this->posting(['status' => 'closed']);
        $user = User::factory()->create();
        $this->assertThrows(fn () => Livewire::actingAs($user)->test(ExploreJobs::class)->call('previewJob', $posting->id), \Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->assertThrows(fn () => Livewire::actingAs($user)->test(ExploreJobs::class)->call('trackJob', $posting->id), \Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->assertDatabaseMissing('job_applications', ['user_id' => $user->id, 'position' => $posting->title]);
    }

    public function test_sorting_and_individual_filter_removal_preserve_other_filters(): void
    {
        $older = $this->posting(['title' => 'Older engineer', 'location' => 'Jakarta Selatan', 'created_at' => now()->subDays(2)]);
        $this->posting(['title' => 'Newer engineer', 'location' => 'Jakarta Selatan']);
        Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)->set('sortBy', 'oldest')
            ->assertViewHas('postings', fn ($rows) => $rows->first()->id === $older->id)
            ->set('search', 'engineer')->set('selectedProvince', 'DKI Jakarta')->set('selectedLocation', 'Jakarta Selatan')
            ->call('clearFilter', 'selectedProvince')->assertSet('selectedProvince', '')->assertSet('selectedLocation', '')->assertSet('search', 'engineer')
            ->call('resetFilters')->assertSet('sortBy', 'newest');
    }
}
