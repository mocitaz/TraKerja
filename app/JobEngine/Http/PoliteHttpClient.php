<?php

namespace App\JobEngine\Http;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * The only way the job engine talks to other websites:
 * - honest User-Agent (TraKerjaBot with a contact URL), never browser impersonation;
 * - robots.txt is checked before every request;
 * - a minimum delay between requests to the same host (robots crawl-delay if larger);
 * - backs off and retries on 429 and 5xx; never retries a 403 (a block is a "no").
 */
class PoliteHttpClient
{
    private int $requests = 0;

    public function __construct(private RobotsPolicy $robots, private int $minDelayMs = 2000) {}

    public function withMinDelay(int $ms): self
    {
        $clone = clone $this;
        $clone->minDelayMs = $ms;

        return $clone;
    }

    public function requestCount(): int
    {
        return $this->requests;
    }

    /**
     * @param  array<string, string>  $headers  e.g. If-None-Match / If-Modified-Since for conditional requests
     */
    public function get(string $url, array $headers = [], int $attempts = 3, int $timeout = 30): Response
    {
        if (! $this->robots->allows($url)) {
            throw new DisallowedByRobots($url);
        }

        for ($attempt = 1; ; $attempt++) {
            $this->waitForTurn($url);
            $this->requests++;
            $response = Http::withUserAgent(config('job_sources.user_agent'))
                ->withHeaders($headers + ['Accept' => 'text/html,application/json,application/xml;q=0.9,*/*;q=0.8', 'Accept-Language' => 'id-ID,id;q=0.9,en;q=0.7'])
                ->timeout($timeout)
                ->get($url);

            $retryable = $response->status() === 429 || $response->serverError();
            if (! $retryable || $attempt >= $attempts) {
                return $response;
            }

            $wait = (int) ($response->header('Retry-After') ?: 2 ** $attempt * 2);
            usleep(min($wait, 60) * 1_000_000);
        }
    }

    /** Public APIs can return large payloads (Lever: ~1.2 MB in ~25 s), so allow longer. */
    public function getJson(string $url, array $headers = [], int $timeout = 90): array
    {
        $response = $this->get($url, $headers + ['Accept' => 'application/json'], 3, $timeout);
        if (! $response->successful()) {
            throw new RuntimeException("HTTP {$response->status()} for {$url}");
        }

        return $response->json() ?? [];
    }

    private function waitForTurn(string $url): void
    {
        $host = parse_url($url, PHP_URL_HOST);
        $delayMs = max($this->minDelayMs, (int) (($this->robots->crawlDelay($url) ?? 0) * 1000));
        $key = "polite-http:last:{$host}";

        $last = (float) Cache::get($key, 0);
        $waitMs = (int) ($last + $delayMs - microtime(true) * 1000);
        if ($waitMs > 0) {
            usleep($waitMs * 1000);
        }
        Cache::put($key, microtime(true) * 1000, now()->addMinutes(10));
    }
}
