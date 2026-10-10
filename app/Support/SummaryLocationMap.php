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
                $x = round(($longitude - 94) * 20, 2);
                $y = round((7 - $latitude) * 20, 2);
                $extent = max(12, $regions->max(fn ($r) => max($r['bounds'][2] - $r['bounds'][0], $r['bounds'][3] - $r['bounds'][1])) * 2);
                $mapped[$key] = ['name' => ucwords($key), 'total' => 0, 'interviews' => 0, 'accepted' => 0, 'x' => $x, 'y' => $y, 'labels' => [], 'path' => $regions->pluck('path')->implode(' '), 'view' => ($x - $extent * 1.2).' '.($y - $extent / 2).' '.($extent * 2.4).' '.$extent];
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
            $place['color'] = sprintf('#%02x%02x%02x', round(237 - 91 * $intensity), round(217 - 147 * $intensity), round(233 - 104 * $intensity));
            $place['share'] = round($place['total'] / $total * 100, 1);
        }
        unset($place);

        return ['mapped' => array_values($mapped), 'unmapped' => $unmapped, 'maximum' => $maximum];
    }
}
