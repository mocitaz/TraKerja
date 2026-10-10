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
        'category_field',
        'category_major',
        'location',
        'work_type',
        'tech_stack',
        'raw_url',
        'unique_hash',
        'status',
        'report_dead_count',
        'last_validated_at',
    ];

    protected $casts = [
        'last_validated_at' => 'datetime',
        'report_dead_count' => 'integer',
        'tech_stack' => 'array',
    ];

    public function getPlainDescriptionAttribute(): string
    {
        $content = preg_replace('~<(script|style)\b[^>]*>.*?</\1\s*>~is', '', $this->description ?? '');
        $content = preg_replace('~<br\s*/?>|</(?:p|div|li|h[1-6])\s*>~i', "\n", $content);
        return trim(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    public function getSourcePlatformAttribute(): string
    {
        return \App\Support\PlatformCatalog::nameForDomain($this->scraperSource?->target_domain)
            ?? ($this->scraperSource?->name ?: 'Other');
    }

    public function getSourceUrlAttribute(): ?string
    {
        return filter_var($this->raw_url, FILTER_VALIDATE_URL)
            && in_array(strtolower(parse_url($this->raw_url, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true)
            ? $this->raw_url : null;
    }

    /**
     * Get the scraper source that retrieved this posting.
     */
    public function scraperSource(): BelongsTo
    {
        return $this->belongsTo(ScraperSource::class);
    }
}
