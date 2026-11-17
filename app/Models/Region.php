<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `regions` (TraKerja 2.0 Blueprint). */
class Region extends Model
{
    protected $table = 'regions';

    protected $guarded = ['id'];

    protected $casts = ['aliases' => 'array'];

    public $timestamps = false;

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }
}
