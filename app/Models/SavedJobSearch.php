<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedJobSearch extends Model
{
    protected $fillable = ['user_id', 'name', 'filters', 'fingerprint'];

    protected $casts = ['filters' => 'array'];
}
