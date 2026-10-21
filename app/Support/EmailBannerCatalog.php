<?php

namespace App\Support;

class EmailBannerCatalog
{
    public static function all(): array
    {
        return [
            'next-step' => ['label' => 'Next step', 'path' => 'images/email-banners/next-step-v2.jpg'],
            'fresh-start' => ['label' => 'Fresh start', 'path' => 'images/email-banners/fresh-start-v2.jpg'],
            'keep-going' => ['label' => 'Keep going', 'path' => 'images/email-banners/keep-going-v2.jpg'],
            'here-to-help' => ['label' => 'Here to help', 'path' => 'images/email-banners/here-to-help-v2.jpg'],
            'new-perspective' => ['label' => 'New perspective', 'path' => 'images/email-banners/new-perspective-v2.jpg'],
        ];
    }

    public static function find(?string $id): ?array
    {
        return self::all()[$id ?? ''] ?? null;
    }

    public static function rules(): array
    {
        return ['nullable', 'string', \Illuminate\Validation\Rule::in(array_keys(self::all()))];
    }
}
