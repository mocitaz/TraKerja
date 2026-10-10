<?php

namespace App\Support;

class AvatarCatalog
{
    public static function all(): array
    {
        return [
            'amber' => 'Amber · golden bob', 'copper' => 'Copper · orange cap',
            'indigo' => 'Indigo · dark hair and glasses', 'rose' => 'Rose · pink shirt',
            'sage' => 'Sage · curly hair', 'violet' => 'Violet · long hair',
            'midnight' => 'Midnight · beard', 'gold' => 'Gold · round glasses',
            'coral' => 'Coral · tied hair', 'sky' => 'Sky · side part',
            'peach' => 'Peach · short waves', 'lilac' => 'Lilac · twin buns',
        ];
    }

    public static function resolve(?string $id, int $userId): string
    {
        $ids = array_keys(self::all());

        return isset(self::all()[$id ?? '']) ? $id : $ids[max(0, $userId - 1) % count($ids)];
    }

    public static function url(string $id): string
    {
        return asset('images/avatars/'.(isset(self::all()[$id]) ? $id : 'amber').'.svg');
    }
}
