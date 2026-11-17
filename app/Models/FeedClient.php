<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/** A read-only consumer of the vacancy feed API (TraKerja 2.0, Fase 6). Only the key hash is stored. */
class FeedClient extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean', 'last_used_at' => 'datetime'];

    protected $hidden = ['key_hash'];

    /** @return array{0: FeedClient, 1: string} the client and its plain key (shown once) */
    public static function issue(string $name, int $perMinute = 60): array
    {
        $key = 'tkf_'.Str::random(40);
        $client = self::create(['name' => $name, 'key_hash' => hash('sha256', $key), 'key_prefix' => substr($key, 0, 10), 'per_minute' => $perMinute]);

        return [$client, $key];
    }

    public static function findByKey(?string $key): ?self
    {
        return $key ? self::where('key_hash', hash('sha256', $key))->where('is_active', true)->first() : null;
    }
}
