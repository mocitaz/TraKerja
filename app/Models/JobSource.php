<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `job_sources` (TraKerja 2.0 Blueprint). */
class JobSource extends Model
{
    protected $table = 'job_sources';

    protected $guarded = ['id'];

    protected $casts = ['config' => 'array', 'is_active' => 'boolean', 'robots_allowed' => 'boolean', 'robots_checked_at' => 'datetime', 'last_success_at' => 'datetime', 'next_discovery_at' => 'datetime'];

    public function listings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VacancyListing::class);
    }

    public function runs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CrawlRun::class);
    }
}
