<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'scraper_source_id',
        'title',
        'company_name',
        'description',
        'raw_url',
        'unique_hash',
        'status',
        'report_dead_count',
        'last_validated_at',
    ];

    protected $casts = [
        'last_validated_at' => 'datetime',
        'report_dead_count' => 'integer',
    ];

    /**
     * Get the scraper source that retrieved this posting.
     */
    public function scraperSource(): BelongsTo
    {
        return $this->belongsTo(ScraperSource::class);
    }
}
