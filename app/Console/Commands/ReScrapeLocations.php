<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use App\Jobs\ScrapeJobDetailsJob;
use Illuminate\Console\Command;

class ReScrapeLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:re-scrape-locations {--limit=300 : Limit the number of jobs to re-scrape}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-scrape locations for existing job postings by dispatching extraction jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $postings = JobPosting::where('location', 'Indonesia')
            ->where('status', 'active')
            ->limit($this->option('limit'))
            ->get();

        $this->info("Found " . $postings->count() . " active job postings with location 'Indonesia'.");

        $delay = 0;
        foreach ($postings as $posting) {
            $this->line("Queueing re-scrape for: {$posting->title} ({$posting->company_name}) - URL: " . basename($posting->raw_url));
            
            ScrapeJobDetailsJob::dispatch($posting->raw_url, $posting->scraperSource)
                ->delay(now()->addSeconds($delay))
                ->onQueue('extraction');
                
            $delay += 2; // Staggered by 2 seconds to run smoothly
        }

        $this->info("Successfully queued " . $postings->count() . " extraction jobs with 2-second staggering.");
    }
}
