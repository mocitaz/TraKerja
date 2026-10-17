<?php

namespace App\Support;

final class FrontendTranslations
{
    public static function messages(string $locale): array
    {
        $path = lang_path($locale.'.json');
        $keysPath = lang_path('frontend.json');
        if (!is_file($path) || !is_file($keysPath)) return [];
        $catalog = json_decode(file_get_contents($path), true) ?: [];
        $keys = json_decode(file_get_contents($keysPath), true) ?: [];
        return array_filter(array_intersect_key($catalog, array_fill_keys($keys, true)), fn ($text, $key) => $text !== $key, ARRAY_FILTER_USE_BOTH);
    }
}
