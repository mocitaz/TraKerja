<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PakasirService
{
    private const BASE_URL = 'https://app.pakasir.com';
    private const METHODS = ['payment_link', 'qris', 'bri_va', 'bni_va', 'cimb_niaga_va',
        'danamon_va', 'maybank_va', 'bnc_va', 'bsi_va', 'muamalat_va', 'ocbc_va'];

    public function generatePaymentUrl(string $txnId, bool $qrisOnly = false, ?string $redirectUrl = null): string
    {
        if (! $this->validTransactionId($txnId)) {
            throw new InvalidArgumentException('Invalid Pakasir transaction identifier');
        }

        $query = array_filter(['qris_only' => $qrisOnly ? 1 : null, 'redirect' => $redirectUrl], fn ($value) => $value !== null);

        return self::BASE_URL.'/pay-v2/'.$txnId.($query ? '?'.http_build_query($query, '', '&', PHP_QUERY_RFC3986) : '');
    }

    public function createTransaction(string $orderId, int $amount, string $method = 'payment_link'): array
    {
        if (! in_array($method, self::METHODS, true) || $amount < ($method === 'payment_link' || $method === 'qris' ? 500 : 10000)
            || $amount > ($method === 'qris' ? 10000000 : 50000000)) {
            return $this->failure('Unsupported payment method or amount');
        }

        $result = $this->request('POST', 'create-transaction', $orderId, ['method' => $method, 'amount' => $amount]);
        if (! $result['success']) {
            return $result;
        }

        $data = $result['data'];
        if (! $this->validTransactionId($data['txn_id'] ?? null)) {
            return $this->failure('Invalid transaction response');
        }

        if ($method === 'payment_link') {
            if (($data['payment_link'] ?? null) !== $this->generatePaymentUrl($data['txn_id'])) {
                return $this->failure('Invalid payment link');
            }
        } elseif (! $this->matchesTransaction($data, $orderId, $amount, $data['txn_id'], (bool) config('pakasir.sandbox'))
            || ($data['project'] ?? null) !== config('pakasir.slug')) {
            return $this->failure('Transaction does not match order');
        }

        return $result;
    }

    public function checkTransactionStatus(string $orderId, int $amount, ?string $txnId = null, ?bool $sandbox = null): array
    {
        // V1 order IDs cannot be used as V2 transaction IDs or silently recreated.
        if (! $this->validTransactionId($txnId) || $sandbox === null || $sandbox !== (bool) config('pakasir.sandbox')) {
            return $this->failure('Payment requires a valid V2 transaction and matching environment');
        }

        $key = 'pakasir:v2:status:'.hash('sha256', config('pakasir.slug').'|'.$txnId);
        try {
            $result = Cache::lock($key.':lock', 40)->block(3, function () use ($key, $txnId) {
                if (($cached = Cache::get($key)) !== null) {
                    return $cached;
                }

                $result = $this->request('GET', 'transaction-status', $txnId);
                // Share the result between polling, browser returns, webhook and sync.
                Cache::put($key, $result, now()->addSeconds(4));

                return $result;
            });
        } catch (\Throwable $e) {
            Log::warning('Pakasir V2: Status lookup unavailable', ['order_id' => $orderId]);

            return $this->failure('Payment status temporarily unavailable');
        }

        if ($result['success']) {
            if (! isset($result['data']['status']) || ! $this->matchesTransaction($result['data'], $orderId, $amount, $txnId, $sandbox)) {
                return $this->failure('Transaction does not match order');
            }
            $result['data']['project'] = config('pakasir.slug');
        }

        return $result;
    }

    public function cancelTransaction(string $txnId): array
    {
        return $this->validTransactionId($txnId)
            ? $this->request('POST', 'cancel-transaction', $txnId)
            : $this->failure('Invalid transaction identifier');
    }

    public function simulatePayment(string $txnId): array
    {
        if (! config('pakasir.sandbox') || ! $this->validTransactionId($txnId)) {
            return $this->failure('Payment simulation is only available in sandbox');
        }

        return $this->request('POST', 'payment-simulation', $txnId);
    }

    private function matchesTransaction(array $data, string $orderId, int $amount, string $txnId, bool $sandbox): bool
    {
        return ! Validator::make($data, [
            'txn_id' => 'required|string', 'order_id' => 'required|string',
            'amount' => 'required|integer|min:1', 'status' => 'sometimes|required|in:pending,completed,canceled',
            'is_sandbox' => 'required|boolean',
        ])->fails()
            && ($data['project'] ?? config('pakasir.slug')) === config('pakasir.slug')
            && $data['txn_id'] === $txnId && $data['order_id'] === $orderId
            && (int) $data['amount'] === $amount && is_bool($data['is_sandbox']) && $data['is_sandbox'] === $sandbox
            && (isset($data['status']) || isset($data['payment_method']));
    }

    private function validTransactionId(mixed $txnId): bool
    {
        return is_string($txnId) && preg_match('/\A[a-zA-Z0-9_-]{1,100}\z/', $txnId) === 1;
    }

    private function request(string $method, string $operation, string $identifier, array $body = []): array
    {
        $slug = (string) config('pakasir.slug');
        $apiKey = (string) config('pakasir.api_key');
        if ($slug === '' || $apiKey === '') {
            return $this->failure('Pakasir configuration is incomplete');
        }
        if ($method === 'POST') {
            $limitKey = 'pakasir:v2:write:'.hash('sha256', $slug.'|'.$operation);
            if (RateLimiter::tooManyAttempts($limitKey, 2)) {
                return $this->failure('Payment provider rate limit reached; retry shortly');
            }
            RateLimiter::hit($limitKey, 1);
        }

        try {
            $response = Http::acceptJson()->asJson()->withHeaders(['X-Api-Key' => $apiKey])->timeout(30)
                ->withOptions(['allow_redirects' => false])
                ->send($method, self::BASE_URL.'/api/v2/'.$operation.'/'.rawurlencode($slug).'/'.rawurlencode($identifier),
                    $method === 'POST' ? ['json' => $body] : []);
            $data = $response->json();
            if (! $response->successful() || ! is_array($data) || $data === [] || array_is_list($data)) {
                Log::warning('Pakasir V2: Request failed', ['operation' => $operation, 'http_status' => $response->status()]);

                return $this->failure('Payment provider request failed');
            }

            return ['success' => true, 'data' => $data];
        } catch (\Throwable $e) {
            Log::warning('Pakasir V2: Connection failed', ['operation' => $operation]);

            return $this->failure('Payment provider temporarily unavailable');
        }
    }

    private function failure(string $message): array
    {
        return ['success' => false, 'error' => $message];
    }
}
