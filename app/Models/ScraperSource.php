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
            $response = \Illuminate\Support\Facades\Http::timeout(30)
                ->get($this->seed_url);
                
            if ($response->failed()) {
                return [];
            }
            
            $html = $response->body();
            preg_match_all('/href="([^"]*?' . preg_quote($this->target_domain, '/') . '[^"]*?)"/i', $html, $matches);
            
            return array_slice(array_unique($matches[1] ?? []), 0, 10);
        } catch (\Exception $e) {
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
