<?php

namespace App\JobEngine\Personalization;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * The Explore filters as one query step, so Explore and saved-search alerts match the same vacancies.
 * Keys are the Explore property names stored in saved searches.
 */
class VacancyFilters
{
    public static function apply(Builder $query, array $filters): Builder
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $platform = (string) ($filters['selectedPlatform'] ?? '');
        $field = (string) ($filters['selectedField'] ?? '');
        $major = (string) ($filters['selectedMajor'] ?? '');
        $workType = (string) ($filters['selectedWorkType'] ?? '');
        $province = (string) ($filters['selectedProvince'] ?? '');
        $location = (string) ($filters['selectedLocation'] ?? '');

        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhereHas('company', fn ($c) => $c->where('name', 'like', $term)));
        }
        if ($platform !== '') {
            $query->whereHas('listings.source', fn ($q) => $q->where('slug', $platform));
        }
        if ($field !== '') {
            $query->whereHas('sector', fn ($q) => $q->where('name', $field));
        }
        if ($major !== '') {
            $query->whereIn('major_id', DB::table('taxonomy_majors')->where('name', $major)->select('id'));
        }
        if ($workType !== '') {
            $query->where('work_mode', strtolower($workType));
        }
        if ($location !== '') {
            $query->whereHas('region', fn ($q) => $q->where('name', $location));
        } elseif ($province !== '') {
            $query->whereHas('region', fn ($q) => $q->where('name', $province)->orWhereHas('parent', fn ($p) => $p->where('name', $province)));
        }

        return $query;
    }

    /** True when the filters narrow anything (an empty saved search would alert on every new job). */
    public static function narrows(array $filters): bool
    {
        foreach (['search', 'selectedPlatform', 'selectedField', 'selectedMajor', 'selectedWorkType', 'selectedProvince', 'selectedLocation'] as $key) {
            if (trim((string) ($filters[$key] ?? '')) !== '') {
                return true;
            }
        }

        return false;
    }
}
