<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `ai_calls` (TraKerja 2.0 Blueprint). */
class AiCall extends Model
{
    protected $table = 'ai_calls';

    protected $guarded = ['id'];

    protected $casts = ['response' => 'array', 'cost_usd' => 'decimal:7', 'created_at' => 'datetime'];

    public const UPDATED_AT = null;
}
