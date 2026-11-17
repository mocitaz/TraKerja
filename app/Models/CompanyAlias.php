<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `company_aliases` (TraKerja 2.0 Blueprint). */
class CompanyAlias extends Model
{
    protected $table = 'company_aliases';

    protected $guarded = ['id'];

    protected $casts = [];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
