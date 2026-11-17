<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `vacancies` (TraKerja 2.0 Blueprint). */
class Vacancy extends Model
{
    protected $table = 'vacancies';

    protected $guarded = ['id'];

    protected $casts = ['skills' => 'array', 'is_internship' => 'boolean', 'date_posted' => 'date', 'valid_through' => 'date', 'closed_at' => 'datetime', 'first_seen_at' => 'datetime', 'last_seen_open_at' => 'datetime', 'last_verified_at' => 'datetime', 'extraction_confidence' => 'decimal:2', 'enriched_at' => 'datetime', 'ai_filled' => 'array'];

    use \Illuminate\Database\Eloquent\Concerns\HasUlids;

    /** Only public_id is a ULID; the primary key stays an auto-increment id. */
    public function uniqueIds(): array
    {
        return ['public_id'];
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function listings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VacancyListing::class);
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function sector(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TaxonomySector::class, 'sector_id');
    }

    protected static function booted(): void
    {
        // Fase 6: applicants and savers hear when a vacancy closes (from any path: verifier, feed, ingest).
        static::updated(function (Vacancy $vacancy) {
            if ($vacancy->wasChanged('status') && $vacancy->status === 'closed' && in_array($vacancy->getOriginal('status'), ['active', 'suspect'], true)) {
                \App\Jobs\NotifyVacancyClosed::dispatch($vacancy->id)->afterCommit();
            }
        });
    }

    public function primaryListing(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(VacancyListing::class, 'primary_listing_id');
    }

    /** Vacancies people may see in Explore: open, not merged away, not held back as a likely scam. */
    public function scopeVisible($query)
    {
        return $query->where('status', 'active')->whereNull('merged_into_id')->where('scam_flag', '!=', 'suspected');
    }

    // ---- Attributes the existing Explore views read (they were written for job_postings) ----

    public function getCompanyNameAttribute(): ?string
    {
        return $this->company?->name;
    }

    public function getLocationAttribute(): ?string
    {
        if ($this->region) {
            return $this->region->name;
        }
        // Remote boards can list every eligible country; keep the label short and lead with Indonesia.
        $parts = array_values(array_filter(array_map('trim', explode(',', (string) $this->location_text))));
        if (count($parts) > 3) {
            $lead = in_array('Indonesia', $parts, true) ? 'Indonesia' : $parts[0];

            return $lead.' +'.(count($parts) - 1).' negara';
        }

        return $this->location_text;
    }

    public function getWorkTypeAttribute(): ?string
    {
        return ['onsite' => 'Onsite', 'hybrid' => 'Hybrid', 'remote' => 'Remote'][$this->work_mode] ?? null;
    }

    public function getTechStackAttribute(): array
    {
        return $this->skills ?? [];
    }

    /** Where "Open listing" goes: the primary listing (company ATS first), its apply URL when known. */
    public function getSourceUrlAttribute(): ?string
    {
        $listing = $this->primaryListing ?? $this->listings->first();

        // Only http(s) links are ever rendered as hrefs.
        foreach ([$listing?->apply_url, $listing?->url] as $url) {
            if (is_string($url) && preg_match('#^https?://#i', $url)) {
                return $url;
            }
        }

        return null;
    }

    public function getSourcePlatformAttribute(): string
    {
        $listing = $this->primaryListing ?? $this->listings->first();

        return $listing?->source?->name ?? 'TraKerja';
    }

    /** All platforms that list this vacancy, primary first. */
    public function getSourceNamesAttribute(): array
    {
        return $this->listings->sortBy(fn ($l) => $l->id === $this->primary_listing_id ? 0 : 1)
            ->map(fn ($l) => $l->source?->name)->filter()->unique()->values()->all();
    }

    public function getPlainDescriptionAttribute(): string
    {
        return trim(html_entity_decode(strip_tags((string) $this->summary), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    public function getLastValidatedAtAttribute(): ?\Illuminate\Support\Carbon
    {
        return $this->last_verified_at ?? $this->last_seen_open_at;
    }

    /** Salary in the current UI language: "Rp4,5–5,2 jt/bulan" (id) or "Rp4.5–5.2M/month" (en); null without a salary. */
    public function getSalaryLabelAttribute(): ?string
    {
        return $this->salaryLabel();
    }

    /**
     * "Rp4,5–5,2 jt/bulan", "USD 3.000–4.000/bulan" in Indonesian; "Rp4.5–5.2M/month", "USD 3,000–4,000/month" in English.
     * Public SEO pages and social posts pass 'id' so they stay Indonesian whatever the visitor's UI language is.
     */
    public function salaryLabel(?string $locale = null): ?string
    {
        if (! $this->salary_min) {
            return null;
        }
        $en = str_starts_with($locale ?? app()->getLocale(), 'en');
        $periods = $en
            ? ['hour' => 'hour', 'day' => 'day', 'week' => 'week', 'month' => 'month', 'year' => 'year']
            : ['hour' => 'jam', 'day' => 'hari', 'week' => 'minggu', 'month' => 'bulan', 'year' => 'tahun'];
        $period = $periods[$this->salary_period] ?? null;
        [$dec, $thou] = $en ? ['.', ','] : [',', '.'];
        $idr = ($this->salary_currency ?? 'IDR') === 'IDR';
        // Rupiah from 1 million up is written short, with the unit once: "Rp4,5–5,2 jt" / "Rp4.5–5.2M".
        $juta = $idr && $this->salary_min >= 1_000_000;
        $fmt = fn (int $v) => $juta
            ? rtrim(rtrim(number_format($v / 1_000_000, 1, $dec, $thou), '0'), $dec)
            : number_format($v, 0, $dec, $thou);
        $range = $fmt($this->salary_min).($this->salary_max && $this->salary_max !== $this->salary_min ? '–'.$fmt($this->salary_max) : '').($juta ? ($en ? 'M' : ' jt') : '');

        return ($idr ? 'Rp' : $this->salary_currency.' ').$range.($period ? '/'.$period : '');
    }

    /** Public URL for SEO pages: /loker/{slug}-{public_id}. */
    public function getPublicPathAttribute(): string
    {
        return route('loker.show', ['slug' => ($this->slug ?: 'lowongan').'-'.strtolower($this->public_id)]);
    }
}
