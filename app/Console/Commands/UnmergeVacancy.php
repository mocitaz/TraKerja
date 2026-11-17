<?php

namespace App\Console\Commands;

use App\JobEngine\Intelligence\VacancyMerger;
use App\Models\Vacancy;
use Illuminate\Console\Command;

/** php artisan jobs:unmerge 267 [268 ...]   undo wrong dedup merges using the audit trail */
class UnmergeVacancy extends Command
{
    protected $signature = 'jobs:unmerge {ids* : Ids of vacancies that were merged into another}';

    protected $description = 'Undo a dedup merge: move the listings back and reactivate the vacancy';

    public function handle(VacancyMerger $merger): int
    {
        foreach ($this->argument('ids') as $id) {
            $vacancy = Vacancy::find($id);
            if (! $vacancy?->merged_into_id) {
                $this->warn("#{$id}: bukan hasil penggabungan.");

                continue;
            }
            $this->info("#{$id}: {$merger->unmerge($vacancy)} listing dikembalikan.");
        }

        return self::SUCCESS;
    }
}
