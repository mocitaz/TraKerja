<?php

namespace App\Support;

final class AppLocale
{
    public const OPTIONS = ['en' => 'English', 'id' => 'Bahasa Indonesia'];

    public static function valid(mixed $locale): bool
    {
        return is_string($locale) && array_key_exists($locale, self::OPTIONS);
    }
}
