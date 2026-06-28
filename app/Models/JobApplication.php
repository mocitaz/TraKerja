<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Mail\InterviewReminderMail;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'location',
        'platform',
        'status',
        'application_status',
        'recruitment_stage',
        'career_level',
        'platform_link',
        'application_date',
        'interview_date',
        'interview_notes',
        'interview_type',
        'interview_location',
        'notes',
        'is_pinned',
        'is_archived',
        'archived_at',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($jobApplication) {
            // Ensure location is not empty
            if (empty($jobApplication->location)) {
                throw new \Exception('Location is required');
            }
        });
        
        static::updating(function ($jobApplication) {
            // Ensure location is not empty
            if (empty($jobApplication->location)) {
                throw new \Exception('Location is required');
            }
            
            // Un-archive if status changes to a non-archived status
            $originalStatus = $jobApplication->getOriginal('application_status');
            $originalStage = $jobApplication->getOriginal('recruitment_stage');
            $wasArchived = $jobApplication->getOriginal('is_archived') ?? false;
            
            $shouldBeArchived = in_array($jobApplication->application_status, ['Declined', 'Rejected']) 
                || $jobApplication->recruitment_stage === 'Not Processed';
            
            if (!$shouldBeArchived && $wasArchived) {
                $jobApplication->is_archived = false;
                $jobApplication->archived_at = null;
            }
        });
    }

    /**
     * Auto-archive user's rejected or declined job applications that are older than 14 days,
     * if the user has enabled the auto-archive preference.
     *
     * @param int $userId
     * @return int Number of archived jobs
     */
    public static function autoArchiveUserJobs($userId)
    {
        $user = \App\Models\User::find($userId);
        if (!$user || !$user->auto_archive_rejected) {
            return 0;
        }

        return self::where('user_id', $userId)
            ->where('is_archived', false)
            ->where(function ($query) {
                $query->whereIn('application_status', ['Declined', 'Rejected'])
                      ->orWhere('recruitment_stage', 'Not Processed');
            })
            ->where('updated_at', '<=', now()->subDays(14))
            ->update([
                'is_archived' => true,
                'archived_at' => now(),
            ]);
    }

    protected $casts = [
        'application_date' => 'date',
        'interview_date' => 'datetime',
        'is_pinned' => 'boolean',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
    ];
    
    /**
     * Check if this application has an interview scheduled
     */
    public function hasInterview(): bool
    {
        return !is_null($this->interview_date);
    }
    
    /**
     * Check if interview is upcoming (in the future)
     */
    public function hasUpcomingInterview(): bool
    {
        return $this->hasInterview() && $this->interview_date->isFuture();
    }
    
    /**
     * Check if interview was in the past
     */
    public function hasPastInterview(): bool
    {
        return $this->hasInterview() && $this->interview_date->isPast();
    }
    
    /**
     * Get formatted interview date
     */
    public function getFormattedInterviewDateAttribute(): ?string
    {
        return $this->interview_date ? $this->interview_date->format('d M Y, H:i') : null;
    }

    /**
     * Get the user that owns the job application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Send interview reminder email (premium restriction)
     */
    public function sendInterviewReminder(): bool
    {
        $user = $this->user;
        if ($user && $user->canAccessEmailNotifications()) {
            \Mail::to($user->email)->send(new InterviewReminderMail($this));
            return true;
        }
        return false;
    }
    /**
     * Check if application is considered "ghosted" (no update for > 14 days in Applied or Interviewing status)
     */
    public function isGhosted(): bool
    {
        return in_array($this->application_status, ['Applied', 'On Process', 'Interview']) 
            && $this->application_date && $this->application_date->diffInDays(now()) >= 14;
    }
}
