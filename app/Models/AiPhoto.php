<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPhoto extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'style_used',
        'background_used',
        'mode',
        'original_photo_name',
        'result_url',
        'photo_analysis',
    ];

    protected $casts = [
        'photo_analysis' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
