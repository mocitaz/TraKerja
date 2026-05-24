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
