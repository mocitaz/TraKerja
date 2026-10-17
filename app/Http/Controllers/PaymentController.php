<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Services\PakasirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private ?PakasirService $pakasirService = null;

    public function __construct()
    {
        // Controller initialization
    }

    /**
     * Get PakasirService instance (lazy loaded)
     */
    private function getPakasirService(): PakasirService
    {
        if ($this->pakasirService === null) {
            $this->pakasirService = app(PakasirService::class);
        }
        return $this->pakasirService;
    }

    /**
     * Show premium landing page
     */
    public function premium()
    {
        return $this->index();
    }

    /**
     * Show top up add-on page (for premium users)
     */
    public function topup()
    {
        $user = Auth::user();

        // ONLY allow premium users to top-up!
        if (!$user->isPremium()) {
            return redirect()->route('payment.premium')
                ->with('error', __('Silakan upgrade ke Premium terlebih dahulu untuk membeli Add-On!'));
        }

        $package = request()->query('package', 'analyzer');

        if ($package === 'cover_letter') {
            $packageType = 'cl_addon_15';
            $packageName = 'TraKerja Add-On Pack';
            $packageSubtitle = '15 Kredit Cover Letter + 10 Kredit AI Analyzer + 5 Kredit AI Photo';
            $addonPrice = \App\Support\PaymentPackages::ADDON_PRICE;
        } else {
            $packageType = 'addon_10';
            $packageName = 'TraKerja Add-On Pack';
            $packageSubtitle = '10 Kredit AI Analyzer + 15 Kredit Cover Letter + 5 Kredit AI Photo';
            $addonPrice = \App\Support\PaymentPackages::ADDON_PRICE;
        }

        // Group payment channels by category for the view (Hardcoded for Pakasir common options)
        $groupedChannels = collect([
            'E_WALLET' => [
                [
                    'code' => 'qris',
                    'name' => 'QRIS (BCA, OVO, Dana, dll)',
                    'image_url' => asset('images/qris.png'),
                    'category' => ['code' => 'E_WALLET', 'name' => 'Digital Wallet']
                ]
            ],
            'BANK_TRANSFER' => [
                [
                    'code' => 'bca_va',
                    'name' => 'BCA Virtual Account',
                    'image_url' => asset('images/bca.png'),
                    'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Virtual Account']
                ],
                [
                    'code' => 'bni_va',
                    'name' => 'BNI Virtual Account',
                    'image_url' => asset('images/bni.png'),
                    'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Virtual Account']
                ],
                [
                    'code' => 'bri_va',
                    'name' => 'BRI Virtual Account',
                    'image_url' => asset('images/bri.png'),
                    'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Virtual Account']
                ],
                [
                    'code' => 'permata_va',
                    'name' => 'Permata Virtual Account',
                    'image_url' => asset('images/permata.png'),
                    'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Virtual Account']
                ]
            ]
        ])->map(function ($items) {
            return collect($items);
        });

        return view('payment.topup', compact('groupedChannels', 'addonPrice', 'user', 'packageType', 'packageName', 'packageSubtitle'));
    }

    /**
     * Show payment index page
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is already premium
        if ($user->isPremium()) {
            return redirect()->route('profile.edit', ['tab' => 'membership']);
        }

        // Pakasir is simpler, we just need a price and duration
        $premiumPrice = (int) \App\Models\Setting::get('premium_price', 9999);
        $premiumDuration = (int) config('pakasir.premium_duration_days', 365);

        // Group payment channels by category for the view (Hardcoded for Pakasir common options)
        $groupedChannels = collect([
            'E_WALLET' => [
                [
                    'code' => 'qris',
                    'name' => 'QRIS (BCA, OVO, Dana, dll)',
                    'image_url' => asset('images/qris.png'),
                    'category' => ['code' => 'E_WALLET', 'name' => 'Digital Wallet']
                ]
            ],
            'BANK_TRANSFER' => [
                [
                    'code' => 'bca_va',
                    'name' => 'BCA Virtual Account',
                    'image_url' => asset('images/bca.png'),
                    'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Virtual Account']
                ],
                [
                    'code' => 'bni_va',
                    'name' => 'BNI Virtual Account',
                    'image_url' => asset('images/bni.png'),
                    'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Virtual Account']
                ],
                [
                    'code' => 'bri_va',
                    'name' => 'BRI Virtual Account',
                    'image_url' => asset('images/bri.png'),
                    'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Virtual Account']
                ],
                [
                    'code' => 'permata_va',
                    'name' => 'Permata Virtual Account',
                    'image_url' => asset('images/permata.png'),
                    'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Virtual Account']
                ]
            ]
        ])
->map(function ($items) {
            return collect($items);
        });

        return view('payment.index', compact('groupedChannels', 'premiumPrice', 'premiumDuration'));
    }

    /**
     * Create payment and redirect to Pakasir payment page
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_channel_code' => 'required|string|in:qris,bank_transfer,bca_va,bni_va,bri_va,permata_va',
            'package_type' => 'nullable|string|in:premium,addon_10,cl_addon_15',
        ]);

        $user = Auth::user();
        $packageType = $request->input('package_type', 'premium');

        // Check if package is premium and user is already premium
        if ($packageType === 'premium' && $user->isPremium()) {
            return redirect()->route('profile.edit', ['tab' => 'membership']);
        }

        // Check if package is an add-on and user is NOT premium
        if (in_array($packageType, ['addon_10', 'cl_addon_15']) && !$user->isPremium()) {
            return redirect()->route('payment.premium')
                ->with('error', __('Silakan upgrade ke Premium terlebih dahulu untuk membeli Add-On!'));
        }

        try {
            DB::beginTransaction();

            // Generate unique order ID
            $orderId = 'TRAKERJA-' . strtoupper(Str::random(10)) . '-' . time();

            // Determine amount based on package type
            if ($packageType === 'addon_10' || $packageType === 'cl_addon_15') {
                $amount = \App\Support\PaymentPackages::ADDON_PRICE;
            } else {
                $amount = (int) \App\Models\Setting::get('premium_price', 9999);
            }

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'amount' => $amount,
                'payment_channel_code' => $request->payment_channel_code,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?? '0000000000',
                'status' => 'PENDING',
                'request_at' => now(),
                'callback_url' => route('payment.callback', ['order_id' => $orderId]),
                'notification_url' => route('payment.webhook'),
                'notes' => $packageType,
                'metadata' => ['package_type' => $packageType],
            ]);

            // For Pakasir, we can use Integration Via URL (Redirect)
            // or Integration Via API. Redirect is easier.

            $qrisOnly = ($request->payment_channel_code === 'qris');
            $redirectUrl = route('payment.callback', ['order_id' => $orderId]);

            $paymentUrl = $this->getPakasirService()->generatePaymentUrl(
                orderId: $orderId,
                amount: $amount,
                qrisOnly: $qrisOnly,
                redirectUrl: $redirectUrl
            );

            // Update payment with redirect URL
            $payment->update([
                'redirect_url' => $paymentUrl,
                'status' => 'WAITING',
            ]);

            DB::commit();

            Log::info('Pakasir: Payment created successfully', [
                'order_id' => $orderId,
                'user_id' => $user->id,
                'package_type' => $packageType,
            ]);

            // Redirect to Pakasir payment page
            return redirect($paymentUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Pakasir: Payment checkout failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return back()->withErrors([
                'payment_error' => __('Terjadi kesalahan saat memproses pembayaran. Detail: ') . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle callback from Pakasir payment page
     */
    public function callback(Request $request)
    {
        Log::info('Pakasir: Payment callback received (Redirect)', [
            'params' => $request->all(),
        ]);

        $orderId = $request->input('order_id');

        if (!$orderId) {
            return redirect()->route('tracker')
                ->with('error', __('Data pembayaran tidak valid.'));
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return redirect()->route('tracker')
                ->with('error', __('Pembayaran tidak ditemukan.'));
        }

        // Since this is a simple redirect, we should check status via API
        $statusCheck = $this->getPakasirService()->checkTransactionStatus($orderId, $payment->amount);

        if ($statusCheck['success']) {
            $status = $statusCheck['data']['status'] ?? 'pending';

            if ($status === 'completed') {
                $this->handleSuccessfulPayment($payment, $statusCheck['data']);
                return redirect()->route('payment.success', ['orderId' => $orderId]);
            }
        }

        // If not completed yet or check failed, show waiting page
        return redirect()->route('payment.waiting', ['orderId' => $orderId]);
    }

    /**
     * Handle webhook notification from Pakasir
     */
    public function webhook(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|string|max:255',
            'project' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'status' => 'required|string|max:50',
        ]);
        $orderId = $data['order_id'];

        if ($data['project'] !== config('pakasir.slug')) {
            return response()->json(['message' => __('Project mismatch')], 400);
        }

        $payment = Payment::where('order_id', $orderId)->first();
        if (! $payment) {
            return response()->json(['message' => __('Payment not found')], 404);
        }

        if ($payment->amount !== (int) $data['amount']) {
            return response()->json(['message' => __('Amount mismatch')], 400);
        }

        $payload = $request->all();

        // Payload IDs are untrusted; payment state guards grants after provider verification.
        $transactionId = hash('sha256', json_encode($payload));

        // Create webhook log record
        $webhookLog = \App\Models\WebhookLog::create([
            'provider' => 'pakasir',
            'transaction_id' => $transactionId,
            'reference_id' => $orderId,
            'event_type' => 'payment_update',
            'payload' => $payload,
            'status' => 'received',
        ]);

        try {
            $verification = $this->getPakasirService()->checkTransactionStatus($payment->order_id, $payment->amount);
            if (! $verification['success']) {
                $webhookLog->update(['status' => 'failed', 'notes' => 'Provider verification failed']);

                return response()->json(['message' => __('Unable to verify payment')], 503);
            }

            $transaction = $verification['data'];
            $status = $transaction['status'];
            if ($status === 'completed') {
                $this->handleSuccessfulPayment($payment, $transaction);
            } elseif ($status === 'failed' || $status === 'canceled') {
                $status === 'canceled' ? $payment->markAsCanceled() : $payment->markAsFailed();
            }

            $webhookLog->update(['status' => 'processed']);

            return response()->json(['message' => __('Webhook processed successfully')], 200);
        } catch (\Exception $e) {
            Log::error('Pakasir: Webhook processing failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            $webhookLog->update(['status' => 'failed', 'notes' => $e->getMessage()]);

            return response()->json(['message' => __('Processing failed')], 500);
        }
    }

    /**
     * Common logic for successful payment
     */
    private function handleSuccessfulPayment(Payment $payment, array $data): void
    {
        app(\App\Services\PaymentSettlementService::class)->settle($payment, $data);
    }

    /**
     * Show payment success page
     */
    public function success(string $orderId)
    {
        // SECURITY: Scope to authenticated user to prevent IDOR
        $payment = Payment::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$payment) {
            return redirect()->route('tracker')
                ->with('error', __('Pembayaran tidak ditemukan.'));
        }

        return view('payment.success', compact('payment'));
    }

    /**
     * Show payment failed page
     */
    public function failed(string $orderId)
    {
        // SECURITY: Scope to authenticated user to prevent IDOR
        $payment = Payment::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$payment) {
            return redirect()->route('tracker')
                ->with('error', __('Pembayaran tidak ditemukan.'));
        }

        return view('payment.failed', compact('payment'));
    }

    /**
     * Show payment waiting page
     */
    public function waiting(string $orderId)
    {
        // SECURITY: Scope to authenticated user to prevent IDOR
        $payment = Payment::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$payment) {
            return redirect()->route('tracker')
                ->with('error', __('Pembayaran tidak ditemukan.'));
        }

        return view('payment.waiting', compact('payment'));
    }

    /**
     * Show payment history page
     */
    public function history()
    {
        $user = Auth::user();

        $payments = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $orders = Payment::where('user_id', $user->id);
        $now = now();
        $pending = (clone $orders)->whereIn('status', ['PENDING', 'WAITING'])
            ->where(function ($query) use ($now) {
                $query->where('expired_at', '>', $now)
                    ->orWhere(function ($query) use ($now) {
                        $query->whereNull('expired_at')->where(function ($query) use ($now) {
                            $query->whereNull('va_expired_at')->orWhere('va_expired_at', '>', $now);
                        });
                    });
            })->count();
        $expired = (clone $orders)->whereIn('status', ['PENDING', 'WAITING'])
            ->where(function ($query) use ($now) {
                $query->where('expired_at', '<=', $now)
                    ->orWhere(function ($query) use ($now) {
                        $query->whereNull('expired_at')->where('va_expired_at', '<=', $now);
                    });
            })->count();
        $stats = [
            'total' => (clone $orders)->count(),
            'success' => (clone $orders)->where('status', 'SUCCESS')->count(),
            'pending' => $pending,
            'failed' => (clone $orders)->whereIn('status', ['FAILED', 'CANCELED', 'EXPIRED'])->count() + $expired,
        ];

        return view('payment.history', compact('payments', 'stats'));
    }

    /**
     * Check payment status (AJAX)
     */
    public function checkStatus(string $orderId)
    {
        // SECURITY: Scope to authenticated user to prevent IDOR
        $payment = Payment::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$payment) {
            return response()->json(['success' => false, 'message' => __('Payment not found')], 404);
        }

        $statusCheck = $this->getPakasirService()->checkTransactionStatus($orderId, $payment->amount);

        if (!($statusCheck['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => __('Status pembayaran belum dapat diverifikasi. Silakan coba lagi.'),
            ], 503);
        }


        if ($statusCheck['success']) {
            $status = $statusCheck['data']['status'] ?? null;

            if ($status === 'completed' && $payment->status !== 'SUCCESS') {
                $this->handleSuccessfulPayment($payment, $statusCheck['data']);
                // Refresh data model pembayaran agar status terbarunya langsung terambil (SUCCESS)
                $payment->refresh();
            }
        }

        return response()->json([
            'success' => true,
            'status' => $payment->status,
            'payment' => [
                'order_id' => $payment->order_id,
                'amount' => $payment->formatted_amount,
                'status' => $payment->status,
            ],
        ]);
    }
}
