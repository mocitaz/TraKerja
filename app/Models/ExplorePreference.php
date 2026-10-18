<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExplorePreference extends Model
{
    protected $fillable = ['user_id', 'filters'];

    protected $casts = ['filters' => 'array'];
}
