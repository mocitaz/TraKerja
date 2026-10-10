<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataExport extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['filters' => 'array', 'expires_at' => 'datetime'];
}
