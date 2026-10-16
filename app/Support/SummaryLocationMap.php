<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SummaryLocationMap
{
    public static function fromRows(Collection $rows): array
    {
        $places = json_decode(file_get_contents(resource_path('data/indonesia-places.json')), true);
        $boundaries = json_decode(file_get_contents(resource_path('data/indonesia-city-boundaries.json')), true);
        $aliases = ['bali' => 'denpasar', 'solo' => 'surakarta', 'jogja' => 'yogyakarta', 'dki jakarta' => 'jakarta', 'banjarmasin' => 'bandjarmasin'];
        $mapped = [];
        $unmapped = [];
        foreach ($rows as $row) {
            $key = trim(Str::lower(Str::ascii(explode(',', $row['name'])[0])));
            $key = preg_replace('/^(kota|kabupaten|kab\.)\s+/', '', $key);
            $key = $aliases[$key] ?? $key;
            if (!isset($places[$key])) {
                $unmapped[] = $row;
                continue;
            }
            if (!isset($mapped[$key])) {
                [$longitude, $latitude] = $places[$key];
                $regionNames = $key === 'jakarta'
                    ? ['kota jakarta barat', 'kota jakarta pusat', 'kota jakarta selatan', 'kota jakarta timur', 'kota jakarta utara']
                    : [isset($boundaries['kota '.$key]) ? 'kota '.$key : $key];
                $regions = collect($regionNames)->map(fn ($name) => $boundaries[$name] ?? null)->filter();
                if ($regions->isEmpty()) {
                    $unmapped[] = $row;
                    continue;
                }
                $x = round(($longitude - 94) * 20, 2);
                $y = round((7 - $latitude) * 20, 2);
                $left = $regions->min(fn ($r) => $r['bounds'][0]);
                $top = $regions->min(fn ($r) => $r['bounds'][1]);
                $right = $regions->max(fn ($r) => $r['bounds'][2]);
                $bottom = $regions->max(fn ($r) => $r['bounds'][3]);
                $extent = max(4, max($right - $left, $bottom - $top) * 1.8);
                $centerX = ($left + $right) / 2;
                $centerY = ($top + $bottom) / 2;
                $mapped[$key] = ['name' => ucwords($key), 'total' => 0, 'interviews' => 0, 'accepted' => 0, 'x' => $x, 'y' => $y, 'labelX' => $centerX, 'labelY' => $centerY, 'labels' => [], 'path' => $regions->pluck('path')->implode(' '), 'view' => ($centerX - $extent * 1.2).' '.($centerY - $extent / 2).' '.($extent * 2.4).' '.$extent];
            }
            foreach (['total', 'interviews', 'accepted'] as $metric) {
                $mapped[$key][$metric] += $row[$metric];
            }
            $mapped[$key]['labels'][] = $row['name'];
        }

        $maximum = max(1, collect($mapped)->max('total') ?? 1);
        $total = max(1, $rows->sum('total'));
        foreach ($mapped as &$place) {
            $intensity = $place['total'] / $maximum;
            $place['color'] = sprintf('#%02x%02x%02x', round(231 - 158 * $intensity), round(241 - 134 * $intensity), round(235 - 139 * $intensity));
            $place['share'] = round($place['total'] / $total * 100, 1);
        }
        unset($place);

        $views = collect($mapped)->pluck('view')->map(fn ($view) => array_map('floatval', explode(' ', $view)));
        $districts = collect(json_decode(file_get_contents(resource_path('data/indonesia-district-outlines.json')), true))
            ->filter(fn ($district) => $district['path'] !== '' && $views->contains(fn ($view) =>
                $district['bounds'][0] <= $view[0] + $view[2] && $district['bounds'][2] >= $view[0]
                && $district['bounds'][1] <= $view[1] + $view[3] && $district['bounds'][3] >= $view[1]
            ))->values()->all();

        return [
            'mapped' => array_values($mapped), 'unmapped' => $unmapped, 'maximum' => $maximum,
            'districts' => $districts,
        ];
    }
}
