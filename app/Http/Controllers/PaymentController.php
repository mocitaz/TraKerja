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
        $premiumPrice = config('pakasir.premium_price', 15000);
        $premiumDuration = config('pakasir.premium_duration_days', 365);

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
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            // Generate unique order ID
            $orderId = 'TRAKERJA-' . strtoupper(Str::random(10)) . '-' . time();

            // Get premium price
            $amount = config('pakasir.premium_price', 15000);

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
                'payment_error' => 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.'
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

        if (!$orderId || !$status) {
            return response()->json(['message' => 'Missing required data'], 400);
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Verify amount
        if ($payment->amount != $amount) {
            Log::warning('Pakasir: Webhook amount mismatch', [
                'expected' => $payment->amount,
                'received' => $amount
            ]);
            return response()->json(['message' => 'Amount mismatch'], 400);
        }

        try {
            if ($status === 'completed') {
                $this->handleSuccessfulPayment($payment, $data);
            } elseif ($status === 'failed' || $status === 'canceled') {
                $payment->markAsFailed();
            }

            return response()->json(['message' => 'Webhook processed successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Pakasir: Webhook processing failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            return response()->json(['message' => 'Processing failed'], 500);
        }
    }

    /**
     * Common logic for successful payment
     */
    private function handleSuccessfulPayment(Payment $payment, array $data): void
    {
        if ($payment->status === 'SUCCESS') return;

        DB::transaction(function () use ($payment, $data) {
            $payment->update([
                'webhook_data' => $data,
                'status' => 'SUCCESS',
                'paid_at' => now(),
            ]);

            $user = $payment->user;
            $premiumDuration = config('pakasir.premium_duration_days', 365);
            
            $user->update([
                'is_premium' => true,
                'payment_status' => 'paid',
                'premium_until' => now()->addDays($premiumDuration),
                'premium_purchased_at' => now(),
            ]);

            // Send success email notification
            try {
                Mail::to($user->email)->send(new PaymentSuccessMail($payment));
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
        $payment = Payment::where('order_id', $orderId)->first();

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
        $payment = Payment::where('order_id', $orderId)->first();

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
        $payment = Payment::where('order_id', $orderId)->first();

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
        $payment = Payment::where('order_id', $orderId)->first();

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
