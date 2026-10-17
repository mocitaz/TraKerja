<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\AiAnalyzerFreeTrialAnnouncementMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAiAnalyzerAnnouncementBlast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-ai-analyzer-announcement
                            {--dry-run : Run without sending emails}
                            {--limit= : Limit number of emails to send}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send AI Analyzer free trial announcement to all free tier users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $this->info("🔍 Looking for free tier users to send AI Analyzer announcement...");
        $this->newLine();

        // Find users who:
        // 1. Not premium (is_premium = false OR payment_status != 'paid')
        // 2. Not admin (admins don't need this)
        // 3. All users (not just verified)
        $query = User::where('role', '!=', 'admin')
            ->where(function($q) {
                $q->where('is_premium', false)
                  ->orWhere('payment_status', '!=', 'paid');
            })
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
            $this->info("📋 Limit: {$limit} emails");
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->info("✅ No free tier users found.");
            return 0;
        }

        $this->info("📧 Found {$users->count()} free tier user(s):");
        $this->newLine();

        $successCount = 0;
        $failCount = 0;

        $progressBar = $this->output->createProgressBar($users->count());
        $progressBar->start();

        foreach ($users as $user) {
            $hasUsedTrial = $user->has_used_ai_analyzer_trial ? 'Yes' : 'No';

            if ($dryRun) {
                $this->newLine();
                $this->line("  [DRY RUN] Would send to: {$user->name} ({$user->email}) - Trial used: {$hasUsedTrial}");
                $successCount++;
                $progressBar->advance();
                continue;
            }

            try {
                Mail::to($user)->send(new AiAnalyzerFreeTrialAnnouncementMail($user));
                $successCount++;
                $progressBar->advance();
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("  ❌ Failed to send to {$user->email}: {$e->getMessage()}");
                \Log::error("Failed to send AI Analyzer announcement to {$user->email}: " . $e->getMessage());
                $failCount++;
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("📊 Summary:");
        $this->info("  • Total users: {$users->count()}");
        $this->info("  • Successfully sent: {$successCount}");
        if ($failCount > 0) {
            $this->error("  • Failed: {$failCount}");
        }

        if ($dryRun) {
            $this->newLine();
            $this->warn("⚠️  This was a DRY RUN - No emails were actually sent.");
            $this->info("💡 Run without --dry-run to send emails.");
        } else {
            $this->newLine();
            $this->info("✅ AI Analyzer announcement emails sent successfully!");
            $this->info("🎉 Free tier users can now try AI Analyzer for FREE (1x)!");
        }

        return 0;
    }
}
