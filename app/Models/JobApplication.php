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
            
            // Auto-archive logic: Archive if status is Declined or recruitment_stage is Not Processed
            $shouldBeArchived = $jobApplication->application_status === 'Declined' 
                || $jobApplication->recruitment_stage === 'Not Processed';
            
            // Get original values before update
            $originalStatus = $jobApplication->getOriginal('application_status');
            $originalStage = $jobApplication->getOriginal('recruitment_stage');
            $wasArchived = $jobApplication->getOriginal('is_archived') ?? false;
            
            // Check if original status was also archived
            $wasOriginallyArchived = ($originalStatus === 'Declined' || $originalStage === 'Not Processed');
            
            if ($shouldBeArchived && !$wasArchived) {
                // Archive the job automatically
                $jobApplication->is_archived = true;
                $jobApplication->archived_at = now();
            } elseif (!$shouldBeArchived && $wasArchived) {
                // Un-archive if status changed from archived to non-archived
                // This allows user to change status from Declined/Not Processed to other status
                $jobApplication->is_archived = false;
                $jobApplication->archived_at = null;
            }
        });
        
        static::created(function ($jobApplication) {
            // Auto-archive on creation if status is Declined or Not Processed
            $shouldBeArchived = $jobApplication->application_status === 'Declined' 
                || $jobApplication->recruitment_stage === 'Not Processed';
            
            if ($shouldBeArchived) {
                $jobApplication->update([
                    'is_archived' => true,
                    'archived_at' => now(),
                ]);
            }
        });
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
}
