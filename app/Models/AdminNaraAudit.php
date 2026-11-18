<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One tool call Nara Admin ran for an admin. */
class AdminNaraAudit extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'tool', 'args', 'ok', 'rows'];

    protected $casts = ['args' => 'array', 'ok' => 'boolean'];
}
