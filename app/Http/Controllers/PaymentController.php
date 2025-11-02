<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessMail;
use App\Models\Payment;
use App\Models\User;
use App\Services\YukkPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private ?YukkPaymentService $yukkService = null;

    public function __construct()
    {
        // Lazy load YukkPaymentService only when needed (for checkout, etc)
        // This prevents errors when config is missing
    }

    /**
     * Get YukkPaymentService instance (lazy loaded)
     */
    private function getYukkService(): YukkPaymentService
    {
        if ($this->yukkService === null) {
            $this->yukkService = app(YukkPaymentService::class);
        }
        return $this->yukkService;
    }

    /**
     * Show payment coming soon page
     */
    public function index()
    {
        // TODO: Uncomment when payment feature is ready
        
        // $user = Auth::user();
        //
        // // Check if user is already premium
        // if ($user->is_premium && $user->payment_status === 'paid') {
        //     return redirect()->route('profile.edit')
        //         ->with('info', 'Anda sudah menjadi member Premium!');
        // }
        //
        // // Get available payment channels from YUKK
        // $paymentChannels = $this->yukkService->getPaymentChannels();
        //
        // // Fallback: Use static payment channels if API fails
        // if (empty($paymentChannels)) {
        //     Log::warning('YUKK API returned empty payment channels, using fallback static channels');
        //     
        //     $paymentChannels = [
        //         // Bank Transfer (Virtual Account)
        //         [
        //             'code' => 'VA_BCA',
        //             'name' => 'Virtual Account BCA',
        //             'image_url' => 'https://dev.api.yukkpay.com/storage/images/payment-channels/va-bca.png',
        //             'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Bank Transfer']
        //         ],
        //         [
        //             'code' => 'VA_MANDIRI',
        //             'name' => 'Virtual Account MANDIRI',
        //             'image_url' => 'https://dev.api.yukkpay.com/storage/images/payment-channels/va-mandiri.png',
        //             'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Bank Transfer']
        //         ],
        //         [
        //             'code' => 'VA_BNI',
        //             'name' => 'Virtual Account BNI',
        //             'image_url' => 'https://dev.api.yukkpay.com/storage/images/payment-channels/va-bni.png',
        //             'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Bank Transfer']
        //         ],
        //         [
        //             'code' => 'VA_BRI',
        //             'name' => 'Virtual Account BRI',
        //             'image_url' => 'https://dev.api.yukkpay.com/storage/images/payment-channels/va-bri.png',
        //             'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Bank Transfer']
        //         ],
        //         [
        //             'code' => 'VA_PERMATA',
        //             'name' => 'Virtual Account PERMATA',
        //             'image_url' => 'https://dev.api.yukkpay.com/storage/images/payment-channels/va-permata.png',
        //             'category' => ['code' => 'BANK_TRANSFER', 'name' => 'Bank Transfer']
        //         ],
        //         // E-Wallet
        //         [
        //             'code' => 'OVO',
        //             'name' => 'OVO',
        //             'image_url' => 'https://dev.api.yukkpay.com/storage/images/payment-channels/ovo.png',
        //             'category' => ['code' => 'E_WALLET', 'name' => 'E-Wallet']
        //         ],
        //         [
        //             'code' => 'SHOPEEPAY',
        //             'name' => 'ShopeePay',
        //             'image_url' => 'https://dev.api.yukkpay.com/storage/images/payment-channels/shopeepay.png',
        //             'category' => ['code' => 'E_WALLET', 'name' => 'E-Wallet']
        //         ],
        //         // QRIS
        //         [
        //             'code' => 'QRIS',
        //             'name' => 'QRIS',
        //             'image_url' => 'https://dev.api.yukkpay.com/storage/images/payment-channels/qris.png',
        //             'category' => ['code' => 'QRIS', 'name' => 'QRIS']
        //         ],
        //         // Credit Card
        //         [
        //             'code' => 'CREDIT_CARD',
        //             'name' => 'Credit Card',
        //             'image_url' => 'https://dev.api.yukkpay.com/storage/images/payment-channels/credit-card.png',
        //             'category' => ['code' => 'CREDIT_CARD', 'name' => 'Credit Card']
        //         ],
        //     ];
        // }
        //
        // // Group payment channels by category
        // $groupedChannels = collect($paymentChannels)->groupBy('category.code');
        //
        // $premiumPrice = config('yukk.premium_price');
        // $premiumDuration = config('yukk.premium_duration_days');
        //
        // return view('payment.index', compact('groupedChannels', 'premiumPrice', 'premiumDuration'));

        return view('payment.coming-soon');
    }

    /**
     * Create payment and redirect to YUKK payment page
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
            $amount = config('yukk.premium_price');

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
                'callback_url' => config('yukk.callback_url'),
                'notification_url' => config('yukk.notification_url'),
            ]);

            // Prepare payment request data
            $paymentData = $this->getYukkService()->formatPaymentRequest(
                orderId: $orderId,
                amount: $amount,
                paymentChannelCode: $request->payment_channel_code,
                customer: [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '0000000000',
                ]
            );

            // Request payment to YUKK
            $response = $this->getYukkService()->requestPayment($paymentData);

            if (!$response['success']) {
                DB::rollBack();
                return back()->withErrors([
                    'payment_error' => 'Gagal membuat pembayaran: ' . ($response['error'] ?? 'Unknown error')
                ]);
            }

            // Update payment with YUKK response
            $payment->update([
                'yukk_transaction_code' => $response['data']['code'] ?? null,
                'yukk_token' => $response['data']['token'] ?? null,
                'redirect_url' => $response['data']['redirect_url'] ?? null,
                'status' => 'WAITING',
            ]);

            DB::commit();

            Log::info('Payment created successfully', [
                'order_id' => $orderId,
                'user_id' => $user->id,
                'yukk_code' => $payment->yukk_transaction_code,
            ]);

            // Redirect to YUKK payment page
            return redirect($payment->redirect_url);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment checkout failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return back()->withErrors([
                'payment_error' => 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Handle callback from YUKK payment page
     */
    public function callback(Request $request)
    {
        Log::info('Payment callback received', [
            'params' => $request->all(),
        ]);

        $orderId = $request->input('order_id');
        $status = $request->input('status');

        if (!$orderId) {
            return redirect()->route('tracker')
                ->with('error', 'Data pembayaran tidak valid.');
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return redirect()->route('tracker')
                ->with('error', 'Pembayaran tidak ditemukan.');
        }

        // Determine redirect based on status
        if ($status === 'SUCCESS') {
            return redirect()->route('payment.success', ['orderId' => $orderId]);
        } elseif ($status === 'FAILED' || $status === 'CANCELED') {
            return redirect()->route('payment.failed', ['orderId' => $orderId]);
        } else {
            // WAITING or other status
            return redirect()->route('payment.waiting', ['orderId' => $orderId]);
        }
    }

    /**
     * Handle webhook notification from YUKK
     */
    public function webhook(Request $request)
    {
        Log::info('Payment webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        // Verify webhook signature
        $signature = $request->header('Signature');
        $data = $request->all();

        if (!$this->getYukkService()->verifyWebhookSignature($signature, $data)) {
            Log::warning('Webhook signature verification failed', [
                'signature' => $signature,
            ]);
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $orderId = $data['order_id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$orderId || !$status) {
            Log::warning('Webhook missing required data', ['data' => $data]);
            return response()->json(['message' => 'Missing required data'], 400);
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            Log::warning('Payment not found for webhook', ['order_id' => $orderId]);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        try {
            DB::beginTransaction();

            // Update payment with webhook data
            $payment->update([
                'webhook_data' => $data,
                'status' => $status,
            ]);

            // If payment is successful, upgrade user to premium
            if ($status === 'SUCCESS') {
                $user = $payment->user;
                $premiumDuration = config('yukk.premium_duration_days');
                
                $user->update([
                    'is_premium' => true,
                    'payment_status' => 'paid',
                    'premium_until' => now()->addDays($premiumDuration),
                    'premium_purchased_at' => now(),
                ]);

                $payment->markAsSuccess();

                // Send success email notification
                try {
                    Mail::to($user->email)->send(new PaymentSuccessMail($payment));
                    Log::info('Payment success email sent', [
                        'user_id' => $user->id,
                        'order_id' => $orderId,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success email', [
                        'user_id' => $user->id,
                        'order_id' => $orderId,
                        'error' => $e->getMessage(),
                    ]);
                    // Don't fail the webhook if email fails
                }

                Log::info('User upgraded to premium', [
                    'user_id' => $user->id,
                    'order_id' => $orderId,
                    'premium_until' => $user->premium_until,
                ]);
            } elseif ($status === 'FAILED') {
                $payment->markAsFailed();
            }

            DB::commit();

            return response()->json([
                'message' => 'Webhook processed successfully',
                'order_id' => $orderId,
                'status' => $status,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return response()->json([
                'message' => 'Webhook processing failed',
                'error' => $e->getMessage(),
            ], 500);
        }
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
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        // Check status from YUKK API if transaction code exists
        if ($payment->yukk_transaction_code) {
            $response = $this->getYukkService()->checkPaymentStatus($payment->yukk_transaction_code);
            
            if ($response['success']) {
                $status = $response['data']['status'] ?? $payment->status;
                
                if ($status !== $payment->status) {
                    $payment->update(['status' => $status]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'status' => $payment->status,
            'payment' => [
                'order_id' => $payment->order_id,
                'amount' => $payment->formatted_amount,
                'payment_method' => $payment->payment_method,
                'status' => $payment->status,
            ],
        ]);
    }
}

