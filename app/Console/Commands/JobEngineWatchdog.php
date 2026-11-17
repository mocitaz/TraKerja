<?php

namespace App\Console\Commands;

use App\JobEngine\Ai\AiGateway;
use App\JobEngine\Ingest\EngineAlerts;
use App\JobEngine\Ingest\EngineMonitor;
use App\Models\CrawlRun;
use App\Models\JobSource;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Every 10 minutes: look for the job-engine problems nobody notices until the data goes stale, and raise or
 * resolve a Telegram alert for each one (see EngineAlerts for de-duplication).
 */
class JobEngineWatchdog extends Command
{
    protected $signature = 'jobs:watchdog {--print : Print the problems instead of sending alerts}';

    protected $description = 'Check queue workers, scraper runs, verification and AI budget; alert on Telegram';

    /** Queues served by the trakerja-worker Supervisor program. */
    private const QUEUES = ['discovery', 'ingest', 'ai', 'default'];

    public function handle(EngineAlerts $alerts, EngineMonitor $monitor, AiGateway $ai): int
    {
        $problems = [];
        foreach ([$this->queues(), $this->failedJobs(), $this->stuckRuns(), $this->staleSources(), $this->verification(), $this->anomalies($monitor), $this->aiBudget($ai), $this->aiErrors()] as $found) {
            $problems += $found;
        }

        if ($this->option('print')) {
            foreach ($problems as $key => [$level, $title, $details]) {
                $this->line(strtoupper($level)." [{$key}] {$title}");
                foreach ($details as $label => $value) {
                    $this->line("    {$label}: {$value}");
                }
            }
            $this->info($problems ? count($problems).' problem(s).' : 'All clear.');

            return self::SUCCESS;
        }

        foreach ($problems as $key => [$level, $title, $details]) {
            $alerts->raise("watchdog:{$key}", $level, $title, $details);
        }
        foreach ($alerts->openKeys() as $open) {
            if (str_starts_with($open, 'watchdog:') && ! isset($problems[Str::after($open, 'watchdog:')])) {
                $alerts->resolve($open);
            }
        }
        $this->info($problems ? count($problems).' open problem(s).' : 'All clear.');

        return self::SUCCESS;
    }

    /** Jobs waiting too long: the worker is down, stuck, or too slow for the load. */
    private function queues(): array
    {
        $out = [];
        $waiting = DB::table('jobs')->whereNull('reserved_at')->where('available_at', '<=', now()->subMinutes(15)->timestamp)
            ->selectRaw('queue, count(*) as n, min(available_at) as oldest')->groupBy('queue')->get();
        foreach ($waiting as $q) {
            $minutes = (int) floor((now()->timestamp - (int) $q->oldest) / 60);
            $out["queue:{$q->queue}"] = [$minutes >= 30 ? EngineAlerts::CRITICAL : EngineAlerts::WARNING, "Queue \"{$q->queue}\" is not being processed", [
                'Waiting jobs' => number_format($q->n),
                'Oldest job waiting' => "{$minutes} min",
                'Likely cause' => in_array($q->queue, self::QUEUES, true) ? 'trakerja-worker is down or stuck' : 'no worker listens to this queue',
                'Check' => 'sudo supervisorctl status trakerja-worker:*',
            ]];
        }

        return $out;
    }

    private function failedJobs(): array
    {
        $recent = DB::table('failed_jobs')->where('failed_at', '>=', now()->subHour())->get(['payload', 'exception']);
        if ($recent->count() < 3) {
            return [];
        }
        $classes = $recent->map(fn ($j) => class_basename((string) (json_decode($j->payload, true)['displayName'] ?? '?')))->countBy()->sortDesc();
        $first = Str::of((string) $recent->first()->exception)->before("\n")->limit(160);

        return ['failed_jobs' => [EngineAlerts::WARNING, "{$recent->count()} queue jobs failed in the last hour", [
            'Jobs' => $classes->map(fn ($n, $c) => "{$c} ×{$n}")->implode(', '),
            'Latest error' => (string) $first,
            'Check' => 'php artisan queue:failed',
        ]]];
    }

    /** Runs still "running" long after the job timeout: the worker died mid-run. */
    private function stuckRuns(): array
    {
        $stuck = CrawlRun::with('source')->where('status', 'running')->where('started_at', '<', now()->subMinutes(30))->get();
        if ($stuck->isEmpty()) {
            return [];
        }

        return ['stuck_runs' => [EngineAlerts::WARNING, "{$stuck->count()} scraper run(s) stuck in \"running\"", [
            'Runs' => $stuck->map(fn ($r) => ($r->source?->name ?? 'all sources').' #'.$r->id.' since '.$r->started_at->timezone('Asia/Jakarta')->format('H:i'))->implode(', '),
            'Likely cause' => 'the worker was killed or restarted during the run',
        ]]];
    }

