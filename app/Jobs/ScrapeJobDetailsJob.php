<?php

namespace App\Jobs;

use App\Models\ScraperSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $proxy = [
            'host' => '127.0.0.1',
            'port' => '8080',
            'username' => 'user',
            'password' => 'pass'
        ];

        $microserviceUrl = config('scraper.microservice_url');
        
        if ($microserviceUrl) {
            try {
                $response = Http::timeout(45)
                    ->post($microserviceUrl . '/scrape', [
                        'url' => $this->url,
                        'proxy' => $proxy,
                        'selectors' => $this->source->selectors_config
                    ]);

                if ($response->successful() && $response->json('success')) {
                    StoreAndTagJob::dispatchSync($response->json(), $this->url, $this->source->id);
                    return;
                }
            } catch (\Exception $e) {
                Log::warning("External microservice failed for {$this->url}: " . $e->getMessage() . ". Falling back to lightweight HTTP GET parser.");
            }
        }

        // Lightweight HTTP GET parser fallback
        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
                ])
                ->get($this->url);

            if ($response->successful()) {
                $html = $response->body();
                
                // Extract title
                preg_match('/<title>(.*?)<\/title>/i', $html, $titleMatch);
                $title = trim($titleMatch[1] ?? 'Job Opportunity');
                $company = $this->source->name ?: 'Target Portal';

                // Try to extract clean title and company name from page titles
                if (str_contains($this->source->target_domain, 'linkedin.com')) {
                    // Indonesian: "PT Berca Hardayaperkasa membuka lowongan Pengembang Fullstack di Jakarta | LinkedIn"
                    if (preg_match('/^(.*?)\s+membuka\s+lowongan\s+(.*?)(?:\s+di\s+.*?)?(\s*\|.*)?$/i', $title, $titleParts)) {
                        $company = trim($titleParts[1]);
                        $title = trim($titleParts[2]);
                    }
                    // English: "Nordia Digital hiring Junior Web Developer in Jakarta, ID | LinkedIn"
                    elseif (preg_match('/^(.*?)\s+hiring\s+(.*?)(?:\s+in\s+.*?)?(\s*\|.*)?$/i', $title, $titleParts)) {
                        $company = trim($titleParts[1]);
                        $title = trim($titleParts[2]);
                    }
                } elseif (str_contains($this->source->target_domain, 'kalibrr.com')) {
                    // Kalibrr: "Junior Laravel Developer at KoinWorks | Kalibrr"
                    if (preg_match('/^(.*?)\s+at\s+(.*?)(?:\s*\|.*)?$/i', $title, $titleParts)) {
                        $title = trim($titleParts[1]);
                        $company = trim($titleParts[2]);
                    }
                }

                // Clean remaining suffix patterns
                $title = preg_replace('/ - Jobs.*| \| LinkedIn.*| Lowongan.*| Karir.*| \| Kalibrr.*/i', '', $title);
                $company = preg_replace('/ - Jobs.*| \| LinkedIn.*| Lowongan.*| Karir.*| \| Kalibrr.*/i', '', $company);

                $payload = [
                    'title' => $title ?: 'Job Opportunity',
                    'company' => $company ?: ($this->source->name ?: 'Target Portal'),
                    'description' => 'Job description content. Please refer to the source portal link to view details and apply.',
                    'isClosed' => str_contains(strtolower($html), 'no longer accepting applications') || str_contains(strtolower($html), 'sudah ditutup') || str_contains(strtolower($html), 'expired'),
                    'success' => true
                ];

                StoreAndTagJob::dispatchSync($payload, $this->url, $this->source->id);
            } else {
                throw new \Exception("Lightweight HTTP fetch failed with status: " . $response->status());
            }
        } catch (\Exception $e) {
            Log::error("ScrapeJobDetailsJob completely failed for URL: {$this->url} -> " . $e->getMessage());
            throw $e;
        }
    }
}
