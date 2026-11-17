<?php

namespace App\JobEngine\Intelligence;

/**
 * ALL-CAPS job titles ("SALES INTERNET RAKYAT (IRA) - C") read as shouting in lists and on posters. Turn them
 * into title case and keep short acronyms ("Sales Internet Rakyat (IRA) - C"). Mixed-case titles are left as is.
 */
final class TitleCase
{
    private const ACRONYMS = ['IT', 'HR', 'HRD', 'QA', 'UI', 'UX', 'PT', 'CV', 'SPG', 'SPB', 'CS', 'BD', 'AI', 'SEO', 'CEO', 'CFO', 'CTO', 'COO', 'B2B', 'B2C', 'F&B', 'GA', 'PPIC', 'QC', 'K3', 'HSE', 'SDM', 'TU', 'NTT', 'NTB', 'DKI', 'SMA', 'SMK', 'D3', 'S1', 'S2', 'IOS', 'API', 'SAP', 'ERP', 'CRM', 'KOL', 'MC', 'DSE', 'TL', 'SPV', 'ATM', 'POS', 'R&D', 'PR', 'BPO'];

    public static function tidy(string $title): string
    {
        $letters = preg_replace('/[^\p{L}]/u', '', $title);
        if ($letters === '' || mb_strlen($letters) < 4 || mb_strtoupper($letters) !== $letters) {
            return $title;
        }

        return preg_replace_callback('/[\p{L}\p{N}&]+/u', function ($m) use ($title) {
            $word = $m[0];
            $inParens = (bool) preg_match('/\(\s*'.preg_quote($word, '/').'\s*\)/u', $title);
            if (mb_strlen($word) === 1 || preg_match('/\d/', $word) || ($inParens && mb_strlen($word) <= 5) || in_array($word, self::ACRONYMS, true)) {
                return $word;
            }

            return mb_convert_case(mb_strtolower($word), MB_CASE_TITLE);
        }, $title);
    }
}