    /** Active sources without a successful discovery for a day, even if no error was raised. */
    private function staleSources(): array
    {
        if (! config('job_sources.schedule_enabled')) {
            return [];
        }
        $out = [];
        $last = CrawlRun::where('run_type', 'discover')->whereIn('status', ['success', 'partial'])
            ->selectRaw('job_source_id, max(finished_at) as at')->groupBy('job_source_id')->pluck('at', 'job_source_id');
        foreach (JobSource::where('is_active', true)->where('created_at', '<', now()->subDay())->get() as $source) {
            $at = isset($last[$source->id]) ? Carbon::parse($last[$source->id]) : null;
            if ($at && $at->gt(now()->subDay())) {
                continue;
            }
            $lastRun = CrawlRun::where('job_source_id', $source->id)->latest('id')->first();
            $out["source:{$source->slug}"] = [$source->health === 'failing' ? EngineAlerts::CRITICAL : EngineAlerts::WARNING, "{$source->name}: no successful run in 24 hours", [
                'Last success' => $at ? $at->timezone('Asia/Jakarta')->format('d M, H:i').' WIB' : 'never',
                'Health' => $source->health.($source->health_reason ? ' · '.Str::limit($source->health_reason, 120) : ''),
                'Last run' => $lastRun ? "{$lastRun->status}, ".$lastRun->started_at->timezone('Asia/Jakarta')->format('d M, H:i').' WIB'.($lastRun->error_summary ? ' · '.Str::limit($lastRun->error_summary, 120) : '') : 'none',
            ]];
        }

        return $out;
    }

    private function verification(): array
    {
        if (! config('job_sources.schedule_enabled')) {
            return [];
        }
        $last = CrawlRun::where('run_type', 'verify')->latest('id')->first();
        if ($last && $last->started_at->gt(now()->subHours(3))) {
            return [];
        }

        return ['verify' => [EngineAlerts::WARNING, 'Job verification has not run for 3 hours', [
            'Last run' => $last ? $last->started_at->timezone('Asia/Jakarta')->format('d M, H:i').' WIB' : 'never',
            'Impact' => 'closed jobs may still be shown as open',
            'Check' => 'php artisan schedule:list and the cron entry for schedule:run',
        ]]];
    }

    private function anomalies(EngineMonitor $monitor): array
    {
        $out = [];
        foreach ($monitor->anomalies() as $a) {
            $out['anomaly:'.Str::slug($a['source'])] = [EngineAlerts::WARNING, "{$a['source']}: new jobs dropped sharply", [
                'Last 24 hours' => "{$a['today']} new jobs",
                'Usual' => "~{$a['average']} per day",
                'Likely cause' => 'the site layout or API changed; check the run history',
            ]];
        }

        return $out;
    }

    private function aiBudget(AiGateway $ai): array
    {
        $budget = (float) config('services.openai.jobs.daily_budget_usd');
        $spent = $ai->spentToday();
        if ($budget <= 0 || $spent < .8 * $budget) {
            return [];
        }
        $errors = DB::table('ai_calls')->where('created_at', '>=', now()->startOfDay())->where('status', 'error')->count();

        return ['ai_budget' => [$spent >= $budget ? EngineAlerts::CRITICAL : EngineAlerts::WARNING, $spent >= $budget ? 'AI daily budget reached: enrichment paused until midnight' : 'AI cost is at 80% of the daily budget', [
            'Spent today' => '$'.number_format($spent, 3).' of $'.number_format($budget, 2),
            'Failed AI calls today' => $errors ?: null,
        ]]];
    }

    /** Most AI calls failing in the last hour, e.g. a revoked API key: enrichment silently stops. */
    private function aiErrors(): array
    {
        $calls = DB::table('ai_calls')->where('created_at', '>=', now()->subHour())->whereIn('status', ['ok', 'error', 'invalid_output'])->get(['status', 'response']);
        $errors = $calls->where('status', 'error');
        if ($errors->count() < 3 || $errors->count() < $calls->count() * .5) {
            return [];
        }
        $sample = (string) $errors->last()->response;
        $badKey = str_contains($sample, 'invalid_api_key') || str_contains($sample, '"status": 401') || str_contains($sample, 'Incorrect API key');

        return ['ai_errors' => [EngineAlerts::CRITICAL, $badKey ? 'OpenAI rejected the API key: AI enrichment has stopped' : 'Most AI calls are failing', [
            'Failed calls (1 hour)' => $errors->count().' of '.$calls->count(),
            'Error' => Str::limit(preg_replace('/sk-[A-Za-z0-9_\-*]+/', 'sk-…', preg_match('/"message"\s*:\s*"([^"]+)/', stripslashes($sample), $m) ? $m[1] : $sample), 160),
            'Check' => $badKey ? 'OPENAI_JOBS_API_KEY in .env, then php artisan optimize' : 'storage/logs and the OpenAI status page',
        ]]];
    }
}
