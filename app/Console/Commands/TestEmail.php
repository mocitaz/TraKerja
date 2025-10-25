<?php

namespace App\Console\Commands;

use App\Mail\GoalAchievedMail;
use App\Mail\InterviewReminderMail;
use App\Mail\WelcomeMail;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\UserGoal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {type=welcome} {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending emails (types: welcome, interview, goal)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $email = $this->argument('email');
        
        try {
            switch ($type) {
                case 'welcome':
                    $this->testWelcomeEmail($email);
                    break;
                case 'interview':
                    $this->testInterviewEmail($email);
                    break;
                case 'goal':
                    $this->testGoalEmail($email);
                    break;
                default:
                    $this->error("Unknown type: {$type}");
                    $this->info("Available types: welcome, interview, goal");
                    return Command::FAILURE;
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to send email: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
    
    private function testWelcomeEmail($email)
    {
        $user = $email 
            ? User::where('email', $email)->firstOrFail()
            : User::first();
            
        if (!$user) {
            $this->error('No users found in database');
            return;
        }
        
        $this->info("Sending welcome email to: {$user->email}");
        Mail::to($user->email)->send(new WelcomeMail($user));
        $this->info('✓ Welcome email sent successfully!');
    }
    
    private function testInterviewEmail($email)
    {
        $application = $email
            ? JobApplication::whereHas('user', function($q) use ($email) {
                $q->where('email', $email);
              })->whereNotNull('interview_date')->first()
            : JobApplication::whereNotNull('interview_date')->with('user')->first();
            
        if (!$application) {
            $this->error('No job applications with interview date found');
            return;
        }
        
        $this->info("Sending interview reminder to: {$application->user->email}");
        $this->info("Interview: {$application->company} - {$application->position}");
        Mail::to($application->user->email)->send(new InterviewReminderMail($application));
        $this->info('✓ Interview reminder sent successfully!');
    }
    
    private function testGoalEmail($email)
    {
        $goal = $email
            ? UserGoal::whereHas('user', function($q) use ($email) {
                $q->where('email', $email);
              })->with('user')->first()
            : UserGoal::with('user')->first();
            
        if (!$goal) {
            $this->error('No goals found in database');
            return;
        }
        
        $this->info("Sending goal achieved email to: {$goal->user->email}");
        $this->info("Goal: Weekly Goal");
        Mail::to($goal->user->email)->send(new GoalAchievedMail($goal));
        $this->info('✓ Goal achieved email sent successfully!');
    }
}
