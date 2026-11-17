<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `crawl_runs` (TraKerja 2.0 Blueprint). */
class CrawlRun extends Model
{
    protected $table = 'crawl_runs';

    protected $guarded = ['id'];

    protected $casts = ['started_at' => 'datetime', 'finished_at' => 'datetime', 'cost_usd' => 'decimal:6'];

    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(JobSource::class, 'job_source_id');
    }
}
