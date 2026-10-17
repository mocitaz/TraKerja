<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AiStudioProcessingService;

class RecoverAiStudio extends Command
{
    protected $signature = 'ai-studio:recover';
    protected $description = 'Recover queued Studio requests, refund expired work, and remove expired source files';
    public function handle(AiStudioProcessingService $service): int
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('ai_studio_runs')) {
            $this->warn('AI Studio recovery skipped: run the ai_studio_runs migration before enabling processing.');
            return self::SUCCESS;
        }

        $service->recover();
        return self::SUCCESS;
    }
}
