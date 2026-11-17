<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * Jooble official REST API (free key, https://jooble.org/api/about), Indonesian locations.
 * Does nothing until JOOBLE_API_KEY is set. Jooble aggregates many boards, so its listings
 * overlap with others; the matcher (Fase 3) folds them into existing vacancies.
 */
class JoobleAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'jooble';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $key = config('services.jooble.key');
        if (! $key) {
            return;
        }
        $yielded = 0;
        foreach ($config['keywords'] ?? ['admin', 'sales', 'staff', 'engineer', 'marketing', 'finance'] as $keyword) {
            $jobs = Http::withUserAgent(config('job_sources.user_agent'))->timeout(60)
                ->post("https://jooble.org/api/{$key}", ['keywords' => $keyword, 'location' => 'Indonesia', 'page' => 1])
                ->json('jobs', []);
            foreach ($jobs as $job) {
                if ($yielded++ >= $limit) {
                    return;
                }

                yield new ScrapedJob(
                    source: 'jooble', url: $job['link'], externalId: (string) ($job['id'] ?? md5($job['link'])),
                    title: isset($job['title']) ? trim(strip_tags($job['title'])) : null, company: $job['company'] ?? null,
                    locationText: $job['location'] ?? null, city: $job['location'] ?? null, country: 'ID',
                    employmentType: Vocabulary::employmentType($job['type'] ?? null), salaryText: ($job['salary'] ?? null) ?: null,
                    datePosted: isset($job['updated']) ? Carbon::parse($job['updated'])->toDateString() : null,
                    description: isset($job['snippet']) ? trim(strip_tags(html_entity_decode($job['snippet']))) : null,
                    applyUrl: $job['link'], extractionMethod: 'api', raw: $job,
                );
            }
            usleep(1_000_000);
        }
    }
}
