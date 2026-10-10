<?php

namespace Tests\Feature;

use App\Livewire\DashboardActivity;
use App\Livewire\DashboardNextMoves;
use App\Models\JobApplication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardInteractionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_calendar_uses_owned_data_and_cannot_navigate_into_the_future(): void
    {
        $this->travelTo(Carbon::parse('2026-10-10 12:00:00'));
        $owner = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $owner->id, 'application_date' => '2026-10-05']);
        JobApplication::factory()->create(['application_date' => '2026-10-05']);
        Livewire::actingAs($owner)->test(DashboardActivity::class)
            ->assertSet('calendarMonth', '2026-10')
            ->assertViewHas('calendarDays', fn ($days) => collect($days)->firstWhere('date', '2026-10-05')['count'] === 1)
            ->call('moveMonth', -1)->assertSet('calendarMonth', '2026-09')
            ->call('moveMonth', 1)->assertSet('calendarMonth', '2026-10')
            ->call('moveMonth', 1)->assertStatus(422);
    }

    public function test_activity_day_shows_only_owned_applications_and_includes_archived(): void
    {
        $owner = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => 'Own archived company', 'application_date' => today(), 'is_archived' => true]);
        JobApplication::factory()->create(['company_name' => 'Private other company', 'application_date' => today()]);
        Livewire::actingAs($owner)->test(DashboardActivity::class)->call('selectDate', today()->format('Y-m-d'))->assertSet('showDay', true)->assertSee('Own archived company')->assertDontSee('Private other company')->call('setRange', 3)->assertSet('months', 3)->assertSet('showDay', false);
    }

    public function test_mobile_calendar_stops_at_the_selected_history_window(): void
    {
        $this->travelTo(Carbon::parse('2026-10-10 12:00:00'));
        $component = Livewire::actingAs(User::factory()->create())->test(DashboardActivity::class)->call('setRange', 3);
        foreach (range(1, 3) as $month) {
            $component->call('moveMonth', -1);
        }
        $component->assertSet('calendarMonth', '2026-07')->call('moveMonth', -1)->assertStatus(422);
    }

    public function test_followup_completion_and_undo_are_persisted_and_owner_scoped(): void
    {
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => 'Due company', 'application_date' => today()->subDays(20), 'application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'interview_date' => null]);
        $component = Livewire::actingAs($owner)->test(DashboardNextMoves::class)->call('complete', 'followup', $job->id)->assertSee('Follow-up recorded');
        $this->assertDatabaseHas('dashboard_actions', ['user_id' => $owner->id, 'job_application_id' => $job->id, 'action' => 'followup']);
        Livewire::actingAs($owner)->test(DashboardNextMoves::class)->assertDontSee('Time to follow up');
        $component->call('undo')->assertSee('Time to follow up');
        $foreign = JobApplication::factory()->create();
        Livewire::actingAs($owner)->test(DashboardNextMoves::class)->call('complete', 'followup', $foreign->id)->assertNotFound();
    }

    public function test_activity_rejects_future_dates_and_shows_an_empty_day_honestly(): void
    {
        $owner = User::factory()->create();
        Livewire::actingAs($owner)->test(DashboardActivity::class)
            ->call('selectDate', today()->addDay()->format('Y-m-d'))->assertStatus(422);
        Livewire::actingAs($owner)->test(DashboardActivity::class)
            ->call('selectDate', today()->format('Y-m-d'))->assertSet('showDay', true)
            ->assertViewHas('dayApplications', fn ($applications) => $applications->total() === 0);
    }

    public function test_snooze_expires_the_next_day_without_marking_complete(): void
    {
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $owner->id, 'application_date' => today()->subDays(20), 'application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'interview_date' => null]);
        Livewire::actingAs($owner)->test(DashboardNextMoves::class)->call('snooze', 'followup', $job->id)->assertDontSee('Time to follow up');
        $this->assertDatabaseHas('dashboard_actions', ['user_id' => $owner->id, 'job_application_id' => $job->id, 'completed_at' => null]);
        $this->travel(1)->days();
        Livewire::actingAs($owner)->test(DashboardNextMoves::class)->assertSee('Time to follow up');
    }

    public function test_prepared_interview_resets_when_its_schedule_changes(): void
    {
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview', 'interview_date' => now()->addDay()]);
        Livewire::actingAs($owner)->test(DashboardNextMoves::class)->call('complete', 'interview', $job->id)->assertSee('You’re prepared');
        $job->update(['interview_date' => now()->addDays(2)]);
        Livewire::actingAs($owner)->test(DashboardNextMoves::class)->assertSee('Prepare for your interview');
    }
}
