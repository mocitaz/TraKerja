<?php

namespace App\JobEngine\Ingest;

use App\JobEngine\Adapters\AshbyAdapter;
use App\JobEngine\Adapters\DeallsAdapter;
use App\JobEngine\Adapters\GreenhouseAdapter;
use App\JobEngine\Adapters\HimalayasAdapter;
use App\JobEngine\Adapters\JobicyAdapter;
use App\JobEngine\Adapters\JoobleAdapter;
use App\JobEngine\Adapters\KalibrrAdapter;
use App\JobEngine\Adapters\LeverAdapter;
use App\JobEngine\Adapters\PintarnyaAdapter;
use App\JobEngine\Adapters\RemotiveAdapter;
use App\JobEngine\Adapters\SmartRecruitersAdapter;
use App\JobEngine\Adapters\SourceAdapter;
use App\JobEngine\Adapters\WeWorkRemotelyAdapter;
use App\JobEngine\Adapters\WorkableAdapter;
use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\Http\RobotsPolicy;
use App\JobEngine\Verify\VerificationService;
use App\Models\CrawlRun;
use App\Models\JobSource;
use App\Models\VacancyListing;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

/** Runs one discovery pass for one source and records it in crawl_runs. */
class DiscoveryRunner
{
    /** Indonesian sources first (TraKerja 2.0 focus). */
    public const ADAPTERS = [
        'dealls' => DeallsAdapter::class,
        'pintarnya' => PintarnyaAdapter::class,
        'kalibrr' => KalibrrAdapter::class,
        'greenhouse' => GreenhouseAdapter::class,
        'lever' => LeverAdapter::class,
        'workable' => WorkableAdapter::class,
        'smartrecruiters' => SmartRecruitersAdapter::class,
        'ashby' => AshbyAdapter::class,
        'jooble' => JoobleAdapter::class,
        // Remote jobs open to applicants in Indonesia
        'remotive' => RemotiveAdapter::class,
        'jobicy' => JobicyAdapter::class,
        'himalayas' => HimalayasAdapter::class,
        'weworkremotely' => WeWorkRemotelyAdapter::class,
    ];

    public function __construct(private IngestService $ingest, private RobotsPolicy $robots) {}

    /**
     * The adapter read the whole feed (fewer items than the limit), so a listing it did not see
     * has left the feed: one miss makes it suspect, two close it.
     */
    private function markMissedFromFeed(JobSource $source, array $seenUrlHashes, array $seenExternalIds): void
    {
        VacancyListing::where('job_source_id', $source->id)
            ->whereIn('status', ['active', 'suspect'])
            ->whereNotIn('url_hash', $seenUrlHashes)
            ->where(fn ($q) => $q->whereNull('external_id')->orWhereNotIn('external_id', $seenExternalIds))
            ->with('vacancy')
            ->each(function (VacancyListing $listing) {
                $misses = $listing->consecutive_misses + 1;
                $listing->update(['consecutive_misses' => $misses, 'status' => $misses >= 2 ? 'closed' : 'suspect', 'last_checked_at' => now()]);
                if ($listing->vacancy) {
                    VerificationService::syncVacancyStatus($listing->vacancy, ['gone_from_feed']);
                }
            });
    }

    /** $refresh re-reads and rewrites known vacancies (e.g. after the stored fields change). */
    public function run(JobSource $source, int $limit, string $trigger = 'manual', ?callable $progress = null, bool $refresh = false): CrawlRun
    {
        $class = self::ADAPTERS[$source->slug] ?? throw new InvalidArgumentException("No adapter for source {$source->slug}.");
        /** @var SourceAdapter $adapter */
        $adapter = app($class);
        $http = new PoliteHttpClient($this->robots, $source->min_delay_ms);

        $run = CrawlRun::create([
            'job_source_id' => $source->id, 'run_type' => 'discover', 'trigger' => $trigger,
            'started_at' => now(), 'status' => 'running', 'worker_host' => gethostname() ?: null,
        ]);
        $counts = ['found' => 0, 'new' => 0, 'updated' => 0, 'merged' => 0, 'unchanged' => 0, 'skipped' => 0, 'failed' => 0];
        $seen = [];
        $seenExternal = [];
        $errors = [];

        // Page-based adapters skip URLs we already hold and have seen within the last day.
        $isKnown = function (string $url, ?string $lastmod) use ($source): bool {
            $listing = VacancyListing::where('url_hash', hash('sha256', IngestService::canonicalUrl($url)))
                ->where('job_source_id', $source->id)->first(['last_seen_at', 'updated_at']);
            if (! $listing) {
                return false;
            }
            if ($lastmod && $listing->updated_at && $lastmod > $listing->updated_at->toDateString()) {
                return false;
            }

            return $listing->last_seen_at && $listing->last_seen_at->gt(now()->subDay());
        };

        try {
            foreach ($adapter->discover($http, $source->config ?? [], $limit, $refresh ? null : $isKnown) as $job) {
                $counts['found']++;
                $seen[] = hash('sha256', IngestService::canonicalUrl($job->url));
                if ($job->externalId !== null) {
                    $seenExternal[] = $job->externalId;
                }
                try {
                    $counts[$this->ingest->ingest($source, $job, $run, $refresh)]++;
                } catch (Throwable $e) {
                    $counts['failed']++;
                    $errors[] = Str::limit($e->getMessage(), 200);
                }
                $progress && $progress($counts);
            }
            $status = $counts['failed'] > 0 ? 'partial' : 'success';
        } catch (Throwable $e) {
            $status = 'failed';
            $errors[] = Str::limit($e->getMessage(), 300);
        }

        $run->update([
            'finished_at' => now(),
            'status' => $status,
            'found' => $counts['found'],
            'new_listings' => $counts['new'],
            'updated_listings' => $counts['updated'] + $counts['merged'],
            'unchanged_listings' => $counts['unchanged'],
            'failed_items' => $counts['failed'] + $counts['skipped'],
            'requests' => $http->requestCount(),
            'error_summary' => $errors ? implode("\n", array_slice(array_unique($errors), 0, 10)) : null,
        ]);
        if ($status !== 'failed' && $counts['found'] < $limit && in_array($source->kind, ['api', 'ats', 'rss'], true) && ! $refresh) {
            $this->markMissedFromFeed($source, $seen, $seenExternal);
        }
        app(SourceHealth::class)->afterRun($source->refresh(), $run->refresh(), $limit);

        return $run;
    }
}
