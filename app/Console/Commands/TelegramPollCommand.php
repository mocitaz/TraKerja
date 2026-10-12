<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramPollCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll {--once : Run only once instead of polling indefinitely}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll incoming messages for the Telegram interactive admin assistant';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegram): int
    {
        $token = config('services.telegram.bot_token');
        if (empty($token)) {
            $this->error('TELEGRAM_BOT_TOKEN is not configured.');
            return Command::FAILURE;
        }

        $this->info('Starting Telegram Bot polling listener...');
        $this->comment('Press Ctrl+C to stop.');

        $offset = 0;
        $runOnce = (bool) $this->option('once');

        do {
            try {
                $response = Http::timeout(10)->get("https://api.telegram.org/bot{$token}/getUpdates", [
                    'offset'  => $offset,
                    'timeout' => 5,
                ]);

                if ($response->successful()) {
                    $updates = $response->json('result', []);
                    foreach ($updates as $update) {
                        $offset = $update['update_id'] + 1;
                        $text = $update['message']['text'] ?? '(non-text)';
                        $from = $update['message']['from']['first_name'] ?? 'Unknown';
                        $this->line("<info>[INCOMING]</info> from {$from}: {$text}");

                        $telegram->handleIncomingUpdate($update);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Telegram poll connection error: ' . $e->getMessage());
                sleep(2);
            }

            if ($runOnce) {
                break;
            }

            usleep(500000); // 0.5s pause
        } while (true);

        return Command::SUCCESS;
    }
}
