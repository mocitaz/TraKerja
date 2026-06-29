<?php

namespace App\Jobs;

use App\Models\ScraperSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DiscoverLinksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public bool $force;

    public function __construct(bool $force = false)
    {
        $this->force = $force;
        $this->queue = 'discovery';
    }

    public function handle()
    {
        $sources = ScraperSource::where('is_active', true)->get();

        if (!$this->force) {
            $sources = $sources->filter(fn($source) => $source->isDue());
            if ($sources->isEmpty()) {
                echo "No active scraper sources are due for crawling.\n";
                return;
            }
        }

        echo "Processing " . $sources->count() . " active scraper sources...\n";

        // Multi-keyword and multi-page configurations to scale to 1000+ postings safely
        $keywords = ['Laravel', 'PHP', 'React', 'Vue', 'Golang', 'DevOps', 'Mobile', 'QA', 'Python'];
        $pagesToCrawl = 3;
        $delaySeconds = 0;

        foreach ($sources as $source) {
            echo "Running discovery for: " . $source->name . " (" . $source->target_domain . ")...\n";
            $originalSeedUrl = $source->seed_url;
            $discoveredCount = 0;

            foreach ($keywords as $keyword) {
                for ($page = 1; $page <= $pagesToCrawl; $page++) {
                    
                    // Generate dynamic seed URL for pages and keywords
                    if (str_contains($source->target_domain, 'linkedin.com')) {
                        $start = ($page - 1) * 25;
                        $source->seed_url = "https://id.linkedin.com/jobs/search?keywords=" . urlencode($keyword) . "&start=" . $start;
                    } elseif (str_contains($source->target_domain, 'kalibrr.com')) {
                        $source->seed_url = "https://www.kalibrr.com/job-board/te/" . urlencode(strtolower($keyword)) . "/" . $page;
                    } elseif (str_contains($source->target_domain, 'jobstreet.co.id')) {
                        $source->seed_url = "https://www.jobstreet.co.id/id/" . urlencode(strtolower($keyword)) . "-jobs?page=" . $page;
                    }

                    echo "  -> Discovery Query: [" . $keyword . "] page " . $page . "\n";
                    $discoveredUrls = $source->executeDiscovery();
                    $discoveredCount += count($discoveredUrls);

                    foreach ($discoveredUrls as $url) {
                        // Stagger execution delay (e.g. 4 seconds increment) to prevent anti-bot blocking
                        ScrapeJobDetailsJob::dispatch($url, $source)
                            ->delay(now()->addSeconds($delaySeconds))
                            ->onQueue('extraction');
                        
                        $delaySeconds += 4;
                    }
                }
            }

            // Restore original seed url structure
            $source->seed_url = $originalSeedUrl;
            $source->updateLastRun();

            echo "Discovered and queued total of " . $discoveredCount . " job details URLs for " . $source->name . ".\n";
        }
    }
}
