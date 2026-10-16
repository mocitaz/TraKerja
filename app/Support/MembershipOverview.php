<?php

namespace App\Support;

use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;

final class MembershipOverview
{
    public static function forUser(User $user): array
    {
        $active = $user->isPremium();
        $expired = !$active && $user->is_premium && $user->payment_status === 'paid'
            && $user->premium_until !== null && $user->premium_until->lte(now());

        return [
            'user' => $user,
            'active' => $active,
            'expired' => $expired,
            'monetizationEnabled' => Setting::isMonetizationEnabled(),
            'premiumPrice' => (int) Setting::get('premium_price', 9999),
            'premiumDuration' => (int) config('pakasir.premium_duration_days', 365),
            'addonPrice' => PaymentPackages::ADDON_PRICE,
            'addonBenefits' => PaymentPackages::ADDON_BENEFITS,
            'creditBalances' => [
                ['label' => 'AI Analyzer', 'field' => 'ai_credits', 'balance' => max(0, (int) $user->ai_credits), 'tone' => 'blue'],
                ['label' => 'Cover Letter', 'field' => 'cl_credits', 'balance' => max(0, (int) $user->cl_credits), 'tone' => 'lavender'],
                ['label' => 'AI Photo', 'field' => 'photo_credits', 'balance' => max(0, (int) $user->photo_credits), 'tone' => 'peach'],
            ],
            'pendingPayment' => Payment::where('user_id', $user->id)
                ->whereIn('status', ['PENDING', 'WAITING'])
                ->where(fn ($query) => $query->whereNull('expired_at')->orWhere('expired_at', '>', now()))
                ->orderByDesc('created_at')->orderByDesc('id')->first(),
            'payments' => Payment::where('user_id', $user->id)->orderByDesc('created_at')->orderByDesc('id')->paginate(8)->withQueryString()->appends(['tab' => 'membership']),
        ];
    }
}
