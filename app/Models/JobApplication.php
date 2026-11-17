<?php

namespace App\Models;

use App\Notifications\ActivityNotice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Mail\InterviewReminderMail;
use App\Support\JobApplicationStatus;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vacancy_id',
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
        'interview_platform',
        'interview_location',
        'notes',
        'is_pinned',
        'is_archived',
        'archived_at',
    ];

    protected $attributes = [
        'platform_link' => '',
        'notes' => '',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($jobApplication) {
            if ($jobApplication->interview_date === '') {
                $jobApplication->interview_date = null;
            }
            if ($jobApplication->interview_type === '') {
                $jobApplication->interview_type = null;
            }
            if ($jobApplication->interview_location === '') {
                $jobApplication->interview_location = null;
            }
            if ($jobApplication->interview_notes === '') {
                $jobApplication->interview_notes = null;
            }
            if ($jobApplication->platform_link === null) {
                $jobApplication->platform_link = '';
            }

            $normalized = JobApplicationStatus::normalize(
                $jobApplication->application_status ?? 'On Process',
                $jobApplication->recruitment_stage ?? 'Applied'
            );
            if (!in_array($normalized['application_status'], JobApplicationStatus::OPTIONS, true)) {
                throw new \InvalidArgumentException('Invalid application status');
            }
            $jobApplication->fill($normalized);
            $jobApplication->status = $normalized['application_status'];
        });

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

            $shouldBeArchived = in_array($jobApplication->application_status, ['Rejected'])
                || $jobApplication->recruitment_stage === 'Not Processed';

            if (!$shouldBeArchived && $wasArchived) {
                $jobApplication->is_archived = false;
                $jobApplication->archived_at = null;
            }
        });
    }

    /** In-app activity notices for the owner (TraKerja 2.0, Fase 6). */
    protected static function booted(): void
    {
        $label = fn (self $app) => ['position' => (string) $app->position, 'company' => (string) $app->company_name];
        $url = fn (self $app) => route('jobs.show', $app->id);

        static::created(function (self $app) use ($label, $url) {
            ActivityNotice::toOwner($app->user_id, new ActivityNotice('application_created', 'ph-briefcase', 'plum',
                'Application recorded: :position', $label($app), 'At :company. Keep it updated as you move through selection.', $label($app), $url($app)));
        });

        static::updated(function (self $app) use ($label, $url) {
            if ($app->wasChanged('application_status') && $app->application_status === 'Accepted') {
                $notice = new ActivityNotice('application_accepted', 'ph-confetti', 'green', 'Congratulations! You were accepted', [], ':position at :company', $label($app), $url($app));
            } elseif ($app->wasChanged('application_status') && $app->application_status === 'Rejected') {
                $notice = new ActivityNotice('application_rejected', 'ph-x-circle', 'grey', 'Application marked as not successful', [], ':position at :company. Every step counts.', $label($app), $url($app));
            } elseif ($app->wasChanged('interview_date') && $app->interview_date) {
                $notice = new ActivityNotice('interview_scheduled', 'ph-calendar-check', 'blue', 'Selection scheduled: :position', $label($app),
                    ':date WIB', ['date' => $app->interview_date->copy()->timezone('Asia/Jakarta')->translatedFormat('d M Y · H:i')], route('interviews'));
            } elseif ($app->wasChanged('recruitment_stage')) {
                $notice = new ActivityNotice('stage_changed', 'ph-arrow-circle-right', 'plum', 'New stage: :stage', ['stage' => (string) $app->recruitment_stage], ':position at :company', $label($app), $url($app));
            } else {
                return;
            }
            ActivityNotice::toOwner($app->user_id, $notice);
        });

        static::deleted(function (self $app) use ($label) {
            ActivityNotice::toOwner($app->user_id, new ActivityNotice('application_deleted', 'ph-trash', 'red',
                'Application deleted: :position', $label($app), 'At :company. It is no longer in your tracker.', $label($app), route('tracker')));
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
                $query->whereIn('application_status', ['Rejected'])
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
        if ($user && $user->wantsEmailNotification('interview')) {
            \Mail::to($user)->send(new InterviewReminderMail($this));
            return true;
        }
        return false;
    }
    /** Eligible for a follow-up draft after 14 days since applying. */
    public function isGhosted(): bool
    {
        return !$this->is_archived
            && $this->application_status === 'On Process'
            && !in_array($this->recruitment_stage, ['Not Processed', 'Offering'], true)
            && $this->application_date
            && $this->application_date->lte(today()->subDays(14));
    }
}
