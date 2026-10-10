<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PakasirService
{
    private string $slug;
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->slug = config('pakasir.slug');
        $this->apiKey = config('pakasir.api_key');
        $this->baseUrl = 'https://app.pakasir.com';
    }

    /**
     * Generate Payment URL for redirection
     */
    public function generatePaymentUrl(string $orderId, int $amount, bool $qrisOnly = false, ?string $redirectUrl = null): string
    {
        $url = "{$this->baseUrl}/pay/{$this->slug}/{$amount}?order_id={$orderId}";

        if ($qrisOnly) {
            $url .= "&qris_only=1";
        }

        if ($redirectUrl) {
            $url .= "&redirect=" . urlencode($redirectUrl);
        }

        return $url;
    }

    /**
     * Check transaction status via API
     */
    public function checkTransactionStatus(string $orderId, int $amount): array
    {
        try {
            $response = Http::timeout(30)
                ->get("{$this->baseUrl}/api/transactiondetail", [
                    'project' => $this->slug,
                    'amount' => $amount,
                    'order_id' => $orderId,
                    'api_key' => $this->apiKey,
                ]);

            if ($response->successful()) {
                $transaction = $response->json('transaction');
                if (! is_array($transaction) || Validator::make($transaction, [
                    'project' => 'required|string',
                    'order_id' => 'required|string',
                    'amount' => 'required|integer|min:1',
                    'status' => 'required|string',
                ])->fails()
                    || $transaction['project'] !== $this->slug
                    || $transaction['order_id'] !== $orderId
                    || (int) $transaction['amount'] !== $amount) {
                    Log::warning('Pakasir: Transaction detail does not match stored order', [
                        'order_id' => $orderId,
                    ]);

                    return ['success' => false, 'error' => 'Invalid transaction detail'];
                }

                return [
                    'success' => true,
                    'data' => $transaction,
                ];
            }

            Log::error('Pakasir: Failed to check transaction status', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to check transaction status',
                'debug' => [
                    'http_status' => $response->status(),
                    'response_body' => $response->json() ?? $response->body(),
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Pakasir: Exception checking transaction status', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'debug' => [
                    'exception' => $e->getMessage(),
                    'trace' => substr($e->getTraceAsString(), 0, 300)
                ]
            ];
        }
    }

    /**
     * Create transaction via API (useful if we want to show QR in our own UI)
     */
    public function createTransaction(string $orderId, int $amount, string $method = 'qris'): array
    {
        try {
            $response = Http::timeout(30)
                ->post("{$this->baseUrl}/api/transactioncreate/{$method}", [
                    'project' => $this->slug,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'api_key' => $this->apiKey,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()['payment'] ?? [],
                ];
            }

            Log::error('Pakasir: Failed to create transaction', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Failed to create transaction',
            ];
        } catch (\Exception $e) {
            Log::error('Pakasir: Exception creating transaction', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
