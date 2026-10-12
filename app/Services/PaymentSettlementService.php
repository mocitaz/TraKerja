<?php

namespace App\Services;

use App\Mail\PaymentSuccessMail;
use App\Models\Payment;
use App\Models\PaymentGrant;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PaymentSettlementService
{
    public function settle(Payment $payment, array $verifiedTransaction): bool
    {
        return DB::transaction(function () use ($payment, $verifiedTransaction) {
            $locked = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            if (Validator::make($verifiedTransaction, [
                'project' => 'required|string', 'order_id' => 'required|string',
                'amount' => 'required|integer|min:1', 'status' => 'required|in:completed',
            ])->fails()
                || $verifiedTransaction['project'] !== config('pakasir.slug')
                || $verifiedTransaction['order_id'] !== $locked->order_id
                || (int) $verifiedTransaction['amount'] !== $locked->amount) {
                throw new InvalidArgumentException('Verified transaction does not match payment');
            }

            $hasGrant = PaymentGrant::where('payment_id', $locked->id)->exists();
            if ($locked->isSuccess() || $hasGrant || $locked->paid_at !== null) {
                if (! $hasGrant) {
                    PaymentGrant::create(['payment_id' => $locked->id, 'benefit' => 'legacy_settlement',
                        'quantity' => 0, 'granted_at' => $locked->paid_at ?? now()]);
                }
                if (! $locked->isSuccess()) {
                    $locked->markAsSuccess();
                }

                return false;
            }

            $user = User::whereKey($locked->user_id)->lockForUpdate()->firstOrFail();
            $package = $locked->notes ?? ($locked->metadata['package_type'] ?? 'premium');
            $isAddon = in_array($package, ['addon_10', 'cl_addon_15'], true);
            if ($isAddon) {
                $benefits = \App\Support\PaymentPackages::ADDON_BENEFITS;
                foreach ($benefits as $field => $quantity) {
                    $user->increment($field, $quantity);
                }
            } else {
                $startsAt = $user->isPremium() && $user->premium_until !== null
                    ? $user->premium_until->copy()
                    : now();
                $user->forceFill([
                    'is_premium' => true, 'payment_status' => 'paid',
                    'premium_until' => $startsAt->addDays((int) config('pakasir.premium_duration_days', 365)),
                    'premium_purchased_at' => now(),
                ])->save();
                $benefits = ['premium' => 1];
                foreach (['ai_credits' => 'ai_analyzer', 'cl_credits' => 'cover_letters', 'photo_credits' => 'ai_photo'] as $field => $feature) {
                    $limit = Setting::getLimit($feature, $user);
                    $benefits[$field] = $limit === 'unlimited' ? 9999 : (int) $limit;
                    $user->setAttribute($field, $benefits[$field]);
                }
                $user->save();
            }

            foreach ($benefits as $benefit => $quantity) {
                PaymentGrant::create(['payment_id' => $locked->id, 'benefit' => $benefit,
                    'quantity' => $quantity, 'granted_at' => now()]);
            }
            $locked->update(['webhook_data' => $verifiedTransaction, 'status' => 'SUCCESS', 'paid_at' => now()]);

            DB::afterCommit(function () use ($locked, $user, $isAddon) {
                ActivityLogger::log($isAddon ? 'top_up' : 'premium_upgrade',
                    $isAddon ? 'User melakukan Top Up Add-On' : 'User upgrade ke akun Premium',
                    'success', ['order_id' => $locked->order_id, 'amount' => $locked->amount], $user->id);
                try {
                    Mail::to($user->email)->send(new PaymentSuccessMail($locked));
                } catch (\Exception $e) {
                    Log::error('Pakasir: Failed to send success email', ['error' => $e->getMessage()]);
                }

                try {
                    app(\App\Services\TelegramService::class)->sendPaymentSuccess($locked, $user, $isAddon);
                } catch (\Throwable $e) {
                    Log::error('Pakasir: Failed to send Telegram alert', ['error' => $e->getMessage()]);
                }
            });

            return true;
        });
    }
}
