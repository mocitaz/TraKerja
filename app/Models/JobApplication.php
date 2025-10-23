<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        });
    }

    protected $casts = [
        'application_date' => 'date',
        'interview_date' => 'datetime',
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


}
