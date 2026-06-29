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
        // 1. Clean up garbage locations in the DB
        $validCities = [];
        foreach (\App\Helpers\LocationHelper::getAllProvinces() as $prov) {
            foreach (\App\Helpers\LocationHelper::getCitiesForProvince($prov) as $city) {
                $validCities[] = $city;
            }
        }
        $validCities[] = 'Remote';
        $validCities[] = 'Indonesia';

        $this->info("Cleaning up invalid/garbage locations (like 'web', 'Web Developer')...");
        
        // Find postings where the location column doesn't match any standard city/province/remote tag
        $invalidJobsCount = JobPosting::whereNotNull('location')
            ->whereNotIn('location', $validCities)
            ->update(['location' => 'Indonesia']);
            
        $this->info("Reset {$invalidJobsCount} postings with invalid location values back to 'Indonesia'.");

        // 2. Perform a local scan to re-classify postings based on title/description
        $this->info("Re-classifying locations using title/description text-scan...");
        $jobsToFix = JobPosting::where(function($q) {
            $q->whereNull('location')
              ->orWhere('location', 'Indonesia');
        })->where('status', 'active')->get();

        $this->info("Scanning " . $jobsToFix->count() . " active jobs locally...");
        $reclassifiedCount = 0;
        foreach ($jobsToFix as $job) {
            $classification = \App\Helpers\LocationHelper::classify('', $job->title, $job->description);
            if ($classification['city'] !== 'Indonesia') {
                $job->update(['location' => $classification['city']]);
                $reclassifiedCount++;
            }
        }
        $this->info("Successfully re-classified {$reclassifiedCount} jobs into specific cities or 'Remote' using description WFH scans.");

        // 3. Queue remaining 'Indonesia' jobs to re-scrape from source details page
        $postings = JobPosting::where('location', 'Indonesia')
            ->where('status', 'active')
            ->limit($this->option('limit'))
            ->get();

        $this->info("Found " . $postings->count() . " active job postings still matching 'Indonesia'. Queueing re-scrape jobs...");

        $delay = 0;
        foreach ($postings as $posting) {
            $this->line("Queueing re-scrape for: {$posting->title} ({$posting->company_name}) - URL: " . basename($posting->raw_url));
            ScrapeJobDetailsJob::dispatch($posting->raw_url, $posting->scraperSource)
                ->delay(now()->addSeconds($delay))
                ->onQueue('extraction');
                
            $delay += 2; // Staggered by 2 seconds to prevent rate-limiting
        }

        $this->info("Successfully queued " . $postings->count() . " extraction jobs with 2-second staggering.");
    }
}
