<?php

namespace App\JobEngine\Ingest;

use App\JobEngine\Intelligence\Matcher;
use App\JobEngine\Intelligence\ScamDetector;
use App\JobEngine\Intelligence\TextSimilarity;
use App\JobEngine\Normalize\CompanyResolver;
use App\JobEngine\Normalize\IndonesianText;
use App\JobEngine\Normalize\PiiScrubber;
use App\JobEngine\Normalize\RegionResolver;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use App\Models\CrawlRun;
use App\Models\JobSource;
use App\Models\Vacancy;
use App\Models\VacancyListing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Stores what an adapter found:
 * 1. finds the listing by (source, external id) or canonical URL;
 * 2. unchanged content only refreshes last_seen_at (no rewrite, no AI);
 * 3. changed or new content updates the listing and its vacancy.
 *
 * Fase 1 links each listing to its own vacancy. Cross-platform matching (several listings,
 * one vacancy) is added by the matcher in Fase 3 without changing this contract.
 */
class IngestService
{
    public function __construct(
        private CompanyResolver $companies,
        private RegionResolver $regions,
        private Matcher $matcher,
    ) {}

    /** @return 'new'|'updated'|'merged'|'unchanged'|'skipped' */
    public function ingest(JobSource $source, ScrapedJob $job, ?CrawlRun $run = null, bool $force = false): string
    {
        if (! $job->title || ! $job->company) {
            return 'skipped';
        }

        $now = now();
        $urlHash = hash('sha256', self::canonicalUrl($job->url));
        $contentHash = $job->contentHash();

        $listing = ($job->externalId
            ? VacancyListing::where('job_source_id', $source->id)->where('external_id', $job->externalId)->first()
            : null) ?? VacancyListing::where('url_hash', $urlHash)->first();

        if (! $force && $listing && $listing->content_hash === $contentHash && $listing->vacancy_id) {
            // Seen again: a listing missed from a feed earlier is open after all.
            $listing->update(['last_seen_at' => $now, 'consecutive_misses' => 0]
                + ($job->isOpen !== false && $listing->status !== 'active' ? ['status' => 'active'] : []));
            if ($job->isOpen !== false) {
                Vacancy::whereKey($listing->vacancy_id)->update(['last_seen_open_at' => $now]);
            }

            return 'unchanged';
        }

        return DB::transaction(function () use ($source, $job, $listing, $urlHash, $contentHash, $now, $run) {
            $isNew = ! $listing;
            $listing ??= new VacancyListing(['job_source_id' => $source->id, 'first_seen_at' => $now]);
            $listing->fill([
                'external_id' => $job->externalId,
                'url' => $job->url,
                'url_hash' => $urlHash,
                'apply_url' => $job->applyUrl,
                'apply_url_hash' => $job->applyUrl ? hash('sha256', self::canonicalUrl($job->applyUrl)) : null,
                'simhash' => TextSimilarity::simhash($job->description),
                'raw_title' => Str::limit((string) $job->title, 250, ''),
                'raw_company' => Str::limit((string) $job->company, 250, ''),
                'raw_location' => $job->locationText ? Str::limit($job->locationText, 250, '') : null,
                'raw_salary' => $job->salaryText,
                'raw_payload' => $job->raw,
                'description' => $job->description ? Str::limit(PiiScrubber::scrub(trim($job->description)), 8000, '') : null,
                'content_hash' => $contentHash,
                'status' => $this->status($job),
                'extraction_method' => $job->extractionMethod,
                'last_seen_at' => $now,
                'consecutive_misses' => 0,
                'next_check_at' => $now->copy()->addDay(),
            ])->save();

            $company = $this->companies->resolve($job->company, $job->companyDomain, $job->companyLogo);
            $attributes = $this->vacancyAttributes($job, $company->id, $now);

            // A listing without a vacancy may be a vacancy we already know from another platform.
            $match = null;
            if (! $listing->vacancy_id) {
                $match = $this->matcher->find($listing, $company->id, $attributes['region_id'], $attributes['title']);
            }

            if ($match) {
                $vacancy = $match['vacancy'];
                $this->fillGaps($vacancy, $attributes);
                $listing->update(['vacancy_id' => $vacancy->id, 'match_method' => $match['method'], 'match_score' => $match['score']]);
            } else {
                $vacancy = $listing->vacancy ?? new Vacancy(['first_seen_at' => $now]);
                // Only the primary listing may overwrite a vacancy; other platforms only fill gaps.
                if (! $vacancy->exists || ! $vacancy->primary_listing_id || $vacancy->primary_listing_id === $listing->id) {
                    $vacancy->fill($attributes)->save();
                } else {
                    $this->fillGaps($vacancy, $attributes);
                }
                if (! $listing->vacancy_id) {
                    $listing->update(['vacancy_id' => $vacancy->id, 'match_method' => 'new']);
                }
            }

            $vacancy->update([
                'primary_listing_id' => $this->primaryListingId($vacancy) ?? $listing->id,
                'listings_count' => $vacancy->listings()->count(),
            ]);

            if ($match) {
                return 'merged';
            }
            // Pipeline: a brand-new vacancy is enriched (then embedded) as soon as the transaction commits.
            if ($vacancy->wasRecentlyCreated && config('job_sources.auto_pipeline')) {
                \App\Jobs\EnrichVacancyJob::dispatch($vacancy->id)->afterCommit();
            }

            return $isNew ? 'new' : 'updated';
        });
    }

