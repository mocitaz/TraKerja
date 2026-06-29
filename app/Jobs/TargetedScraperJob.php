<?php

namespace App\Jobs;

use App\Models\ScraperSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class TargetedScraperJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $target;
    public string $sector;
    public string $major;

    public function __construct(int $target, string $sector, string $major)
    {
        $this->target = $target;
        $this->sector = $sector;
        $this->major = $major;
        $this->queue = 'discovery';
    }

    public function handle()
    {
        $cacheKey = 'targeted_scraping_progress';
        
        $progress = [
            'target' => $this->target,
            'sector' => $this->sector,
            'major' => $this->major,
            'current' => 0,
            'status' => 'RUNNING',
            'started_at' => now()->toDateTimeString(),
        ];
        
        Cache::put($cacheKey, $progress, now()->addHours(6));
        
        ScrapeJobDetailsJob::logToLiveBuffer("Targeted Ingestion: Memulai misi pencarian target " . $this->target . " loker untuk bidang " . $this->sector . " (" . $this->major . ")", 'success');

        // Fetch keywords for the target major
        $map = \App\Helpers\CategoryHelper::getMap();
        $keywords = $map[$this->sector][$this->major] ?? [];
        if (empty($keywords)) {
            $keywords = [$this->major];
        }

        $sources = ScraperSource::where('is_active', true)->get();
        
        // Crawl up to 2 pages if target count is large
        $pages = $this->target > 25 ? 2 : 1;

        foreach ($sources as $source) {
            $originalSeedUrl = $source->seed_url;

            foreach ($keywords as $keyword) {
                for ($page = 1; $page <= $pages; $page++) {
                    // Check if target is already reached or cancelled
                    $currentProgress = Cache::get($cacheKey);
                    if (!$currentProgress || $currentProgress['current'] >= $this->target || $currentProgress['status'] !== 'RUNNING') {
                        break 3;
                    }

                    ScrapeJobDetailsJob::logToLiveBuffer("Targeted Ingestion: Menelusuri [" . $keyword . "] halaman " . $page . " di " . $source->name);

                    // Dynamically set search URL
                    if (str_contains($source->target_domain, 'linkedin.com')) {
                        $start = ($page - 1) * 25;
                        $source->seed_url = "https://id.linkedin.com/jobs/search?keywords=" . urlencode($keyword) . "&start=" . $start;
                    } elseif (str_contains($source->target_domain, 'kalibrr.com')) {
                        $source->seed_url = "https://www.kalibrr.com/job-board/te/" . urlencode(strtolower($keyword)) . "/" . $page;
                    } elseif (str_contains($source->target_domain, 'jobstreet.co.id')) {
                        $source->seed_url = "https://www.jobstreet.co.id/id/" . urlencode(strtolower($keyword)) . "-jobs?page=" . $page;
                    }

                    $discoveredUrls = $source->executeDiscovery();
                    
                    foreach ($discoveredUrls as $url) {
                        $currentProgress = Cache::get($cacheKey);
                        if (!$currentProgress || $currentProgress['current'] >= $this->target) {
                            break 3;
                        }

                        // Dispatch details scraper job
                        ScrapeJobDetailsJob::dispatch($url, $source)->onQueue('extraction');
                    }
                }
            }

            // Restore source state
            $source->seed_url = $originalSeedUrl;
            $source->save();
        }
    }
}
