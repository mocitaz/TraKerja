<?php

namespace App\JobEngine\Http;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * robots.txt rules for our bot, cached per host for a day. Uses the group for our product token
 * ("TraKerjaBot") when present, otherwise "*". Longest matching rule wins; Allow wins ties.
 * Supports the "*" and "$" wildcards. An unreachable or missing robots.txt allows crawling.
 */
class RobotsPolicy
{
    public const PRODUCT_TOKEN = 'trakerjabot';

    public function allows(string $url): bool
    {
        $parts = parse_url($url);
        $path = ($parts['path'] ?? '/').(isset($parts['query']) ? '?'.$parts['query'] : '');
        $rules = $this->rulesFor(($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? ''));

        $verdict = true;
        $bestLength = -1;
        foreach ($rules as [$type, $pattern]) {
            if ($pattern === '') {
                continue; // "Disallow:" with no value allows everything
            }
            if ($this->matches($pattern, $path) && (strlen($pattern) > $bestLength || ($type === 'allow' && strlen($pattern) === $bestLength))) {
                $bestLength = strlen($pattern);
                $verdict = $type === 'allow';
            }
        }

        return $verdict;
    }

    public function crawlDelay(string $url): ?float
    {
        $host = parse_url($url, PHP_URL_HOST);

        return Cache::get("robots-delay:{$host}");
    }

    /** @return array<int, array{0: string, 1: string}> */
    private function rulesFor(string $origin): array
    {
        $host = parse_url($origin, PHP_URL_HOST);

        return Cache::remember("robots:{$host}", now()->addDay(), function () use ($origin, $host) {
            try {
                $response = Http::withUserAgent(config('job_sources.user_agent'))->timeout(15)->get($origin.'/robots.txt');
            } catch (\Throwable) {
                return [];
            }
            if (! $response->successful()) {
                return [];
            }

            [$rules, $delay] = $this->parse($response->body());
            if ($delay !== null) {
                Cache::put("robots-delay:{$host}", $delay, now()->addDay());
            }

            return $rules;
        });
    }

    /** @return array{0: array<int, array{0: string, 1: string}>, 1: ?float} */
    public function parse(string $body): array
    {
        $groups = [];
        $agents = [];
        $inRules = false;
        foreach (preg_split('/\r\n|\r|\n/', $body) as $line) {
            $line = trim(preg_replace('/#.*/', '', $line));
            if (! str_contains($line, ':')) {
                continue;
            }
            [$field, $value] = array_map('trim', explode(':', $line, 2));
            $field = strtolower($field);

            if ($field === 'user-agent') {
                if ($inRules) {
                    $agents = [];
                    $inRules = false;
                }
                $agents[] = strtolower($value);

                continue;
            }
            if (in_array($field, ['allow', 'disallow', 'crawl-delay'], true)) {
                $inRules = true;
                foreach ($agents as $agent) {
                    $groups[$agent][] = [$field, $value];
                }
            }
        }

        $chosen = $groups[self::PRODUCT_TOKEN] ?? $groups['*'] ?? [];
        $rules = [];
        $delay = null;
        foreach ($chosen as [$field, $value]) {
            if ($field === 'crawl-delay') {
                $delay = is_numeric($value) ? (float) $value : null;
            } else {
                $rules[] = [$field, $value];
            }
        }

        return [$rules, $delay];
    }

    private function matches(string $pattern, string $path): bool
    {
        $anchored = str_ends_with($pattern, '$');
        $regex = preg_quote(rtrim($pattern, '$'), '#');
        $regex = str_replace('\*', '.*', $regex);

        return (bool) preg_match('#^'.$regex.($anchored ? '$' : '').'#', $path);
    }
}
