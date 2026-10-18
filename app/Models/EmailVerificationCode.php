<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationCode extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['code_hash'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'sent_at' => 'datetime',
            'send_window_started_at' => 'datetime',
            'attempt_window_started_at' => 'datetime',
            'attempts' => 'integer',
            'send_count' => 'integer',
            'attempt_count' => 'integer',
        ];
    }
}
