<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MembershipPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now()->setDate(2026, 10, 9)->setTime(12, 0));
        Http::preventStrayRequests();
        Setting::set('monetization_enabled', true);
    }

    public function test_guest_and_admin_cannot_access_a_customer_portal(): void
    {
        $this->get('/membership')->assertRedirect(route('login'));
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);
        $this->actingAs($admin)->get('/membership')->assertForbidden();
    }

    public function test_active_member_sees_real_expiry_balances_and_only_their_payments(): void
    {
        $user = User::factory()->create(['is_premium' => true, 'payment_status' => 'paid', 'ai_credits' => 7, 'cl_credits' => 12, 'photo_credits' => 2]);
        $user->forceFill(['premium_until' => now()->addDays(20)])->save();
        $own = Payment::create(['user_id' => $user->id, 'order_id' => 'OWN-ORDER', 'payment_channel_code' => 'qris', 'customer_name' => 'Test User', 'customer_email' => 'test@example.test', 'customer_phone' => '0800000000', 'amount' => 14999, 'status' => 'WAITING', 'notes' => 'addon_10']);
        Payment::create(['user_id' => User::factory()->create()->id, 'order_id' => 'PRIVATE-OTHER-ORDER', 'payment_channel_code' => 'qris', 'customer_name' => 'Test User', 'customer_email' => 'test@example.test', 'customer_phone' => '0800000000', 'amount' => 9999, 'status' => 'SUCCESS']);
        $this->actingAs($user)->get('/membership')->assertOk()->assertSee('Premium active')
            ->assertSee('29 Oct 2026')->assertSee('OWN-ORDER')->assertDontSee('PRIVATE-OTHER-ORDER')
            ->assertSee(route('payment.waiting', $own->order_id), false)
            ->assertViewHas('creditBalances', fn ($balances) => array_column($balances, 'balance') === [7, 12, 2])
            ->assertSee(route('payment.topup'), false);
        $this->assertSame('WAITING', $own->fresh()->status);
        $this->assertSame(7, $user->fresh()->ai_credits);
        Http::assertNothingSent();
    }

    public function test_expired_and_indefinite_memberships_are_not_mislabelled(): void
    {
        $user = User::factory()->create(['is_premium' => true, 'payment_status' => 'paid']);
        $user->forceFill(['premium_until' => now()->subDay()])->save();
        $this->actingAs($user)->get('/membership')->assertOk()->assertSee('Premium expired')
            ->assertSee('Reactivate Premium')->assertDontSee('href="'.route('payment.topup').'"', false);
        $user->forceFill(['premium_until' => null])->save();
        $this->actingAs($user->fresh())->get('/membership')->assertOk()->assertSee('Premium active')->assertSee('No expiry recorded');
        $this->actingAs($user->fresh())->get(route('payment.premium'))->assertRedirect('/membership');
    }

    public function test_free_mode_explains_access_and_dropdown_always_links_to_the_portal(): void
    {
        $user = User::factory()->create(['is_premium' => false, 'payment_status' => 'free']);
        Setting::set('monetization_enabled', false);
        $this->actingAs($user)->get('/membership')->assertOk()->assertSee('Free account')->assertSee('Free access is enabled')
            ->assertSee('No payments yet.')->assertDontSee('href="'.route('payment.topup').'"', false)
            ->assertDontSee('href="'.route('payment.premium').'"', false);
        $this->actingAs($user)->get(route('dashboard'))->assertOk()->assertSee('Membership &amp; Credits', false)
            ->assertSee('href="'.url('/membership').'"', false);
    }

    public function test_pending_payment_notice_is_owned_unexpired_and_visible_beyond_the_history_page(): void
    {
        $user = User::factory()->create();
        $attributes = ['payment_channel_code' => 'qris', 'customer_name' => 'Test User', 'customer_email' => 'test@example.test', 'customer_phone' => '0800000000', 'amount' => 9999];
        $pending = Payment::create($attributes + ['user_id' => $user->id, 'order_id' => 'PENDING-OWN', 'status' => 'WAITING', 'expired_at' => now()->addDay()]);
        foreach (range(1, 9) as $number) {
            Payment::create($attributes + ['user_id' => $user->id, 'order_id' => 'PAID-'.$number, 'status' => 'SUCCESS']);
        }
        Payment::create($attributes + ['user_id' => $user->id, 'order_id' => 'EXPIRED-OWN', 'status' => 'WAITING', 'expired_at' => now()->subDay()]);
        Payment::create($attributes + ['user_id' => User::factory()->create()->id, 'order_id' => 'FOREIGN-PENDING', 'status' => 'WAITING', 'expired_at' => now()->addDay()]);
        $this->actingAs($user)->get('/membership')->assertOk()
            ->assertViewHas('pendingPayment', fn ($payment) => $payment->id === $pending->id)
            ->assertSee('A payment is waiting for you')->assertSee('PENDING-OWN')->assertDontSee('FOREIGN-PENDING');
        $pending->update(['status' => 'SUCCESS']);
        $this->get('/membership')->assertOk()->assertViewHas('pendingPayment', null)->assertDontSee('A payment is waiting for you');
    }

    public function test_payment_history_is_paginated_and_expired_orders_cannot_offer_resume(): void
    {
        $user = User::factory()->create();
        foreach (range(1, 10) as $number) {
            Payment::create(['user_id' => $user->id, 'order_id' => 'ORDER-'.$number, 'payment_channel_code' => 'qris', 'customer_name' => 'Test User', 'customer_email' => 'test@example.test', 'customer_phone' => '0800000000', 'amount' => 9999, 'status' => 'WAITING', 'expired_at' => now()->subDay()]);
        }
        $this->actingAs($user)->get('/membership')->assertOk()
            ->assertViewHas('payments', fn ($payments) => $payments->total() === 10 && $payments->count() === 8)
            ->assertDontSee('Resume payment');
    }
}
