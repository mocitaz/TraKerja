<?php

namespace Tests\Feature;

use App\Livewire\Admin\Analytics;
use App\Livewire\Admin\UserActivities;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class AdminInsightsWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_filters_reset_and_detail_are_database_backed(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $user = User::factory()->create();
        $first = UserActivity::create(['user_id' => $user->id, 'activity_type' => 'login', 'description' => 'Signed in', 'status' => 'success', 'ip_address' => '127.0.0.1']);
        UserActivity::create(['user_id' => $user->id, 'activity_type' => 'ai_photo', 'description' => 'Provider unavailable', 'status' => 'failed']);
        Livewire::test(UserActivities::class)->set('filterStatus', 'failed')
            ->assertViewHas('activities', fn ($logs) => $logs->total() === 1)
            ->call('resetFilters')->assertViewHas('activities', fn ($logs) => $logs->total() === 2)
            ->call('showActivity', $first->id)->assertSee('127.0.0.1')->assertSet('selectedActivityId', $first->id)
            ->call('closeActivity')->assertSet('selectedActivityId', null)
            ->set('perPage', 1000000)->assertViewHas('activities', fn ($logs) => $logs->perPage() <= 100);
    }

    public function test_analytics_exports_and_timeline_boundaries_are_real(): void
    {
        $this->travelTo(now()->setDate(2026, 10, 10)->setTime(12, 0));
        Cache::flush();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        User::factory()->create(['created_at' => now()->subDays(6)->startOfDay()]);
        UserActivity::create(['activity_type' => 'csv_export', 'description' => 'Exported', 'status' => 'success']);
        UserActivity::create(['activity_type' => 'cv_export', 'description' => 'Failed export', 'status' => 'failed']);
        Livewire::test(Analytics::class)->set('periodFilter', '7')
            ->assertViewHas('stats', fn ($stats) => $stats['totalExports'] === 1)
            ->assertSet('userGrowth.total.6', 1)->assertSet('userRegistrationByDay.data.0', 1);
    }
}
