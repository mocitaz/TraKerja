<?php

namespace App\JobEngine;

/** Allowed values for vacancy fields, shared by the engine, the golden set and the review page. */
final class Vocabulary
{
    public const EMPLOYMENT_TYPES = ['full_time', 'part_time', 'contract', 'internship', 'freelance'];

    public const WORK_MODES = ['onsite', 'hybrid', 'remote'];

    public const SENIORITY = ['intern', 'entry', 'mid', 'senior', 'lead', 'manager'];

    public const EDUCATION = ['sd', 'smp', 'sma_smk', 'd3', 's1', 's2', 'any'];

    public const SALARY_PERIODS = ['hour', 'day', 'week', 'month', 'year'];

    public const ENUMS = [
        'employment_type' => self::EMPLOYMENT_TYPES,
        'work_mode' => self::WORK_MODES,
        'seniority' => self::SENIORITY,
        'education_min' => self::EDUCATION,
    ];

    /** Map a source's employment-type wording (schema.org, Indonesian, English) to our vocabulary. */
    public static function employmentType(?string $value): ?string
    {
        $v = strtolower((string) $value);

        return match (true) {
            $v === '' => null,
            str_contains($v, 'intern') || str_contains($v, 'magang') => 'internship',
            str_contains($v, 'contract') || str_contains($v, 'kontrak') || str_contains($v, 'temporary') => 'contract',
            str_contains($v, 'part') || str_contains($v, 'paruh') => 'part_time',
            str_contains($v, 'freelance') || str_contains($v, 'lepas') || str_contains($v, 'per_diem') => 'freelance',
            str_contains($v, 'full') || str_contains($v, 'penuh') || str_contains($v, 'tetap') => 'full_time',
            default => null,
        };
    }

    public static function salaryPeriod(?string $value): ?string
    {
        return match (strtolower((string) $value)) {
            'hour', 'hourly', 'jam' => 'hour',
            'day', 'daily', 'hari' => 'day',
            'week', 'weekly', 'minggu' => 'week',
            'month', 'monthly', 'bulan' => 'month',
            'year', 'yearly', 'annual', 'tahun' => 'year',
            default => null,
        };
    }
}
