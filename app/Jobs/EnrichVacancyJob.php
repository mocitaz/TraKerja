<?php

namespace App\Jobs;

use App\JobEngine\Intelligence\AiEnricher;
use App\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

/** Enrich one vacancy on the "ai" queue, so several workers can call the model in parallel. */
class EnrichVacancyJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 2;

    public int $timeout = 300;

    public function __construct(public int $vacancyId)
    {
        $this->onQueue('ai');
    }

    public function uniqueId(): string
    {
        return 'enrich-'.$this->vacancyId;
    }

    public function handle(AiEnricher $enricher): void
    {
        $vacancy = Vacancy::with('company')->find($this->vacancyId);
        if ($vacancy && $vacancy->enriched_at === null) {
            $enricher->enrich($vacancy);
        }
    }
}
