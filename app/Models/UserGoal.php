<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserGoal extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'target_applied_weekly',
        'target_followup_weekly',
        'is_achieved'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_achieved' => 'boolean',
    ];

    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the current week is within the goal period
     */
    public function isCurrentWeek(): bool
    {
        $now = Carbon::now();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Get the current week's goal for a user
     */
    public static function getCurrentWeekGoal(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();
    }

    /**
     * Get weekly goal history for a user
     */
    public static function getWeeklyHistory(int $userId, int $weeks = 8)
    {
        return self::where('user_id', $userId)
            ->orderBy('start_date', 'desc')
            ->limit($weeks)
            ->get();
    }
}
