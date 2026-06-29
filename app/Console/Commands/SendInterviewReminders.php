<?php

namespace App\Console\Commands;

use App\Mail\InterviewReminderMail;
use App\Models\JobApplication;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendInterviewReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interviews:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for upcoming interviews (24 hours before)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for upcoming interviews...');
        
        // Get interviews happening on the next calendar day (tomorrow)
        $tomorrowStart = Carbon::tomorrow('Asia/Jakarta')->startOfDay();
        $tomorrowEnd = Carbon::tomorrow('Asia/Jakarta')->endOfDay();
        
        $applications = JobApplication::whereBetween('interview_date', [$tomorrowStart, $tomorrowEnd])
            ->whereNotNull('interview_date')
            ->with('user')
            ->get();
        
        $count = 0;
        
        foreach ($applications as $application) {
            $user = $application->user;
            if (!$user || !$user->canAccessEmailNotifications() || !$user->notify_interview_reminders) {
                $this->info("Skipped {$application->company_name} for user {$user->email} (limit or preferences disabled)");
                continue;
            }
            
            try {
                Mail::to($user->email)
                    ->send(new InterviewReminderMail($application));
                
                $count++;
                $this->info("✓ Sent reminder to {$user->email} for {$application->company_name}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to send reminder to {$user->email}: {$e->getMessage()}");
            }
        }
        
        $this->info("\nTotal reminders sent: {$count}");
        
        return Command::SUCCESS;
    }
}
