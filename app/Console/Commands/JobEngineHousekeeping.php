<?php

namespace App\Console\Commands;

use App\Models\Vacancy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/** Daily maintenance for the job engine (TraKerja 2.0). Safe to run any time. */
class JobEngineHousekeeping extends Command
{
    protected $signature = 'jobs:housekeeping {--dry-run : Report only}';

    protected $description = 'Archive long-closed vacancies, prune old logs, draw the weekly QA sample, retry failed engine jobs';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $old = Vacancy::where('status', 'closed')->where('closed_at', '<', now()->subDays(30));
        $checks = DB::table('vacancy_checks')->where('checked_at', '<', now()->subDays(90));
        // ai_calls doubles as the AI answer cache: keep successful answers, prune failures and skips.
        $calls = DB::table('ai_calls')->where('created_at', '<', now()->subDays(90))->where('status', '!=', 'ok');
        $counts = ['archived' => (clone $old)->count(), 'checks_pruned' => (clone $checks)->count(), 'ai_calls_pruned' => (clone $calls)->count()];

        if (! $dry) {
            $old->update(['status' => 'archived', 'updated_at' => now()]);
            $checks->delete();
            $calls->delete();
        }

        // Weekly QA sample, drawn automatically on Mondays (once per ISO week).
        $sample = now()->format('o-\WW');
        if (now()->isMonday() && ! DB::table('vacancy_qa_checks')->where('sample', $sample)->exists()) {
            $ids = Vacancy::where('status', 'active')->inRandomOrder()->limit(20)->pluck('id')
                ->merge(Vacancy::where('status', 'closed')->where('closed_at', '>=', now()->subWeek())->inRandomOrder()->limit(5)->pluck('id'));
            $counts['qa_sample'] = $ids->count();
            if (! $dry) {
                foreach ($ids as $id) {
                    DB::table('vacancy_qa_checks')->insertOrIgnore(['vacancy_id' => $id, 'sample' => $sample, 'created_at' => now(), 'updated_at' => now()]);
                }
            }
        }

        // One automatic retry for failed engine jobs (ingest / ai) from the last day.
        $failed = DB::table('failed_jobs')->whereIn('queue', ['ingest', 'ai'])->where('failed_at', '>=', now()->subDay())->pluck('uuid');
        $counts['retried'] = $failed->count();
        if (! $dry && $failed->isNotEmpty()) {
            Artisan::call('queue:retry', ['id' => $failed->all()]);
        }

        $this->info(($dry ? '[dry run] ' : '').json_encode($counts));

        return self::SUCCESS;
    }
}
