<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBookmark extends Model
{
    protected $fillable = ['user_id', 'job_posting_id'];
}
