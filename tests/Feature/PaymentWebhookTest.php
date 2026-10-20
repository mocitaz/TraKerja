<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Payment $payment;

    protected function setUp(): void
    {
        parent::setUp();
        config(['pakasir.slug' => 'test-merchant', 'pakasir.api_key' => 'test-key',
            'pakasir.webhook_secret' => 'test-webhook-secret', 'pakasir.sandbox' => false]);
        $this->withHeader('X-Secret', 'test-webhook-secret');
        Mail::fake();
        Http::preventStrayRequests();
        $this->user = User::factory()->create(['ai_credits' => 0, 'cl_credits' => 0, 'photo_credits' => 0]);
        $this->payment = Payment::create([
            'user_id' => $this->user->id,
            'order_id' => 'WEBHOOK-TEST-1',
            'pakasir_txn_id' => 'txn-webhook-1', 'pakasir_is_sandbox' => false,
            'amount' => 14999,
            'status' => 'PENDING',
            'notes' => 'addon_10',
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '0800000000',
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'order_id' => $this->payment->order_id,
            'txn_id' => $this->payment->pakasir_txn_id, 'is_sandbox' => false,
            'amount' => $this->payment->amount,
            'project' => 'test-merchant',
            'status' => 'completed',
        ], $overrides);
    }

    private function assertNoBenefits(): void
    {
        $this->assertSame('PENDING', $this->payment->fresh()->status);
        $user = $this->user->fresh();
        $this->assertSame(0, $user->ai_credits);
        $this->assertSame(0, $user->cl_credits);
        $this->assertSame(0, $user->photo_credits);
        $this->assertFalse($user->isPremium());
        Mail::assertNothingSent();
    }

    public function test_forged_completed_payload_cannot_grant_benefits_when_provider_is_pending(): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response($this->payload(['status' => 'pending']))]);

        $this->postJson('/payment/webhook', $this->payload())->assertStatus(503);

        $this->assertNoBenefits();
        Http::assertSent(fn ($request) => $request->method() === 'GET'
            && $request->url() === 'https://app.pakasir.com/api/v2/transaction-status/test-merchant/txn-webhook-1'
            && $request->hasHeader('X-Api-Key', 'test-key') && ! str_contains($request->url(), 'test-key'));
    }

    public function test_verified_completion_grants_benefits_once_even_if_payload_claims_failure(): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response($this->payload())]);

        $this->postJson('/payment/webhook', $this->payload(['status' => 'canceled']))->assertOk();
        $this->postJson('/payment/webhook', $this->payload())->assertOk();

        $this->assertSame('SUCCESS', $this->payment->fresh()->status);
        $this->assertSame(10, $this->user->fresh()->ai_credits);
        $this->assertSame(15, $this->user->fresh()->cl_credits);
        $this->assertSame(5, $this->user->fresh()->photo_credits);
        $this->assertSame('completed', $this->payment->fresh()->webhook_data['status']);
        Mail::assertSentCount(1);
    }

    #[DataProvider('invalidTransactions')]
    public function test_invalid_provider_detail_does_not_grant_benefits(array $overrides): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response($this->payload($overrides))]);

        $this->postJson('/payment/webhook', $this->payload())->assertStatus(503);

        $this->assertNoBenefits();
    }

    public static function invalidTransactions(): array
    {
        return [
            'wrong order' => [['order_id' => 'OTHER-ORDER']],
            'wrong amount' => [['amount' => 1]],
            'wrong merchant' => [['project' => 'other-merchant']],
            'missing order' => [['order_id' => null]],
            'missing status' => [['status' => null]],
            'invalid amount' => [['amount' => '14999garbage']],
            'wrong transaction' => [['txn_id' => 'other-txn']],
            'wrong environment' => [['is_sandbox' => true]],
            'missing environment' => [['is_sandbox' => null]],
            'unknown status' => [['status' => 'failed']],
        ];
    }

    public function test_provider_failure_leaves_payment_pending_and_allows_retry(): void
    {
        Http::fakeSequence()->push([], 500)->push($this->payload());

        $this->postJson('/payment/webhook', $this->payload())->assertStatus(503);
        $this->assertNoBenefits();
        $this->travel(5)->seconds();
        $this->postJson('/payment/webhook', $this->payload())->assertOk();

        $this->assertSame('SUCCESS', $this->payment->fresh()->status);
        $this->assertSame(10, $this->user->fresh()->ai_credits);
    }

    public function test_provider_timeout_does_not_grant_benefits(): void
    {
        Http::fake(fn () => throw new ConnectionException('Synthetic provider timeout'));

        $this->postJson('/payment/webhook', $this->payload())->assertStatus(503);

        $this->assertNoBenefits();
    }

    public function test_empty_provider_response_does_not_grant_benefits(): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response([])]);

        $this->postJson('/payment/webhook', $this->payload())->assertStatus(503);

        $this->assertNoBenefits();
    }

    public function test_wrong_webhook_merchant_is_rejected_before_calling_provider(): void
    {
        $this->postJson('/payment/webhook', $this->payload(['project' => 'other-merchant']))->assertStatus(400);

        $this->assertNoBenefits();
        Http::assertNothingSent();
    }

    public function test_identical_payload_is_reverified_after_provider_was_pending(): void
    {
        Http::fakeSequence()
            ->push($this->payload(['status' => 'pending']))
            ->push($this->payload());

        $this->postJson('/payment/webhook', $this->payload())->assertStatus(503);
        $this->assertNoBenefits();
        $this->postJson('/payment/webhook', $this->payload())->assertStatus(503);
        Http::assertSentCount(1);
        $this->travel(5)->seconds();
        $this->postJson('/payment/webhook', $this->payload())->assertOk();

        $this->assertSame('SUCCESS', $this->payment->fresh()->status);
        $this->assertSame(10, $this->user->fresh()->ai_credits);
        Http::assertSentCount(2);
    }

    public function test_verified_failure_cannot_downgrade_success_or_grant_again(): void
    {
        Http::fakeSequence()
            ->push($this->payload())
            ->push($this->payload(['status' => 'canceled']))
            ->push($this->payload());

        $this->postJson('/payment/webhook', $this->payload())->assertOk();
        $this->travel(5)->seconds();
        $this->postJson('/payment/webhook', $this->payload(['status' => 'canceled']))->assertOk();
        $this->assertSame('SUCCESS', $this->payment->fresh()->status);
        $this->travel(5)->seconds();
        $this->postJson('/payment/webhook', $this->payload())->assertOk();

        $this->assertSame(10, $this->user->fresh()->ai_credits);
        Mail::assertSentCount(1);
    }

    public function test_premium_is_only_activated_after_verified_completion(): void
    {
        $this->payment->update(['notes' => 'premium']);
        Http::fakeSequence()
            ->push($this->payload(['status' => 'pending']))
            ->push($this->payload());

        $this->postJson('/payment/webhook', $this->payload())->assertStatus(503);
        $this->assertNoBenefits();
        $this->travel(5)->seconds();
        $this->postJson('/payment/webhook', $this->payload())->assertOk();

        $this->assertTrue($this->user->fresh()->isPremium());
        $this->assertSame('SUCCESS', $this->payment->fresh()->status);
        Mail::assertSentCount(1);
    }

    public function test_wrong_webhook_amount_is_rejected_before_calling_provider(): void
    {
        $this->postJson('/payment/webhook', $this->payload(['amount' => 1]))->assertStatus(400);

        $this->assertNoBenefits();
        Http::assertNothingSent();
    }

    public function test_unknown_order_is_rejected_before_calling_provider(): void
    {
        $this->postJson('/payment/webhook', $this->payload(['order_id' => 'UNKNOWN']))->assertNotFound();

        $this->assertNoBenefits();
        Http::assertNothingSent();
    }

    public function test_malformed_webhook_is_rejected_before_calling_provider(): void
    {
        $this->postJson('/payment/webhook', $this->payload(['order_id' => ['invalid']]))->assertUnprocessable();

        $this->assertNoBenefits();
        Http::assertNothingSent();
    }

    public function test_missing_or_invalid_secret_cannot_reach_provider_or_grant(): void
    {
        $this->withHeader('X-Secret', 'wrong-secret')->postJson('/payment/webhook', $this->payload())->assertUnauthorized();
        $this->withHeader('X-Secret', '')->postJson('/payment/webhook', $this->payload())->assertUnauthorized();
        $this->assertNoBenefits();
        Http::assertNothingSent();
    }

    public function test_unconfigured_webhook_fails_closed(): void
    {
        config(['pakasir.webhook_secret' => '']);
        $this->postJson('/payment/webhook', $this->payload())->assertStatus(503);
        $this->assertNoBenefits();
        Http::assertNothingSent();
    }

    public function test_v2_webhook_without_project_field_and_without_json_content_type_is_supported(): void
    {
        $data = $this->payload();
        unset($data['project']);
        Http::fake(['app.pakasir.com/*' => Http::response($data)]);
        $this->call('POST', '/payment/webhook', [], [], [], [
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded', 'HTTP_ACCEPT' => 'application/json',
            'HTTP_X_SECRET' => 'test-webhook-secret',
        ], json_encode($data))->assertOk();
        $this->assertSame('SUCCESS', $this->payment->fresh()->status);
        $this->assertSame(10, $this->user->fresh()->ai_credits);
    }

    public function test_wrong_transaction_or_sandbox_webhook_is_rejected_before_provider_lookup(): void
    {
        foreach ([['txn_id' => 'wrong-txn'], ['is_sandbox' => true]] as $overrides) {
            $this->postJson('/payment/webhook', $this->payload($overrides))->assertStatus(400);
        }
        $this->assertNoBenefits();
        Http::assertNothingSent();
    }

    public function test_legacy_payment_is_not_bound_to_an_untrusted_webhook_transaction(): void
    {
        $this->payment->update(['pakasir_txn_id' => null, 'pakasir_is_sandbox' => null]);
        $this->postJson('/payment/webhook', $this->payload(['txn_id' => 'txn-claim']))->assertStatus(400);
        $this->assertNoBenefits();
        Http::assertNothingSent();
    }
}
