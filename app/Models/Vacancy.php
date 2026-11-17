<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `vacancies` (TraKerja 2.0 Blueprint). */
class Vacancy extends Model
{
    protected $table = 'vacancies';

    protected $guarded = ['id'];

    protected $casts = ['skills' => 'array', 'is_internship' => 'boolean', 'date_posted' => 'date', 'valid_through' => 'date', 'closed_at' => 'datetime', 'first_seen_at' => 'datetime', 'last_seen_open_at' => 'datetime', 'last_verified_at' => 'datetime', 'extraction_confidence' => 'decimal:2', 'enriched_at' => 'datetime', 'ai_filled' => 'array'];

    use \Illuminate\Database\Eloquent\Concerns\HasUlids;

    /** Only public_id is a ULID; the primary key stays an auto-increment id. */
    public function uniqueIds(): array
    {
        return ['public_id'];
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function listings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VacancyListing::class);
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function sector(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TaxonomySector::class, 'sector_id');
    }
}
