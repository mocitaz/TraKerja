<?php

namespace App\JobEngine\Adapters;

/** True when an ATS location string points to Indonesia (city, province or the country itself). */
final class IndonesiaLocation
{
    public const PATTERN = '/indonesia|jakarta|surabaya|bandung|medan|bekasi|tangerang|semarang|yogyakarta|jogja|bali|denpasar|makassar|bogor|depok|batam|malang|palembang|balikpapan|pekanbaru|\bID\b/i';

    public static function matches(?string $location): bool
    {
        return (bool) preg_match(self::PATTERN, (string) $location);
    }

    /**
     * Keep an ATS job when it is in Indonesia, or remote without being tied to another country
     * ("Remote, Bangkok, Thailand" is a Thai role, not one open to applicants in Indonesia).
     */
    public static function keep(?string $location, bool $remote): bool
    {
        if (self::matches($location)) {
            return true;
        }
        $place = trim(preg_replace('/\b(remote|anywhere|worldwide|wfh)\b/i', '', (string) $location), " ,-·|/");

        return $remote && $place === '';
    }
}
