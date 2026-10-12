<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramDailyRecapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:telegram-daily {period=morning : morning or evening}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send morning or evening daily recap report to Telegram admin channel';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegram): int
    {
        $period = strtolower($this->argument('period'));
        $this->info("Sending {$period} daily recap to Telegram channel...");

        $success = $telegram->sendDailyRecap($period);

        if ($success) {
            $this->info("Daily recap ({$period}) sent successfully.");
            return Command::SUCCESS;
        }

        $this->warn("Failed or Telegram notification is disabled.");
        return Command::FAILURE;
    }
}
