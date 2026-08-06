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
                        // Manual activation block like handleSuccessfulPayment
                        \DB::transaction(function () use ($payment, $statusCheck) {
                            $payment->update([
                                'webhook_data' => $statusCheck['data'],
                                'status' => 'SUCCESS',
                                'paid_at' => now(),
                            ]);

                            $user = $payment->user;
                            $packageType = $payment->notes ?? ($payment->metadata['package_type'] ?? 'premium');

                            if ($packageType === 'addon_10' || $packageType === 'cl_addon_15') {
                                $user->addAiCredits(10);
                                $user->addClCredits(15);
                                $user->addPhotoCredits(5);
                            } else {
                                $premiumDuration = (int) config('pakasir.premium_duration_days', 365);
                                $user->update([
                                    'is_premium' => true,
                                    'payment_status' => 'paid',
                                    'premium_until' => now()->addDays($premiumDuration),
                                    'premium_purchased_at' => now(),
                                ]);

                                $analyzerLimit = \App\Models\Setting::getLimit('ai_analyzer', $user);
                                $clLimit = \App\Models\Setting::getLimit('cover_letters', $user);
                                $photoLimit = \App\Models\Setting::getLimit('ai_photo', $user);

                                $user->update([
                                    'ai_credits' => $analyzerLimit === 'unlimited' ? 9999 : (int) $analyzerLimit,
                                    'cl_credits' => $clLimit === 'unlimited' ? 9999 : (int) $clLimit,
                                    'photo_credits' => $photoLimit === 'unlimited' ? 9999 : (int) $photoLimit,
                                ]);
                            }

                            $this->info("-> SUCCESS: Payment synchronized and credits/premium granted successfully!");
                        });
                    } elseif (in_array($apiStatus, ['failed', 'canceled'])) {
                        $payment->update(['status' => 'FAILED']);
                        $this->warn("-> FAILED: Payment updated to FAILED status.");
                    } else {
                        $this->line("-> No change: Payment remains in pending/waiting status.");
                    }
                } else {
                    $this->error("-> Failed to check status from API: " . ($statusCheck['error'] ?? 'Unknown Error'));
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
