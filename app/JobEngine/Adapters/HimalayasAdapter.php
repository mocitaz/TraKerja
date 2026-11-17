<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;

/** Himalayas public API (offset paging; ?page= HTML paths are disallowed, the API is not). */
class HimalayasAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'himalayas';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $yielded = 0;
        for ($offset = 0; $offset < 200 && $yielded < $limit; $offset += 20) {
            $jobs = $http->getJson("https://himalayas.app/jobs/api?limit=20&offset={$offset}")['jobs'] ?? [];
            if ($jobs === []) {
                return;
            }
            foreach ($jobs as $job) {
                $restrictions = implode(', ', (array) ($job['locationRestrictions'] ?? []));
                if (! RemoteEligibility::openToIndonesia($restrictions)) {
                    continue;
                }
                if ($yielded++ >= $limit) {
                    return;
                }

                yield new ScrapedJob(
                    source: 'himalayas', url: $job['applicationLink'] ?? $job['guid'] ?? '', externalId: (string) ($job['guid'] ?? md5($job['title'].$job['companyName'])),
                    title: $job['title'] ?? null, company: $job['companyName'] ?? null, companyLogo: $job['companyLogo'] ?? null,
                    locationText: $restrictions ?: 'Worldwide', workMode: 'remote',
                    employmentType: Vocabulary::employmentType($job['employmentType'] ?? null),
                    salaryMin: isset($job['minSalary']) ? (int) $job['minSalary'] : null,
                    salaryMax: isset($job['maxSalary']) ? (int) $job['maxSalary'] : null,
                    salaryCurrency: $job['currency'] ?? null, salaryPeriod: isset($job['minSalary']) ? 'year' : null,
                    datePosted: isset($job['pubDate']) ? date('Y-m-d', (int) $job['pubDate']) : null,
                    validThrough: isset($job['expiryDate']) ? date('Y-m-d', (int) $job['expiryDate']) : null,
                    description: isset($job['description']) ? trim(strip_tags(html_entity_decode($job['description']))) : null,
                    applyUrl: $job['applicationLink'] ?? null, isOpen: true, extractionMethod: 'api',
                    raw: array_diff_key($job, ['description' => true, 'excerpt' => true]),
                );
            }
        }
    }
}
