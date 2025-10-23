<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProject extends Model
{
    use HasFactory;

    protected $table = 'user_projects';

    protected $fillable = [
        'user_id',
        'project_name',
        'role',
        'start_date',
        'end_date',
        'is_ongoing',
        'description',
        'project_url',
        'repository_url',
        'technologies',
        'display_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_ongoing' => 'boolean',
        'technologies' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                     ->orderBy('start_date', 'desc');
    }
}
