<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use App\Services\DeadLinkDetector;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ValidateDeadLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:validate-dead-links {--limit=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform lightweight dead-link footprint checking on active job postings';

    /**
     * Execute the console command.
     */
    public function handle(DeadLinkDetector $detector)
    {
        $limit = (int) $this->option('limit');
        $this->info("Starting dead-link validation scan for up to {$limit} postings...");

        // Query active or flagged postings, prioritized by reported count or oldest validation timestamp
        $postings = JobPosting::whereIn('status', ['active', 'reported_dead'])
            ->orderByDesc('report_dead_count')
            ->orderBy('last_validated_at')
            ->limit($limit)
            ->get();

        $closedCount = 0;
        $activeCount = 0;
        $escalatedCount = 0;

        foreach ($postings as $posting) {
            $this->comment("Checking: [{$posting->company_name}] {$posting->title}...");
            
            $result = $detector->validate($posting);

            if ($result === 'closed') {
                $posting->update([
                    'status' => 'closed',
                    'last_validated_at' => now(),
                ]);
                $closedCount++;
                $this->error("➔ CLOSED/DEAD!");
            } elseif ($result === 'active') {
                $posting->update([
                    'status' => 'active',
                    'report_dead_count' => 0, // Reset report count on validation success
                    'last_validated_at' => now(),
                ]);
                $activeCount++;
                $this->info("➔ ACTIVE/LIVE");
            } else {
                // Escalated cases (e.g. 403 Forbidden)
                $posting->update([
                    'last_validated_at' => now(),
                ]);
                $escalatedCount++;
                $this->warn("➔ ESCALATED (anti-bot triggered or timeout)");
            }
        }

        $this->info("\nScan completed successfully.");
        $this->info("Active: {$activeCount} | Closed: {$closedCount} | Escalated: {$escalatedCount}");
        
        return Command::SUCCESS;
    }
}
