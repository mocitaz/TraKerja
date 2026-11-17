<?php

namespace App\Console\Commands;

use App\JobEngine\Intelligence\AiEnricher;
use App\Models\Vacancy;
use Illuminate\Console\Command;

/** php artisan jobs:enrich --limit=200   complete vacancies that have not been enriched yet (AI + scam rules) */
class EnrichVacancies extends Command
{
    protected $signature = 'jobs:enrich {--limit=200} {--again : Also re-enrich vacancies done before}
        {--queue : Dispatch to the "ai" queue (run several workers: php artisan queue:work --queue=ai)}';

    protected $description = 'Complete vacancies with gpt-5-nano (empty fields only) and flag likely scams';

    public function handle(AiEnricher $enricher): int
    {
        $query = Vacancy::query()->with('company')->whereIn('status', ['active', 'suspect'])->orderByDesc('id');
        if (! $this->option('again')) {
            $query->whereNull('enriched_at');
        }

        if ($this->option('queue')) {
            $ids = $query->limit((int) $this->option('limit'))->pluck('id');
            $ids->each(fn ($id) => \App\Jobs\EnrichVacancyJob::dispatch($id));
            $this->info("{$ids->count()} loker dikirim ke antrean ai.");

            return self::SUCCESS;
        }

        $counts = ['enriched' => 0, 'failed' => 0, 'skipped_budget' => 0];
        $bar = $this->output->createProgressBar(min((int) $this->option('limit'), $query->count()));
        foreach ($query->limit((int) $this->option('limit'))->get() as $vacancy) {
            $result = $enricher->enrich($vacancy);
            $counts[$result]++;
            $bar->advance();
            if ($result === 'skipped_budget') {
                $this->newLine();
                $this->warn('Anggaran AI harian habis; sisa loker diproses besok.');
                break;
            }
        }
        $bar->finish();
        $this->newLine();

        $cost = (float) \App\Models\AiCall::where('created_at', '>=', now()->startOfDay())->sum('cost_usd');
        $this->info(sprintf('Diperkaya %d, gagal %d. Biaya AI hari ini ±$%.4f.', $counts['enriched'], $counts['failed'], $cost));

        return self::SUCCESS;
    }
}
