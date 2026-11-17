<?php

namespace App\JobEngine\Ingest;

use App\Models\CrawlRun;
use App\Models\JobSource;
use Illuminate\Support\Facades\Log;

/**
 * After every discovery run: decide the source's health, adapt how often it is crawled,
 * and alert admins when health changes (TraKerja 2.0, Fase 4).
 */
class SourceHealth
{
    public function afterRun(JobSource $source, CrawlRun $run, int $limit): void
    {
        [$health, $reason] = $this->evaluate($source, $run);
        // Self-healing: three failed runs in a row pause the source for 6 hours instead of retrying every 2.
        $lastThree = CrawlRun::where('job_source_id', $source->id)->where('run_type', 'discover')->latest('id')->limit(3)->pluck('status');
        $paused = $health === 'failing' && $lastThree->count() === 3 && $lastThree->every(fn ($s) => $s === 'failed');
        if ($paused) {
            [$health, $reason] = ['paused', 'Paused for 6 hours after 3 failed runs in a row. '.$reason];
        }
        $interval = $this->nextInterval($source, $run, $health);
        $previous = $source->health;

        $source->update([
            'health' => $health,
            'health_reason' => $reason,
            'discovery_interval_minutes' => $interval,
            'next_discovery_at' => now()->addMinutes(match ($health) { 'failing' => 120, 'paused' => 360, default => $interval }),
            'last_success_at' => $run->status === 'failed' ? $source->last_success_at : now(),
        ]);

        if ($previous !== $health) {
            $this->alert($source, $previous, $health, $reason, $run);
        }
    }

    /** @return array{0: string, 1: ?string} */
    private function evaluate(JobSource $source, CrawlRun $run): array
    {
        if ($run->status === 'failed') {
            return ['failing', 'Run failed: '.mb_substr((string) $run->error_summary, 0, 180)];
        }
        if ($run->found > 0 && $run->failed_items / $run->found > 0.3) {
            return ['degraded', sprintf('%d%% of listings could not be processed (the site layout may have changed)', round($run->failed_items / $run->found * 100))];
        }
        if ($run->found === 0) {
            $before = CrawlRun::where('job_source_id', $source->id)->where('run_type', 'discover')
                ->whereKeyNot($run->id)->where('status', '!=', 'failed')->latest('id')->value('found');
            if ($before > 0) {
                return ['degraded', "0 listings found; the previous run found {$before}"];
            }
        }

        return ['ok', null];
    }

    /** Busy sources are crawled more often; quiet ones back off, between 3 and 48 hours. */
    private function nextInterval(JobSource $source, CrawlRun $run, string $health): int
    {
        $current = $source->discovery_interval_minutes ?: 720;
        $new = $run->new_listings;

        $interval = match (true) {
            $health !== 'ok' => $current,
            $new >= 20 => 360,
            $new >= 5 => 720,
            $new >= 1 => 1080,
            default => (int) round($current * 1.5),
        };

        return max(180, min(2880, $interval));
    }

    private function alert(JobSource $source, ?string $from, string $to, ?string $reason, CrawlRun $run): void
    {
        Log::warning('Job source health changed', ['source' => $source->slug, 'from' => $from, 'to' => $to, 'reason' => $reason]);
        $alerts = app(EngineAlerts::class);
        $key = 'source-health:'.$source->slug;
        $runLine = "{$run->status} · {$run->found} read, {$run->new_listings} new, {$run->failed_items} failed";

        if ($to === 'ok') {
            $alerts->resolve($key, "{$source->name} is healthy again", ['Previous state' => $from, 'Last run' => $runLine]);

            return;
        }
        $alerts->raise($key, $to === 'degraded' ? EngineAlerts::WARNING : EngineAlerts::CRITICAL, "{$source->name}: ".(['failing' => 'scraper failing', 'degraded' => 'scraper degraded', 'paused' => 'scraper paused'][$to] ?? $to), [
            'Reason' => $reason,
            'Last run' => $runLine,
            'Next attempt' => $source->next_discovery_at?->timezone('Asia/Jakarta')->format('d M, H:i').' WIB',
        ]);
    }
}
