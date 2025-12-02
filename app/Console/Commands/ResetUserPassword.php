<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password {email} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->option('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email '{$email}' tidak ditemukan.");
            return 1;
        }

        if (!$password) {
            $password = $this->secret('Masukkan password baru (atau tekan Enter untuk generate random):');
            
            if (empty($password)) {
                $password = \Str::random(12);
                $this->info("Password random generated: {$password}");
            }
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("Password berhasil direset untuk user: {$email}");
        $this->line("Password baru: {$password}");

        return 0;
    }
}


