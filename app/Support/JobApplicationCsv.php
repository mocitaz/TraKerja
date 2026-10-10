<?php

namespace App\Support;

use App\Models\JobApplication;

class JobApplicationCsv
{
    public const HEADERS = ['Company Name', 'Position', 'Location', 'Platform', 'Application Status', 'Recruitment Stage', 'Career Level', 'Application Date', 'Platform Link', 'Notes', 'Interview Date', 'Interview Type', 'Interview Location', 'Interview Notes', 'CSV Encoding'];
    private const COLUMNS = ['company_name', 'position', 'location', 'platform', 'application_status', 'recruitment_stage', 'career_level', 'application_date', 'platform_link', 'notes', 'interview_date', 'interview_type', 'interview_location', 'interview_notes'];

    public static function text(mixed $value): string
    {
        $value = (string) ($value ?? '');
        return preg_match('/^[\s]*[=+@-]/u', $value) || str_starts_with($value, "'") ? "'".$value : $value;
    }

    public static function row(JobApplication $job): array
    {
        $row = [];
        foreach (self::COLUMNS as $column) {
            $value = $job->$column;
            if ($column === 'application_date') $value = $value?->format('Y-m-d');
            if ($column === 'interview_date') $value = $value?->format('Y-m-d H:i:s');
            $row[] = self::text($value);
        }
        return [...$row, 'trakerja-v1'];
    }
}
