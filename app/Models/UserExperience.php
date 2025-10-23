<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserExperience extends Model
{
    use HasFactory;

    protected $table = 'user_experiences';

    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'employment_type',
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

    /**
     * Get the user that owns the experience.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted duration (e.g., "Jan 2020 - Dec 2022" or "Jan 2020 - Present")
     */
    public function getDurationAttribute(): string
    {
        $start = $this->start_date->format('M Y');
        $end = $this->is_current ? 'Present' : ($this->end_date ? $this->end_date->format('M Y') : 'Present');
        
        return "{$start} - {$end}";
    }

    /**
     * Get total duration in months
     */
    public function getDurationInMonthsAttribute(): int
    {
        $endDate = $this->is_current ? Carbon::now() : ($this->end_date ?? Carbon::now());
        return $this->start_date->diffInMonths($endDate);
    }

    /**
     * Get formatted duration text (e.g., "2 years 3 months")
     */
    public function getDurationTextAttribute(): string
    {
        $months = $this->durationInMonths;
        $years = floor($months / 12);
        $remainingMonths = $months % 12;
        
        $text = [];
        if ($years > 0) {
            $text[] = $years . ' ' . ($years == 1 ? 'year' : 'years');
        }
        if ($remainingMonths > 0) {
            $text[] = $remainingMonths . ' ' . ($remainingMonths == 1 ? 'month' : 'months');
        }
        
        return implode(' ', $text) ?: '0 months';
    }

    /**
     * Scope for ordering by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                     ->orderBy('start_date', 'desc');
    }
}
