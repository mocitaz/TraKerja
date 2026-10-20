<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PakasirV2CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['pakasir.slug' => 'test-merchant', 'pakasir.api_key' => 'test-key',
            'pakasir.webhook_secret' => 'test-secret', 'pakasir.sandbox' => false]);
        Http::preventStrayRequests();
        Mail::fake();
    }

    private function order(User $user, array $attributes = []): Payment
    {
        return Payment::create(array_replace(['user_id' => $user->id, 'order_id' => 'ORDER-V2', 'amount' => 14999,
            'pakasir_txn_id' => 'txn-v2', 'pakasir_is_sandbox' => false, 'status' => 'WAITING', 'notes' => 'addon_10',
            'customer_name' => $user->name, 'customer_email' => $user->email, 'customer_phone' => '0800000000',
            'redirect_url' => 'https://app.pakasir.com/pay-v2/txn-v2'], $attributes));
    }

    public function test_checkout_persists_provider_identity_and_return_order_before_redirecting(): void
    {
        $user = User::factory()->create();
        Http::fake(['app.pakasir.com/*' => Http::response(['txn_id' => 'txn-created', 'payment_link' => 'https://app.pakasir.com/pay-v2/txn-created'])]);
        $response = $this->actingAs($user)->post('/payment/checkout', ['payment_channel_code' => 'qris']);
        $response->assertRedirect();
        $payment = Payment::where('user_id', $user->id)->sole();
        $this->assertSame('txn-created', $payment->pakasir_txn_id);
        $this->assertFalse($payment->pakasir_is_sandbox);
        $this->assertSame(2, $payment->metadata['pakasir_api_version']);
        $this->assertSame('WAITING', $payment->status);
        $this->assertSame($payment->redirect_url, $response->headers->get('Location'));
        parse_str(parse_url($payment->redirect_url, PHP_URL_QUERY), $query);
        $this->assertSame(route('payment.callback', ['order_id' => $payment->order_id]), $query['redirect']);
        $this->assertFalse($user->fresh()->isPremium());
    }

    public function test_provider_failure_cannot_create_a_waiting_order_or_expose_provider_response(): void
    {
        $user = User::factory()->create();
        Http::fake(['app.pakasir.com/*' => Http::response(['message' => 'private-provider-message'], 500)]);
        $this->actingAs($user)->from('/premium')->post('/payment/checkout', ['payment_channel_code' => 'qris'])
            ->assertRedirect('/premium')->assertSessionHasErrors('payment_error');
        $this->assertDatabaseCount('payments', 0);
        $this->assertFalse($user->fresh()->isPremium());
        $this->assertStringNotContainsString('private-provider-message', session('errors')->first('payment_error'));
    }

    public function test_polling_records_verified_cancellation(): void
    {
        $user = User::factory()->create();
        $order = $this->order($user);
        Http::fake(['app.pakasir.com/*' => Http::response(['txn_id' => 'txn-v2', 'order_id' => 'ORDER-V2',
            'amount' => 14999, 'is_sandbox' => false, 'status' => 'canceled'])]);
        $this->actingAs($user)->getJson('/payment/check-status/ORDER-V2')->assertOk()->assertJsonPath('status', 'CANCELED');
        $this->assertSame('CANCELED', $order->fresh()->status);
        $this->assertDatabaseCount('payment_grants', 0);
    }

    public function test_browser_return_and_webhook_share_verification_and_grant_only_once(): void
    {
        $user = User::factory()->create(['ai_credits' => 0, 'cl_credits' => 0, 'photo_credits' => 0]);
        $order = $this->order($user);
        $transaction = ['txn_id' => 'txn-v2', 'order_id' => 'ORDER-V2', 'amount' => 14999,
            'is_sandbox' => false, 'status' => 'completed'];
        Http::fake(['app.pakasir.com/*' => Http::response($transaction)]);
        $this->actingAs($user)->get('/payment/callback?order_id=ORDER-V2')
            ->assertRedirect(route('payment.success', 'ORDER-V2'));
        $this->withHeader('X-Secret', 'test-secret')->postJson('/payment/webhook', $transaction)->assertOk();
        $this->assertSame('SUCCESS', $order->fresh()->status);
        $this->assertSame(10, $user->fresh()->ai_credits);
        $this->assertDatabaseCount('payment_grants', 3);
        Http::assertSentCount(1);
        Mail::assertSentCount(1);
    }

    public function test_legacy_pending_order_preserves_history_but_does_not_offer_retired_checkout_or_poll(): void
    {
        $user = User::factory()->create();
        $order = $this->order($user, ['pakasir_txn_id' => null, 'pakasir_is_sandbox' => null,
            'redirect_url' => 'https://app.pakasir.com/pay/test-merchant/14999?order_id=ORDER-V2']);
        $this->actingAs($user)->get(route('payment.waiting', $order->order_id))->assertOk()
            ->assertSee('Payment needs review')->assertSee('Contact support')
            ->assertDontSee('Resume payment')->assertDontSee('paymentWaitingStatus', false);
        $this->getJson('/payment/check-status/ORDER-V2')->assertStatus(503);
        $this->assertSame('WAITING', $order->fresh()->status);
        $this->assertDatabaseCount('payment_grants', 0);
        Http::assertNothingSent();
    }

    public function test_sync_does_not_recreate_legacy_orders_or_call_v1(): void
    {
        $user = User::factory()->create();
        $order = $this->order($user, ['pakasir_txn_id' => null, 'pakasir_is_sandbox' => null]);
        $this->artisan('payments:sync-pending')->assertExitCode(0);
        $this->assertSame('WAITING', $order->fresh()->status);
        $this->assertDatabaseCount('payments', 1);
        Http::assertNothingSent();
    }

    public function test_another_users_order_cannot_be_polled(): void
    {
        $owner = User::factory()->create();
        $this->order($owner);
        $other = User::factory()->create();
        $this->actingAs($other)->getJson('/payment/check-status/ORDER-V2')->assertNotFound();
        Http::assertNothingSent();
    }

    public function test_migration_preserves_historical_paid_orders_without_inventing_v2_identity(): void
    {
        $migration = require database_path('migrations/2026_10_20_000001_add_pakasir_v2_identity_to_payments.php');
        $migration->down();
        $user = User::factory()->create();
        $payment = Payment::create(['user_id' => $user->id, 'order_id' => 'HISTORICAL-PAID', 'amount' => 14999,
            'status' => 'SUCCESS', 'paid_at' => now(), 'customer_name' => $user->name,
            'customer_email' => $user->email, 'customer_phone' => '0800000000']);
        $migration->up();
        $payment->refresh();
        $this->assertSame('SUCCESS', $payment->status);
        $this->assertNotNull($payment->paid_at);
        $this->assertSame(14999, $payment->amount);
        $this->assertNull($payment->pakasir_txn_id);
        $this->assertNull($payment->pakasir_is_sandbox);
        $this->actingAs($user)->get(route('payment.success', $payment->order_id))->assertOk()->assertSee('Payment received');
        Http::assertNothingSent();
    }
}
