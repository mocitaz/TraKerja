<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;

/**
 * One adapter per source. An adapter only knows how to find and read that source;
 * normalization, dedup and storage happen in the ingest pipeline.
 */
interface SourceAdapter
{
    /** Registry key in config/job_sources.php and job_sources.slug. */
    public function slug(): string;

    /**
     * Yield vacancies until $limit is reached.
     *
     * @param  array<string, mixed>  $config  the source's entry in job_sources.config
     * @param  (callable(string $url, ?string $lastmod): bool)|null  $isKnown  returns true when the URL is
     *         already stored and unchanged, so page-based adapters can skip downloading it
     * @return iterable<ScrapedJob>
     */
    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable;
}
