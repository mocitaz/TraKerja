<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityWelcomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_in_user_can_open_welcome_page_with_local_visuals_and_real_actions(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/community')->assertOk()->assertSee('Welcome back')
            ->assertSee('https://t.me/HubTrakerja', false)->assertSee(route('dashboard'), false)
            ->assertSee('/images/avatars/peach.svg', false)->assertSee('Join HubTrakerja')
            ->assertDontSee('real-time')->assertDontSee('HUBTRAKERJA · ON TELEGRAM')->assertDontSee('Community topics')->assertDontSee('Career conversations')->assertDontSee('Peer support')->assertViewIs('community.index')->assertDontSee('community-modal-title', false);
    }

    public function test_login_popup_has_a_named_dialog_and_can_be_dismissed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->withSession(['show_telegram_popup' => true])->get('/dashboard')->assertOk()
            ->assertSee('aria-labelledby="community-modal-title"', false)->assertSee('x-dialog="open"', false)
            ->assertSee('Close welcome message')->assertSee('Go to Dashboard')->assertSee('community-modal-description', false);
    }

    public function test_welcome_page_does_not_repeat_the_login_popup_and_requires_sign_in(): void
    {
        $this->get('/community')->assertRedirect(route('login'));
        $user = User::factory()->create();
        $this->actingAs($user)->withSession(['show_telegram_popup' => true])->get('/community')->assertOk()
            ->assertDontSee('community-modal-title', false)->assertSee('community-page-title', false);
    }
}
