<?php

namespace App\Support;

use App\Models\Payment;

final class PaymentPresentation
{
    public static function status(Payment $payment): string
    {
        $expiry = $payment->expired_at ?? $payment->va_expired_at;
        if (in_array($payment->status, ['PENDING', 'WAITING'], true) && $expiry?->isPast()) {
            return 'EXPIRED';
        }
        return $payment->status;
    }

    public static function package(Payment $payment): string
    {
        return ($payment->notes ?? $payment->metadata['package_type'] ?? 'premium') === 'premium'
            ? 'TraKerja Premium' : 'Credit pack';
    }

    public static function label(string $status): string
    {
        return match ($status) {
            'SUCCESS' => 'Paid', 'FAILED' => 'Failed', 'CANCELED' => 'Canceled', 'EXPIRED' => 'Expired',
            'PENDING', 'WAITING' => 'Awaiting payment', default => 'Status unavailable',
        };
    }

    public static function resumeUrl(Payment $payment): ?string
    {
        if (!in_array(self::status($payment), ['PENDING', 'WAITING'], true)) {
            return null;
        }
        $url = $payment->redirect_url;
        return is_string($url) && filter_var($url, FILTER_VALIDATE_URL)
            && in_array(strtolower(parse_url($url, PHP_URL_SCHEME) ?? ''), ['https', 'http'], true) ? $url : null;
    }

    public static function method(Payment $payment): string
    {
        return $payment->payment_channel_name ?: match ($payment->payment_channel_code) {
            'qris' => 'QRIS', 'bank_transfer' => 'Bank transfer', 'bca_va' => 'BCA virtual account',
            'bni_va' => 'BNI virtual account', 'bri_va' => 'BRI virtual account', 'permata_va' => 'Permata virtual account',
            default => 'Pakasir checkout',
        };
    }

    public static function benefit(string $benefit, int $quantity): string
    {
        return match ($benefit) {
            'premium' => __('Premium membership'), 'ai_credits' => __(':quantity AI credits', ['quantity' => $quantity]),
            'cl_credits' => __(':quantity cover letters', ['quantity' => $quantity]),
            'photo_credits' => __(':quantity photo credits', ['quantity' => $quantity]),
            default => __('Recorded benefit'),
        };
    }
}
