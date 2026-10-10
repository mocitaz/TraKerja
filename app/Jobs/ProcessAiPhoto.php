<?php

namespace App\Jobs;

use App\Services\AiPhotoProcessingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Queue\Queueable;

class ProcessAiPhoto implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $timeout = 330;
    public int $tries = 1;
    public bool $failOnTimeout = true;
    public int $uniqueFor = 600;

    public function __construct(public int $photoId)
    {
        $this->onConnection('ai_photos')->onQueue('ai-photo');
    }

    public function handle(AiPhotoProcessingService $service): void
    {
        $service->process($this->photoId);
    }

    public function uniqueId(): string
    {
        return (string) $this->photoId;
    }

    public function failed(?\Throwable $exception): void
    {
        app(AiPhotoProcessingService::class)->fail($this->photoId, 'Pemrosesan terhenti. Kredit yang dipesan telah dikembalikan.');
    }
}
