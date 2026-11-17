<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Job-data engine table `companies` (TraKerja 2.0 Blueprint). */
class Company extends Model
{
    protected $table = 'companies';

    protected $guarded = ['id'];

    protected $casts = ['is_verified' => 'boolean'];

    public function aliases(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompanyAlias::class);
    }

    public function vacancies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Vacancy::class);
    }
}
