<?php

namespace App\Jobs;

use App\Services\AiStudioProcessingService;
use Illuminate\Contracts\Queue\{ShouldQueue, ShouldBeUnique};
use Illuminate\Foundation\Queue\Queueable;

class ProcessAiStudio implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    public int $timeout = 160;
    public int $tries = 1;
    public bool $failOnTimeout = true;
    public int $uniqueFor = 240;

    public function __construct(public int $runId) { $this->onConnection('ai_studio')->onQueue('ai-studio'); }
    public function uniqueId(): string { return (string) $this->runId; }
    public function handle(AiStudioProcessingService $service): void { $service->process($this->runId); }
    public function failed(?\Throwable $exception): void { app(AiStudioProcessingService::class)->fail($this->runId, 'processing'); }
}
