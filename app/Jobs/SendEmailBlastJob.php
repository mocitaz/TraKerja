<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\AiAnalyzerFreeTrialAnnouncementMail;
use App\Mail\JobApplicationReminderMail;
use App\Mail\MonthlyMotivationMail;
use App\Mail\CustomEmailBlastMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendEmailBlastJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $emailType,
        public ?string $customSubject = null,
        public ?string $customContent = null,
        public ?string $customButtonText = null,
        public ?string $customButtonUrl = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            switch ($this->emailType) {
                case 'ai_analyzer':
                    Mail::to($this->user->email)->send(new AiAnalyzerFreeTrialAnnouncementMail($this->user));
                    break;
                case 'job_reminder':
                    Mail::to($this->user->email)->send(new JobApplicationReminderMail($this->user));
                    break;
                case 'monthly_motivation':
                    Mail::to($this->user->email)->send(new MonthlyMotivationMail($this->user));
                    break;
                case 'custom':
                    Mail::to($this->user->email)->send(new CustomEmailBlastMail(
                        $this->user,
                        $this->customSubject,
                        $this->customContent,
                        $this->customButtonText,
                        $this->customButtonUrl
                    ));
                    break;
            }
        } catch (Throwable $e) {
            Log::error("Email blast failed for {$this->user->email}: " . $e->getMessage(), [
                'user_id' => $this->user->id,
                'email_type' => $this->emailType,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("Email blast job failed permanently", [
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'email_type' => $this->emailType,
            'error' => $exception->getMessage(),
        ]);
    }
}
