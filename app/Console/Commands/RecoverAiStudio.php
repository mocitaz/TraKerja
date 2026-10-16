<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AiStudioProcessingService;

class RecoverAiStudio extends Command
{
    protected $signature = 'ai-studio:recover';
    protected $description = 'Recover queued Studio requests, refund expired work, and remove expired source files';
    public function handle(AiStudioProcessingService $service): int { $service->recover(); return self::SUCCESS; }
}
