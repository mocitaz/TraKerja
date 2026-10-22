<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailBlastTemplate extends Model
{
    protected $fillable = ['name', 'language', 'subject', 'content', 'button_text', 'button_url', 'source_prompt', 'reviewed_by', 'reviewed_at'];
    protected $casts = ['reviewed_at' => 'datetime'];
}
