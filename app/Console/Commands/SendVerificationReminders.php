<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\EmailVerificationReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendVerificationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-verification-reminders 
                            {--min-days=3 : Minimum days after registration}
                            {--max-reminders=3 : Maximum number of reminders per user}
                            {--reminder-interval=3 : Days between reminders}
                            {--dry-run : Run without sending emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email verification reminders to users who registered at least X days ago but haven\'t verified their email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minDays = (int) $this->option('min-days');
        $maxReminders = (int) $this->option('max-reminders');
        $reminderInterval = (int) $this->option('reminder-interval');
        $dryRun = $this->option('dry-run');
        
        $this->info("🔍 Looking for users who registered at least {$minDays} days ago without email verification...");
        $this->info("📋 Settings: Max {$maxReminders} reminders, interval {$reminderInterval} days");
        $this->newLine();
        
        // Calculate cutoff date (users registered at least X days ago)
        $cutoffDate = Carbon::now()->subDays($minDays);
        
        // Find users who:
        // 1. Registered at least X days ago (created_at <= cutoff)
        // 2. Email not verified
        // 3. Not admin (admins don't need verification)
        // 4. Haven't reached max reminder count OR
        // 5. Last reminder was sent more than interval days ago
        $users = User::whereNull('email_verified_at')
            ->where('role', '!=', 'admin')
            ->where('created_at', '<=', $cutoffDate)
            ->where(function($query) use ($maxReminders, $reminderInterval) {
                $query->where('verification_reminder_count', '<', $maxReminders)
                    ->orWhere(function($q) use ($reminderInterval) {
                        $q->whereNotNull('last_verification_reminder_sent_at')
                          ->where('last_verification_reminder_sent_at', '<=', Carbon::now()->subDays($reminderInterval));
                    });
            })
            ->orderBy('created_at', 'asc')
            ->get();
        
        if ($users->isEmpty()) {
            $this->info("✅ No users found who need verification reminders.");
            return 0;
        }
        
        $this->info("📧 Found {$users->count()} user(s) who need verification reminders:");
        $this->newLine();
        
        $successCount = 0;
        $failCount = 0;
        $skippedCount = 0;
        
        foreach ($users as $user) {
            $registeredDaysAgo = $user->created_at->diffInDays(now());
            $remindersSent = $user->verification_reminder_count ?? 0;
            $lastSent = $user->last_verification_reminder_sent_at 
                ? $user->last_verification_reminder_sent_at->diffForHumans() 
                : 'never';
            
            $this->line("  • {$user->name} ({$user->email})");
            $this->line("    Registered: {$registeredDaysAgo} days ago | Reminders sent: {$remindersSent} | Last sent: {$lastSent}");
            
            // Check if we should skip this user
            if ($remindersSent >= $maxReminders) {
                $this->line("    ⏭️  Skipped - Max reminders reached");
                $skippedCount++;
                continue;
            }
            
            // Check interval (if reminder was sent before)
            if ($user->last_verification_reminder_sent_at) {
                $daysSinceLastReminder = $user->last_verification_reminder_sent_at->diffInDays(now());
                if ($daysSinceLastReminder < $reminderInterval) {
                    $this->line("    ⏭️  Skipped - Sent {$daysSinceLastReminder} days ago (interval: {$reminderInterval} days)");
                    $skippedCount++;
                    continue;
                }
            }
            
            if ($dryRun) {
                $this->line("    [DRY RUN] Would send email to {$user->email}");
                $successCount++;
                continue;
            }
            
            try {
                Mail::to($user->email)->send(new EmailVerificationReminderMail($user));
                
                // Update tracking fields
                $user->last_verification_reminder_sent_at = now();
                $user->verification_reminder_count = $remindersSent + 1;
                $user->save();
                
                $this->line("    ✅ Reminder sent successfully (#{$user->verification_reminder_count})");
                $successCount++;
            } catch (\Exception $e) {
                $this->error("    ❌ Failed to send: {$e->getMessage()}");
                \Log::error("Failed to send verification reminder to {$user->email}: " . $e->getMessage());
                $failCount++;
            }
        }
        
        $this->newLine();
        $this->info("📊 Summary:");
        $this->info("  • Total users found: {$users->count()}");
        $this->info("  • Successfully sent: {$successCount}");
        if ($skippedCount > 0) {
            $this->warn("  • Skipped: {$skippedCount}");
        }
        if ($failCount > 0) {
            $this->error("  • Failed: {$failCount}");
        }
        
        if ($dryRun) {
            $this->warn("⚠️  This was a DRY RUN - No emails were actually sent.");
            $this->info("💡 Run without --dry-run to send emails.");
        } else {
            $this->info("✅ Email verification reminders sent successfully!");
        }
        
        return 0;
    }
}
