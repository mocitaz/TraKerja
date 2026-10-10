<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentWaitingUiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['pakasir.slug' => 'test-merchant', 'pakasir.api_key' => 'test-key']);
        Http::preventStrayRequests();
    }

    private function payment(User $user): Payment
    {
        return Payment::create(['user_id' => $user->id, 'order_id' => 'WAIT-UI-1', 'amount' => 14999,
            'status' => 'WAITING', 'payment_method' => 'bca_va', 'va_number' => '1234567890',
            'customer_phone' => '0800000000', 'customer_name' => $user->name, 'customer_email' => $user->email]);
    }

    public function test_waiting_page_renders_compiled_polling_component_and_payment_details(): void
    {
        $user = User::factory()->create();
        $payment = $this->payment($user);
        $this->actingAs($user)->get(route('payment.waiting', $payment->order_id))->assertOk()
            ->assertSee('paymentWaitingStatus', false)->assertSee('role="alert"', false)
            ->assertSee('Try again')->assertSee('1234567890')->assertDontSee('statusCheckInterval', false)
            ->assertSee(\Illuminate\Support\Js::from(route('payment.check-status', $payment->order_id))->toHtml(), false);
        Http::assertNothingSent();
    }

    public function test_provider_verification_failure_is_retryable_without_changing_payment(): void
    {
        $user = User::factory()->create();
        $payment = $this->payment($user);
        Http::fake(['app.pakasir.com/*' => Http::response([], 503)]);
        $this->actingAs($user)->getJson(route('payment.check-status', $payment->order_id))
            ->assertStatus(503)->assertJsonPath('success', false);
        $this->assertSame('WAITING', $payment->fresh()->status);
        $this->assertDatabaseCount('payment_grants', 0);
    }
}
