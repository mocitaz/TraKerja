<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Carbon;

/** Workable public widget API for the Indonesian accounts in config('boards'). */
class WorkableAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'workable';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $yielded = 0;
        foreach ($config['boards'] ?? [] as $account) {
            $data = $http->getJson("https://apply.workable.com/api/v1/widget/accounts/{$account}?details=true");
            foreach ($data['jobs'] ?? [] as $job) {
                $location = implode(', ', array_filter([$job['city'] ?? null, $job['state'] ?? null, $job['country'] ?? null]));
                if (! IndonesiaLocation::keep($location, (bool) ($job['telecommuting'] ?? false))) {
                    continue;
                }
                if ($yielded++ >= $limit) {
                    return;
                }

                yield new ScrapedJob(
                    source: 'workable', url: $job['url'] ?? $job['shortlink'], externalId: $account.'-'.($job['shortcode'] ?? $job['code'] ?? md5($job['url'] ?? '')),
                    title: $job['title'] ?? null, company: $data['name'] ?? $account,
                    locationText: $location ?: null, city: $job['city'] ?? null, province: $job['state'] ?? null,
                    country: IndonesiaLocation::matches($job['country'] ?? '') ? 'ID' : null,
                    employmentType: Vocabulary::employmentType($job['employment_type'] ?? null),
                    workMode: ($job['telecommuting'] ?? false) ? 'remote' : null,
                    datePosted: isset($job['published_on']) ? Carbon::parse($job['published_on'])->toDateString() : null,
                    description: isset($job['description']) ? trim(strip_tags(html_entity_decode($job['description']))) : null,
                    applyUrl: $job['application_url'] ?? $job['url'] ?? null, isOpen: true, extractionMethod: 'api',
                    educationMin: null,
                    raw: array_diff_key($job, ['description' => true]),
                );
            }
        }
    }
}
