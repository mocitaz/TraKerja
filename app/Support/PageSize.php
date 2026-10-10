<?php

namespace App\Support;

class PageSize
{
    public static function resolve(mixed $value, array $allowed, int $default): int
    {
        if (!is_int($value) && !(is_string($value) && ctype_digit($value))) return $default;
        return in_array((int) $value, $allowed, true) ? (int) $value : $default;
    }
}
