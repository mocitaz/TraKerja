<?php

namespace App\Models;

use App\Services\JobPageFetcher;
use App\Services\ScraperPageFetcher;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

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
            $page = app(ScraperPageFetcher::class)->fetch($this->seed_url);
            $crawler = new Crawler($page['html']);
            $target = strtolower($this->target_domain);
            $urls = [];
            $seen = [];
            $validator = app(JobPageFetcher::class);

            foreach ($crawler->filter('a[href]') as $link) {
                $href = trim($link->getAttribute('href'));
                if ($href === '' || preg_match('/[\x00-\x20\x7f\\\\]/', $href)) {
                    continue;
                }
                try {
                    $url = (string) UriResolver::resolve(new Uri($this->seed_url), new Uri($href));
                    if (isset($seen[$url])) {
                        continue;
                    }
                    $seen[$url] = true;
                    $host = strtolower(parse_url($url, PHP_URL_HOST) ?: '');
                    if ($host !== $target && ! str_ends_with($host, '.'.$target)) {
                        continue;
                    }
                    $path = parse_url($url, PHP_URL_PATH) ?: '';
                    if ($target === 'linkedin.com' || str_ends_with($target, '.linkedin.com')) {
                        if (! str_contains($path, '/jobs/view/') || str_contains($url, 'signup') || str_contains($url, 'login')) {
                            continue;
                        }
                    } elseif ($target === 'jobstreet.co.id' || str_ends_with($target, '.jobstreet.co.id')) {
                        if (! str_contains($path, '/job/') && ! str_contains($path, '/jobs/')) {
                            continue;
                        }
                    } elseif ($target === 'kalibrr.com' || str_ends_with($target, '.kalibrr.com')) {
                        if (! str_contains($path, '/c/') || ! str_contains($path, '/jobs/')) {
                            continue;
                        }
                    }
                    $validator->validateUrl($url);
                    $urls[] = $url;
                    if (count($urls) >= 10) {
                        break;
                    }
                } catch (\Exception) {
                    continue;
                }
            }

            return $urls;
        } catch (\Exception $e) {
            Log::warning("Discovery fetch failed for source {$this->id}.");

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

    /**
     * Check if the source is due for crawling based on last_run_at and frequency_minutes.
     */
    public function isDue(): bool
    {
        if (is_null($this->last_run_at)) {
            return true;
        }

        return now()->diffInMinutes($this->last_run_at) >= $this->frequency_minutes;
    }
}
