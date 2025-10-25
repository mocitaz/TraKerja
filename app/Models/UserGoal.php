<?php

namespace App\Models;

use App\Mail\GoalAchievedMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class UserGoal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'target_date',
        'start_date',
        'end_date',
        'target_applied_weekly',
        'target_followup_weekly',
        'is_achieved'
    ];

    protected $casts = [
        'target_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_achieved' => 'boolean',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Listen for updates to send email when goal is achieved
        static::updating(function ($goal) {
            // Check if is_achieved changed from false to true
            if ($goal->isDirty('is_achieved') && $goal->is_achieved && !$goal->getOriginal('is_achieved')) {
                try {
                    Mail::to($goal->user->email)->send(new GoalAchievedMail($goal));
                } catch (\Exception $e) {
                    \Log::error('Failed to send goal achieved email: ' . $e->getMessage());
                }
            }
        });
    }

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
