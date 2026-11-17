<?php

namespace App\Console\Commands;

use App\JobEngine\Ingest\EngineMonitor;
use App\Services\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/** Morning summary of the job engine, to the admin Telegram channel when alerts are on. */
class JobEngineDailyReport extends Command
{
    protected $signature = 'jobs:daily-report {--print : Print instead of sending}';

    protected $description = 'Send the daily job-engine summary (new, closed, failures, anomalies, AI cost)';

    public function handle(EngineMonitor $monitor): int
    {
        $s = $monitor->summary();
        $text = self::format($s);

        if ($this->option('print') || ! config('job_sources.telegram_alerts')) {
            $this->line(strip_tags($text));
            Log::info('Job engine daily report', $s);

            return self::SUCCESS;
        }
        rescue(fn () => app(TelegramService::class)->sendMessage($text, [[['text' => 'Open Engine Monitor', 'url' => route('admin.job-engine')]]]), null, true);
        $this->info('Sent.');

        return self::SUCCESS;
    }

    /** HTML message in the same style as the other admin alerts. */
    public static function format(array $s): string
    {
        $n = fn ($v) => number_format((int) $v, 0, ',', '.');
        $healthy = ! $s['problem_sources'] && ! $s['anomalies'] && ! $s['failed_jobs'] && ! $s['failed_runs'];
        $lines = [
            ($healthy ? '🟢' : '🟠').' <b>JOB ENGINE · DAILY REPORT</b>',
            '<i>'.now('Asia/Jakarta')->format('d M Y').' · last 24 hours</i>',
            '',
            '<b>Catalogue</b>',
            "• Open jobs: <b>{$n($s['open'])}</b>",
            "• New: +{$n($s['new'])} · Closed: {$n($s['closed'])}",
            '',
            '<b>Scrapers</b>',
            "• Runs: {$n($s['runs'])}".($s['failed_runs'] ? " · <b>{$n($s['failed_runs'])} failed</b>" : ' · all succeeded'),
        ];
        foreach ($s['problem_sources'] as $name => $health) {
            $lines[] = '• ⚠️ '.e($name).': '.e($health);
        }
        foreach ($s['anomalies'] as $a) {
            $lines[] = '• 📉 '.e($a['source']).": {$a['today']} new (usually ~{$a['average']}/day)";
        }
        $lines[] = '';
        $lines[] = '<b>Queue &amp; AI</b>';
        $lines[] = '• Failed jobs: '.($s['failed_jobs'] ? "<b>{$n($s['failed_jobs'])}</b>" : '0');
        $lines[] = '• AI cost: $'.number_format($s['ai_usd'], 3);
        if ($s['pending_pairs'] || $s['reports']) {
            $lines[] = '';
            $lines[] = '<b>To review</b>';
            $lines[] = "• Duplicate pairs: {$n($s['pending_pairs'])} · User reports: {$n($s['reports'])}";
        }
        $lines[] = '';
        $lines[] = $healthy ? '✅ All sources healthy.' : 'Open Engine Monitor for details.';

        return implode("\n", $lines);
    }
}
