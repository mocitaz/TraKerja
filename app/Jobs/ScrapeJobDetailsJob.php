<?php

namespace App\Jobs;

use App\Models\ScraperLogsAndMetric;
use App\Models\ScraperSource;
use App\Services\ScraperPageFetcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeJobDetailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public string $url;

    public ScraperSource $source;

    public function __construct(string $url, ScraperSource $source)
    {
        $this->url = $url;
        $this->source = $source;
        $this->queue = 'extraction';
    }

    public function handle()
    {
        $html = null;
        $title = null;
        $company = null;
        $bodyContent = null;
        $location = null;
        $success = false;

        try {
            $page = app(ScraperPageFetcher::class)->fetch($this->url);
            $html = $page['html'];
            $title = $page['title'];
            $success = true;
            self::logToLiveBuffer('Halaman lowongan berhasil diambil.', 'success');
        } catch (\Exception $e) {
            self::logToLiveBuffer('Gagal mengambil halaman lowongan.', 'warning');
            Log::warning("Job page fetch failed for source {$this->source->id}.");
        }

        // ==========================================
        // PARSING & PENGEKSTRAKAN DATA (Symfony DomCrawler & CSS Selectors)
        // ==========================================
        if ($success && $html) {
            try {
                $crawler = new Crawler($html);

                $selectors = $this->source->selectors_config;
                $titleSelector = $selectors['title'] ?? 'h1';
                $companySelector = $selectors['company'] ?? '';
                $bodySelector = $selectors['body'] ?? '';
                $locationSelector = $selectors['location'] ?? '';

                // Extract title
                if ($crawler->filter($titleSelector)->count() > 0) {
                    $title = trim($crawler->filter($titleSelector)->first()->text());
                }

                // Extract company
                if ($companySelector && $crawler->filter($companySelector)->count() > 0) {
                    $company = trim($crawler->filter($companySelector)->first()->text());
                }

                // Extract body / description
                if ($bodySelector && $crawler->filter($bodySelector)->count() > 0) {
                    $bodyContent = trim($crawler->filter($bodySelector)->first()->text());
                }

                // Extract location
                if ($locationSelector && $crawler->filter($locationSelector)->count() > 0) {
                    $location = trim($crawler->filter($locationSelector)->first()->text());
                }

                self::logToLiveBuffer('CSS Crawler berhasil mengekstrak data detail.', 'success');
            } catch (\Exception $e) {
                self::logToLiveBuffer('Gagal mem-parsing HTML dengan CSS Selector: '.$e->getMessage(), 'warning');
            }
        }

        // ==========================================
        // TAHAP 3: Regex Fallback (Terakhir)
        // ==========================================
        if (! $title || ! $company) {
            self::logToLiveBuffer('Tahap 3: Menjalankan Regex Parser Fallback');

            if ($html) {
                preg_match('/<title>(.*?)<\/title>/i', $html, $titleMatch);
                $pageTitle = trim($titleMatch[1] ?? 'Job Opportunity');

                // Try to extract clean title and company name from page titles
                if (str_contains($this->source->target_domain, 'linkedin.com')) {
                    if (preg_match('/^(.*?)\s+membuka\s+lowongan\s+(.*?)(?:\s+di\s+.*?)?(\s*\|.*)?$/i', $pageTitle, $titleParts)) {
                        $company = trim($titleParts[1]);
                        $title = trim($titleParts[2]);
                    } elseif (preg_match('/^(.*?)\s+hiring\s+(.*?)(?:\s+in\s+.*?)?(\s*\|.*)?$/i', $pageTitle, $titleParts)) {
                        $company = trim($titleParts[1]);
                        $title = trim($titleParts[2]);
                    }
                } elseif (str_contains($this->source->target_domain, 'kalibrr.com')) {
                    if (preg_match('/^(.*?)\s+at\s+(.*?)(?:\s*\|.*)?$/i', $pageTitle, $titleParts)) {
                        $title = trim($titleParts[1]);
                        $company = trim($titleParts[2]);
                    }
                }

                if (! $title) {
                    $title = preg_replace('/ - Jobs.*| \| LinkedIn.*| Lowongan.*| Karir.*| \| Kalibrr.*/i', '', $pageTitle);
                }
            }
        }

        // Clean company and title
        $title = $title ?: 'Job Opportunity';
        $company = $company ?: ($this->source->name ?: 'Target Portal');

        $title = preg_replace('/ - Jobs.*| \| LinkedIn.*| Lowongan.*| Karir.*| \| Kalibrr.*/i', '', $title);
        $company = preg_replace('/ - Jobs.*| \| LinkedIn.*| Lowongan.*| Karir.*| \| Kalibrr.*/i', '', $company);

        // ==========================================
        // TAHAP 4: Finalize & Simpan Metrics
        // ==========================================
        if ($success) {
            self::logToLiveBuffer("Menyimpan hasil ke database untuk lowongan: {$title}", 'success');

            $payload = [
                'title' => $title,
                'company' => $company,
                'description' => $bodyContent ?: 'Job description content. Please refer to the source portal link to view details and apply.',
                'location' => $location,
                'isClosed' => str_contains(strtolower($html ?? ''), 'no longer accepting applications') || str_contains(strtolower($html ?? ''), 'sudah ditutup') || str_contains(strtolower($html ?? ''), 'expired'),
                'success' => true,
            ];

            StoreAndTagJob::dispatchSync($payload, $this->url, $this->source->id);

            // Record success metric
            $this->recordMetric(true);
        } else {
            self::logToLiveBuffer('Tahap 4 Gagal: Gagal mengekstrak data apa pun untuk URL '.basename($this->url), 'error');
            Log::error("ScrapeJobDetailsJob completely failed for URL: {$this->url}");

            $this->recordMetric(false);
            throw new \Exception('Failed to scrape details for URL: '.$this->url);
        }
    }

    /**
     * Helper to write logs to cache memory live buffer.
     */
    public static function logToLiveBuffer(string $message, string $level = 'info'): void
    {
        $logs = Cache::get('scraper_live_logs', []);
        $logs[] = [
            'timestamp' => now()->timezone('Asia/Jakarta')->format('H:i:s'),
            'level' => strtoupper($level),
            'message' => $message,
        ];
        if (count($logs) > 30) {
            array_shift($logs);
        }
        Cache::put('scraper_live_logs', $logs, now()->addHours(12));
    }

    /**
     * Record metrics to DB.
     */
    protected function recordMetric(bool $isSuccess): void
    {
        try {
            ScraperLogsAndMetric::create([
                'scraper_source_id' => $this->source->id,
                'session_started_at' => now(),
                'session_ended_at' => now(),
                'discovered_links_count' => 1,
                'successfully_scraped_count' => $isSuccess ? 1 : 0,
                'failed_scraped_count' => $isSuccess ? 0 : 1,
                'proxy_ip_used' => '127.0.0.1',
                'bandwidth_bytes_consumed' => 1024,
                'estimated_cost_usd' => 0.0005,
                'status' => $isSuccess ? 'success' : 'failed',
                'error_summary' => $isSuccess ? null : 'Failed to scrape details',
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to record metric: '.$e->getMessage());
        }
    }
}
