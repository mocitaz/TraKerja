<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScraperLogsAndMetric extends Model
{
    use HasFactory;

    // Table name if Laravel doesn't pluralize correctly
    protected $table = 'scraper_logs_and_metrics';

    // Disable standard timestamps if we are using start/end datetimes manually
    public $timestamps = false;

    protected $fillable = [
        'scraper_source_id',
        'session_started_at',
        'session_ended_at',
        'discovered_links_count',
        'successfully_scraped_count',
        'failed_scraped_count',
        'proxy_ip_used',
        'bandwidth_bytes_consumed',
        'estimated_cost_usd',
        'status',
        'error_summary',
    ];

    protected $casts = [
        'session_started_at' => 'datetime',
        'session_ended_at' => 'datetime',
        'discovered_links_count' => 'integer',
        'successfully_scraped_count' => 'integer',
        'failed_scraped_count' => 'integer',
        'bandwidth_bytes_consumed' => 'integer',
        'estimated_cost_usd' => 'decimal:4',
    ];

    /**
     * Get the scraper source associated with these logs.
     */
    public function scraperSource(): BelongsTo
    {
        return $this->belongsTo(ScraperSource::class);
    }
}
