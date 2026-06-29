<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScraperSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'target_domain',
        'seed_url',
        'is_active',
        'selectors_config',
        'frequency_minutes',
        'delay_between_requests_ms',
        'max_concurrency',
        'last_run_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'selectors_config' => 'array',
        'last_run_at' => 'datetime',
    ];

    /**
     * Get job postings crawled from this source.
     */
    public function jobPostings(): HasMany
    {
        return $this->hasMany(JobPosting::class);
    }

    /**
     * Get execution logs and metrics for this source.
     */
    public function logsAndMetrics(): HasMany
    {
        return $this->hasMany(ScraperLogsAndMetric::class);
    }

    /**
     * Execute discovery of job URLs.
     */
    public function executeDiscovery(): array
    {
        try {
            echo "  [HTTP GET] Requesting: " . $this->seed_url . "\n";
            $response = \Illuminate\Support\Facades\Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ])
                ->get($this->seed_url);
                
            if ($response->failed()) {
                echo "  [HTTP FAIL] Status code: " . $response->status() . "\n";
                \Illuminate\Support\Facades\Log::warning("Discovery request failed for source {$this->id} ({$this->name}) with status code: " . $response->status() . " on URL: " . $this->seed_url);
                return [];
            }
            
            $html = $response->body();
            echo "  [HTTP SUCCESS] Received " . strlen($html) . " bytes\n";
            preg_match_all('/href="([^"]*?' . preg_quote($this->target_domain, '/') . '[^"]*?)"/i', $html, $matches);
            
            $urls = array_unique($matches[1] ?? []);
            $filteredUrls = [];
            
            foreach ($urls as $url) {
                $url = html_entity_decode($url);
                
                if (str_contains($this->target_domain, 'linkedin.com')) {
                    if (str_contains($url, '/jobs/view/') && !str_contains($url, 'signup') && !str_contains($url, 'login')) {
                        $filteredUrls[] = $url;
                    }
                } elseif (str_contains($this->target_domain, 'jobstreet.co.id')) {
                    if (str_contains($url, '/job/') || str_contains($url, '/jobs/')) {
                        $filteredUrls[] = $url;
                    }
                } elseif (str_contains($this->target_domain, 'kalibrr.com')) {
                    if (str_contains($url, '/c/') && str_contains($url, '/jobs/')) {
                        $filteredUrls[] = $url;
                    }
                } else {
                    $filteredUrls[] = $url;
                }
            }
            
            $urls = array_slice(array_unique($filteredUrls), 0, 10);
            
            \Illuminate\Support\Facades\Log::info("Discovery for source {$this->id} ({$this->name}): status " . $response->status() . ", body size " . strlen($html) . " bytes, found " . count($urls) . " links.");
            
            return $urls;
        } catch (\Exception $e) {
            echo "  [HTTP ERROR] Exception thrown: " . $e->getMessage() . "\n";
            \Illuminate\Support\Facades\Log::error("Discovery failed for source {$this->id}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Helper to update the last run timestamp.
     */
    public function updateLastRun(): void
    {
        $this->update(['last_run_at' => now()]);
    }
}
