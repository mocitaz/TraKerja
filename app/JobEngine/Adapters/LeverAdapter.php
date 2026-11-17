<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/** Lever public postings API for the boards in config('boards'), filtered to Indonesian locations. */
class LeverAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'lever';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $yielded = 0;
        foreach ($config['boards'] ?? [] as $board) {
            foreach ($http->getJson("https://api.lever.co/v0/postings/{$board}?mode=json") as $post) {
                $location = Arr::get($post, 'categories.location', '');
                if (isset($config['location_filter']) && ! preg_match($config['location_filter'], (string) $location)) {
                    continue;
                }
                if ($yielded++ >= $limit) {
                    return;
                }

                yield new ScrapedJob(
                    source: 'lever',
                    url: $post['hostedUrl'],
                    externalId: $board.'-'.$post['id'],
                    title: $post['text'] ?? null,
                    company: Str::headline($board),
                    locationText: $location ?: null,
                    city: $location ? trim(explode(',', $location)[0]) : null,
                    country: 'ID',
                    employmentType: Vocabulary::employmentType(Arr::get($post, 'categories.commitment')),
                    workMode: match ($post['workplaceType'] ?? null) { 'remote' => 'remote', 'hybrid' => 'hybrid', 'on-site', 'onsite' => 'onsite', default => null },
                    datePosted: isset($post['createdAt']) ? date('Y-m-d', intdiv((int) $post['createdAt'], 1000)) : null,
                    description: $post['descriptionPlain'] ?? null,
                    applyUrl: $post['applyUrl'] ?? $post['hostedUrl'],
                    isOpen: true,
                    extractionMethod: 'api',
                    raw: Arr::except($post, ['description', 'descriptionPlain', 'lists', 'additional', 'additionalPlain']),
                );
            }
        }
    }
}
