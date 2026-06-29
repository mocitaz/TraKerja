<?php

namespace App\Helpers;

class LocationHelper
{
    protected static array $provinces = [
        'DKI Jakarta' => [
            'Jakarta', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Pusat', 'Jakarta Utara', 'Jakarta Timur', 'Jakarta Raya'
        ],
        'Jawa Barat' => [
            'Bandung', 'Bekasi', 'Depok', 'Bogor', 'Cimahi', 'Tasikmalaya', 'Cirebon', 'Sukabumi', 'Karawang', 'Cikarang'
        ],
        'Jawa Timur' => [
            'Surabaya', 'Malang', 'Sidoarjo', 'Gresik', 'Kediri', 'Madiun', 'Jember', 'Pasuruan', 'Banyuwangi', 'Batu'
        ],
        'Jawa Tengah' => [
            'Semarang', 'Surakarta', 'Solo', 'Magelang', 'Pekalongan', 'Tegal', 'Cilacap', 'Purwokerto', 'Kudus'
        ],
        'DI Yogyakarta' => [
            'Yogyakarta', 'Jogja', 'Sleman', 'Bantul', 'Kulon Progo', 'Gunungkidul'
        ],
        'Banten' => [
            'Tangerang Selatan', 'Tangerang', 'Serang', 'Cilegon', 'Balaraja'
        ],
        'Sumatera Utara' => [
            'Medan', 'Binjai', 'Deli Serdang', 'Pematangsiantar', 'Toba'
        ],
        'Sumatera Barat' => [
            'Padang', 'Bukittinggi', 'Payakumbuh'
        ],
        'Riau' => [
            'Pekanbaru', 'Dumai'
        ],
        'Kepulauan Riau' => [
            'Batam', 'Tanjungpinang'
        ],
        'Sumatera Selatan' => [
            'Palembang', 'Lubuklinggau', 'Prabumulih'
        ],
        'Lampung' => [
            'Bandar Lampung', 'Metro'
        ],
        'Bali' => [
            'Denpasar', 'Badung', 'Gianyar', 'Singaraja', 'Kuta'
        ],
        'Nusa Tenggara Barat' => [
            'Mataram', 'Lombok', 'Sumbawa'
        ],
        'Nusa Tenggara Timur' => [
            'Kupang', 'Labuan Bajo'
        ],
        'Kalimantan Barat' => [
            'Pontianak', 'Singkawang'
        ],
        'Kalimantan Selatan' => [
            'Banjarmasin', 'Banjarbaru', 'Martapura'
        ],
        'Kalimantan Timur' => [
            'Samarinda', 'Balikpapan', 'Bontang', 'Kutai'
        ],
        'Sulawesi Selatan' => [
            'Makassar', 'Gowa', 'Maros', 'Parepare'
        ],
        'Sulawesi Utara' => [
            'Manado', 'Bitung', 'Tomohon'
        ],
        'Sulawesi Tengah' => [
            'Palu'
        ],
        'Sulawesi Tenggara' => [
            'Kendari'
        ],
        'Gorontalo' => [
            'Gorontalo'
        ],
        'Maluku' => [
            'Ambon'
        ],
        'Papua' => [
            'Jayapura', 'Sorong', 'Manokwari', 'Merauke'
        ]
    ];
    
    protected static array $remoteKeywords = [
        'remote', 'wfh', 'work from home', 'kerja dari rumah', 'telecommute'
    ];

    /**
     * Classify location text into normalized province and city.
     */
    public static function classify(string $locationText, string $title = '', string $description = ''): array
    {
        $locationText = trim($locationText);
        
        // 1. Check for remote/wfh
        foreach (self::$remoteKeywords as $keyword) {
            if (stripos($locationText, $keyword) !== false || 
                stripos($title, $keyword) !== false) {
                return [
                    'province' => 'Remote / WFH',
                    'city' => 'Remote'
                ];
            }
        }
        
        // 2. Scan locationText for specific cities
        foreach (self::$provinces as $province => $cities) {
            foreach ($cities as $city) {
                if (stripos($locationText, $city) !== false) {
                    return [
                        'province' => $province,
                        'city' => self::normalizeCity($city)
                    ];
                }
            }
        }
        
        // 3. Scan title and description for specific cities
        foreach (self::$provinces as $province => $cities) {
            foreach ($cities as $city) {
                if (stripos($title, $city) !== false || 
                    stripos(substr($description, 0, 1000), $city) !== false) {
                    return [
                        'province' => $province,
                        'city' => self::normalizeCity($city)
                    ];
                }
            }
        }
        
        // 4. Default fallback
        if (stripos($locationText, 'indonesia') !== false) {
            return [
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta'
            ];
        }
        
        return [
            'province' => 'Lainnya',
            'city' => !empty($locationText) ? self::normalizeCity($locationText) : 'Indonesia'
        ];
    }
    
    /**
     * Normalize specific city variants to standard name.
     */
    public static function normalizeCity(string $city): string
    {
        $city = trim($city);
        if (strcasecmp($city, 'Solo') === 0) return 'Surakarta';
        if (strcasecmp($city, 'Jogja') === 0) return 'Yogyakarta';
        if (strcasecmp($city, 'Jakarta Raya') === 0) return 'Jakarta';
        if (strcasecmp($city, 'Jakarta Selatan') === 0) return 'Jakarta';
        if (strcasecmp($city, 'Jakarta Barat') === 0) return 'Jakarta';
        if (strcasecmp($city, 'Jakarta Pusat') === 0) return 'Jakarta';
        if (strcasecmp($city, 'Jakarta Utara') === 0) return 'Jakarta';
        if (strcasecmp($city, 'Jakarta Timur') === 0) return 'Jakarta';
        return $city;
    }

    /**
     * Map a normalized city back to its province.
     */
    public static function getProvinceForCity(string $city): string
    {
        $city = self::normalizeCity($city);
        foreach (self::$provinces as $province => $cities) {
            foreach ($cities as $c) {
                if (strcasecmp($city, self::normalizeCity($c)) === 0) {
                    return $province;
                }
            }
        }
        
        if (strcasecmp($city, 'Remote') === 0) {
            return 'Remote / WFH';
        }
        
        return 'Lainnya';
    }

    /**
     * Get statistics of jobs per province and city.
     */
    public static function getLocationStatistics(): array
    {
        $jobLocations = \App\Models\JobPosting::where('status', 'active')
            ->whereNotNull('location')
            ->select('location', \DB::raw('count(*) as count'))
            ->groupBy('location')
            ->get();
            
        $stats = [];
        
        foreach ($jobLocations as $item) {
            $city = $item->location;
            $count = $item->count;
            $province = self::getProvinceForCity($city);
            
            if (!isset($stats[$province])) {
                $stats[$province] = [
                    'count' => 0,
                    'cities' => []
                ];
            }
            
            $stats[$province]['count'] += $count;
            $stats[$province]['cities'][] = [
                'name' => $city,
                'count' => $count
            ];
        }
        
        // Sort provinces by job count descending
        uasort($stats, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        
        return $stats;
    }
}
