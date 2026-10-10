<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Services\PakasirService;
use Illuminate\Support\Facades\Log;

class SyncPendingPayments extends Command
{
    protected $signature = 'payments:sync-pending';
    protected $description = 'Sync all pending or waiting Pakasir payments with the API status';

    public function handle()
    {
        $this->info('Starting sync for pending/waiting payments...');

        $payments = Payment::whereIn('status', ['PENDING', 'WAITING'])->get();

        if ($payments->isEmpty()) {
            $this->info('No pending or waiting payments found.');
            return 0;
        }

        $this->info("Found {$payments->count()} payments to check.");
        $pakasir = new PakasirService();

        foreach ($payments as $payment) {
            $this->info("Checking Order ID: {$payment->order_id} (Amount: Rp " . number_format($payment->amount) . ")...");

            try {
                $statusCheck = $pakasir->checkTransactionStatus($payment->order_id, $payment->amount);

                if ($statusCheck['success']) {
                    $apiStatus = $statusCheck['data']['status'] ?? null;
                    $this->line("-> Pakasir API Status: {$apiStatus}");

                    if ($apiStatus === 'completed') {
                        $granted = app(\App\Services\PaymentSettlementService::class)->settle($payment, $statusCheck['data']);
                        $this->info($granted
                            ? '-> SUCCESS: Payment synchronized and benefits granted.'
                            : '-> SUCCESS: Payment already settled; no duplicate benefits.');
                    } elseif (in_array($apiStatus, ['failed', 'canceled'])) {
                        $apiStatus === 'canceled' ? $payment->markAsCanceled() : $payment->markAsFailed();
                        $this->warn("-> FAILED: Payment updated to FAILED status.");
                    } else {
                        $this->line("-> No change: Payment remains in pending/waiting status.");
                    }
                } else {
                    $errorMsg = $statusCheck['error'] ?? 'Unknown Error';
                    $this->error("-> Failed to check status from API: " . $errorMsg);
                    if (isset($statusCheck['debug'])) {
                        $this->line("   Debug: " . json_encode($statusCheck['debug']));
                    }
                }
            } catch (\Exception $e) {
                $this->error("-> Error processing Order ID {$payment->order_id}: " . $e->getMessage());
            }

            // Small delay to prevent API rate limits
            usleep(200000); 
        }

        $this->info('Payment sync complete.');
        return 0;
    }
}
