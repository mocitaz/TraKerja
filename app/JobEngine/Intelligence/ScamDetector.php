<?php

namespace App\JobEngine\Intelligence;

/**
 * Rule-based red flags for fake vacancies common in Indonesia (registration fees, deposits,
 * "work from your phone, paid daily"). Returns the matched reasons; two or more reasons, or one
 * money-related reason, mark the vacancy "suspected" so it is held back from the public feed.
 */
final class ScamDetector
{
    private const MONEY = [
        'biaya pendaftaran' => '/biaya\s+(pendaftaran|administrasi|admin|seragam|pelatihan|training|medical|tes)/u',
        'transfer/deposit' => '/\b(transfer|deposit|uang\s+jaminan|top\s*up)\b.{0,40}\b(rp|rupiah|\d)/u',
        'bayar untuk kerja' => '/\b(wajib|harus)\s+(membayar|bayar|transfer)\b/u',
    ];

    private const SIGNALS = [
        'gaji harian tanpa syarat' => '/gaji\s+(harian|per\s*hari).{0,60}(tanpa\s+(pengalaman|syarat|modal)|dari\s+rumah|via\s+hp)/u',
        'kerja dari hp' => '/kerja(an)?\s+(dari|lewat|via)\s+(hp|handphone|rumah)\b.{0,40}(gaji|penghasilan|cuan)/u',
        'kontak pribadi saja' => '/(hubungi|chat|kirim\s+cv)\s+(via\s+)?(wa|whatsapp|telegram)\b/u',
        'like/rating berbayar' => '/\b(like|follow|rating|review|klik\s+iklan)\b.{0,40}\b(dibayar|komisi|gaji)\b/u',
    ];

    /** @return array{flag: 'none'|'suspected', reasons: array<int, string>} */
    public static function check(?string $title, ?string $description): array
    {
        $text = mb_strtolower(($title ?? '')."\n".($description ?? ''));
        $reasons = [];
        $money = false;
        foreach (self::MONEY as $reason => $pattern) {
            if (preg_match($pattern, $text)) {
                $reasons[] = $reason;
                $money = true;
            }
        }
        foreach (self::SIGNALS as $reason => $pattern) {
            if (preg_match($pattern, $text)) {
                $reasons[] = $reason;
            }
        }

        return ['flag' => ($money || count($reasons) >= 2) ? 'suspected' : 'none', 'reasons' => $reasons];
    }
}
