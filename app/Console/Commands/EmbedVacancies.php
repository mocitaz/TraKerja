<?php

namespace App\Console\Commands;

use App\JobEngine\Personalization\Embeddings;
use Illuminate\Console\Command;

class EmbedVacancies extends Command
{
    protected $signature = 'jobs:embed {--limit=500 : Most vacancies to embed in this run}';

    protected $description = 'Embed new or changed vacancies for CV match scores (TraKerja 2.0, Fase 6)';

    public function handle(Embeddings $embeddings): int
    {
        $count = $embeddings->embedVacancies((int) $this->option('limit'));
        $this->info("Embedded {$count} vacancies.");

        return self::SUCCESS;
    }
}
