<?php

namespace App\JobEngine\Verify;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\Http\RobotsPolicy;
use App\Models\CrawlRun;
use App\Models\Vacancy;
use App\Models\VacancyListing;
use Illuminate\Support\Facades\DB;

/**
 * Runs the verifier over listings that are due, applies the state machine
 * (active -> suspect -> closed; back to active when a check passes), records every check in
 * vacancy_checks, and derives the vacancy status from all of its listings.
 */
class VerificationService
{
    public function __construct(private ListingVerifier $verifier, private RobotsPolicy $robots) {}

    /** @return array<string, int> counts per verdict */
    public function run(int $budget, ?callable $progress = null): array
    {
        $run = CrawlRun::create(['run_type' => 'verify', 'trigger' => 'manual', 'started_at' => now(), 'status' => 'running', 'worker_host' => gethostname() ?: null]);
        $clients = [];
        $counts = ['open' => 0, 'suspect' => 0, 'closed' => 0, 'blocked' => 0];
        // Free check first: jobs whose own deadline passed are closed without fetching anything.
        $counts['closed'] += $this->closeExpired();

        $listings = VacancyListing::query()->with('source')
            ->whereIn('status', ['active', 'suspect'])
            ->where(fn ($q) => $q->whereNull('next_check_at')->orWhere('next_check_at', '<=', now()))
            ->orderByRaw("CASE status WHEN 'suspect' THEN 0 ELSE 1 END") // settle suspects first
            ->orderBy('next_check_at')
            ->limit($budget)
            ->get();

        foreach ($listings as $listing) {
            $http = $clients[$listing->job_source_id] ??= new PoliteHttpClient($this->robots, $listing->source?->min_delay_ms ?? 2000);
            $check = $this->verifier->check($listing, $http);
            $this->apply($listing, $check);
            $counts[$check['verdict']]++;
            $progress && $progress($counts);
        }

        $run->update([
            'finished_at' => now(), 'status' => 'success', 'found' => $listings->count(),
            'updated_listings' => $counts['suspect'] + $counts['closed'], 'unchanged_listings' => $counts['open'],
            'failed_items' => $counts['blocked'], 'requests' => array_sum(array_map(fn ($c) => $c->requestCount(), $clients)),
        ]);

        return $counts;
    }

    /**
     * Close open vacancies whose application deadline (valid_through) passed more than a day ago. The page check
     * only notices this for JSON-LD sources, so API and ATS jobs could otherwise stay "open" past their deadline.
     * Updated one by one so the closed-vacancy notifications (Vacancy::booted) still fire.
     */
    public function closeExpired(): int
    {
        $closed = 0;
        \App\Models\Vacancy::whereIn('status', ['active', 'suspect'])->whereNotNull('valid_through')
            ->where('valid_through', '<', now()->subDay())->chunkById(200, function ($vacancies) use (&$closed) {
                foreach ($vacancies as $vacancy) {
                    $vacancy->update(['status' => 'closed', 'closed_at' => now(), 'closed_reason' => 'valid_through']);
                    VacancyListing::where('vacancy_id', $vacancy->id)->whereIn('status', ['active', 'suspect'])->update(['status' => 'closed', 'last_checked_at' => now()]);
                    $closed++;
                }
            });

        return $closed;
    }

    public function apply(VacancyListing $listing, array $check): void
    {
        DB::table('vacancy_checks')->insert([
            'vacancy_listing_id' => $listing->id, 'checked_at' => now(), 'http_status' => $check['http_status'],
            'final_url' => $check['final_url'], 'latency_ms' => $check['latency_ms'],
            'signals' => json_encode($check['signals']), 'verdict' => $check['verdict'],
        ]);

        $status = match ($check['verdict']) {
            'closed' => 'closed',
            // A second medium signal on a later check closes the listing.
            'suspect' => $listing->status === 'suspect' ? 'closed' : 'suspect',
            'open' => 'active',
            default => $listing->status, // blocked: no evidence either way
        };

        $listing->update([
            'status' => $status,
            'last_checked_at' => now(),
            'next_check_at' => $check['verdict'] === 'blocked' ? now()->addHours(6) : $this->nextCheck($listing, $status),
            'consecutive_misses' => $check['verdict'] === 'open' ? 0 : $listing->consecutive_misses,
        ]);

        if ($listing->vacancy) {
            self::syncVacancyStatus($listing->vacancy, $check['signals']);
        }
    }

    /** A vacancy is active while any listing is active, suspect while any is suspect, closed when all are closed. */
    public static function syncVacancyStatus(Vacancy $vacancy, array $signals = []): void
    {
        if ($vacancy->status === 'archived') {
            return;
        }
        $statuses = $vacancy->listings()->pluck('status');
        $status = match (true) {
            $statuses->contains('active') => 'active',
            $statuses->contains('suspect') => 'suspect',
            $statuses->isEmpty() => $vacancy->status,
            default => 'closed',
        };
        $vacancy->update([
            'status' => $status,
            'last_verified_at' => now(),
            'last_seen_open_at' => $status === 'active' ? now() : $vacancy->last_seen_open_at,
            'closed_at' => $status === 'closed' ? ($vacancy->closed_at ?? now()) : null,
            'closed_reason' => $status === 'closed' ? ($vacancy->closed_reason ?? ($signals[0] ?? 'all_sources_closed')) : null,
        ]);
    }

    /** Tracked/bookmarked: 12 h. Younger than 14 days: 24 h. Older: 60 h. */
    private function nextCheck(VacancyListing $listing, string $status): ?\Illuminate\Support\Carbon
    {
        if ($status === 'closed') {
            return null;
        }
        if ($status === 'suspect') {
            return now()->addHours(6);
        }
        $vacancyId = $listing->vacancy_id;
        $tracked = $vacancyId && (DB::table('job_applications')->where('vacancy_id', $vacancyId)->exists()
            || DB::table('job_bookmarks')->where('vacancy_id', $vacancyId)->exists());
        if ($tracked) {
            return now()->addHours(12);
        }

        return $listing->first_seen_at && $listing->first_seen_at->gt(now()->subDays(14)) ? now()->addDay() : now()->addHours(60);
    }
}
