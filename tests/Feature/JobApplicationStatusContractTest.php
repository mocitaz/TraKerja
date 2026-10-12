<?php

namespace Tests\Feature;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationImportExportController;
use App\Livewire\AnalyticsCards;
use App\Livewire\ExploreJobs;
use App\Livewire\JobApplicationForm;
use App\Livewire\JobKanbanBoard;
use App\Livewire\JobTableList;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\ScraperSource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class JobApplicationStatusContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_outcomes_follow_status_instead_of_recruitment_stage(): void
    {
        $user = User::factory()->create();
        foreach (['Offering', 'Not Processed'] as $stage) {
            JobApplication::factory()->create(['user_id' => $user->id, 'application_status' => 'On Process', 'recruitment_stage' => $stage]);
        }
        $method = new \ReflectionMethod(\App\Http\Controllers\SummaryController::class, 'getFunnelData');
        $funnel = $method->invoke(new \App\Http\Controllers\SummaryController, $user->id, ['start' => null]);
        $this->assertSame(0, $funnel['Accepted']);
        $this->assertSame(0, $funnel['Rejected']);
        $this->assertSame(2, $funnel['Pending']);
        $admin = new \App\Livewire\Admin\Analytics;
        $admin->periodFilter = 'all';
        $adminMethod = new \ReflectionMethod($admin, 'fetchJobApplicationsByStatus');
        $this->assertSame([2, 0, 0, 0, 2], $adminMethod->invoke($admin)['data']);
    }

    public function test_explore_saved_job_is_visible_in_process_column(): void
    {
        $user = User::factory()->create();
        $source = ScraperSource::create(['name' => 'LinkedIn', 'target_domain' => 'linkedin.com', 'seed_url' => 'https://linkedin.com/jobs', 'selectors_config' => []]);
        $posting = JobPosting::create(['scraper_source_id' => $source->id, 'title' => 'Contract engineer', 'description' => 'An engineering vacancy', 'company_name' => 'Contract Corp', 'raw_url' => 'https://linkedin.com/jobs/contract', 'unique_hash' => md5('contract'), 'status' => 'active']);
        Livewire::actingAs($user)->test(ExploreJobs::class)->call('trackJob', $posting->id);
        $this->assertDatabaseHas('job_applications', ['user_id' => $user->id, 'application_status' => 'On Process', 'recruitment_stage' => 'Applied']);
        Livewire::actingAs($user)->test(JobKanbanBoard::class)->assertSee('Contract Corp');
    }

    public function test_form_table_and_board_offer_identical_statuses(): void
    {
        $expected = ['On Process', 'Accepted', 'Rejected'];
        $this->assertSame($expected, (new JobApplicationForm)->applicationStatusOptions);
        $this->assertSame($expected, (new JobTableList)->statusOptions);
        $this->assertSame($expected, (new JobKanbanBoard)->statusOptions);
    }

    public function test_phase_aliases_preserve_stage_when_saved(): void
    {
        foreach (['Applied' => 'Applied', 'Interview' => 'HR - Interview', 'Offering' => 'Offering', 'Pending' => 'Applied'] as $status => $stage) {
            $job = JobApplication::factory()->create(['application_status' => $status, 'recruitment_stage' => 'Applied']);
            $this->assertSame('On Process', $job->fresh()->application_status);
            $this->assertSame($stage, $job->fresh()->recruitment_stage);
        }
        $job = JobApplication::factory()->create(['application_status' => 'Interview', 'recruitment_stage' => 'User - Interview']);
        $this->assertSame('User - Interview', $job->fresh()->recruitment_stage);
    }

    public function test_declined_alias_is_rejected_and_counted_in_summaries(): void
    {
        $user = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $user->id, 'application_status' => 'Declined']);
        $this->assertDatabaseHas('job_applications', ['user_id' => $user->id, 'application_status' => 'Rejected']);
        Livewire::actingAs($user)->test(AnalyticsCards::class)->assertSet('declinedCount', 1);
        $this->actingAs($user);
        $this->assertSame(1, (new DashboardController)->index()->getData()['declinedCount']);
        $summary = new \ReflectionMethod(\App\Http\Controllers\SummaryController::class, 'getFunnelData');
        $data = $summary->invoke(new \App\Http\Controllers\SummaryController, $user->id, ['start' => null]);
        $this->assertSame(1, $data['Rejected']);
        Livewire::test(JobTableList::class)->set('statusFilter', 'Rejected')->assertSee('Rejected');
        Livewire::test(JobKanbanBoard::class)->assertSee('Rejected');
    }

    public function test_board_rejects_invalid_status_and_preserves_job(): void
    {
        $user = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $user->id, 'application_status' => 'On Process']);
        Livewire::actingAs($user)->test(JobKanbanBoard::class)->call('updateStatus', $job->id, 'Invented')->assertHasErrors('newStatus');
        $this->assertSame('On Process', $job->fresh()->application_status);
    }

    public function test_csv_accepts_canonical_status_and_normalizes_legacy_alias(): void
    {
        $this->actingAs(User::factory()->create());
        $method = new \ReflectionMethod(JobApplicationImportExportController::class, 'importRow');
        foreach (['Rejected' => 'Rejected', 'Declined' => 'Rejected', 'Offering' => 'On Process'] as $status => $expected) {
            $result = $method->invoke(new JobApplicationImportExportController, ['company_name' => 'CSV Corp', 'position' => 'Engineer', 'location' => 'Jakarta', 'platform' => 'LinkedIn', 'application_status' => $status, 'recruitment_stage' => 'Applied', 'career_level' => 'Full Time', 'application_date' => '2026-10-08'], 2);
            $this->assertTrue($result['success'], implode(', ', $result['errors'] ?? []));
            $this->assertSame($expected, $result['data']['application_status']);
            if ($status === 'Offering') $this->assertSame('Offering', $result['data']['recruitment_stage']);
        }
    }

    public function test_withdrawn_legacy_records_keep_not_processed_stage(): void
    {
        $job = JobApplication::factory()->create(['application_status' => 'Withdrawn', 'recruitment_stage' => 'Applied']);
        $this->assertSame('Rejected', $job->fresh()->application_status);
        $this->assertSame('Not Processed', $job->fresh()->recruitment_stage);
    }

    public function test_migration_covers_every_previous_status_and_keeps_specific_interview_stage(): void
    {
        $expected = ['Applied' => ['On Process', 'Applied'], 'Pending' => ['On Process', 'Applied'], 'Interview' => ['On Process', 'HR - Interview'], 'Offering' => ['On Process', 'Offering'], 'Offered' => ['On Process', 'Offering'], 'Declined' => ['Rejected', 'Applied'], 'Withdrawn' => ['Rejected', 'Not Processed']];
        foreach ($expected as $alias => [$status, $stage]) {
            $job = JobApplication::factory()->create(['application_status' => 'On Process', 'recruitment_stage' => 'Applied']);
            DB::table('job_applications')->where('id', $job->id)->update(['application_status' => $alias]);
            $migration = require database_path('migrations/2026_10_08_000002_normalize_job_application_statuses.php');
            $migration->up();
            $this->assertSame($status, $job->fresh()->application_status, $alias);
            $this->assertSame($stage, $job->fresh()->recruitment_stage, $alias);
        }
        $job = JobApplication::factory()->create(['application_status' => 'On Process', 'recruitment_stage' => 'User - Interview']);
        DB::table('job_applications')->where('id', $job->id)->update(['application_status' => 'Interview']);
        $migration->up();
        $this->assertSame('User - Interview', $job->fresh()->recruitment_stage);
    }

    public function test_legacy_status_field_tracks_canonical_model_updates(): void
    {
        $job = JobApplication::factory()->create(['status' => 'Applied', 'application_status' => 'Accepted']);
        $this->assertSame('Accepted', $job->fresh()->status);
        $job->update(['application_status' => 'Rejected']);
        $this->assertSame('Rejected', $job->fresh()->status);
    }

    public function test_migration_recovers_empty_status_from_legacy_without_overriding_current_outcomes(): void
    {
        $legacy = JobApplication::factory()->create(['application_status' => 'On Process']);
        $current = JobApplication::factory()->create(['application_status' => 'On Process']);
        DB::table('job_applications')->where('id', $legacy->id)->update(['application_status' => '', 'status' => 'Declined']);
        DB::table('job_applications')->where('id', $current->id)->update(['status' => 'Accepted']);
        $migration = require database_path('migrations/2026_10_08_000002_normalize_job_application_statuses.php');
        $migration->up();
        $this->assertSame('Rejected', $legacy->fresh()->application_status);
        $this->assertSame('Rejected', $legacy->fresh()->status);
        $this->assertSame('On Process', $current->fresh()->application_status);
        $this->assertSame('On Process', $current->fresh()->status);
    }

    public function test_form_shortcuts_keep_outcomes_separate_from_stages(): void
    {
        $user = User::factory()->create();
        Livewire::actingAs($user)->test(JobApplicationForm::class)
            ->call('resetFormForNewJob', 'Offering')
            ->assertSet('application_status', 'On Process')->assertSet('recruitment_stage', 'Offering')
            ->call('resetFormForNewJob', 'Accepted')
            ->assertSet('application_status', 'Accepted')->assertSet('recruitment_stage', 'Applied');
    }

    public function test_form_saves_rejection_and_blocks_unknown_status(): void
    {
        $user = User::factory()->create();
        $form = Livewire::actingAs($user)->test(JobApplicationForm::class)
            ->set('company_name', 'Form Corp')->set('position', 'Engineer')->set('platform', 'LinkedIn')
            ->set('application_date', now()->format('Y-m-d'))->set('isRemote', true)
            ->set('application_status', 'Rejected')->set('recruitment_stage', 'Applied');
        $form->call('save')->assertHasNoErrors();
        $this->assertDatabaseHas('job_applications', ['user_id' => $user->id, 'application_status' => 'Rejected']);
        $form->set('company_name', 'Invalid Corp')->set('position', 'Engineer')->set('platform', 'LinkedIn')
            ->set('application_date', now()->format('Y-m-d'))->set('isRemote', true)
            ->set('recruitment_stage', 'Applied')->set('application_status', 'Invented')->call('save')
            ->assertHasErrors('application_status');
        $this->assertDatabaseMissing('job_applications', ['company_name' => 'Invalid Corp']);
    }

    public function test_csv_public_import_persists_rejected_without_model_events(): void
    {
        \App\Models\Setting::set('monetization_enabled', false);
        $user = User::factory()->create();
        $this->actingAs($user);
        $content = "Company Name,Position,Location,Platform,Application Status,Recruitment Stage,Career Level,Application Date,Platform Link\nCSV Corp,Engineer,Jakarta,LinkedIn,Rejected,Applied,Full Time,2026-10-08,https://linkedin.com/jobs/contract\nLegacy Corp,Engineer,Jakarta,LinkedIn,Interview,Applied,Full Time,2026-10-08,https://linkedin.com/jobs/contract\n";
        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('applications.csv', $content);
        $request = \Illuminate\Http\Request::create('/import', 'POST', [], [], ['csv_file' => $file]);
        (new JobApplicationImportExportController)->importFromCsv($request);
        $this->assertDatabaseHas('job_applications', ['user_id' => $user->id, 'company_name' => 'CSV Corp', 'application_status' => 'Rejected']);
        $this->assertDatabaseHas('job_applications', ['user_id' => $user->id, 'company_name' => 'Legacy Corp', 'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview']);
        $response = $this->get(route('csv.export'))->assertOk();
        $export = $response->streamedContent();
        $this->assertStringContainsString('Rejected', $export);
        $this->assertStringNotContainsString('Declined', $export);
    }

    public function test_migration_normalizes_legacy_rows_without_changing_archive_or_timestamps(): void
    {
        $job = JobApplication::factory()->create(['application_status' => 'Accepted', 'recruitment_stage' => 'Applied', 'is_archived' => true, 'archived_at' => now()->subDays(20)]);
        DB::table('job_applications')->where('id', $job->id)->update(['application_status' => 'Offering']);
        $before = DB::table('job_applications')->find($job->id);
        $migration = require database_path('migrations/2026_10_08_000002_normalize_job_application_statuses.php');
        $migration->up();
        $after = DB::table('job_applications')->find($job->id);
        $this->assertSame('On Process', $after->application_status);
        $this->assertSame('Offering', $after->recruitment_stage);
        $this->assertSame($before->updated_at, $after->updated_at);
        $this->assertSame($before->archived_at, $after->archived_at);
        $this->assertSame($before->is_archived, $after->is_archived);
        $migration->up();
        $this->assertEquals($after, DB::table('job_applications')->find($job->id));
    }

    public function test_form_can_save_rejected_status_at_hr_interview_stage(): void
    {
        $user = User::factory()->create();
        $form = Livewire::actingAs($user)->test(JobApplicationForm::class)
            ->set('company_name', 'Interview Corp')
            ->set('position', 'Product Manager')
            ->set('platform', 'LinkedIn')
            ->set('application_date', now()->format('Y-m-d'))
            ->set('isRemote', true)
            ->set('recruitment_stage', 'HR - Interview')
            ->set('application_status', 'Rejected')
            ->set('interview_date', ''); // Left blank as rejection happened

        $form->call('save')->assertHasNoErrors();

        $this->assertDatabaseHas('job_applications', [
            'user_id' => $user->id,
            'company_name' => 'Interview Corp',
            'recruitment_stage' => 'HR - Interview',
            'application_status' => 'Rejected',
            'interview_date' => null,
        ]);
    }
}
