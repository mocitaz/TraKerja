<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/** SmartRecruiters public postings API for the Indonesian companies in config('boards'). */
class SmartRecruitersAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'smartrecruiters';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $yielded = 0;
        foreach ($config['boards'] ?? [] as $company) {
            foreach ($http->getJson("https://api.smartrecruiters.com/v1/companies/{$company}/postings?limit=100&country=id")['content'] ?? [] as $post) {
                $loc = $post['location'] ?? [];
                $location = implode(', ', array_filter([$loc['city'] ?? null, $loc['region'] ?? null, $loc['country'] ?? null]));
                if (! IndonesiaLocation::keep($location, (bool) ($loc['remote'] ?? false))) {
                    continue;
                }
                if ($yielded++ >= $limit) {
                    return;
                }
                $url = "https://jobs.smartrecruiters.com/{$company}/{$post['id']}";

                yield new ScrapedJob(
                    source: 'smartrecruiters', url: $url, externalId: $company.'-'.$post['id'],
                    title: $post['name'] ?? null, company: Arr::get($post, 'company.name', $company),
                    locationText: $location ?: null, city: $loc['city'] ?? null, province: $loc['region'] ?? null,
                    country: strtolower($loc['country'] ?? '') === 'id' ? 'ID' : null,
                    employmentType: Vocabulary::employmentType(Arr::get($post, 'typeOfEmployment.label')),
                    workMode: ($loc['remote'] ?? false) ? 'remote' : (($loc['hybrid'] ?? false) ? 'hybrid' : null),
                    datePosted: isset($post['releasedDate']) ? Carbon::parse($post['releasedDate'])->toDateString() : null,
                    applyUrl: $url, isOpen: true, extractionMethod: 'api', raw: $post,
                );
            }
        }
    }
}
