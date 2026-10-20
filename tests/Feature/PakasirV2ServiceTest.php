<?php

namespace Tests\Feature;

use App\Services\PakasirService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PakasirV2ServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['pakasir.slug' => 'test-merchant', 'pakasir.api_key' => 'test-api-key', 'pakasir.sandbox' => false]);
        Http::preventStrayRequests();
    }

    private function transaction(array $overrides = []): array
    {
        return array_replace(['txn_id' => 'txn-1', 'order_id' => 'ORDER-1', 'amount' => 14999,
            'is_sandbox' => false, 'status' => 'completed'], $overrides);
    }

    public function test_checkout_creation_uses_v2_json_and_keeps_credentials_out_of_urls_and_body(): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response(['txn_id' => 'txn-1', 'payment_link' => 'https://app.pakasir.com/pay-v2/txn-1'])]);
        $service = app(PakasirService::class);
        $this->assertTrue($service->createTransaction('ORDER-1', 14999)['success']);
        Http::assertSent(fn ($request) => $request->url() === 'https://app.pakasir.com/api/v2/create-transaction/test-merchant/ORDER-1'
            && $request->hasHeader('X-Api-Key', 'test-api-key')
            && $request->data() === ['method' => 'payment_link', 'amount' => 14999]);
        $url = $service->generatePaymentUrl('txn-1', true, 'https://trakerja.com/payment/callback?order_id=ORDER-1');
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $this->assertSame('1', $query['qris_only']);
        $this->assertSame('https://trakerja.com/payment/callback?order_id=ORDER-1', $query['redirect']);
    }

    public static function invalidCreationResponses(): array
    {
        return [
            'old v1 envelope' => [['payment' => ['txn_id' => 'txn-1']]],
            'missing transaction' => [['payment_link' => 'https://app.pakasir.com/pay-v2/txn-1']],
            'malicious link' => [['txn_id' => 'txn-1', 'payment_link' => 'https://example.test/pay-v2/txn-1']],
            'wrong transaction link' => [['txn_id' => 'txn-1', 'payment_link' => 'https://app.pakasir.com/pay-v2/txn-other']],
        ];
    }

    #[DataProvider('invalidCreationResponses')]
    public function test_invalid_create_responses_cannot_become_checkout_links(array $data): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response($data)]);
        $this->assertFalse(app(PakasirService::class)->createTransaction('ORDER-1', 14999)['success']);
    }

    public function test_direct_qris_response_and_minimum_amount_are_validated(): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response($this->transaction([
            'project' => 'test-merchant', 'payment_method' => 'qris',
        ]))]);
        $service = app(PakasirService::class);
        $this->assertTrue($service->createTransaction('ORDER-1', 14999, 'qris')['success']);
        $this->assertFalse($service->createTransaction('ORDER-2', 999, 'bri_va')['success']);
        $this->assertFalse($service->createTransaction('ORDER-2', 10000001, 'qris')['success']);
        $this->assertFalse($service->createTransaction('ORDER-2', 14999, 'bca_va')['success']);
        Http::assertSentCount(1);
    }

    public function test_status_checks_are_shared_for_four_seconds_then_refresh(): void
    {
        Http::fakeSequence()->push($this->transaction(['status' => 'pending']))->push($this->transaction());
        $service = app(PakasirService::class);
        foreach (range(1, 3) as $attempt) {
            $this->assertSame('pending', $service->checkTransactionStatus('ORDER-1', 14999, 'txn-1', false)['data']['status']);
        }
        Http::assertSentCount(1);
        $this->travel(5)->seconds();
        $this->assertSame('completed', $service->checkTransactionStatus('ORDER-1', 14999, 'txn-1', false)['data']['status']);
        Http::assertSentCount(2);
    }

    public function test_cached_status_is_still_bound_to_the_expected_order_and_amount(): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response($this->transaction())]);
        $service = app(PakasirService::class);
        $this->assertTrue($service->checkTransactionStatus('ORDER-1', 14999, 'txn-1', false)['success']);
        $this->assertFalse($service->checkTransactionStatus('OTHER-ORDER', 14999, 'txn-1', false)['success']);
        $this->assertFalse($service->checkTransactionStatus('ORDER-1', 1, 'txn-1', false)['success']);
        Http::assertSentCount(1);
    }

    public function test_missing_v2_identity_and_wrong_environment_do_not_call_retired_api(): void
    {
        $service = app(PakasirService::class);
        $this->assertFalse($service->checkTransactionStatus('ORDER-1', 14999)['success']);
        $this->assertFalse($service->checkTransactionStatus('ORDER-1', 14999, 'txn-1', true)['success']);
        Http::assertNothingSent();
    }

    public function test_cancellation_and_sandbox_simulation_use_v2_headers_and_paths(): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response(['message' => 'Berhasil'])]);
        $service = app(PakasirService::class);
        $this->assertFalse($service->simulatePayment('txn-1')['success']);
        Http::assertNothingSent();
        config(['pakasir.sandbox' => true]);
        $this->assertTrue($service->simulatePayment('txn-1')['success']);
        $this->assertTrue($service->cancelTransaction('txn-1')['success']);
        Http::assertSent(fn ($request) => $request->method() === 'POST'
            && $request->url() === 'https://app.pakasir.com/api/v2/payment-simulation/test-merchant/txn-1'
            && $request->hasHeader('X-Api-Key', 'test-api-key'));
        Http::assertSent(fn ($request) => $request->url() === 'https://app.pakasir.com/api/v2/cancel-transaction/test-merchant/txn-1');
    }

    public function test_write_rate_limit_prevents_a_third_create_request_in_one_second(): void
    {
        Http::fake(['app.pakasir.com/*' => Http::response(['txn_id' => 'txn-1', 'payment_link' => 'https://app.pakasir.com/pay-v2/txn-1'])]);
        $service = app(PakasirService::class);
        $this->assertTrue($service->createTransaction('ORDER-1', 14999)['success']);
        $this->assertTrue($service->createTransaction('ORDER-1', 14999)['success']);
        $this->assertFalse($service->createTransaction('ORDER-1', 14999)['success']);
        Http::assertSentCount(2);
    }
}
