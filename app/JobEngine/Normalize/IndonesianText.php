<?php

namespace App\JobEngine\Normalize;

/**
 * Rule-based readers for how Indonesian vacancies write salary, seniority and education.
 * They only answer when the text is explicit; anything unclear returns null so the AI step
 * (Fase 3) or a reviewer can decide, instead of the engine guessing.
 */
final class IndonesianText
{
    /**
     * "Rp 4,5jt - 5,2jt / bln", "Rp4.500.000 - Rp5.200.000", "5-7 juta", "150rb/hari", "$1,000 - $2,000 per month".
     *
     * @return array{min: int, max: ?int, currency: string, period: ?string}|null
     */
    public static function salary(?string $text): ?array
    {
        if (! $text) {
            return null;
        }
        $t = mb_strtolower(str_replace(["\u{00A0}", '–', '—'], [' ', '-', '-'], $text));
        if (preg_match('/\b(nego|negotiable|kompetitif|competitive|dirahasiakan|confidential)\b/u', $t) && ! preg_match('/\d/', $t)) {
            return null;
        }

        $currency = preg_match('/(\$|usd|us\$)/u', $t) ? 'USD' : (preg_match('/(sgd|s\$)/u', $t) ? 'SGD' : 'IDR');
        $period = match (true) {
            (bool) preg_match('/(\/|per|tiap)\s*(jam|hour|hr)\b/u', $t) => 'hour',
            (bool) preg_match('/(\/|per|tiap)\s*(hari|day)\b|harian|daily/u', $t) => 'day',
            (bool) preg_match('/(\/|per|tiap)\s*(minggu|week)\b|mingguan|weekly/u', $t) => 'week',
            (bool) preg_match('/(\/|per|tiap)\s*(tahun|thn|year|yr|annum)\b|tahunan|annual|yearly/u', $t) => 'year',
            (bool) preg_match('/(\/|per|tiap)\s*(bulan|bln|month|mo)\b|bulanan|monthly/u', $t) => 'month',
            default => null,
        };

        // number, optional decimal comma/point, optional multiplier word
        preg_match_all('/(\d{1,3}(?:[.,]\d{3})+|\d+(?:[.,]\d+)?)\s*(juta|jt|rb|ribu|k|m)?\b/u', $t, $m, PREG_SET_ORDER);
        $values = [];
        foreach ($m as $match) {
            $values[] = self::number($match[1], $match[2] ?? '');
        }
        // "5-7 juta": the multiplier written once applies to both ends.
        if (count($m) >= 2 && ($m[0][2] ?? '') === '' && ($m[1][2] ?? '') !== '' && $values[0] < 1000) {
            $values[0] = self::number($m[0][1], $m[1][2]);
        }
        $values = array_values(array_filter($values, fn ($v) => $v >= ($currency === 'IDR' ? 10_000 : 1)));
        if ($values === []) {
            return null;
        }

        $min = min($values[0], $values[1] ?? $values[0]);
        $max = isset($values[1]) ? max($values[0], $values[1]) : null;
        if ($currency === 'IDR' && $period === null) {
            $period = $min >= 1_000_000 ? 'month' : null; // Indonesian postings default to monthly pay
        }

        return ['min' => $min, 'max' => $max, 'currency' => $currency, 'period' => $period];
    }

    /** Salary written inside a description: only right after an explicit salary keyword. */
    public static function salaryFromDescription(?string $text): ?array
    {
        if (! $text || ! preg_match('/\b(gaji|salary|upah|penghasilan|take home pay|thp)\b[^\n\d]{0,25}((?:rp|idr|\$)?\s*[\d.,]+\s*(?:juta|jt|rb|ribu|k)?(?:\s*-\s*(?:rp|idr|\$)?\s*[\d.,]+\s*(?:juta|jt|rb|ribu|k)?)?[^\n]{0,15})/iu', $text, $m)) {
            return null;
        }

        return self::salary($m[2]);
    }

    public static function seniority(?string $title): ?string
    {
        $t = ' '.mb_strtolower((string) $title).' ';

        return match (true) {
            (bool) preg_match('/\b(intern|internship|magang|pkl|trainee)\b/u', $t) => 'intern',
            (bool) preg_match('/\b([ase]?vp|vice president|director|direktur|head of|chief|c[eotf]o)\b/u', $t) => 'manager',
            (bool) preg_match('/\bassistant manager\b|\b(supervisor|spv)\b/u', $t) => 'mid',
            (bool) preg_match('/\bmanager\b|\bmanajer\b/u', $t) => 'manager',
            (bool) preg_match('/\b(lead|principal|kepala|team leader)\b/u', $t) => 'lead',
            (bool) preg_match('/\b(senior|sr\.?)\s/u', $t) => 'senior',
            (bool) preg_match('/\b(junior|jr\.?|fresh ?grad(uate)?|entry level)\b/u', $t) => 'entry',
            default => null,
        };
    }

    /** Lowest education level the text asks for, e.g. "Min. SMA/SMK, diutamakan D3" -> sma_smk. */
    public static function educationMin(?string $text): ?string
    {
        if (! $text) {
            return null;
        }
        $t = mb_strtolower($text);
        $levels = [
            'sd' => '/\b(sd|sekolah dasar)\b/u',
            'smp' => '/\b(smp|sltp)\b/u',
            'sma_smk' => '/\b(sma|smk|slta|stm|ma|high school)\b/u',
            'd3' => '/\b(d3|d-3|diploma(?: 3| iii)?|associate degree)\b/u',
            's1' => '/\b(s1|s-1|sarjana|bachelor\'?s?|d4)\b/u',
            's2' => '/\b(s2|s-2|magister|master\'?s?)\b/u',
        ];
        // Only count mentions in an education context to avoid "SD Card" or "MA" in other senses.
        if (! preg_match('/(pendidikan|lulusan|minimal|min\.|education|degree|ijazah|jenjang)/u', $t)) {
            return null;
        }
        foreach ($levels as $level => $pattern) {
            if (preg_match($pattern, $t)) {
                return $level;
            }
        }

        return null;
    }

    private static function number(string $num, string $unit): int
    {
        $unit = trim($unit);
        $hasMultiplier = in_array($unit, ['juta', 'jt', 'm', 'rb', 'ribu', 'k'], true);
        if ($hasMultiplier) {
            $value = (float) str_replace(',', '.', str_replace('.', '', preg_replace('/[.,](?=\d{3}\b)/', '', $num)));
            if (preg_match('/^\d+[.,]\d{1,2}$/', $num)) {
                $value = (float) str_replace(',', '.', $num);
            }
        } else {
            $value = (float) preg_replace('/[.,]/', '', $num);
        }

        return (int) round($value * match ($unit) {
            'juta', 'jt', 'm' => 1_000_000,
            'rb', 'ribu', 'k' => 1_000,
            default => 1,
        });
    }
}
