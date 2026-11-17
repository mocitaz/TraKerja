<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\DisallowedByRobots;
use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\Parsing\JobPostingJsonLd;

/**
 * Sources that list vacancy URLs in a sitemap and embed a schema.org JobPosting on each page.
 * Newest sitemap entries (by <lastmod>) are read first; known, unchanged URLs are not fetched.
 */
abstract class SitemapJsonLdAdapter implements SourceAdapter
{
    /** Regex a sitemap <loc> must match to count as a vacancy page. */
    abstract protected function vacancyUrlPattern(): string;

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $entries = $this->sitemapEntries($http, $config['discovery']);
        $yielded = 0;

        foreach ($entries as [$url, $lastmod]) {
            if ($yielded >= $limit) {
                return;
            }
            if ($isKnown && $isKnown($url, $lastmod)) {
                continue;
            }
            try {
                $response = $http->get($url);
            } catch (DisallowedByRobots) {
                continue;
            }
            if (! $response->successful()) {
                continue;
            }
            $posting = JobPostingJsonLd::find($response->body());
            if (! $posting) {
                continue;
            }
            $job = JobPostingJsonLd::toScrapedJob($posting, $this->slug(), $url);
            $job->externalId ??= $this->externalIdFromUrl($url);
            $yielded++;

            yield $job;
        }
    }

    protected function externalIdFromUrl(string $url): ?string
    {
        return null;
    }

    /** @return array<int, array{0: string, 1: ?string}> url and lastmod, newest first */
    protected function sitemapEntries(PoliteHttpClient $http, string $sitemapUrl): array
    {
        $xml = $http->get($sitemapUrl)->body();
        preg_match_all('#<url>(.*?)</url>#s', $xml, $blocks);
        $entries = [];
        foreach ($blocks[1] as $block) {
            if (! preg_match('#<loc>\s*([^<\s]+)\s*</loc>#', $block, $loc) || ! preg_match($this->vacancyUrlPattern(), $loc[1])) {
                continue;
            }
            $lastmod = preg_match('#<lastmod>\s*([^<\s]+)\s*</lastmod>#', $block, $lm) ? $lm[1] : null;
            $entries[] = [html_entity_decode($loc[1]), $lastmod];
        }

        // Newest first when the sitemap says; otherwise keep the source's order.
        if (array_filter(array_column($entries, 1))) {
            usort($entries, fn ($a, $b) => strcmp((string) $b[1], (string) $a[1]));
        }

        return $entries;
    }
}
