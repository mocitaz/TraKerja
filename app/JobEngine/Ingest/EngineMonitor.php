<?php

namespace App\JobEngine\Ingest;

use App\Models\CrawlRun;
use App\Models\JobSource;
use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;

/** Shared health checks for the admin overview, the daily report and Telegram alerts. */
class EngineMonitor
{
    /**
     * Sources whose new-listing volume dropped sharply: the usual failure mode of a changed page layout,
     * which often raises no error at all.
     *
     * @return array<int, array{source: string, today: int, average: float}>
     */
    public function anomalies(): array
    {
        $out = [];
        foreach (JobSource::where('is_active', true)->get() as $source) {
            $runs = CrawlRun::where('job_source_id', $source->id)->where('run_type', 'discover')->where('started_at', '>=', now()->subDays(8));
            $today = (int) (clone $runs)->where('started_at', '>=', now()->subDay())->sum('new_listings');
            $before = (clone $runs)->where('started_at', '<', now()->subDay())->get(['started_at', 'new_listings'])
                ->groupBy(fn ($r) => $r->started_at->toDateString())->map->sum('new_listings');
            if ($before->count() < 3) {
                continue; // not enough history to judge
            }
            $average = $before->avg();
            if ($average >= 5 && $today < $average * 0.2) {
                $out[] = ['source' => $source->name, 'today' => $today, 'average' => round($average, 1)];
            }
        }

        return $out;
    }

    /** Figures for the daily report. */
    public function summary(): array
    {
        $since = now()->subDay();

        return [
            'open' => Vacancy::visible()->count(),
            'new' => Vacancy::visible()->where('first_seen_at', '>=', $since)->count(),
            'closed' => Vacancy::where('status', 'closed')->where('closed_at', '>=', $since)->count(),
            'runs' => CrawlRun::where('started_at', '>=', $since)->count(),
            'failed_runs' => CrawlRun::where('started_at', '>=', $since)->where('status', 'failed')->count(),
            'problem_sources' => JobSource::where('is_active', true)->whereIn('health', ['failing', 'degraded', 'paused'])->pluck('health', 'name')->all(),
            'ai_usd' => (float) DB::table('ai_calls')->where('created_at', '>=', $since)->sum('cost_usd'),
            'failed_jobs' => DB::table('failed_jobs')->where('failed_at', '>=', $since)->count(),
            'pending_pairs' => DB::table('dedup_reviews')->where('status', 'pending')->count(),
            'reports' => DB::table('vacancy_reports')->whereNull('resolved_at')->where('created_at', '>=', $since)->count(),
            'anomalies' => $this->anomalies(),
        ];
    }
}
