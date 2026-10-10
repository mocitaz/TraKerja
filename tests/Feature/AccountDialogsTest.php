<?php

namespace Tests\Feature;

use App\Livewire\Layout\GamificationWidget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AccountDialogsTest extends TestCase
{
    use RefreshDatabase;

    public function test_rewards_show_actual_level_progress_and_next_threshold(): void
    {
        $user = User::factory()->create(['xp' => 225, 'level' => 2]);
        Livewire::actingAs($user)->test(GamificationWidget::class)
            ->assertSee('225')->assertSee('75 XP to Level 3')->assertSee('Proactive Seeker')
            ->assertSee('aria-valuenow="63"', false)->assertSee('Close rewards')
            ->assertSee('Selection stage')->assertSee('Offering or accepted');
        $this->assertSame(225, $user->fresh()->xp);
    }

    public function test_maximum_level_does_not_show_a_fake_next_level(): void
    {
        $user = User::factory()->create(['xp' => 1300, 'level' => 5]);
        Livewire::actingAs($user)->test(GamificationWidget::class)->assertSee('Highest level unlocked')
            ->assertSee('aria-valuenow="100"', false)->assertDontSee('XP to Level 6');
    }

    public function test_logout_confirmation_retains_post_and_csrf_and_is_named(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard')->assertOk();
        $response->assertSee('data-dialog-native="closeConfirmModal"', false)->assertSee('aria-describedby="confirm-message"', false)
            ->assertSee('confirm-error', false)->assertSee('Stay here')->assertSee('Sign out of TraKerja?')
            ->assertSee('prepareTraKerjaLogout', false)->assertSee('name="_token"', false);
        $this->assertAuthenticatedAs($user);
    }
}
