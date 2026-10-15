<?php

namespace App\Support;

final class JobApplicationStatus
{
    public const OPTIONS = ['On Process', 'Accepted', 'Rejected'];
    public const VALIDATION_RULE = 'required|string|in:On Process,Accepted,Rejected';
    public const STAGES = ['Applied', 'Follow Up', 'Assessment Test', 'Psychotest', 'HR - Interview', 'User - Interview', 'LGD', 'Presentation Round', 'Offering', 'Not Processed'];
    public const SCHEDULED_STAGES = ['HR - Interview', 'User - Interview', 'Psychotest', 'Assessment Test', 'LGD', 'Presentation Round'];

    public static function canSchedule(?string $stage): bool
    {
        return in_array($stage, self::SCHEDULED_STAGES, true);
    }

    public static function normalize(?string $status, ?string $stage): array
    {
        $status = trim($status ?? '');
        $stage = trim($stage ?? '');
        $phase = match ($status) {
            'Applied', 'Pending' => 'Applied',
            'Interview' => 'HR - Interview',
            'Offering', 'Offered' => 'Offering',
            default => null,
        };
        if ($phase !== null) {
            if ($stage === '' || $stage === 'Applied') {
                $stage = $phase;
            }
            $status = 'On Process';
        }
        if ($status === 'Withdrawn') {
            return ['application_status' => 'Rejected', 'recruitment_stage' => 'Not Processed'];
        }
        if ($status === 'Declined') {
            $status = 'Rejected';
        }
        return ['application_status' => $status, 'recruitment_stage' => $stage];
    }
}
