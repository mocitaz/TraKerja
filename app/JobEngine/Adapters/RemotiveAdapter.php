<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Carbon;

/** Remotive public API. Terms: few requests per day and a link back; one request per run. */
class RemotiveAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'remotive';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $yielded = 0;
        foreach ($http->getJson('https://remotive.com/api/remote-jobs')['jobs'] ?? [] as $job) {
            if (! RemoteEligibility::openToIndonesia($job['candidate_required_location'] ?? null)) {
                continue;
            }
            if ($yielded++ >= $limit) {
                return;
            }

            yield new ScrapedJob(
                source: 'remotive', url: $job['url'], externalId: (string) $job['id'],
                title: $job['title'] ?? null, company: $job['company_name'] ?? null, companyLogo: $job['company_logo'] ?? null,
                locationText: $job['candidate_required_location'] ?? null, workMode: 'remote',
                employmentType: Vocabulary::employmentType($job['job_type'] ?? null),
                salaryText: ($job['salary'] ?? null) ?: null,
                datePosted: isset($job['publication_date']) ? Carbon::parse($job['publication_date'])->toDateString() : null,
                description: isset($job['description']) ? trim(strip_tags(html_entity_decode($job['description']))) : null,
                applyUrl: $job['url'], isOpen: true, extractionMethod: 'api',
                raw: array_diff_key($job, ['description' => true]),
            );
        }
    }
}
