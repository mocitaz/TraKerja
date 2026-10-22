<?php

namespace Tests\Feature;

use App\Livewire\Admin\UserActivities;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_follows_filters_and_groups_failed_and_error_statuses(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $member = User::factory()->create();
        foreach (['success', 'failed', 'error'] as $status) {
            UserActivity::create(['user_id' => $member->id, 'activity_type' => 'login', 'description' => 'Account event', 'status' => $status, 'ip_address' => '192.0.2.7']);
        }
        UserActivity::create(['activity_type' => 'csv_export', 'description' => 'System export', 'status' => 'pending', 'ip_address' => '192.0.2.8']);

        Livewire::test(UserActivities::class)
            ->assertViewHas('stats', ['total' => 4, 'successful' => 1, 'failed' => 2, 'people' => 1])
            ->set('filterStatus', 'failed')
            ->assertViewHas('stats', ['total' => 2, 'successful' => 0, 'failed' => 2, 'people' => 1])
            ->assertViewHas('activities', fn ($rows) => $rows->total() === 2)
            ->set('search', '192.0.2.8')
            ->assertViewHas('stats', ['total' => 0, 'successful' => 0, 'failed' => 0, 'people' => 0])
            ->call('resetFilters')
            ->set('search', ' 192.0.2.8 ')
            ->assertViewHas('stats', ['total' => 1, 'successful' => 0, 'failed' => 0, 'people' => 0])
            ->assertSee('System / Guest')
            ->set('filterType', 'login')
            ->assertViewHas('activities', fn ($rows) => $rows->total() === 0);
    }

    public function test_detail_escapes_payload_and_renders_missing_identity_and_jakarta_time(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $record = UserActivity::create([
            'activity_type' => 'csv_export', 'description' => '<script>alert(1)</script>',
            'status' => 'error', 'metadata' => ['result' => '<script>alert(2)</script>'],
        ]);
        $record->forceFill(['created_at' => '2026-10-22 03:10:00'])->save();
        Livewire::test(UserActivities::class)->call('showActivity', $record->id)
            ->assertSee('System / Guest')->assertSee('Not recorded')
            ->assertSee('22 Oct 2026, 03:10:00')
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<script>alert(2)</script>', false)
            ->call('closeActivity')->assertSet('selectedActivityId', null);
    }

    public function test_retention_save_preserves_logs_and_invalid_cleanup_is_rejected(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $record = UserActivity::create(['activity_type' => 'login', 'description' => 'Old log', 'status' => 'success']);
        $record->forceFill(['created_at' => now()->subDays(200)])->save();
        Livewire::test(UserActivities::class)->set('pruneDays', 30)->call('saveSettings')
            ->assertHasNoErrors()->assertSet('showSettingsModal', false)
            ->set('pruneDays', 0)->call('cleanNow')->assertHasErrors(['pruneDays']);
        $this->assertSame('30', Setting::where('key', 'activity_log_prune_days')->value('value'));
        $this->assertDatabaseHas('user_activities', ['id' => $record->id]);
    }
}
