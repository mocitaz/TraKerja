<?php

namespace App\Console\Commands;

use App\Services\AdminNara\AdminNaraDigest;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class AdminNaraDigestCommand extends Command
{
    protected $signature = 'admin:nara-digest {--always : Send even when nothing needs attention} {--dry : Print it instead of sending}';

    protected $description = 'Telegram digest of what needs the admin\'s attention (sent only when something does, unless --always)';

    public function handle(AdminNaraDigest $digest, TelegramService $telegram): int
    {
        $data = $digest->data();
        if ($this->option('dry')) {
            $this->line(strip_tags($digest->message()));

            return self::SUCCESS;
        }
        if (! $data['needs_attention'] && ! $this->option('always')) {
            $this->info('Nothing needs attention; nothing sent.');

            return self::SUCCESS;
        }

        return $telegram->sendMessage($digest->message()) ? self::SUCCESS : self::FAILURE;
    }
}