    /** Copy values the vacancy is still missing; while any listing is open, keep the vacancy active. */
    private function fillGaps(Vacancy $vacancy, array $attributes): void
    {
        foreach ($attributes as $field => $value) {
            if ($value !== null && $vacancy->{$field} === null && ! in_array($field, ['closed_at', 'closed_reason'], true)) {
                $vacancy->{$field} = $value;
            }
        }
        if ($attributes['status'] === 'active') {
            $vacancy->fill(['status' => 'active', 'closed_at' => null, 'closed_reason' => null, 'last_seen_open_at' => now()]);
        }
        $vacancy->save();
    }

    /**
     * The listing behind the "Lamar" button: the company's own ATS/career page first,
     * then the most recently seen open listing.
     */
    private function primaryListingId(Vacancy $vacancy): ?int
    {
        return $vacancy->listings()
            ->join('job_sources', 'job_sources.id', '=', 'vacancy_listings.job_source_id')
            ->orderByRaw("CASE WHEN vacancy_listings.status = 'active' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE job_sources.kind WHEN 'ats' THEN 0 WHEN 'direct' THEN 0 ELSE 1 END")
            ->orderByDesc('vacancy_listings.last_seen_at')
            ->value('vacancy_listings.id');
    }

    private function vacancyAttributes(ScrapedJob $job, int $companyId, $now): array
    {
        $status = $this->status($job);
        $description = PiiScrubber::scrub($job->description);
        $title = trim(preg_replace('/\s+/u', ' ', (string) $job->title));

        // Rule-based Indonesian readers fill only what the source left empty.
        if ($job->salaryMin === null) {
            $salary = IndonesianText::salary($job->salaryText) ?? IndonesianText::salaryFromDescription($job->description);
            if ($salary) {
                [$job->salaryMin, $job->salaryMax, $job->salaryCurrency, $job->salaryPeriod] = [$salary['min'], $salary['max'], $salary['currency'], $salary['period']];
            }
        }
        $job->educationMin ??= IndonesianText::educationMin($job->description);

        return [
            'company_id' => $companyId,
            'title' => Str::limit($title, 250, ''),
            'title_normalized' => Str::limit(CompanyResolver::normalize($title), 250, ''),
            'slug' => Str::limit(Str::slug($title), 120, ''),
            'employment_type' => in_array($job->employmentType, Vocabulary::EMPLOYMENT_TYPES, true) ? $job->employmentType : null,
            'work_mode' => in_array($job->workMode, Vocabulary::WORK_MODES, true) ? $job->workMode : null,
            'education_min' => in_array($job->educationMin, Vocabulary::EDUCATION, true) ? $job->educationMin : null,
            'seniority' => IndonesianText::seniority($title),
            'scam_flag' => ScamDetector::check($title, $job->description)['flag'],
            'is_internship' => $job->employmentType === 'internship'
                || (bool) preg_match('/\b(magang|intern(ship)?|pkl|trainee)\b/i', $title),
            'region_id' => $this->regions->resolve($job->city, $job->province, $job->locationText),
            'location_text' => $job->locationText ? Str::limit($job->locationText, 250, '') : null,
            'country' => $job->country,
            'salary_min' => $job->salaryMin,
            'salary_max' => $job->salaryMax,
            'salary_currency' => $job->salaryCurrency ? strtoupper(substr($job->salaryCurrency, 0, 3)) : null,
            'salary_period' => $job->salaryPeriod,
            'summary' => $description ? Str::limit(trim(preg_replace('/\s+/u', ' ', $description)), 600) : null,
            'date_posted' => $job->datePosted,
            'valid_through' => $job->validThrough,
            'status' => $status,
            'closed_at' => $status === 'closed' ? $now : null,
            'closed_reason' => $status === 'closed' ? ($job->isOpen === false ? 'source_expired' : 'valid_through') : null,
            'last_seen_open_at' => $status === 'active' ? $now : null,
            'extraction_confidence' => match ($job->extractionMethod) {
                'api', 'jsonld', 'next_data' => 0.9, 'parser' => 0.75, default => 0.5,
            },
        ];
    }

    private function status(ScrapedJob $job): string
    {
        if ($job->isOpen === false) {
            return 'closed';
        }
        if ($job->validThrough && $job->validThrough < now()->toDateString()) {
            return 'closed';
        }

        return 'active';
    }

    /** Same page regardless of tracking parameters, fragments or trailing slash. */
    public static function canonicalUrl(string $url): string
    {
        $parts = parse_url(trim($url));
        $query = [];
        parse_str($parts['query'] ?? '', $query);
        $query = array_filter($query, fn ($key) => ! preg_match('/^(utm_|fbclid|gclid|ref|source|tracking)/i', (string) $key), ARRAY_FILTER_USE_KEY);
        ksort($query);

        return strtolower($parts['host'] ?? '').rtrim($parts['path'] ?? '/', '/').($query ? '?'.http_build_query($query) : '');
    }
}
