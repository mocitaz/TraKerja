<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SatisfactionSurvey extends Model
{
    protected $fillable = [
        'user_id',
        'q1_overall',
        'q2_navigation',
        'q3_speed',
        'q4_cv_builder',
        'q5_ai_analyzer',
        'q6_job_tracker',
        'q7_cover_letter',
        'q8_interviews',
        'q9_premium',
        'q10_recommend',
        'q11_design',
        'q12_cv_templates',
        'feedback',
    ];

    /**
     * Get the user that filled the survey.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
