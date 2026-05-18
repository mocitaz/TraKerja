<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailBlastLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_type',
        'target_audience',
        'total_target',
        'success_count',
        'failed_count',
        'failed_details',
    ];

    protected $casts = [
        'failed_details' => 'array',
    ];
}
