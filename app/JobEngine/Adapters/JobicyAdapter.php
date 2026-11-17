<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Carbon;

/** Jobicy public API, APAC and worldwide listings. Terms ask for attribution and a link back. */
class JobicyAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'jobicy';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $yielded = 0;
        // The default feed is mostly UK/US-only roles; ask for APAC and worldwide ones directly.
        $jobs = [];
        foreach (['apac', 'anywhere'] as $geo) {
            foreach ($http->getJson("https://jobicy.com/api/v2/remote-jobs?count=50&geo={$geo}")['jobs'] ?? [] as $job) {
                $jobs[$job['id']] = $job;
            }
        }
        foreach ($jobs as $job) {
            if (! RemoteEligibility::openToIndonesia($job['jobGeo'] ?? null)) {
                continue;
            }
            if ($yielded++ >= $limit) {
                return;
            }
            $annual = isset($job['annualSalaryMin']);

            yield new ScrapedJob(
                source: 'jobicy', url: $job['url'], externalId: (string) $job['id'],
                title: isset($job['jobTitle']) ? html_entity_decode($job['jobTitle']) : null,
                company: $job['companyName'] ?? null, companyLogo: $job['companyLogo'] ?? null,
                locationText: $job['jobGeo'] ?? null, workMode: 'remote',
                employmentType: Vocabulary::employmentType(implode(' ', (array) ($job['jobType'] ?? []))),
                salaryMin: isset($job['annualSalaryMin']) ? (int) $job['annualSalaryMin'] : (isset($job['salaryMin']) ? (int) $job['salaryMin'] : null),
                salaryMax: isset($job['annualSalaryMax']) ? (int) $job['annualSalaryMax'] : (isset($job['salaryMax']) ? (int) $job['salaryMax'] : null),
                salaryCurrency: $job['salaryCurrency'] ?? null,
                salaryPeriod: $annual ? 'year' : Vocabulary::salaryPeriod($job['salaryPeriod'] ?? null),
                datePosted: isset($job['pubDate']) ? Carbon::parse($job['pubDate'])->toDateString() : null,
                description: isset($job['jobDescription']) ? trim(strip_tags(html_entity_decode($job['jobDescription']))) : null,
                applyUrl: $job['url'], isOpen: true, extractionMethod: 'api',
                raw: array_diff_key($job, ['jobDescription' => true, 'jobExcerpt' => true]),
            );
        }
    }
}
