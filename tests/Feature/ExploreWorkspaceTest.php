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
        JobApplication::factory()->create(['user_id' => $user->id, 'company_name' => $posting->company_name, 'position' => $posting->title, 'platform_link' => $posting->raw_url]);
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
        $posting = $this->posting(['location' => 'Jakarta'], 'custom.example');
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
        $this->assertDatabaseMissing('job_applications', ['user_id' => $user->id, 'position' => $posting->title, 'platform_link' => $posting->raw_url]);
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
    public function test_bookmarks_do_not_create_applications_and_closed_saved_jobs_can_be_removed(): void
    {
        $user = User::factory()->create();
        $posting = $this->posting();
        $component = Livewire::actingAs($user)->test(ExploreJobs::class)->call('toggleBookmark', $posting->id)
            ->assertViewHas('bookmarkCount', 1)->call('setCollection', 'saved')
            ->assertViewHas('postings', fn ($rows) => $rows->total() === 1);
        $this->assertDatabaseCount('job_applications', 0);
        Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)->call('setCollection', 'saved')
            ->assertViewHas('postings', fn ($rows) => $rows->total() === 0);
        $posting->update(['status' => 'closed']);
        $this->actingAs($user);
        $component->call('setCollection', 'saved')->assertSee('This listing is no longer active.')
            ->call('toggleBookmark', $posting->id)->assertViewHas('bookmarkCount', 0);
        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_application_date_is_explicit_and_distinct_postings_are_not_merged_by_title(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        $user = User::factory()->create();
        $first = $this->posting(['location' => 'Jakarta']);
        $second = $this->posting(['location' => 'Jakarta']);
        $component = Livewire::actingAs($user)->test(ExploreJobs::class)->call('initiateTrackJob', $first->id)
            ->set('appliedOn', now()->addDay()->toDateString())->call('confirmTrackJob')->assertHasErrors('appliedOn')
            ->set('appliedOn', now()->subDays(3)->toDateString())->call('confirmTrackJob')->assertHasNoErrors()
            ->call('initiateTrackJob', $first->id)->call('confirmTrackJob')
            ->call('initiateTrackJob', $second->id)->call('confirmTrackJob');
        $this->assertDatabaseCount('job_applications', 2);
        $this->assertDatabaseHas('job_applications', ['user_id' => $user->id, 'platform_link' => $first->raw_url, 'application_date' => now()->subDays(3)->startOfDay()->format('Y-m-d H:i:s')]);
        $component->call('setCollection', 'applied')->assertViewHas('postings', fn ($rows) => $rows->total() === 2);
    }

    public function test_missing_location_must_be_supplied_instead_of_invented(): void
    {
        $posting = $this->posting();
        Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)->call('initiateTrackJob', $posting->id)
            ->call('confirmTrackJob')->assertHasErrors('applicationLocation')
            ->set('applicationLocation', 'Bandung')->call('confirmTrackJob')->assertHasNoErrors();
        $this->assertDatabaseHas('job_applications', ['platform_link' => $posting->raw_url, 'location' => 'Bandung']);
    }

    public function test_saved_searches_and_filters_restore_per_account_and_resist_cross_account_access(): void
    {
        $user = User::factory()->create();
        $component = Livewire::actingAs($user)->test(ExploreJobs::class)->set('search', 'Engineer')->set('selectedWorkType', 'Remote')
            ->set('searchName', 'Remote engineering')->call('saveSearch')->assertHasNoErrors();
        $saved = \App\Models\SavedJobSearch::where('user_id', $user->id)->firstOrFail();
        $component->set('searchName', 'Updated name')->call('saveSearch');
        $this->assertDatabaseCount('saved_job_searches', 1);
        $component->call('resetFilters')->call('applySavedSearch', $saved->id)->assertSet('search', 'Engineer')->assertSet('selectedWorkType', 'Remote');
        Livewire::actingAs($user)->test(ExploreJobs::class)->assertSet('search', 'Engineer')->assertSet('selectedWorkType', 'Remote');
        Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)->assertSet('search', '');
        $this->assertThrows(fn () => Livewire::test(ExploreJobs::class)->call('applySavedSearch', $saved->id), \Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->assertThrows(fn () => Livewire::test(ExploreJobs::class)->call('deleteSavedSearch', $saved->id), \Illuminate\Database\Eloquent\ModelNotFoundException::class);
    }

    public function test_explicit_url_filters_override_remembered_filters_and_saved_search_limit_is_enforced(): void
    {
        $user = User::factory()->create();
        \App\Models\ExplorePreference::create(['user_id' => $user->id, 'filters' => ['search' => 'Old search', 'selectedWorkType' => 'Remote']]);
        Livewire::actingAs($user)->withQueryParams(['search' => 'New search'])->test(ExploreJobs::class)
            ->assertSet('search', 'New search')->assertSet('selectedWorkType', '');
        foreach (range(1, 20) as $i) \App\Models\SavedJobSearch::create(['user_id' => $user->id, 'name' => 'Search '.$i, 'filters' => ['search' => 'Role '.$i], 'fingerprint' => hash('sha256', 'Role '.$i)]);
        Livewire::actingAs($user)->test(ExploreJobs::class)->set('searchName', 'Overflow')->call('saveSearch')->assertHasErrors('searchName');
        $this->assertDatabaseCount('saved_job_searches', 20);
    }

    public function test_missing_workspace_tables_do_not_break_browsing(): void
    {
        \Illuminate\Support\Facades\Schema::drop('job_bookmarks');
        $posting = $this->posting(['location' => 'Bandung']);
        Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)->assertSee($posting->title)
            ->call('toggleBookmark', $posting->id)->assertSee('Saved jobs will be available after the workspace update.');
        $this->assertDatabaseCount('job_applications', 0);
    }

}
