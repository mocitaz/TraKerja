<?php

namespace App\Jobs;

use App\Models\ScraperSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DiscoverPlatformKeywordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ScraperSource $source;
    public string $keyword;
    public int $pagesToCrawl;

    /**
     * Create a new job instance.
     */
    public function __construct(ScraperSource $source, string $keyword, int $pagesToCrawl = 2)
    {
        $this->source = $source;
        $this->keyword = $keyword;
        $this->pagesToCrawl = $pagesToCrawl;
        $this->queue = 'discovery';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $originalSeedUrl = $this->source->seed_url;
        $discoveredCount = 0;
        $delaySeconds = 0;

        for ($page = 1; $page <= $this->pagesToCrawl; $page++) {
            // Generate dynamic seed URL for pages and keywords
            if (str_contains($this->source->target_domain, 'linkedin.com')) {
                $start = ($page - 1) * 25;
                $this->source->seed_url = "https://id.linkedin.com/jobs/search?keywords=" . urlencode($this->keyword) . "&start=" . $start;
            } elseif (str_contains($this->source->target_domain, 'kalibrr.com')) {
                $this->source->seed_url = "https://www.kalibrr.com/job-board/te/" . urlencode(strtolower($this->keyword)) . "/" . $page;
            } elseif (str_contains($this->source->target_domain, 'jobstreet.co.id')) {
                $this->source->seed_url = "https://www.jobstreet.co.id/id/" . urlencode(strtolower($this->keyword)) . "-jobs?page=" . $page;
            }

            ScrapeJobDetailsJob::logToLiveBuffer(" -> Mencari kata kunci [" . $this->keyword . "] Halaman " . $page);
            echo "  -> Discovery Query: [" . $this->keyword . "] page " . $page . "\n";
            
            $discoveredUrls = $this->source->executeDiscovery();
            $discoveredCount += count($discoveredUrls);

            foreach ($discoveredUrls as $url) {
                // Stagger execution delay (e.g. 4 seconds increment) to prevent anti-bot blocking
                ScrapeJobDetailsJob::dispatch($url, $this->source)
                    ->delay(now()->addSeconds($delaySeconds))
                    ->onQueue('extraction');
                
                $delaySeconds += 4;
            }
        }

        // Restore original seed url structure
        $this->source->seed_url = $originalSeedUrl;
        $this->source->updateLastRun();

        if ($discoveredCount > 0) {
            ScrapeJobDetailsJob::logToLiveBuffer("Discovery Engine: Selesai! Berhasil mengantrekan " . $discoveredCount . " tautan detail lowongan untuk " . $this->source->name, 'success');
            echo "Discovered and queued total of " . $discoveredCount . " job details URLs for " . $this->source->name . ".\n";
        }
    }
}
