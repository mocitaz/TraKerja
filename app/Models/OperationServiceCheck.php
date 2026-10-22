<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationServiceCheck extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected $casts = ['created_at' => 'datetime'];

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
