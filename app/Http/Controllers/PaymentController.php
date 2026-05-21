<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessMail;
use App\Models\Payment;
use App\Models\User;
use App\Services\PakasirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
                ->with('error', 'Silakan upgrade ke Premium terlebih dahulu untuk membeli Add-On!');
        }

        $package = request()->query('package', 'analyzer');
        
        if ($package === 'cover_letter') {
            $packageType = 'cl_addon_15';
            $packageName = 'TraKerja Add-On Pack';
            $packageSubtitle = '15 Kredit Cover Letter + 10 Kredit AI Analyzer';
            $addonPrice = 14999;
        } else {
            $packageType = 'addon_10';
            $packageName = 'TraKerja Add-On Pack';
            $packageSubtitle = '10 Kredit AI Analyzer + 15 Kredit Cover Letter';
            $addonPrice = 14999;
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
        if ($user->is_premium && $user->payment_status === 'paid') {
            return redirect()->route('profile.edit')
                ->with('info', 'Anda sudah menjadi member Premium!');
        }

        // Pakasir is simpler, we just need a price and duration
        $premiumPrice = (int) \App\Models\Setting::get('premium_price', 19999);
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
            'payment_channel_code' => 'required|string',
            'package_type' => 'nullable|string|in:premium,addon_10,cl_addon_15',
        ]);

        $user = Auth::user();
        $packageType = $request->input('package_type', 'premium');

        // Check if package is premium and user is already premium
        if ($packageType === 'premium' && $user->is_premium && $user->payment_status === 'paid') {
            return redirect()->route('profile.edit')
                ->with('info', 'Anda sudah menjadi member Premium!');
        }

        // Check if package is an add-on and user is NOT premium
        if (in_array($packageType, ['addon_10', 'cl_addon_15']) && !$user->isPremium()) {
            return redirect()->route('payment.premium')
                ->with('error', 'Silakan upgrade ke Premium terlebih dahulu untuk membeli Add-On!');
        }

        try {
            DB::beginTransaction();

            // Generate unique order ID
            $orderId = 'TRAKERJA-' . strtoupper(Str::random(10)) . '-' . time();

            // Determine amount based on package type
            if ($packageType === 'addon_10' || $packageType === 'cl_addon_15') {
                $amount = 14999; // Rp 14.999 for 10/15 credits
            } else {
                $amount = (int) \App\Models\Setting::get('premium_price', 19999);
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
                'payment_error' => 'Terjadi kesalahan saat memproses pembayaran. Detail: ' . $e->getMessage()
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
                ->with('error', 'Data pembayaran tidak valid.');
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return redirect()->route('tracker')
                ->with('error', 'Pembayaran tidak ditemukan.');
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
        Log::info('Pakasir: Webhook received', [
            'body' => $request->all(),
        ]);

        $data = $request->all();
        $orderId = $data['order_id'] ?? null;
        $status = $data['status'] ?? null;
        $amount = $data['amount'] ?? null;
        
        // Extract Pakasir transaction ID or generate a hash of the payload to prevent duplicates
        $transactionId = $data['transaction_id'] ?? $data['id'] ?? md5(json_encode($data));

        if (!$orderId || !$status) {
            return response()->json(['message' => 'Missing required data'], 400);
        }

        // Check if this webhook was already processed
        $existingLog = \App\Models\WebhookLog::where('transaction_id', $transactionId)
            ->where('status', 'processed')
            ->first();
            
        if ($existingLog) {
            Log::info('Pakasir: Webhook already processed', ['transaction_id' => $transactionId]);
            return response()->json(['message' => 'Already processed'], 200);
        }
        
        // Create webhook log record
        $webhookLog = \App\Models\WebhookLog::create([
            'provider' => 'pakasir',
            'transaction_id' => $transactionId,
            'reference_id' => $orderId,
            'event_type' => $data['event'] ?? 'payment_update',
            'payload' => $data,
            'status' => 'received',
        ]);

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            $webhookLog->update(['status' => 'failed', 'notes' => 'Payment not found']);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Verify amount
        if ($payment->amount != $amount) {
            Log::warning('Pakasir: Webhook amount mismatch', [
                'expected' => $payment->amount,
                'received' => $amount
            ]);
            $webhookLog->update(['status' => 'failed', 'notes' => 'Amount mismatch']);
            return response()->json(['message' => 'Amount mismatch'], 400);
        }

        try {
            if ($status === 'completed') {
                $this->handleSuccessfulPayment($payment, $data);
            } elseif ($status === 'failed' || $status === 'canceled') {
                $payment->markAsFailed();
            }

            $webhookLog->update(['status' => 'processed']);
            return response()->json(['message' => 'Webhook processed successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Pakasir: Webhook processing failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            $webhookLog->update(['status' => 'failed', 'notes' => $e->getMessage()]);
            return response()->json(['message' => 'Processing failed'], 500);
        }
    }

    /**
     * Common logic for successful payment
     */
    private function handleSuccessfulPayment(Payment $payment, array $data): void
    {
        DB::transaction(function () use ($payment, $data) {
            // Re-fetch payment with write lock to prevent parallel execution race conditions
            $lockedPayment = Payment::where('id', $payment->id)->lockForUpdate()->first();

            // If payment has already been processed successfully by another thread, exit early
            if (!$lockedPayment || $lockedPayment->status === 'SUCCESS') {
                return;
            }

            $lockedPayment->update([
                'webhook_data' => $data,
                'status' => 'SUCCESS',
                'paid_at' => now(),
            ]);

            $user = $lockedPayment->user;
            
            // Check the package type from notes or metadata
            $packageType = $lockedPayment->notes ?? ($lockedPayment->metadata['package_type'] ?? 'premium');

            if ($packageType === 'addon_10' || $packageType === 'cl_addon_15') {
                // Add BOTH 10 AI credits AND 15 Cover Letter credits!
                $user->addAiCredits(10);
                $user->addClCredits(15);
            } else {
                // Default: Premium purchase
                $premiumDuration = (int) config('pakasir.premium_duration_days', 365);
                
                $user->update([
                    'is_premium' => true,
                    'payment_status' => 'paid',
                    'premium_until' => now()->addDays($premiumDuration),
                    'premium_purchased_at' => now(),
                ]);

                // Add credits as requested by user
                $user->addAiCredits(5);
                $user->addClCredits(15);
                $user->addPhotoCredits(5);
            }

            // Send success email notification
            try {
                Mail::to($user->email)->send(new PaymentSuccessMail($lockedPayment));
            } catch (\Exception $e) {
                Log::error('Pakasir: Failed to send success email', ['error' => $e->getMessage()]);
            }
        });
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
                ->with('error', 'Pembayaran tidak ditemukan.');
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
                ->with('error', 'Pembayaran tidak ditemukan.');
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
                ->with('error', 'Pembayaran tidak ditemukan.');
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

        // Calculate stats
        $stats = [
            'total' => Payment::where('user_id', $user->id)->count(),
            'success' => Payment::where('user_id', $user->id)->where('status', 'SUCCESS')->count(),
            'pending' => Payment::where('user_id', $user->id)->whereIn('status', ['PENDING', 'WAITING'])->count(),
            'failed' => Payment::where('user_id', $user->id)->whereIn('status', ['FAILED', 'CANCELED'])->count(),
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
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        // Check status from Pakasir API
        $statusCheck = $this->getPakasirService()->checkTransactionStatus($orderId, $payment->amount);
        
        if ($statusCheck['success']) {
            $status = $statusCheck['data']['status'] ?? $payment->status;
            
            if ($status === 'completed' && $payment->status !== 'SUCCESS') {
                $this->handleSuccessfulPayment($payment, $statusCheck['data']);
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
