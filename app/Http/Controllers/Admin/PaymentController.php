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
        $query = Payment::with('user');

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('yukk_transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => Payment::count(),
            'success' => Payment::where('status', 'SUCCESS')->count(),
            'pending' => Payment::whereIn('status', ['PENDING', 'WAITING'])->count(),
            'failed' => Payment::whereIn('status', ['FAILED', 'CANCELED', 'EXPIRED'])->count(),
            'total_revenue' => Payment::where('status', 'SUCCESS')->sum('amount'),
            'today_revenue' => Payment::where('status', 'SUCCESS')
                ->whereDate('paid_at', today())
                ->sum('amount'),
        ];

        // Payment method distribution
        $paymentMethods = Payment::where('status', 'SUCCESS')
            ->select('payment_channel_code', DB::raw('count(*) as count'), DB::raw('sum(amount) as revenue'))
            ->groupBy('payment_channel_code')
            ->get();

        return view('admin.payments', compact('payments', 'stats', 'paymentMethods'));
    }

    /**
     * Show payment details
     */
    public function show(string $id)
    {
        $payment = Payment::with('user')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'payment' => $payment,
            'user' => $payment->user,
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
                'message' => 'Hanya payment dengan status PENDING atau WAITING yang bisa dibatalkan',
            ], 400);
        }

        if (!$payment->yukk_transaction_code) {
            return response()->json([
                'success' => false,
                'message' => 'Payment tidak memiliki YUKK transaction code',
            ], 400);
        }

        try {
            $response = $this->yukkService->cancelVATransaction($payment->yukk_transaction_code);

            if ($response['success']) {
                $payment->update(['status' => 'CANCELED']);
                
                Log::info('Payment canceled by admin', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment berhasil dibatalkan',
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
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $query = Payment::with('user');

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

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
                'YUKK Transaction Code',
            ]);

            // CSV Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->order_id,
                    $payment->user->name ?? 'N/A',
                    $payment->user->email ?? 'N/A',
                    $payment->amount,
                    $payment->payment_method,
                    $payment->status,
                    $payment->va_number ?? 'N/A',
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->paid_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $payment->yukk_transaction_code ?? 'N/A',
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
