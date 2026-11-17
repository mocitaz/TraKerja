<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/** Greenhouse public job-board API for the verified Indonesian boards in config('boards'). */
class GreenhouseAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'greenhouse';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $yielded = 0;
        foreach ($config['boards'] ?? [] as $board) {
            $jobs = $http->getJson("https://boards-api.greenhouse.io/v1/boards/{$board}/jobs?content=true")['jobs'] ?? [];
            foreach ($jobs as $job) {
                // Regional boards (e.g. Xendit) list Bangkok-only or US-only roles too.
                if (! IndonesiaLocation::matches($job['location']['name'] ?? '')) {
                    continue;
                }
                if ($yielded++ >= $limit) {
                    return;
                }
                yield self::map($job, $board);
            }
        }
    }

    /** Also used offline by jobs:golden-eval on stored samples. */
    public static function map(array $job, string $board): ScrapedJob
    {
        $location = $job['location']['name'] ?? null;
        // "Bangkok, Thailand; Jakarta, Indonesia; ..." -> use the Indonesian segment for the city.
        $segments = array_map('trim', explode(';', (string) $location));
        $indonesian = collect($segments)->first(fn ($s) => IndonesiaLocation::matches($s)) ?? $segments[0];

        return new ScrapedJob(
            source: 'greenhouse',
            url: $job['absolute_url'],
            externalId: $board.'-'.$job['id'],
            title: $job['title'] ?? null,
            company: $job['company_name'] ?? Str::headline($board),
            locationText: $location,
            city: $indonesian !== '' ? trim(explode(',', $indonesian)[0]) : null,
            country: $location && stripos($location, 'indonesia') !== false ? 'ID' : null,
            workMode: $location && stripos($location, 'remote') !== false ? 'remote' : null,
            datePosted: isset($job['first_published']) ? Carbon::parse($job['first_published'])->toDateString() : null,
            description: isset($job['content']) ? trim(strip_tags(html_entity_decode($job['content']))) : null,
            applyUrl: $job['absolute_url'],
            isOpen: true,
            extractionMethod: 'api',
            raw: array_diff_key($job, ['content' => true]),
        );
    }
}
