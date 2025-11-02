<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class YukkPaymentService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $mid;
    private string $scope;

    public function __construct()
    {
        $environment = config('yukk.environment');
        $this->baseUrl = config("yukk.base_url.{$environment}");
        $this->clientId = config('yukk.client_id');
        $this->clientSecret = config('yukk.client_secret');
        $this->mid = config('yukk.mid');
        $this->scope = config('yukk.scope');
    }

    /**
     * Get access token from YUKK API (cached for 14 minutes)
     */
    public function getAccessToken(): string
    {
        return Cache::remember('yukk_access_token', now()->addMinutes(14), function () {
            try {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])
                    ->post("{$this->baseUrl}/api/oauth/token", [
                        'grant_type' => 'client_credentials',
                        'scope' => $this->scope,
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('YUKK: Access token obtained successfully');
                    return $data['result']['access_token'];
                }

                Log::error('YUKK: Failed to get access token', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                throw new \Exception('Failed to get YUKK access token: ' . $response->body());
            } catch (\Exception $e) {
                Log::error('YUKK: Exception getting access token', [
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        });
    }

    /**
     * Get all available payment channels
     */
    public function getPaymentChannels(): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$accessToken}",
                    'MID' => $this->mid,
                ])
                ->get("{$this->baseUrl}/api/payment-channels");

            if ($response->successful()) {
                $data = $response->json();
                Log::info('YUKK: Payment channels retrieved successfully', [
                    'count' => count($data['result'] ?? []),
                ]);
                return $data['result'] ?? [];
            }

            Log::error('YUKK: Failed to get payment channels', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('YUKK: Exception getting payment channels', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Request payment and create transaction
     */
    public function requestPayment(array $paymentData): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$accessToken}",
                    'MID' => $this->mid,
                ])
                ->post("{$this->baseUrl}/api/transactions/request-payment", $paymentData);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('YUKK: Payment request successful', [
                    'order_id' => $paymentData['order_details']['order_id'],
                    'yukk_code' => $data['result']['code'] ?? null,
                ]);
                return [
                    'success' => true,
                    'data' => $data['result'] ?? [],
                ];
            }

            Log::error('YUKK: Payment request failed', [
                'status' => $response->status(),
                'response' => $response->body(),
                'request' => $paymentData,
            ]);

            return [
                'success' => false,
                'error' => $response->json()['status_message'] ?? 'Payment request failed',
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('YUKK: Exception requesting payment', [
                'error' => $e->getMessage(),
                'request' => $paymentData,
            ]);

            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus(string $yukkTransactionCode): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$accessToken}",
                    'MID' => $this->mid,
                ])
                ->get("{$this->baseUrl}/api/transactions/{$yukkTransactionCode}/status");

            if ($response->successful()) {
                $data = $response->json();
                Log::info('YUKK: Payment status checked', [
                    'yukk_code' => $yukkTransactionCode,
                    'status' => $data['result']['status'] ?? null,
                ]);
                return [
                    'success' => true,
                    'data' => $data['result'] ?? [],
                ];
            }

            Log::error('YUKK: Failed to check payment status', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to check payment status',
            ];
        } catch (\Exception $e) {
            Log::error('YUKK: Exception checking payment status', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cancel VA transaction
     */
    public function cancelVATransaction(string $yukkTransactionCode): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$accessToken}",
                    'MID' => $this->mid,
                ])
                ->post("{$this->baseUrl}/api/transactions/{$yukkTransactionCode}/cancel");

            if ($response->successful()) {
                $data = $response->json();
                Log::info('YUKK: Transaction canceled', [
                    'yukk_code' => $yukkTransactionCode,
                ]);
                return [
                    'success' => true,
                    'data' => $data['result'] ?? [],
                ];
            }

            Log::error('YUKK: Failed to cancel transaction', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to cancel transaction',
            ];
        } catch (\Exception $e) {
            Log::error('YUKK: Exception canceling transaction', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $signature, array $data): bool
    {
        $expectedSignature = hash('sha512', 
            $this->clientSecret .
            $data['order_id'] .
            $data['payment_channel']['code'] .
            $data['grand_total'] .
            $data['status']
        );

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Format payment request data
     */
    public function formatPaymentRequest(
        string $orderId,
        int $amount,
        string $paymentChannelCode,
        array $customer,
        ?array $va = null
    ): array {
        $data = [
            'request_time' => (string) time(),
            'payment' => [
                'pmt_channel_code' => $paymentChannelCode,
            ],
            'order_details' => [
                'order_id' => $orderId,
                'amount' => $amount,
            ],
            'customer' => [
                'name' => $customer['name'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
            ],
            'notification_url' => config('yukk.notification_url'),
            'callback_url' => config('yukk.callback_url'),
        ];

        // Add VA specific data if provided
        if ($va && isset($va['account_id'])) {
            $data['va'] = [
                'account_id' => $va['account_id'],
            ];
            
            if (isset($va['expires_in'])) {
                $data['va']['expires_in'] = $va['expires_in'];
            }
        }

        return $data;
    }
}

