<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `taxonomy_sectors` (TraKerja 2.0 Blueprint). */
class TaxonomySector extends Model
{
    protected $table = 'taxonomy_sectors';

    protected $guarded = ['id'];

    protected $casts = [];

    public $timestamps = false;
}
