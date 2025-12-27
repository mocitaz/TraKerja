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

        // Find all jobs that should be archived but aren't yet
        $jobsToArchive = JobApplication::where(function ($query) {
            $query->where('application_status', 'Declined')
                  ->orWhere('recruitment_stage', 'Not Processed');
        })
        ->where('is_archived', false)
        ->get();

        if ($jobsToArchive->isEmpty()) {
            $this->info('No jobs found to archive.');
            return 0;
        }

        $this->info("Found {$jobsToArchive->count()} job(s) to archive.");

        $bar = $this->output->createProgressBar($jobsToArchive->count());
        $bar->start();

        // Use DB update directly to avoid triggering model events
        // This is more efficient and prevents any potential issues with model boot events
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
