<?php

namespace App\Livewire;

use App\Models\DashboardAction;
use App\Models\JobApplication;
use App\Services\ActivityLogger;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DashboardNextMoves extends Component
{
    #[Locked]
    public ?int $undoActionId = null;

    public string $notice = '';

    public ?string $confirmAction = null;

    public ?int $confirmJobId = null;

    private function ownedJob(string $action, int $id): JobApplication
    {
        abort_unless(in_array($action, ['interview', 'followup'], true), 422);
        $job = JobApplication::where('user_id', auth()->id())->find($id);
        abort_unless($job, 404);
        abort_if($job->is_archived || $job->application_status !== 'On Process', 422);
        abort_unless($action === 'followup' ? $job->isGhosted() : $job->interview_date?->gte(now()), 422);

        return $job;
    }

    public function askComplete(string $action, int $id): void
    {
        $this->ownedJob($action, $id);
        $this->confirmAction = $action;
        $this->confirmJobId = $id;
    }

    public function complete(string $action, int $id): void
    {
        $job = $this->ownedJob($action, $id);
        $record = DashboardAction::updateOrCreate(['user_id' => auth()->id(), 'job_application_id' => $id, 'action' => $action], ['context' => $action === 'interview' ? $job->interview_date->toIso8601String() : null, 'completed_at' => now(), 'snoozed_until' => null]);
        $this->undoActionId = $record->id;
        $this->notice = $action === 'followup' ? 'Follow-up recorded. The next application is ready to review.' : 'Interview preparation saved. You’re prepared.';
        $this->confirmAction = null;
        $this->confirmJobId = null;
        $this->dispatch('next-move-updated');
        ActivityLogger::log('dashboard_'.$action.'_completed', $action === 'followup' ? 'Recorded a follow-up already sent to '.$job->company_name : 'Marked interview preparation complete for '.$job->company_name, 'success', ['job_id' => $job->id]);
    }

    public function snooze(string $action, int $id): void
    {
        $job = $this->ownedJob($action, $id);
        $record = DashboardAction::updateOrCreate(['user_id' => auth()->id(), 'job_application_id' => $id, 'action' => $action], ['context' => $action === 'interview' ? $job->interview_date->toIso8601String() : null, 'completed_at' => null, 'snoozed_until' => today()->addDay()]);
        $this->undoActionId = $record->id;
        $this->notice = 'Set aside until tomorrow. This does not mark the action complete.';
        $this->dispatch('next-move-updated');
    }

    public function undo(): void
    {
        if ($this->undoActionId) {
            DashboardAction::where('user_id', auth()->id())->whereKey($this->undoActionId)->delete();
        }
        $this->undoActionId = null;
        $this->notice = 'Action restored.';
        $this->dispatch('next-move-updated');
    }

    public function render()
    {
        abort_unless(auth()->check(), 401);
        $user = auth()->user()->load('profile');
        $actions = DashboardAction::where('user_id', $user->id)->get();
        $interviews = JobApplication::where('user_id', $user->id)->where('is_archived', false)->where('application_status', 'On Process')->where('interview_date', '>=', now())->orderBy('interview_date')->orderBy('id')->get();
        $upcomingInterviews = $interviews->reject(fn ($job) => $actions->contains(fn ($a) => $a->action === 'interview' && $a->job_application_id === $job->id && $a->context === $job->interview_date->toIso8601String() && $a->snoozed_until?->isFuture()))->take(3);
        $nextInterview = $upcomingInterviews->first();
        $interviewPrepared = $nextInterview && $actions->contains(fn ($a) => $a->action === 'interview' && $a->job_application_id === $nextInterview->id && $a->context === $nextInterview->interview_date->toIso8601String() && $a->completed_at);
        $hiddenFollowups = $actions->filter(fn ($a) => $a->action === 'followup' && ($a->snoozed_until?->isFuture() || $a->completed_at?->gt(now()->subDays(14))))->pluck('job_application_id');
        $followups = JobApplication::where('user_id', $user->id)->where('is_archived', false)->where('application_status', 'On Process')->where(function ($q) {
            $q->whereNull('recruitment_stage')->orWhereNotIn('recruitment_stage', ['Offering', 'Not Processed']);
        })->whereDate('application_date', '<=', today()->subDays(14))->whereNotIn('id', $hiddenFollowups);
        $followUpCount = (clone $followups)->count();
        $nextFollowUp = $followups->orderBy('application_date')->orderBy('id')->first();
        $profile = $user->profile;
        $cvEssentials = ['Contact details' => filled($profile?->full_name) && filled($profile?->phone_number) && filled($profile?->domicile), 'Summary' => filled($profile?->bio), 'Experience or education' => $user->experiences()->exists() || $user->educations()->exists(), 'Skills' => $user->skills()->exists()];
        $deferredCount = $actions->filter(fn ($a) => $a->snoozed_until?->isFuture())->count();

        $primaryMove = $nextInterview && ! $interviewPrepared ? 'interview' : ($nextFollowUp ? 'followup' : (count(array_filter($cvEssentials)) < 4 ? 'cv' : ($nextInterview ? 'interview' : 'cv')));

        return view('dashboard.next-move', compact('upcomingInterviews', 'nextFollowUp', 'followUpCount', 'cvEssentials', 'interviewPrepared', 'deferredCount', 'primaryMove'));
    }
}
