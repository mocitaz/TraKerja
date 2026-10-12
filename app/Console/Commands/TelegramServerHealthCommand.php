<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramServerHealthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:server-health {--force : Force sending report even if usage is normal}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check server disk & resource health and report to Telegram if abnormal';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegram): int
    {
        $force = (bool) $this->option('force');
        $this->info("Checking server health (force=" . ($force ? 'true' : 'false') . ")...");

        $sent = $telegram->sendServerHealth($force);

        if ($sent) {
            $this->info("Server health report sent to Telegram channel.");
        } else {
            $this->info("Server health normal. No alert needed.");
        }

        return Command::SUCCESS;
    }
}
