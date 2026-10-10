<?php

namespace App\Support;

use Illuminate\Support\Collection;

final class ApplicationSummary
{
    public static function fromApplications(Collection $jobs): array
    {
        $interviewStages = ['HR - Interview', 'User - Interview'];
        $rank = static function (Collection $group, string $name) use ($interviewStages): array {
            $interviews = $group->whereIn('recruitment_stage', $interviewStages)->count();
            return [
                'name' => $name ?: 'Not recorded',
                'total' => $group->count(),
                'interviews' => $interviews,
                'accepted' => $group->where('application_status', 'Accepted')->count(),
                'interview_share' => round($interviews / max(1, $group->count()) * 100, 1),
            ];
        };
        $group = static fn (string $field) => $jobs->groupBy($field)
            ->map(fn ($items, $name) => $rank($items, (string) $name))->sortByDesc('total')->values();
        $statusCounts = collect(JobApplicationStatus::OPTIONS)->mapWithKeys(
            fn ($status) => [$status => $jobs->where('application_status', $status)->count()]
        );
        $active = $jobs->where('application_status', 'On Process');
        return [
            'total' => $jobs->count(),
            'statuses' => $statusCounts,
            'offers' => $jobs->filter(fn ($job) => $job->application_status === 'Accepted' || $job->recruitment_stage === 'Offering')->count(),
            'archived' => $jobs->where('is_archived', true)->count(),
            'stages' => collect(JobApplicationStatus::STAGES)->map(fn ($stage) => [
                'name' => $stage, 'count' => $active->where('recruitment_stage', $stage)->count(),
            ]),
            'sources' => $group('platform'),
            'roles' => $group('position'),
            'locations' => $group('location'),
            'companies' => $group('company_name'),
            'employment' => $group('career_level'),
        ];
    }
}
