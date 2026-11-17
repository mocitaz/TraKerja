<?php

namespace App\Console\Commands;

use App\JobEngine\Verify\VerificationService;
use Illuminate\Console\Command;

/** php artisan jobs:verify [--budget=300]   check whether stored vacancies are still open */
class VerifyVacancies extends Command
{
    protected $signature = 'jobs:verify {--budget= : Max listings to check (default config job_sources.verify_budget)}';

    protected $description = 'Check due listings and close vacancies that are no longer open';

    public function handle(VerificationService $verification): int
    {
        $budget = (int) ($this->option('budget') ?: config('job_sources.verify_budget'));
        $counts = $verification->run($budget, function (array $c) {
            $this->output->write(sprintf("\r   buka %d · dicurigai %d · tutup %d · terblokir %d", $c['open'], $c['suspect'], $c['closed'], $c['blocked']));
        });
        $this->newLine();
        $this->info(sprintf('Selesai: %d dicek.', array_sum($counts)));

        return self::SUCCESS;
    }
}
