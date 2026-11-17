<?php

namespace App\Console\Commands;

use App\JobEngine\Intelligence\Matcher;
use App\JobEngine\Intelligence\VacancyMerger;
use App\Models\Vacancy;
use Illuminate\Console\Command;

/**
 * php artisan jobs:dedup [--no-ai] [--dry-run]
 * Finds vacancies that are the same job on several platforms and folds each into the older one.
 * New listings are matched at ingest time; this command cleans up what is already stored.
 */
class DedupVacancies extends Command
{
    protected $signature = 'jobs:dedup {--no-ai : Do not ask the AI about gray-zone pairs} {--dry-run : Report only}';

    protected $description = 'Merge vacancies that describe the same job on different platforms';

    public function handle(Matcher $matcher, VacancyMerger $merger): int
    {
        $merged = 0;
        $byMethod = [];
        $vacancies = Vacancy::query()->whereIn('status', ['active', 'suspect', 'closed'])->whereNull('merged_into_id')->orderByDesc('id')->get();

        foreach ($vacancies as $vacancy) {
            $vacancy->refresh();
            if ($vacancy->merged_into_id || ! ($listing = $vacancy->listings()->orderBy('id')->first())) {
                continue;
            }
            $match = $matcher->find($listing, $vacancy->company_id, $vacancy->region_id, $vacancy->title, $vacancy->id, ! $this->option('no-ai'));
            // Fold the newer vacancy into the older one so links and bookmarks stay stable.
            if (! $match || $match['vacancy']->id > $vacancy->id) {
                continue;
            }
            $byMethod[$match['method']] = ($byMethod[$match['method']] ?? 0) + 1;
            $this->line(sprintf('  %s: "%s" (#%d) = "%s" (#%d)', $match['method'], $vacancy->title, $vacancy->id, $match['vacancy']->title, $match['vacancy']->id));
            if (! $this->option('dry-run')) {
                $merger->merge($vacancy, $match['vacancy'], $match['method'], $match['score']);
            }
            $merged++;
        }

        $pending = \Illuminate\Support\Facades\DB::table('dedup_reviews')->where('status', 'pending')->count();
        $this->info(sprintf('%s %d duplikat (%s). Menunggu tinjauan manual: %d pasangan.',
            $this->option('dry-run') ? 'Ditemukan' : 'Digabung', $merged, json_encode($byMethod), $pending));

        return self::SUCCESS;
    }
}
