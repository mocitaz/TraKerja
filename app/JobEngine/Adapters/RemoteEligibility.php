<?php

namespace App\JobEngine\Adapters;

/** Remote jobs an applicant living in Indonesia can actually take: worldwide, APAC/Asia or Indonesia. */
final class RemoteEligibility
{
    public static function openToIndonesia(?string $restriction): bool
    {
        $r = mb_strtolower(trim((string) $restriction));
        if ($r === '' || $r === 'remote') {
            return true;
        }

        return (bool) preg_match('/\b(worldwide|anywhere|global|world|apac|asia|asia-pacific|asia pacific|southeast asia|sea|indonesia|utc\s*\+\s*(7|8|9))\b/u', $r);
    }
}
