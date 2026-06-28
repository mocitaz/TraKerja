<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;

class ArchiveOldDeclinedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:archive-old-declined';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive old job applications with Declined status or Not Processed stage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to archive old declined and not processed jobs...');

        // Find users who have enabled auto-archive
        $usersWithAutoArchive = \App\Models\User::where('auto_archive_rejected', true)->pluck('id');

        if ($usersWithAutoArchive->isEmpty()) {
            $this->info('No users have enabled auto-archive.');
            return 0;
        }

        // Find all jobs that should be archived (Declined/Rejected status or Not Processed stage)
        // and belong to opted-in users and are at least 14 days old
        $jobsToArchive = JobApplication::whereIn('user_id', $usersWithAutoArchive)
            ->where(function ($query) {
                $query->whereIn('application_status', ['Declined', 'Rejected'])
                      ->orWhere('recruitment_stage', 'Not Processed');
            })
            ->where('is_archived', false)
            ->where('updated_at', '<=', now()->subDays(14))
            ->get();

        if ($jobsToArchive->isEmpty()) {
            $this->info('No jobs found to archive.');
            return 0;
        }

        $this->info("Found {$jobsToArchive->count()} job(s) to archive.");

        $bar = $this->output->createProgressBar($jobsToArchive->count());
        $bar->start();

        // Use DB update directly to avoid triggering model events
        $updated = DB::table('job_applications')
            ->whereIn('id', $jobsToArchive->pluck('id'))
            ->update([
                'is_archived' => true,
                'archived_at' => now(),
            ]);

        $bar->finish();
        $this->newLine();
        $this->info("Successfully archived {$updated} job(s).");

        return 0;
    }
}
