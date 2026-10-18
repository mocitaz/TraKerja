<?php
namespace Tests\Feature;

use App\Livewire\InterviewCalendar;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InterviewWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calendar_agenda_and_metrics_follow_the_same_owner_month_and_stage_filter(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 10, 9)->setTime(12, 0));
        $user = User::factory()->create();
        foreach (['HR - Interview', 'Assessment Test'] as $stage) JobApplication::factory()->create(['user_id' => $user->id, 'company_name' => $stage.' Corp', 'recruitment_stage' => $stage, 'interview_date' => '2026-10-10 09:00:00']);
        JobApplication::factory()->create(['user_id' => $user->id, 'company_name' => 'Next month Corp', 'recruitment_stage' => 'HR - Interview', 'interview_date' => '2026-11-01 10:00:00']);
        JobApplication::factory()->create(['company_name' => 'Other owner Corp', 'recruitment_stage' => 'HR - Interview', 'interview_date' => '2026-10-10 09:00:00']);
        JobApplication::factory()->create(['user_id' => $user->id, 'company_name' => 'Archived Corp', 'is_archived' => true, 'recruitment_stage' => 'HR - Interview', 'interview_date' => '2026-10-10 09:00:00']);
        Livewire::actingAs($user)->test(InterviewCalendar::class)->assertViewHas('monthTotal', 2)->assertViewHas('upcomingTotal', 2)
            ->assertDontSee('Other owner Corp')->assertDontSee('Archived Corp')->assertSee('Next month Corp')
            ->set('viewMode', 'list')->assertViewHas('agenda', fn ($list) => $list->total() === 2)
            ->set('filterType', 'Assessment Test')->assertViewHas('monthTotal', 1)
            ->call('selectDate', '2026-10-10')->assertViewHas('daySchedule', fn ($list) => $list->count() === 1)
            ->call('resetSelectedDate')->assertSet('selectedDate', null);
    }

    public function test_year_navigation_and_today_reset_selection(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 12, 15)->setTime(12, 0));
        Livewire::actingAs(User::factory()->create())->test(InterviewCalendar::class)->call('nextMonth')
            ->assertSet('currentMonth', 1)->assertSet('currentYear', 2027)->call('previousMonth')
            ->assertSet('currentMonth', 12)->assertSet('currentYear', 2026)->call('selectDate', '2026-11-30')
            ->assertSet('currentMonth', 11)->call('goToToday')->assertSet('currentMonth', 12)->assertSet('selectedDate', null);
    }

    public function test_details_and_editing_are_owner_scoped_with_safe_meeting_links(): void
    {
        $user = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $user->id, 'recruitment_stage' => 'HR - Interview', 'interview_date' => now()->addDay(), 'interview_location' => 'javascript:alert(1)', 'interview_notes' => '<script>unsafe()</script>']);
        Livewire::actingAs($user)->test(InterviewCalendar::class)->call('viewInterviewDetails', $job->id)
            ->assertSet('selectedInterviewId', $job->id)->assertSee('Interview schedule')
            ->assertDontSee('href="javascript:', false)->assertDontSee('<script>unsafe()</script>', false)
            ->call('closeModal')->assertSet('selectedInterviewId', null)->call('editInterview', $job->id)
            ->assertSet('editingSchedule', true)->assertSet('selectedInterviewId', $job->id)->assertNoRedirect();
        $this->assertThrows(fn () => Livewire::actingAs(User::factory()->create())->test(InterviewCalendar::class)->call('viewInterviewDetails', $job->id), \Illuminate\Database\Eloquent\ModelNotFoundException::class);
    }

    public function test_agenda_is_paginated_and_empty_month_is_honest(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 10, 9)->setTime(12, 0));
        $user = User::factory()->create();
        JobApplication::factory()->count(23)->create(['user_id' => $user->id, 'recruitment_stage' => 'HR - Interview', 'interview_date' => '2026-10-15 09:00:00']);
        Livewire::actingAs($user)->test(InterviewCalendar::class)->set('viewMode', 'list')
            ->assertViewHas('agenda', fn ($list) => $list->total() === 23 && $list->count() === 20)
            ->call('nextMonth')->assertViewHas('monthTotal', 0)->assertSee('No schedules this month.');
    }
    public function test_focus_agenda_crosses_months_filters_and_paginates_without_changing_calendar_month(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 10, 30)->setTime(12, 0));
        $user = User::factory()->create();
        foreach (range(1, 7) as $day) JobApplication::factory()->create(['user_id' => $user->id, 'recruitment_stage' => 'Assessment Test', 'interview_date' => now()->startOfDay()->addDays($day)->setTime(9, 0)]);
        JobApplication::factory()->create(['user_id' => $user->id, 'recruitment_stage' => 'HR - Interview', 'interview_date' => now()->setTime(15, 0)]);
        Livewire::actingAs($user)->test(InterviewCalendar::class)
            ->assertViewHas('focusAgenda', fn ($rows) => $rows->total() === 7 && $rows->count() === 5)
            ->assertSet('currentMonth', 10)
            ->call('setFocusRange', 'today')->assertViewHas('focusAgenda', fn ($rows) => $rows->total() === 1)
            ->call('nextMonth')->assertViewHas('focusAgenda', fn ($rows) => $rows->total() === 1)
            ->set('filterType', 'Assessment Test')->assertViewHas('focusAgenda', fn ($rows) => $rows->total() === 0)
            ->call('setFocusRange', 'week')->assertViewHas('focusAgenda', fn ($rows) => $rows->total() === 6);
    }

    public function test_inline_reschedule_validates_input_preserves_stage_and_keeps_month(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        $user = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $user->id, 'recruitment_stage' => 'Assessment Test', 'interview_date' => now()->addDay(), 'interview_type' => 'Video']);
        $month = now('Asia/Jakarta')->month;
        Livewire::actingAs($user)->test(InterviewCalendar::class)->call('editInterview', $job->id)
            ->set('scheduleLocation', str_repeat('x', 256))->call('saveSchedule')->assertHasErrors('scheduleLocation')
            ->set('scheduleLocation', '')->set('scheduleTime', '25:01')->call('saveSchedule')->assertHasErrors('scheduleTime')
            ->set('scheduleTime', '14:30')->set('scheduleDate', '2026-11-02')->set('scheduleLocation', 'https://example.test/meeting')
            ->set('scheduleNotes', 'Bring the case study')->call('saveSchedule')->assertHasNoErrors()
            ->assertSet('editingSchedule', false)->assertSet('currentMonth', $month)->assertNoRedirect();
        $job->refresh();
        $this->assertSame('2026-11-02 14:30', $job->interview_date->format('Y-m-d H:i'));
        $this->assertSame('Assessment Test', $job->recruitment_stage);
        $this->assertSame('Bring the case study', $job->interview_notes);
    }

    public function test_inline_reschedule_refuses_stale_data_and_other_owners(): void
    {
        $user = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $user->id, 'recruitment_stage' => 'HR - Interview', 'interview_date' => now()->addDay()]);
        $component = Livewire::actingAs($user)->test(InterviewCalendar::class)->call('editInterview', $job->id);
        $job->update(['interview_notes' => 'Saved in another tab']);
        $component->set('scheduleNotes', 'Stale overwrite')->call('saveSchedule')->assertHasErrors('scheduleDate');
        $this->assertSame('Saved in another tab', $job->fresh()->interview_notes);
        $this->assertThrows(fn () => Livewire::actingAs(User::factory()->create())->test(InterviewCalendar::class)->call('editInterview', $job->id), \Illuminate\Database\Eloquent\ModelNotFoundException::class);
    }

    public function test_reschedule_notifications_respect_preferences_and_do_not_repeat_for_notes_only(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        $user = User::factory()->create(['is_premium' => true, 'payment_status' => 'paid', 'premium_until' => now()->addDays(30), 'email_notifications_enabled' => false, 'notify_interview_reminders' => true]);
        $job = JobApplication::factory()->create(['user_id' => $user->id, 'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview', 'interview_type' => 'Video', 'interview_date' => now()->addDay()->setTime(10, 0)]);
        $component = Livewire::actingAs($user)->test(InterviewCalendar::class)->call('editInterview', $job->id)
            ->set('scheduleTime', '11:00')->call('saveSchedule')->assertHasNoErrors();
        \Illuminate\Support\Facades\Mail::assertNothingSent();
        $user->forceFill(['email_notifications_enabled' => true])->save();
        $component->call('editInterview', $job->id)->set('scheduleNotes', 'A new note')->call('saveSchedule')->assertHasNoErrors();
        \Illuminate\Support\Facades\Mail::assertNothingSent();
        $component->call('editInterview', $job->id)->set('scheduleTime', '12:00')->call('saveSchedule')->assertHasNoErrors();
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\InterviewScheduledMail::class, 1);
    }

}
