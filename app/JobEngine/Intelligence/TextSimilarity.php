<?php

namespace App\JobEngine\Intelligence;

/** Cheap, dependency-free similarity measures for job titles and descriptions. */
final class TextSimilarity
{
    /** Title as a matching key: lowercase, common abbreviations expanded, job codes and punctuation removed. */
    public static function normalizeTitle(string $title): string
    {
        $t = mb_strtolower(html_entity_decode($title));
        $t = preg_replace('/\((?:[a-z]*\d[\w-]*|req[^)]*|id[^)]*)\)|\b(?:req|ref|job)\s*(?:id)?\s*[#:]?\s*[a-z]*\d[\w-]*/u', ' ', $t);
        $t = preg_replace(['/\bsr\.?\b/u', '/\bjr\.?\b/u', '/\bmgr\b/u', '/\bspv\b/u', '/\beng\.?\b/u', '/\bexec\.?\b/u'],
            ['senior', 'junior', 'manager', 'supervisor', 'engineer', 'executive'], $t);
        $t = preg_replace('/\b(wfh|wfo|hybrid|remote|urgent|segera|dibutuhkan|lowongan|loker)\b/u', ' ', $t);

        return trim(preg_replace('/\s+/u', ' ', preg_replace('/[^\p{L}\p{N}]+/u', ' ', $t)));
    }

    /** Filler words that do not change which job a title describes. */
    private const FILLER = ['staff', 'staf', 'team', 'tim', 'member', 'karyawan', 'pegawai', 'posisi', 'position', 'untuk', 'for', 'and', 'dan', 'the', 'di', 'at', 'area', 'cabang', 'branch'];

    /**
     * Title similarity for dedup: average of word overlap and Jaro-Winkler on the normalized
     * title. Word overlap keeps "Data Analyst" and "Data Engineer" apart; Jaro-Winkler tolerates
     * small spelling differences.
     */
    public static function titleScore(string $a, string $b): float
    {
        $na = self::normalizeTitle($a);
        $nb = self::normalizeTitle($b);
        $ta = array_values(array_diff(explode(' ', $na), self::FILLER));
        $tb = array_values(array_diff(explode(' ', $nb), self::FILLER));
        $union = count(array_unique(array_merge($ta, $tb)));
        $overlap = $union ? count(array_intersect(array_unique($ta), array_unique($tb))) / $union : 0.0;

        return round(($overlap + self::jaroWinkler(implode(' ', $ta), implode(' ', $tb))) / 2, 3);
    }

    /** Jaro-Winkler similarity in [0, 1]. */
    public static function jaroWinkler(string $a, string $b): float
    {
        if ($a === $b) {
            return 1.0;
        }
        $la = mb_strlen($a);
        $lb = mb_strlen($b);
        if ($la === 0 || $lb === 0) {
            return 0.0;
        }
        $a = mb_str_split($a);
        $b = mb_str_split($b);
        $window = max(0, intdiv(max($la, $lb), 2) - 1);
        $matchA = array_fill(0, $la, false);
        $matchB = array_fill(0, $lb, false);
        $matches = 0;
        for ($i = 0; $i < $la; $i++) {
            for ($j = max(0, $i - $window); $j < min($lb, $i + $window + 1); $j++) {
                if (! $matchB[$j] && $a[$i] === $b[$j]) {
                    $matchA[$i] = $matchB[$j] = true;
                    $matches++;
                    break;
                }
            }
        }
        if ($matches === 0) {
            return 0.0;
        }
        $transpositions = 0;
        for ($i = 0, $k = 0; $i < $la; $i++) {
            if ($matchA[$i]) {
                while (! $matchB[$k]) {
                    $k++;
                }
                $transpositions += $a[$i] !== $b[$k] ? 1 : 0;
                $k++;
            }
        }
        $jaro = ($matches / $la + $matches / $lb + ($matches - $transpositions / 2) / $matches) / 3;
        $prefix = 0;
        for ($i = 0; $i < min(4, $la, $lb) && $a[$i] === $b[$i]; $i++) {
            $prefix++;
        }

        return $jaro + $prefix * 0.1 * (1 - $jaro);
    }

    /** 63-bit SimHash of word 3-grams (fits a signed and an unsigned BIGINT). */
    public static function simhash(?string $text): ?int
    {
        $words = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower((string) $text), -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) < 8) {
            return null;
        }
        $vector = array_fill(0, 63, 0);
        for ($i = 0; $i + 2 < count($words); $i++) {
            $hash = hexdec(substr(md5($words[$i].' '.$words[$i + 1].' '.$words[$i + 2]), 0, 15)) & PHP_INT_MAX;
            for ($bit = 0; $bit < 63; $bit++) {
                $vector[$bit] += (($hash >> $bit) & 1) ? 1 : -1;
            }
        }
        $result = 0;
        foreach ($vector as $bit => $weight) {
            if ($weight > 0) {
                $result |= (1 << $bit);
            }
        }

        return $result;
    }

    /** 1.0 for identical fingerprints, 0.0 for opposite ones. */
    public static function simhashSimilarity(int $a, int $b): float
    {
        $xor = $a ^ $b;
        $distance = 0;
        while ($xor) {
            $xor &= $xor - 1;
            $distance++;
        }

        return 1 - $distance / 63;
    }
}
