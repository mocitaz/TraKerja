<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/** Ashby public job-board API for the Indonesian organizations in config('boards'). */
class AshbyAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'ashby';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $yielded = 0;
        foreach ($config['boards'] ?? [] as $org) {
            foreach ($http->getJson("https://api.ashbyhq.com/posting-api/job-board/{$org}")['jobs'] ?? [] as $job) {
                $address = Arr::get($job, 'address.postalAddress', []);
                $location = $job['location'] ?? implode(', ', array_filter($address));
                if (! IndonesiaLocation::keep($location.' '.implode(' ', array_filter($address)), (bool) ($job['isRemote'] ?? false))) {
                    continue;
                }
                if ($yielded++ >= $limit) {
                    return;
                }

                yield new ScrapedJob(
                    source: 'ashby', url: $job['jobUrl'], externalId: $org.'-'.$job['id'],
                    title: $job['title'] ?? null, company: $config['names'][$org] ?? ucfirst($org),
                    locationText: $location ?: null, city: $address['addressLocality'] ?? null, province: $address['addressRegion'] ?? null,
                    country: IndonesiaLocation::matches($address['addressCountry'] ?? '') ? 'ID' : null,
                    employmentType: Vocabulary::employmentType($job['employmentType'] ?? null),
                    workMode: ($job['isRemote'] ?? false) ? 'remote' : null,
                    datePosted: isset($job['publishedAt']) ? Carbon::parse($job['publishedAt'])->toDateString() : null,
                    description: $job['descriptionPlain'] ?? null,
                    applyUrl: $job['applyUrl'] ?? $job['jobUrl'], isOpen: true, extractionMethod: 'api',
                    raw: array_diff_key($job, ['descriptionHtml' => true, 'descriptionPlain' => true]),
                );
            }
        }
    }
}
