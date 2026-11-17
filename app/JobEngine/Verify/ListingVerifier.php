<?php

namespace App\JobEngine\Verify;

use App\JobEngine\Http\DisallowedByRobots;
use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\Intelligence\TextSimilarity;
use App\JobEngine\Parsing\JobPostingJsonLd;
use App\Models\VacancyListing;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Throwable;

/**
 * Checks whether one listing is still open (TraKerja 2.0, Fase 4).
 *
 * Signals: strong ones close a listing at once; a medium one makes it "suspect" and a second
 * medium one (on a later check) closes it. Being blocked (403/429/5xx/timeout) is never
 * evidence that a job closed, so it changes nothing and the check is retried later.
 */
class ListingVerifier
{
    public const STRONG = ['http_gone', 'valid_through_passed', 'source_expired'];

    public const MEDIUM = ['redirected', 'posting_removed', 'title_replaced', 'gone_from_feed'];

    /** @return array{verdict: 'open'|'suspect'|'closed'|'blocked', signals: array<int, string>, http_status: ?int, final_url: ?string, latency_ms: int} */
    public function check(VacancyListing $listing, PoliteHttpClient $http): array
    {
        $kind = $listing->source?->kind;
        $started = microtime(true);
        $result = fn (string $verdict, array $signals, ?int $status = null, ?string $final = null) => [
            'verdict' => $verdict, 'signals' => $signals, 'http_status' => $status, 'final_url' => $final,
            'latency_ms' => (int) ((microtime(true) - $started) * 1000),
        ];

        // Feed-based sources are verified by feed membership during discovery (see DiscoveryRunner).
        if (! in_array($kind, ['jsonld', 'next_data', 'html'], true)) {
            return $result($listing->consecutive_misses >= 1 ? 'suspect' : 'open', $listing->consecutive_misses >= 1 ? ['gone_from_feed'] : []);
        }

        try {
            $response = $http->get($listing->url, attempts: 1);
        } catch (DisallowedByRobots) {
            return $result('blocked', ['robots_disallowed']);
        } catch (Throwable) {
            return $result('blocked', ['network_error']);
        }

        $status = $response->status();
        $final = (string) ($response->effectiveUri() ?? $listing->url);
        if (in_array($status, [404, 410], true)) {
            return $result('closed', ['http_gone'], $status, $final);
        }
        if ($status === 403 || $status === 429 || $status >= 500) {
            return $result('blocked', ["http_{$status}"], $status, $final);
        }

        $signals = [];
        if ($this->redirectedAway($listing->url, $final)) {
            $signals[] = 'redirected';
        }

        $html = $response->body();
        if ($kind === 'next_data') {
            $data = preg_match('#<script id="__NEXT_DATA__"[^>]*>(.*?)</script>#s', $html, $m) ? json_decode($m[1], true) : null;
            if (Arr::get($data, 'props.pageProps.isExpired') === true) {
                $signals[] = 'source_expired';
            }
            if (! is_array(Arr::get($data, 'props.pageProps.job'))) {
                $signals[] = 'posting_removed';
            }
        } elseif ($kind === 'jsonld') {
            $posting = JobPostingJsonLd::find($html);
            if (! $posting) {
                $signals[] = 'posting_removed';
            } else {
                $validThrough = $posting['validThrough'] ?? null;
                if (is_string($validThrough) && $this->isPast($validThrough)) {
                    $signals[] = 'valid_through_passed';
                }
                // Some boards reuse a URL for a different job (seen on Dealls).
                $title = (string) ($posting['title'] ?? '');
                if ($title !== '' && $listing->raw_title && TextSimilarity::titleScore($title, $listing->raw_title) < 0.5) {
                    $signals[] = 'title_replaced';
                }
            }
        }

        $verdict = match (true) {
            (bool) array_intersect($signals, self::STRONG) => 'closed',
            $signals !== [] => 'suspect',
            default => 'open',
        };

        return $result($verdict, $signals, $status, $final);
    }

    private function redirectedAway(string $original, string $final): bool
    {
        $a = parse_url($original);
        $b = parse_url($final);
        if (($a['host'] ?? '') !== ($b['host'] ?? '')) {
            return true;
        }

        return rtrim($a['path'] ?? '/', '/') !== rtrim($b['path'] ?? '/', '/');
    }

    private function isPast(string $date): bool
    {
        try {
            return Carbon::parse($date)->endOfDay()->isPast();
        } catch (Throwable) {
            return false;
        }
    }
}
