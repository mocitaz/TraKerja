<?php

namespace App\Jobs;

use App\Models\JobApplication;
use App\Models\JobBookmark;
use App\Models\User;
use App\Models\Vacancy;
use App\Notifications\VacancyClosed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Tell people a vacancy closed (TraKerja 2.0, Fase 6): applicants whose application is still in progress,
 * and people who saved it without applying. Each user hears about a vacancy once.
 */
class NotifyVacancyClosed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public int $vacancyId) {}

    public function handle(): void
    {
        $vacancy = Vacancy::with('company')->find($this->vacancyId);
        if (! $vacancy || $vacancy->status !== 'closed') {
            return; // reopened (or deleted) before the job ran
        }

        $applications = JobApplication::where('vacancy_id', $vacancy->id)->where('application_status', 'On Process')
            ->get(['id', 'user_id'])->keyBy('user_id');
        $savers = JobBookmark::where('vacancy_id', $vacancy->id)->whereNotIn('user_id', $applications->keys())->pluck('user_id');

        $recipients = User::whereIn('id', $applications->keys()->merge($savers))->where('notify_job_closed', true)->get();
        foreach ($recipients as $user) {
            $already = DatabaseNotification::where('notifiable_type', $user->getMorphClass())->where('notifiable_id', $user->id)
                ->where('type', VacancyClosed::class)->where('data', 'like', '%"vacancy_id":'.$vacancy->id.',%')->exists();
            if ($already) {
                continue;
            }
            $application = $applications->get($user->id);
            $user->notify(new VacancyClosed($vacancy->id, $vacancy->title, $vacancy->company?->name, $application ? 'applied' : 'saved', $application?->id));
        }
    }
}
