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
            ->assertDontSee('Other owner Corp')->assertDontSee('Archived Corp')->assertDontSee('Next month Corp')
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
            ->assertSet('selectedInterviewId', $job->id)->assertSee('Interview details')
            ->assertDontSee('href="javascript:', false)->assertDontSee('<script>unsafe()</script>', false)
            ->call('closeModal')->assertSet('selectedInterviewId', null)->call('editInterview', $job->id)
            ->assertRedirect(route('tracker', ['edit' => $job->id]));
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
}
