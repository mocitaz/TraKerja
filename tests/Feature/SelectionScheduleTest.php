<?php

namespace Tests\Feature;

use App\Livewire\JobApplicationForm;
use App\Livewire\InterviewCalendar;
use App\Models\JobApplication;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class SelectionScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracker_close_event_discards_unsaved_schedule_before_reopening(): void
    {
        $user = User::factory()->create();
        $job = JobApplication::factory()->create([
            'user_id' => $user->id, 'location' => 'Remote', 'platform' => 'LinkedIn',
            'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview',
            'interview_date' => '2026-11-02 10:00:00',
        ]);

        Livewire::actingAs($user)->test(JobApplicationForm::class)
            ->dispatch('edit-job', jobId: $job->id)
            ->set('interview_date', '2026-11-04T14:30')
            ->dispatch('resetFormForNewJob')
            ->assertSet('isEditing', false)
            ->assertSet('interview_date', '')
            ->dispatch('edit-job', jobId: $job->id)
            ->assertSet('interview_date', '2026-11-02T10:00')
            ->set('interview_date', '2026-11-05T09:15')
            ->call('save')->assertHasNoErrors()->assertDispatched('application-saved');

        $this->assertSame('2026-11-05 09:15', $job->fresh()->interview_date->format('Y-m-d H:i'));
    }

    public function test_reopening_same_tracker_application_reloads_the_saved_schedule(): void
    {
        $user = User::factory()->create();
        $job = JobApplication::factory()->create([
            'user_id' => $user->id, 'location' => 'Remote', 'platform' => 'LinkedIn',
            'recruitment_stage' => 'HR - Interview', 'interview_date' => '2026-11-02 10:00:00',
        ]);
        $form = Livewire::actingAs($user)->test(JobApplicationForm::class)->dispatch('edit-job', jobId: $job->id);
        $job->update(['interview_date' => '2026-11-06 11:00:00']);
        $form->dispatch('edit-job', jobId: $job->id)->assertSet('interview_date', '2026-11-06T11:00');
    }

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Setting::set('monetization_enabled', false);
    }

    public function test_assessment_form_uses_its_own_schedule_and_saves_online_platform_and_notes(): void
    {
        $user = User::factory()->create();
        Livewire::actingAs($user)->test(JobApplicationForm::class)
            ->set('company_name', 'Example')->set('position', 'Engineer')->set('isRemote', true)
            ->set('platform', 'LinkedIn')->set('recruitment_stage', 'Assessment Test')
            ->assertSee('Assessment schedule')->assertSee('Delivery method')->assertDontSee('Interview type')
            ->set('interview_type', 'Online')->assertSee('Online platform')
            ->set('interview_platform', 'Assessment portal')->set('interview_date', '2026-11-02T10:00')
            ->set('interview_location', 'https://example.test/test')->set('interview_notes', 'Bring a calculator')
            ->call('save')->assertHasNoErrors();
        $job = JobApplication::sole();
        $this->assertSame('Online', $job->interview_type);
        $this->assertSame('Assessment portal', $job->interview_platform);
        $this->assertSame('Bring a calculator', $job->interview_notes);
        $this->assertSame('Assessment Test', $job->recruitment_stage);
    }

    public function test_phone_is_not_a_new_assessment_method_and_platforms_are_validated(): void
    {
        Livewire::actingAs(User::factory()->create())->test(JobApplicationForm::class)
            ->set('company_name', 'Example')->set('position', 'Engineer')->set('isRemote', true)->set('platform', 'LinkedIn')
            ->set('recruitment_stage', 'Psychotest')->set('interview_type', 'Phone')->call('save')->assertHasErrors('interview_type')
            ->set('interview_type', 'Online')->set('interview_platform', 'Unlisted platform')->call('save')->assertHasErrors('interview_platform');
        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_legacy_schedule_is_preserved_and_onsite_does_not_keep_an_online_platform(): void
    {
        $user = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $user->id, 'location' => 'Remote', 'platform' => 'LinkedIn', 'career_level' => 'Full Time', 'application_status' => 'On Process', 'recruitment_stage' => 'Psychotest', 'interview_type' => 'Video', 'interview_date' => '2026-11-02 10:00:00', 'interview_platform' => 'Zoom']);
        Livewire::actingAs($user)->test(JobApplicationForm::class)->call('editJob', $job->id)
            ->set('notes', 'Unrelated edit')->call('save')->assertHasNoErrors();
        $this->assertSame('Video', $job->fresh()->interview_type);
        $this->assertSame('Zoom', $job->fresh()->interview_platform);
        Livewire::actingAs($user)->test(InterviewCalendar::class)->call('editInterview', $job->id)
            ->assertSee('Psychometric test schedule')->set('scheduleType', 'Onsite')->set('scheduleLocation', 'Testing center')
            ->call('saveSchedule')->assertHasNoErrors();
        $this->assertSame('Onsite', $job->fresh()->interview_type);
        $this->assertNull($job->fresh()->interview_platform);
    }

    public function test_csv_keeps_the_online_platform_with_the_schedule(): void
    {
        $user = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $user->id, 'recruitment_stage' => 'Assessment Test', 'interview_type' => 'Online', 'interview_platform' => 'Assessment portal']);
        $headers = \App\Support\JobApplicationCsv::HEADERS;
        $row = \App\Support\JobApplicationCsv::row($job);
        $this->assertSame('Assessment portal', $row[array_search('Interview Platform', $headers)]);
        $method = new \ReflectionMethod(\App\Http\Controllers\JobApplicationImportExportController::class, 'importRow');
        $data = ['company_name'=>'Example','position'=>'Engineer','location'=>'Remote','platform'=>'LinkedIn','application_status'=>'On Process','recruitment_stage'=>'Assessment Test','career_level'=>'Full Time','application_date'=>'2026-10-18','interview_type'=>'Online','interview_platform'=>'Assessment portal','interview_date'=>'2026-11-02 10:00'];
        $result = $method->invoke(new \App\Http\Controllers\JobApplicationImportExportController, $data, 1);
        $this->assertTrue($result['success']);
        $this->assertSame('Assessment portal', $result['data']['interview_platform']);
    }
    public function test_every_scheduled_stage_has_a_contextual_form_and_phone_is_only_offered_for_interviews(): void
    {
        $component = Livewire::actingAs(User::factory()->create())->test(JobApplicationForm::class);
        foreach (['Psychotest' => 'Psychometric test schedule', 'Assessment Test' => 'Assessment schedule', 'LGD' => 'Group discussion schedule', 'Presentation Round' => 'Presentation schedule', 'HR - Interview' => 'Interview schedule', 'User - Interview' => 'Interview schedule'] as $stage => $heading) {
            $component->set('recruitment_stage', $stage)->assertSee($heading)->assertSee('Date &amp; time · WIB', false);
            if (str_contains($stage, 'Interview')) $component->assertSeeHtml('<option value="Phone">Phone call</option>');
            else $component->assertDontSeeHtml('<option value="Phone">');
        }
    }

    public function test_platform_is_reset_for_a_new_application_and_legacy_panel_is_preserved(): void
    {
        $user = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $user->id, 'platform' => 'LinkedIn', 'location' => 'Remote', 'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview', 'interview_type' => 'Panel', 'interview_date' => now()->addDay()]);
        Livewire::actingAs($user)->test(JobApplicationForm::class)->call('editJob', $job->id)->assertSee('Panel (existing record)')
            ->set('notes', 'Updated')->call('save')->assertHasNoErrors();
        $this->assertSame('Panel', $job->fresh()->interview_type);
        Livewire::actingAs($user)->test(JobApplicationForm::class)->set('interview_platform', 'Zoom')->call('resetFormForNewJob', 'Assessment Test')->assertSet('interview_platform', '');
    }

    public function test_selection_emails_describe_the_actual_stage_and_online_platform(): void
    {
        $job = JobApplication::factory()->create(['recruitment_stage' => 'Psychotest', 'interview_type' => 'Online', 'interview_platform' => 'Assessment portal', 'interview_date' => now()->addDay()]);
        $html = (new \App\Mail\InterviewScheduledMail($job))->render();
        $this->assertStringContainsString('Psychometric test schedule', $html);
        $this->assertStringContainsString('Assessment portal', $html);
        $this->assertStringNotContainsString('Interview Confirmation', $html);
    }

    public function test_scheduling_still_works_before_the_platform_migration_is_applied(): void
    {
        \Illuminate\Support\Facades\Schema::table('job_applications', fn ($table) => $table->dropColumn('interview_platform'));
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(JobApplicationForm::class)
            ->set('company_name', 'Example')->set('position', 'Engineer')->set('isRemote', true)
            ->set('platform', 'LinkedIn')->set('recruitment_stage', 'Assessment Test')
            ->set('interview_type', 'Online')->set('interview_date', '2026-11-02T10:00')
            ->assertDontSeeHtml('wire:model="interview_platform"')
            ->call('save')->assertHasNoErrors();

        $job = JobApplication::sole();
        Livewire::actingAs($user)->test(InterviewCalendar::class)->call('editInterview', $job->id)
            ->set('scheduleTime', '11:00')->call('saveSchedule')->assertHasNoErrors();
        $this->assertSame('11:00', $job->fresh()->interview_date->format('H:i'));
    }

}
