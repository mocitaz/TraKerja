<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `vacancy_listings` (TraKerja 2.0 Blueprint). */
class VacancyListing extends Model
{
    protected $table = 'vacancy_listings';

    protected $guarded = ['id'];

    protected $casts = ['raw_payload' => 'array', 'first_seen_at' => 'datetime', 'last_seen_at' => 'datetime', 'last_checked_at' => 'datetime', 'next_check_at' => 'datetime', 'match_score' => 'decimal:3'];

    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(JobSource::class, 'job_source_id');
    }

    public function vacancy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }
}
