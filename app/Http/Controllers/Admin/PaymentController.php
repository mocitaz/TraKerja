<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\YukkPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private YukkPaymentService $yukkService;

    public function __construct(YukkPaymentService $yukkService)
    {
        $this->yukkService = $yukkService;
    }

    /**
     * Admin payment dashboard - list all payments
     */
    public function index(Request $request)
    {
        $query = $this->filteredPayments($request);
        $payments = (clone $query)->latest()->orderByDesc('id')->paginate((int) $request->input('per_page', 15))->withQueryString();
        $summary = (clone $query)->toBase()->selectRaw(
            "COUNT(*) AS total, SUM(CASE WHEN status = 'SUCCESS' THEN 1 ELSE 0 END) AS success, SUM(CASE WHEN status IN ('PENDING', 'WAITING') THEN 1 ELSE 0 END) AS pending, SUM(CASE WHEN status = 'SUCCESS' THEN amount ELSE 0 END) AS total_revenue"
        )->first();
        $stats = array_map('intval', (array) $summary);
        return view('admin.payments', compact('payments', 'stats'));
    }

    private function filteredPayments(Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:100', 'status' => 'nullable|in:SUCCESS,PENDING,WAITING,FAILED,CANCELED,EXPIRED',
            'environment' => 'nullable|in:live,sandbox,unknown', 'date_from' => 'nullable|date_format:Y-m-d',
            'date_to' => ['nullable', 'date_format:Y-m-d', \Illuminate\Validation\Rule::when($request->filled('date_from'), 'after_or_equal:date_from')], 'per_page' => 'nullable|in:15,30,50,100',
        ]);
        $query = Payment::with('user');
        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if ($search = trim($filters['search'] ?? '')) {
            $query->where(fn ($q) => $q->where('order_id', 'like', '%'.$search.'%')
                ->orWhere('yukk_transaction_code', 'like', '%'.$search.'%')->orWhere('pakasir_txn_id', 'like', '%'.$search.'%')
                ->orWhere('customer_name', 'like', '%'.$search.'%')->orWhere('customer_email', 'like', '%'.$search.'%')
                ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')));
        }
        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);
        match ($filters['environment'] ?? '') {
            'live' => $query->where('pakasir_is_sandbox', false),
            'sandbox' => $query->where('pakasir_is_sandbox', true),
            'unknown' => $query->whereNull('pakasir_is_sandbox'),
            default => null,
        };
        return $query;
    }

    /**
     * Show payment details
     */
    public function show(string $id)
    {
        $payment = Payment::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'payment' => $payment->only(['id', 'order_id', 'amount', 'status', 'payment_channel_code', 'payment_channel_name', 'payment_category', 'va_number', 'created_at', 'paid_at', 'expired_at', 'va_expired_at', 'yukk_transaction_code', 'pakasir_txn_id', 'pakasir_is_sandbox', 'notes', 'customer_name', 'customer_email']) + [
                'payment_method' => $payment->payment_method,
                'can_cancel' => in_array($payment->status, ['PENDING', 'WAITING'], true) && (bool) $payment->yukk_transaction_code,
            ],
            'user' => $payment->user ? ['id' => $payment->user->id, 'name' => $payment->user->name, 'email' => $payment->user->email, 'avatar_url' => $payment->user->avatar_url] : null,
        ]);
    }

    /**
     * Cancel payment (for VA only)
     */
    public function cancel(string $id)
    {
        $payment = Payment::findOrFail($id);

        if (!in_array($payment->status, ['PENDING', 'WAITING'])) {
            return response()->json([
                'success' => false,
                'message' => __('Hanya payment dengan status PENDING atau WAITING yang bisa dibatalkan'),
            ], 400);
        }

        if (!$payment->yukk_transaction_code) {
            return response()->json([
                'success' => false,
                'message' => __('Payment tidak memiliki YUKK transaction code'),
            ], 400);
        }

        try {
            $response = $this->yukkService->cancelVATransaction($payment->yukk_transaction_code);

            if ($response['success']) {
                $payment->markAsCanceled();

                Log::info('Payment canceled by admin', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => __('Payment berhasil dibatalkan'),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Gagal membatalkan payment',
            ], 400);

        } catch (\Exception $e) {
            Log::error('Failed to cancel payment', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => __('Error: ') . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $payments = $this->filteredPayments($request)->latest()->orderByDesc('id')->get();

        $filename = 'payments_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Order ID',
                'User',
                'Email',
                'Amount (IDR)',
                'Payment Method',
                'Status',
                'VA Number',
                'Created At',
                'Paid At',
                'YUKK Transaction Code', 'Pakasir Transaction ID', 'Environment',
            ]);

            // CSV Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->order_id,
                    $payment->user->name ?? $payment->customer_name ?? 'N/A',
                    $payment->user->email ?? $payment->customer_email ?? 'N/A',
                    $payment->amount,
                    $payment->payment_method,
                    $payment->status,
                    $payment->va_number ?? 'N/A',
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->paid_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $payment->yukk_transaction_code ?? 'N/A',
                    $payment->pakasir_txn_id ?? 'N/A',
                    $payment->pakasir_is_sandbox === null ? 'Unspecified' : ($payment->pakasir_is_sandbox ? 'Sandbox' : 'Live'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get payment analytics data (AJAX)
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days

        $startDate = now()->subDays($period);

        // Revenue over time
        $revenueData = Payment::where('status', 'SUCCESS')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment status distribution
        $statusData = Payment::where('created_at', '>=', $startDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Payment method distribution
        $methodData = Payment::where('status', 'SUCCESS')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('payment_channel_code, COUNT(*) as count, SUM(amount) as revenue')
            ->groupBy('payment_channel_code')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'revenue' => $revenueData,
                'status' => $statusData,
                'methods' => $methodData,
            ],
        ]);
    }
}
