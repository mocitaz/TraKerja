<?php

namespace App\Support;

final class UiLabel
{
    public static function value(?string $value): string
    {
        $value ??= '';
        $known = [...JobApplicationStatus::OPTIONS, ...JobApplicationStatus::STAGES,
            'Full Time', 'Part Time', 'Contract', 'Intern', 'Internship', 'Freelance', 'MT',
            'Permanent', 'Remote', 'Hybrid', 'Onsite', 'Other', 'Beginner', 'Intermediate', 'Advanced', 'Expert'];
        return in_array($value, $known, true) ? __($value) : $value;
    }
}
