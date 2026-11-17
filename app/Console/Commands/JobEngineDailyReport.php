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
        $lines = [
            '📊 *Mesin Data Loker · 24 jam terakhir*',
            "Loker buka: {$s['open']} (+{$s['new']} baru, {$s['closed']} tutup)",
            "Run scraper: {$s['runs']}".($s['failed_runs'] ? " ({$s['failed_runs']} gagal)" : ''),
            'Biaya AI: $'.number_format($s['ai_usd'], 3),
        ];
        foreach ($s['problem_sources'] as $name => $health) {
            $lines[] = "⚠️ {$name}: {$health}";
        }
        foreach ($s['anomalies'] as $a) {
            $lines[] = "📉 {$a['source']}: {$a['today']} loker baru, biasanya ~{$a['average']}/hari. Cek apakah tata letak situs berubah.";
        }
        if ($s['failed_jobs']) {
            $lines[] = "❌ {$s['failed_jobs']} job antrean gagal";
        }
        if ($s['pending_pairs'] || $s['reports']) {
            $lines[] = "📝 Tinjauan: {$s['pending_pairs']} duplikat, {$s['reports']} laporan pengguna baru";
        }
        $text = implode("\n", $lines);

        if ($this->option('print') || ! config('job_sources.telegram_alerts')) {
            $this->line($text);
            Log::info('Job engine daily report', $s);

            return self::SUCCESS;
        }
        rescue(fn () => app(TelegramService::class)->sendMessage($text), null, true);
        $this->info('Sent.');

        return self::SUCCESS;
    }
}
