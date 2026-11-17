<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\DisallowedByRobots;
use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * Kalibrr: no JSON-LD, but job pages carry structured data in __NEXT_DATA__ (pageProps.job),
 * including isExpired. The server-rendered listing ignores ?page=, so discovery walks the
 * keyword listings in config('discovery_paths').
 */
class KalibrrAdapter implements SourceAdapter
{
    private const ORIGIN = 'https://www.kalibrr.id';

    public function slug(): string
    {
        return 'kalibrr';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $seen = [];
        $yielded = 0;
        foreach ($config['discovery_paths'] ?? ['/home/all-jobs'] as $listing) {
            $html = $http->get(self::ORIGIN.$listing)->body();
            preg_match_all('#/c/[a-z0-9-]+/jobs/[0-9]+/[a-z0-9-]+#', $html, $m);

            foreach (array_unique($m[0]) as $path) {
                if ($yielded >= $limit) {
                    return;
                }
                $url = self::ORIGIN.$path;
                if (isset($seen[$url]) || ($isKnown && $isKnown($url, null))) {
                    continue;
                }
                $seen[$url] = true;
                try {
                    $response = $http->get($url);
                } catch (DisallowedByRobots) {
                    continue;
                }
                $data = preg_match('#<script id="__NEXT_DATA__"[^>]*>(.*?)</script>#s', $response->body(), $nd) ? json_decode($nd[1], true) : null;
                $job = Arr::get($data, 'props.pageProps.job');
                if (! is_array($job)) {
                    continue;
                }
                $yielded++;

                yield self::map($job, $url, Arr::get($data, 'props.pageProps.isExpired'));
            }
        }
    }

    /** Also used offline by jobs:golden-eval on stored samples. */
    public static function map(array $job, string $url, ?bool $isExpired): ScrapedJob
    {
        $loc = $job['googleLocation']['addressComponents'] ?? [];
        $date = fn ($v) => $v ? Carbon::parse($v)->toDateString() : null;
        $int = fn ($v) => is_numeric($v) && $v > 0 ? (int) $v : null;

        return new ScrapedJob(
            source: 'kalibrr',
            url: $url,
            externalId: (string) $job['id'],
            title: $job['name'] ?? null,
            company: Arr::get($job, 'companyInfo.name', Arr::get($job, 'company.name')),
            companyDomain: null,
            companyLogo: Arr::get($job, 'companyInfo.logo', Arr::get($job, 'company.logo')),
            locationText: implode(', ', array_filter([$loc['city'] ?? null, $loc['region'] ?? null])) ?: null,
            city: $loc['city'] ?? null,
            province: $loc['region'] ?? null,
            country: isset($loc['country']) && stripos($loc['country'], 'indonesia') !== false ? 'ID' : null,
            employmentType: Vocabulary::employmentType($job['tenure'] ?? null),
            workMode: ($job['isWorkFromHome'] ?? false) ? 'remote' : (($job['isHybrid'] ?? false) ? 'hybrid' : null),
            salaryMin: $int($job['baseSalary'] ?? null),
            salaryMax: $int($job['maximumSalary'] ?? null),
            salaryCurrency: $job['salaryCurrency'] ?? null,
            salaryPeriod: Vocabulary::salaryPeriod($job['salaryInterval'] ?? null),
            datePosted: $date($job['activationDate'] ?? null),
            validThrough: $date($job['applicationEndDate'] ?? null),
            description: trim(strip_tags(html_entity_decode(($job['description'] ?? '')."\n".($job['qualifications'] ?? '')))) ?: null,
            applyUrl: $job['applyRedirectUrl'] ?? null,
            isOpen: $isExpired === null ? null : ! $isExpired,
            extractionMethod: 'next_data',
            raw: Arr::except($job, ['description', 'qualifications', 'companyInfo']),
        );
    }
}
