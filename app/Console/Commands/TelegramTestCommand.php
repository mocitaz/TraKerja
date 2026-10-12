<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test {--type=all : all, user, ticket, payment, security, error, recap, health}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test notifications to verify Telegram channel integration';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegram): int
    {
        $type = strtolower((string) $this->option('type'));
        $this->info("Running Telegram test suite for type: [{$type}]...");

        $user = User::first() ?? new User(['name' => 'John Doe', 'email' => 'john@trakerja.com']);

        if (in_array($type, ['all', 'user'])) {
            $this->line('Sending New User alert...');
            $telegram->sendNewUser($user, 'Google OAuth', false);
        }

        if (in_array($type, ['all', 'ticket'])) {
            $ticket = SupportTicket::first() ?? new SupportTicket([
                'category' => 'technical_issue',
                'subject'  => 'Test Support Ticket',
                'message'  => 'Ini adalah contoh pesan tiket bantuan untuk menguji notifikasi Telegram.',
            ]);
            $ticket->setRelation('user', $user);
            $this->line('Sending Support Ticket alert...');
            $telegram->sendSupportTicket($ticket, false);
        }

        if (in_array($type, ['all', 'payment'])) {
            $payment = Payment::first() ?? new Payment([
                'order_id'              => 'ORD-TEST-99881',
                'amount'                => 19999,
                'payment_channel_name'  => 'Pakasir (QRIS)',
            ]);
            $this->line('Sending Payment Confirmed alert...');
            $telegram->sendPaymentSuccess($payment, $user, false, false);
        }

        if (in_array($type, ['all', 'security'])) {
            $this->line('Sending Security alert...');
            $telegram->sendSecurityAlert('BRUTE FORCE LOGIN DETECTED', [
                'Target Account' => 'admin@teknalogi.id',
                'IP Address'     => '103.144.20.12',
                'Failed Trials'  => '5 attempts',
                'Action'         => 'Temporarily throttled for 60s',
            ], false);
        }

        if (in_array($type, ['all', 'error'])) {
            $this->line('Sending Exception error alert...');
            $telegram->reportException(new \RuntimeException('Test diagnostic exception with password=SecretPassword123 & api_key=pakasir_secret_key'));
        }

        if (in_array($type, ['all', 'recap'])) {
            $this->line('Sending Daily Recap alert...');
            $telegram->sendDailyRecap('evening');
        }

        if (in_array($type, ['all', 'health'])) {
            $this->line('Sending Server Health alert...');
            $telegram->sendServerHealth(true);
        }

        $this->info('Telegram test suite completed successfully!');
        return Command::SUCCESS;
    }
}
