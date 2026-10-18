<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\PaymentGrant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    private function order(User $user, string $status = 'WAITING', array $extra = []): Payment
    {
        return Payment::create(array_merge(['user_id' => $user->id, 'order_id' => 'ORDER-'.$user->id.'-'.$status,
            'amount' => 14999, 'status' => $status, 'notes' => 'addon_10', 'customer_name' => $user->name,
            'customer_email' => $user->email, 'customer_phone' => '0800000000', 'redirect_url' => 'https://app.pakasir.com/pay/test/14999'], $extra));
    }

    public function test_checkout_displays_configured_price_duration_and_complete_addon_bundle(): void
    {
        Http::preventStrayRequests();
        config(['pakasir.premium_duration_days' => 180]);
        $user = User::factory()->create();
        $this->actingAs($user)->get('/premium')->assertOk()->assertSee('180 days')->assertSee('Order summary')
            ->assertSee('Choose your bank on Pakasir')->assertDontSee('Lifetime Access');
        $user->forceFill(['is_premium' => true, 'payment_status' => 'paid', 'premium_until' => now()->addDays(10)])->save();
        $this->get('/payment/top-up?package=cover_letter')->assertOk()->assertSee('Credit pack')
            ->assertSee('10 AI credits')->assertSee('15 cover letters')->assertSee('5 photo credits')->assertSee('Rp 14.999');
        Http::assertNothingSent();
    }

    public function test_route_name_cannot_override_real_status_and_expired_order_cannot_be_resumed(): void
    {
        Http::preventStrayRequests();
        $user = User::factory()->create();
        $order = $this->order($user);
        $this->actingAs($user)->get(route('payment.success', $order->order_id))->assertOk()
            ->assertSee('Awaiting payment')->assertDontSee('Payment received');
        $order->forceFill(['expired_at' => now()->subMinute()])->save();
        $this->get(route('payment.waiting', $order->order_id))->assertOk()->assertSee('Payment expired')
            ->assertDontSee('Resume payment')->assertDontSee('paymentWaitingStatus', false);
        $this->get(route('payment.history'))->assertOk()->assertSee('Expired')->assertDontSee('Resume payment')->assertViewHas('stats', fn ($stats) => $stats['pending'] === 0 && $stats['failed'] === 1);
        Http::assertNothingSent();
    }

    public function test_receipt_uses_actual_grants_and_owner_only_history(): void
    {
        $user = User::factory()->create();
        $order = $this->order($user, 'SUCCESS', ['paid_at' => now()]);
        PaymentGrant::create(['payment_id' => $order->id, 'benefit' => 'ai_credits', 'quantity' => 10, 'granted_at' => now()]);
        $other = User::factory()->create();
        $foreign = $this->order($other, 'FAILED', ['order_id' => 'PRIVATE-OTHER-ORDER']);
        $this->actingAs($user)->get(route('payment.failed', $order->order_id))->assertOk()
            ->assertSee('Payment received')->assertSee('10 AI credits')->assertDontSee('15 cover letters')->assertDontSee('Resume payment');
        $this->get(route('payment.history'))->assertOk()->assertSee($order->order_id)->assertDontSee($foreign->order_id);
        $this->get(route('payment.success', $foreign->order_id))->assertRedirect();
    }

    public function test_unsafe_provider_link_is_not_rendered(): void
    {
        $user = User::factory()->create();
        $order = $this->order($user, 'WAITING', ['redirect_url' => 'javascript:alert(1)']);
        $this->actingAs($user)->get(route('payment.waiting', $order->order_id))->assertOk()
            ->assertDontSee('javascript:alert', false)->assertDontSee('Resume payment');
    }
    public function test_checkout_accepts_qris_and_bank_transfer_and_rejects_unknown_methods(): void
    {
        Http::preventStrayRequests();
        config(['pakasir.slug' => 'test-merchant']);
        $user = User::factory()->create();
        foreach (['qris', 'bank_transfer'] as $method) {
            $response = $this->actingAs($user)->post(route('payment.checkout'), [
                'package_type' => 'premium', 'payment_channel_code' => $method,
            ]);
            $response->assertRedirect();
            $this->assertStringStartsWith('https://app.pakasir.com/pay/test-merchant/', $response->headers->get('Location'));
            $this->assertSame($method === 'qris', str_contains($response->headers->get('Location'), 'qris_only=1'));
            $this->assertDatabaseHas('payments', ['user_id' => $user->id, 'status' => 'WAITING', 'payment_channel_code' => $method]);
        }
        $this->post(route('payment.checkout'), ['package_type' => 'premium', 'payment_channel_code' => 'unknown'])
            ->assertSessionHasErrors('payment_channel_code');
        $this->assertDatabaseCount('payments', 2);
        Http::assertNothingSent();
    }

    public function test_status_copy_and_recorded_benefits_follow_the_selected_language(): void
    {
        $user = User::factory()->create(['locale' => 'id']);
        $order = $this->order($user, 'SUCCESS', ['paid_at' => now()]);
        PaymentGrant::create(['payment_id' => $order->id, 'benefit' => 'ai_credits', 'quantity' => 10, 'granted_at' => now()]);

        $this->actingAs($user)->get(route('payment.success', $order->order_id))->assertOk()
            ->assertSee('Pembayaran diterima')->assertSee('10 kredit AI')
            ->assertDontSee('Your payment has been verified');
    }

    public function test_coming_soon_keeps_existing_orders_reachable_without_claiming_a_waitlist(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/payment/coming-soon')->assertOk()
            ->assertSee(route('payment.history'), false)->assertSee(route('membership.index'), false)
            ->assertDontSee('65% Complete')->assertDontSee("You're on the list");
    }

}
