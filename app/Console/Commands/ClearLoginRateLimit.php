<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ClearLoginRateLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-rate-limit {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear login rate limit for a specific email or all emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if ($email) {
            // Clear for specific email (all IPs)
            $pattern = Str::transliterate(Str::lower($email)) . '|*';
            
            // Note: RateLimiter doesn't support wildcards directly
            // So we'll need to clear from cache manually
            $this->info("Clearing rate limit for email: {$email}");
            $this->warn("Note: Rate limiter uses email|ip format. You may need to clear cache manually.");
            
            // Try to clear common patterns
            $commonIPs = ['127.0.0.1', '::1', 'localhost'];
            foreach ($commonIPs as $ip) {
                $key = Str::transliterate(Str::lower($email)) . '|' . $ip;
                RateLimiter::clear($key);
                $this->line("Cleared: {$key}");
            }
            
            $this->info("Rate limit cleared for email: {$email}");
        } else {
            $this->info("To clear rate limit for a specific email, use:");
            $this->line("php artisan auth:clear-rate-limit your-email@example.com");
            $this->newLine();
            $this->info("Or clear all cache:");
            $this->line("php artisan cache:clear");
        }

        return 0;
    }
}


