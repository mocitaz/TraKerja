<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyUserEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify email for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email '{$email}' tidak ditemukan.");
            return 1;
        }

        if ($user->hasVerifiedEmail()) {
            $this->info("Email untuk user '{$email}' sudah terverifikasi.");
            return 0;
        }

        $user->email_verified_at = now();
        $user->save();

        $this->info("Email berhasil diverifikasi untuk user: {$email}");

        return 0;
    }
}


