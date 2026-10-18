<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;

final class SelectionSchedule
{
    public const PLATFORMS = ['Google Meet', 'Zoom', 'Microsoft Teams', 'WhatsApp', 'Assessment portal', 'Other'];
    public const IMPORT_TYPES = ['Online', 'Onsite', 'Phone', 'Video', 'In-person', 'Panel'];

    public static function heading(?string $stage): string
    {
        return __(match ($stage) {
            'Psychotest' => 'Psychometric test schedule',
            'Assessment Test' => 'Assessment schedule',
            'LGD' => 'Group discussion schedule',
            'Presentation Round' => 'Presentation schedule',
            'HR - Interview', 'User - Interview' => 'Interview schedule',
            default => 'Selection schedule',
        });
    }

    public static function online(?string $method): bool
    {
        return in_array($method, ['Online', 'Video'], true);
    }

    public static function methods(?string $stage, ?string $existing = null): array
    {
        $options = ['Online' => 'Online', 'Onsite' => 'Onsite'];
        if (in_array($stage, ['HR - Interview', 'User - Interview'], true)) $options['Phone'] = 'Phone call';
        if ($existing === 'Video') { unset($options['Online']); $options = ['Video' => 'Online'] + $options; }
        if ($existing === 'In-person') { unset($options['Onsite']); $options['In-person'] = 'Onsite'; }
        if ($existing === 'Panel') $options['Panel'] = 'Panel (existing record)';
        if ($existing === 'Phone') $options['Phone'] = 'Phone call';
        return $options;
    }

    public static function methodLabel(?string $method): string
    {
        return __(match ($method) {
            'Video', 'Online' => 'Online', 'In-person', 'Onsite' => 'Onsite',
            'Phone' => 'Phone call', 'Panel' => 'Panel (delivery not recorded)', default => 'Not recorded',
        });
    }

    public static function locationLabel(?string $method): string
    {
        return __(match ($method) {
            'Online', 'Video' => 'Access link', 'Onsite', 'In-person' => 'Venue / address',
            'Phone' => 'Contact number / call instructions', default => 'Location or access details',
        });
    }

    public static function platformAvailable(): bool
    {
        return Schema::hasColumn('job_applications', 'interview_platform');
    }
}
