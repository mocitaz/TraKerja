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

    public function __construct()
    {
        $this->queue = 'discovery';
    }

    public function handle()
    {
        $sources = ScraperSource::where('is_active', true)->get();
        echo "Processing " . $sources->count() . " active scraper sources...\n";

        foreach ($sources as $source) {
            echo "Running discovery for: " . $source->name . " (" . $source->target_domain . ")...\n";
            $discoveredUrls = $source->executeDiscovery();
            echo "Discovered " . count($discoveredUrls) . " URLs for " . $source->name . "\n";

            foreach ($discoveredUrls as $url) {
                echo "-> Dispatching ScrapeJobDetailsJob for URL: " . $url . "\n";
                ScrapeJobDetailsJob::dispatch($url, $source)->onQueue('extraction');
            }
            
            $source->updateLastRun();
        }
    }
}
