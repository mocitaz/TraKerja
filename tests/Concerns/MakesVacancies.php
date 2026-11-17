<?php

namespace Tests\Concerns;

use App\Models\Company;
use App\Models\JobSource;
use App\Models\Region;
use App\Models\Vacancy;
use App\Models\VacancyListing;
use Illuminate\Support\Str;

/**
 * Builds a canonical vacancy with one source listing, for Explore tests.
 * Short keys: company, source (job_sources slug), url, region (region name), source_name/domain for an ad-hoc source.
 */
trait MakesVacancies
{
    protected function makeVacancy(array $attributes = []): Vacancy
    {
        $companyName = $attributes['company'] ?? 'Actual Corp';
        $company = Company::firstOrCreate(['slug' => Str::slug($companyName)], ['name' => $companyName, 'name_normalized' => Str::lower($companyName)]);

        $source = isset($attributes['source'])
            ? JobSource::firstOrCreate(['slug' => $attributes['source']], ['name' => $attributes['source_name'] ?? Str::headline($attributes['source']), 'kind' => 'json_api', 'tier' => 'B', 'base_url' => 'https://'.($attributes['domain'] ?? $attributes['source'].'.com')])
            : JobSource::firstOrCreate(['slug' => 'dealls'], ['name' => 'Dealls', 'kind' => 'jsonld_crawl', 'tier' => 'B', 'base_url' => 'https://dealls.com']);

        $title = $attributes['title'] ?? 'Platform engineer';
        $url = $attributes['url'] ?? 'https://'.parse_url($source->base_url, PHP_URL_HOST).'/jobs/'.Str::random(8);
        $region = isset($attributes['region']) ? Region::where('name', $attributes['region'])->firstOrFail() : null;

        $vacancy = Vacancy::create(array_merge([
            'slug' => Str::slug($title),
            'company_id' => $company->id,
            'title' => $title,
            'title_normalized' => Str::lower($title),
            'summary' => 'Build reliable systems.',
            'region_id' => $region?->id,
            'status' => 'active',
            'first_seen_at' => now(),
            'last_seen_open_at' => now(),
        ], array_diff_key($attributes, array_flip(['company', 'source', 'source_name', 'domain', 'url', 'region']))));

        $listing = VacancyListing::create([
            'vacancy_id' => $vacancy->id, 'job_source_id' => $source->id, 'url' => $url, 'url_hash' => hash('sha256', $url),
            'status' => 'active', 'raw_title' => $title, 'raw_company' => $companyName, 'first_seen_at' => now(), 'last_seen_at' => now(),
        ]);
        $vacancy->update(['primary_listing_id' => $listing->id]);

        return $vacancy->fresh();
    }

    protected function listingUrl(Vacancy $vacancy): string
    {
        return $vacancy->listings()->value('url');
    }
}
