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

    public $queue = 'discovery';

    public function handle()
    {
        $sources = ScraperSource::where('is_active', true)->get();

        foreach ($sources as $source) {
            $discoveredUrls = $source->executeDiscovery();

            foreach ($discoveredUrls as $url) {
                ScrapeJobDetailsJob::dispatch($url, $source)->onQueue('extraction');
            }
            
            $source->updateLastRun();
        }
    }
}
