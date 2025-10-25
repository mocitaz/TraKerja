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
        
        // Get interviews happening in the next 24 hours
        $tomorrow = Carbon::now()->addDay();
        $dayAfterTomorrow = Carbon::now()->addDays(2);
        
        $applications = JobApplication::whereBetween('interview_date', [$tomorrow, $dayAfterTomorrow])
            ->whereNotNull('interview_date')
            ->with('user')
            ->get();
        
        $count = 0;
        
        foreach ($applications as $application) {
            try {
                Mail::to($application->user->email)
                    ->send(new InterviewReminderMail($application));
                
                $count++;
                $this->info("✓ Sent reminder to {$application->user->email} for {$application->company}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to send reminder to {$application->user->email}: {$e->getMessage()}");
            }
        }
        
        $this->info("\nTotal reminders sent: {$count}");
        
        return Command::SUCCESS;
    }
}
