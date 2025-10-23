<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOrganization extends Model
{
    use HasFactory;

    protected $table = 'user_organizations';

    protected $fillable = [
        'user_id',
        'organization_name',
        'role',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'display_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
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
