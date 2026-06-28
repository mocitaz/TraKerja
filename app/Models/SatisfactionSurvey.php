<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SatisfactionSurvey extends Model
{
    protected $fillable = [
        'user_id',
        'score',
        'ease_of_use',
        'features_helpful',
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
