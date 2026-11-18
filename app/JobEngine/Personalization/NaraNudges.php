<?php

namespace App\JobEngine\Personalization;

use App\Models\JobApplication;
use App\Models\SavedJobSearch;
use App\Models\User;
use App\Models\Vacancy;

/**
 * What Nara brings up when the chat opens (at most three): an interview in the next 48 hours, applications whose
 * job closed at the source, and new jobs for the user's saved searches. Each nudge carries a ready-made prompt.
 */
class NaraNudges
{
    public const MAX = 3;

    /** @return array<int, array{icon: string, text: string, prompt: string}> */
    public function for(User $user): array
    {
        return array_slice(array_values(array_filter([
            $this->interview($user),
            $this->closedJobs($user),
            $this->newMatches($user),
        ])), 0, self::MAX);
    }

    private function interview(User $user): ?array
    {
        $next = JobApplication::where('user_id', $user->id)->where(fn ($q) => $q->where('is_archived', false)->orWhereNull('is_archived'))
            ->whereBetween('interview_date', [now(), now()->addHours(48)])->orderBy('interview_date')->first(['company_name', 'position', 'interview_date']);
        if (! $next) {
            return null;
        }
        $when = $next->interview_date->isToday() ? __('today at :time', ['time' => $next->interview_date->format('H:i')])
            : __('tomorrow at :time', ['time' => $next->interview_date->format('H:i')]);
        if (! $next->interview_date->isToday() && ! $next->interview_date->isTomorrow()) {
            $when = $next->interview_date->locale(app()->getLocale())->translatedFormat('l, H:i');
        }

        return [
            'icon' => 'ph-calendar-check',
            'text' => __('Interview with :company :when.', ['company' => $next->company_name, 'when' => $when]),
            'prompt' => __('Help me prepare for my interview with :company for the :position role. Start a short mock interview based on the role.', ['company' => $next->company_name, 'position' => $next->position]),
        ];
    }

    private function closedJobs(User $user): ?array
    {
        $count = JobApplication::where('user_id', $user->id)->where('application_status', 'On Process')
            ->where(fn ($q) => $q->where('is_archived', false)->orWhereNull('is_archived'))
            ->whereHas('vacancy', fn ($q) => $q->whereIn('status', ['closed', 'archived']))->count();
        if (! $count) {
            return null;
        }

        return [
            'icon' => 'ph-x-circle',
            'text' => trans_choice('{1} One job you applied to has closed at its source.|[2,*] :count jobs you applied to have closed at their source.', $count, ['count' => $count]),
            'prompt' => __('Which of my applications are for jobs that have closed at the source, and what should I do about them?'),
        ];
    }

    private function newMatches(User $user): ?array
    {
        $best = null;
        foreach (SavedJobSearch::where('user_id', $user->id)->limit(5)->get() as $search) {
            $filters = (array) $search->filters;
            if (! VacancyFilters::narrows($filters)) {
                continue;
            }
            $count = VacancyFilters::apply(Vacancy::visible(), $filters)->where('first_seen_at', '>=', now()->subDay())->count();
            if ($count && (! $best || $count > $best['count'])) {
                $best = ['count' => $count, 'search' => $search, 'filters' => $filters];
            }
        }
        if (! $best) {
            return null;
        }
        $place = $best['filters']['selectedLocation'] ?? $best['filters']['selectedProvince'] ?? null;
        $role = $best['filters']['search'] ?? null;

        return [
            'icon' => 'ph-sparkle',
            'text' => trans_choice('{1} One new job for ":name" since yesterday.|[2,*] :count new jobs for ":name" since yesterday.', $best['count'], ['count' => $best['count'], 'name' => $best['search']->name]),
            // Phrased as a job request so Nara searches the catalogue (NaraJobFinder::criteria).
            'prompt' => trim(__('Find new :role jobs', ['role' => $role ?? '']).($place ? ' '.__('in :place', ['place' => $place]) : '')).(($best['filters']['selectedWorkType'] ?? '') === 'Remote' ? ' remote' : ''),
        ];
    }
}
