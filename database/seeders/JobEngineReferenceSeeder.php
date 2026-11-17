<?php

namespace Database\Seeders;

use App\Helpers\CategoryHelper;
use App\Helpers\LocationHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Reference data for the job-data engine (TraKerja 2.0 Blueprint, Fase 1): sources from
 * config/job_sources.php, the 15-sector taxonomy from CategoryHelper and Indonesian regions
 * from LocationHelper. Idempotent: safe to run again after the helpers or registry change.
 *
 * php artisan db:seed --class=JobEngineReferenceSeeder
 */
class JobEngineReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $this->sources();
        $this->taxonomy();
        $this->regions();
    }

    private function sources(): void
    {
        foreach (config('job_sources.sources') as $slug => $source) {
            $robots = $source['robots'] ?? null;
            DB::table('job_sources')->updateOrInsert(['slug' => $slug], [
                'name' => $source['name'],
                'kind' => $source['kind'],
                'tier' => $source['tier'],
                'region_scope' => $source['region'] ?? 'mixed',
                'base_url' => $source['discovery'] ?? $source['endpoint'] ?? null,
                // Only tier A-C sources that are not explicitly disabled run by default.
                'is_active' => ($source['enabled'] ?? true) && $source['tier'] !== 'D',
                'robots_allowed' => $robots === null ? null : ! str_contains(strtolower($robots), 'disallowed') || str_contains(strtolower($robots), 'allowed ('),
                'robots_checked_at' => '2026-11-17 00:00:00',
                'config' => json_encode(array_diff_key($source, array_flip(['name', 'kind', 'tier', 'region']))),
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
    }

    private function taxonomy(): void
    {
        $sort = 0;
        foreach (CategoryHelper::getMap() as $sector => $majors) {
            $sectorSlug = Str::slug(Str::after($sector, 'Sektor '));
            DB::table('taxonomy_sectors')->updateOrInsert(['slug' => $sectorSlug], ['name' => $sector, 'sort' => $sort++]);
            $sectorId = DB::table('taxonomy_sectors')->where('slug', $sectorSlug)->value('id');

            foreach ($majors as $major => $keywords) {
                DB::table('taxonomy_majors')->updateOrInsert(
                    ['slug' => $sectorSlug.'--'.Str::slug($major)],
                    ['sector_id' => $sectorId, 'name' => $major, 'keywords' => json_encode(array_values($keywords))],
                );
            }
        }
    }

    /**
     * Cities and regencies exactly as listed in LocationHelper. getCitiesForProvince() normalizes
     * names and would merge "Kabupaten Bekasi" into "Kota Bekasi", so read the source list instead.
     */
    private function rawCities(string $province): array
    {
        $list = (new \ReflectionClass(LocationHelper::class))->getStaticPropertyValue('provinces');

        return array_values(array_unique($list[$province] ?? []));
    }

    private function regions(): void
    {
        foreach (LocationHelper::getAllProvinces() as $province) {
            $provinceSlug = Str::slug($province);
            $aliases = array_values(array_unique(array_filter([
                mb_strtolower($province),
                $province === 'DKI Jakarta' ? 'jakarta' : null,
                $province === 'DI Yogyakarta' ? 'yogyakarta' : null,
                $province === 'DI Yogyakarta' ? 'jogja' : null,
            ])));
            DB::table('regions')->updateOrInsert(['slug' => $provinceSlug], [
                'parent_id' => null, 'level' => 'province', 'name' => $province, 'aliases' => json_encode($aliases),
            ]);
            $provinceId = DB::table('regions')->where('slug', $provinceSlug)->value('id');

            $cityShortNames = [];
            foreach (array_merge(...array_map(fn ($p) => $this->rawCities($p), LocationHelper::getAllProvinces())) as $c) {
                if (! str_starts_with($c, 'Kabupaten')) {
                    $cityShortNames[mb_strtolower(trim(preg_replace('/^(Kota Administrasi|Kota)\s+/i', '', $c)))] = true;
                }
            }
            foreach ($this->rawCities($province) as $city) {
                $short = trim(preg_replace('/^(Kota Administrasi|Kabupaten Administrasi|Kota|Kabupaten)\s+/i', '', $city));
                $isRegency = str_starts_with($city, 'Kabupaten');
                $aliases = array_values(array_unique(array_filter([
                    mb_strtolower($city),
                    // "Bekasi" alone means the city; the regency keeps the "kab" prefix to avoid ambiguity.
                    $isRegency ? 'kab '.mb_strtolower($short) : mb_strtolower($short),
                    $isRegency ? 'kab. '.mb_strtolower($short) : mb_strtolower($short),
                    // ...unless no city has that name: "Bantul" and "Sleman" can only mean the regency.
                    $isRegency && ! isset($cityShortNames[mb_strtolower($short)]) ? mb_strtolower($short) : null,
                ])));
                DB::table('regions')->updateOrInsert(['slug' => $provinceSlug.'--'.Str::slug($city)], [
                    'parent_id' => $provinceId, 'level' => 'city', 'name' => $city, 'aliases' => json_encode($aliases),
                ]);
            }
        }
    }
}
