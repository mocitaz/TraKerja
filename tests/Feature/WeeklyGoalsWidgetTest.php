<?php

namespace Tests\Feature;

use App\Livewire\GoalsCadenceManager;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WeeklyGoalsWidgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_saved_monthly_goal_displays_the_matching_period_when_reopened(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 10, 18)->setTime(12, 0));
        $user = User::factory()->create();
        $user->goals()->create([
            'start_date' => '2026-10-01', 'end_date' => '2026-10-31',
            'target_applied_weekly' => 20, 'target_followup_weekly' => 0,
        ]);
        Livewire::actingAs($user)->test(GoalsCadenceManager::class)
            ->call('openGoalModal')->assertSet('goalPeriod', 'monthly')
            ->assertSet('startDate', '2026-10-01')->assertSet('endDate', '2026-10-31')
            ->assertSee('Monthly goals')->assertSee('This month');
    }

    public function test_invalid_targets_remain_in_dialog_and_do_not_save(): void
    {
        $user = User::factory()->create();
        Livewire::actingAs($user)->test(GoalsCadenceManager::class)
            ->call('openGoalModal')
            ->set('targetAppliedWeekly', 0)->set('targetFollowupWeekly', -1)
            ->call('setWeeklyGoals')
            ->assertHasErrors(['targetAppliedWeekly' => 'min', 'targetFollowupWeekly' => 'min'])
            ->assertSet('showGoalModal', true)
            ->assertSee('Minimum application target is 1')
            ->assertSee('Minimum follow-up target is 0');
        $this->assertDatabaseCount('user_goals', 0);
    }

    public function test_monthly_targets_use_the_current_month_and_report_the_correct_period(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 10, 9)->setTime(12, 0));
        $user = User::factory()->create();
        Livewire::actingAs($user)->test(GoalsCadenceManager::class)
            ->call('openGoalModal')->set('goalPeriod', 'monthly')
            ->assertSet('startDate', '2026-10-01')->assertSet('endDate', '2026-10-31')
            ->set('targetAppliedWeekly', 20)->set('targetFollowupWeekly', 0)
            ->call('setWeeklyGoals')->assertHasNoErrors()
            ->assertSet('showGoalModal', false)
            ->assertDispatched('showNotification', function ($event, $params) {
                return ($params[0]['message'] ?? null) === 'Monthly goals saved successfully!';
            });
        $this->assertDatabaseHas('user_goals', ['user_id' => $user->id, 'target_applied_weekly' => 20]);
    }

    public function test_unconfigured_targets_are_not_presented_as_achieved_and_can_be_saved(): void
    {
        $user = User::factory()->create();
        JobApplication::factory()->count(6)->create(['user_id' => $user->id, 'application_date' => now()]);

        Livewire::actingAs($user)->test(GoalsCadenceManager::class)
            ->assertSee('Set a target')->assertDontSee('Target reached')
            ->call('openGoalModal')->assertSet('showGoalModal', true)
            ->set('targetAppliedWeekly', 5)->set('targetFollowupWeekly', 3)
            ->call('setWeeklyGoals')->assertHasNoErrors()
            ->assertSet('showGoalModal', false)->assertSee('Target reached')->assertSee('Edit goals');

        $this->assertDatabaseHas('user_goals', [
            'user_id' => $user->id, 'target_applied_weekly' => 5, 'target_followup_weekly' => 3,
        ]);
    }

    public function test_targets_can_be_adjusted_and_zero_followup_target_is_clear(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 10, 5)->setTime(12, 0));
        $user = User::factory()->create();
        $goal = $user->goals()->create([
            'start_date' => now('Asia/Jakarta')->startOfWeek()->toDateString(),
            'end_date' => now('Asia/Jakarta')->endOfWeek()->toDateString(),
            'target_applied_weekly' => 5, 'target_followup_weekly' => 3,
        ]);

        Livewire::actingAs($user)->test(GoalsCadenceManager::class)
            ->call('openGoalModal')->set('targetAppliedWeekly', 8)->set('targetFollowupWeekly', 0)
            ->call('setWeeklyGoals')->assertHasNoErrors()
            ->assertSee('No target set')->assertSee('of 8 this week');

        $this->assertSame(8, $goal->fresh()->target_applied_weekly);
        $this->assertSame(0, $goal->fresh()->target_followup_weekly);
        $this->assertDatabaseCount('user_goals', 1);
    }
}
