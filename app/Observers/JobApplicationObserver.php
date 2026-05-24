<?php

namespace App\Observers;

use App\Models\JobApplication;
use App\Services\ActivityLogger;

class JobApplicationObserver
{
    /**
     * Handle the JobApplication "created" event.
     */
    public function created(JobApplication $jobApplication): void
    {
        ActivityLogger::log(
            'job_add',
            "User menambahkan lamaran baru di {$jobApplication->company_name} sebagai {$jobApplication->position}",
            'success',
            ['job_id' => $jobApplication->id, 'company' => $jobApplication->company_name, 'position' => $jobApplication->position],
            $jobApplication->user_id
        );

        // Gamification: +10 XP for applying
        if ($jobApplication->user) {
            $jobApplication->user->addXP(10);
        }
    }

    /**
     * Handle the JobApplication "updated" event.
     */
    public function updated(JobApplication $jobApplication): void
    {
        // Only log if something important changed, like status or stage
        if ($jobApplication->isDirty('application_status') || $jobApplication->isDirty('recruitment_stage')) {
            $changes = [];
            if ($jobApplication->isDirty('application_status')) {
                $changes['status'] = [
                    'old' => $jobApplication->getOriginal('application_status'),
                    'new' => $jobApplication->application_status
                ];
            }
            if ($jobApplication->isDirty('recruitment_stage')) {
                $changes['stage'] = [
                    'old' => $jobApplication->getOriginal('recruitment_stage'),
                    'new' => $jobApplication->recruitment_stage
                ];
            }
            
            ActivityLogger::log(
                'job_edit',
                "User memperbarui status/tahap lamaran di {$jobApplication->company_name}",
                'success',
                ['job_id' => $jobApplication->id, 'changes' => $changes],
                $jobApplication->user_id
            );

            // Gamification logic
            if ($jobApplication->user) {
                $xpToAdd = 0;

                // Stage changes
                if ($jobApplication->isDirty('recruitment_stage')) {
                    $newStage = $jobApplication->recruitment_stage;
                    $oldStage = $jobApplication->getOriginal('recruitment_stage');
                    
                    $interviewStages = ['HR - Interview', 'User - Interview', 'Psychotest', 'Assessment Test', 'Presentation Round', 'LGD'];
                    
                    // If moved TO an interview stage from a non-interview stage
                    if (in_array($newStage, $interviewStages) && !in_array($oldStage, $interviewStages)) {
                        $xpToAdd += 50;
                    }
                    
                    // If moved TO Offered stage
                    if ($newStage === 'Offering' && $oldStage !== 'Offering') {
                        $xpToAdd += 100;
                    }
                }

                // Status changes
                if ($jobApplication->isDirty('application_status')) {
                    $newStatus = $jobApplication->application_status;
                    $oldStatus = $jobApplication->getOriginal('application_status');
                    
                    // If moved TO Accepted
                    if ($newStatus === 'Accepted' && $oldStatus !== 'Accepted') {
                        // Max 100 XP if they didn't already get it from Offering
                        $xpToAdd = max($xpToAdd, 100);
                    }
                }

                if ($xpToAdd > 0) {
                    $jobApplication->user->addXP($xpToAdd);
                }
            }
        }

        if ($jobApplication->isDirty('interview_date') && $jobApplication->interview_date) {
            $formattedDate = $jobApplication->interview_date->format('d M Y H:i');
            ActivityLogger::log(
                'interview_schedule',
                "User mengatur jadwal interview dengan {$jobApplication->company_name} pada {$formattedDate}",
                'success',
                [
                    'job_id' => $jobApplication->id, 
                    'company' => $jobApplication->company_name,
                    'date' => $jobApplication->interview_date->toDateTimeString()
                ],
                $jobApplication->user_id
            );
        }
    }

    /**
     * Handle the JobApplication "deleted" event.
     */
    public function deleted(JobApplication $jobApplication): void
    {
        ActivityLogger::log(
            'job_delete',
            "User menghapus lamaran di {$jobApplication->company_name} sebagai {$jobApplication->position}",
            'success',
            ['job_id' => $jobApplication->id, 'company' => $jobApplication->company_name],
            $jobApplication->user_id
        );
    }
}
