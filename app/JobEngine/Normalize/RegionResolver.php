<?php

namespace App\JobEngine\Normalize;

use App\Models\Region;

/** Maps free-text city/province names to rows in `regions` using their aliases. */
class RegionResolver
{
    /** @var array<string, int>|null */
    private ?array $cities = null;

    /** @var array<string, int>|null */
    private ?array $provinces = null;

    public function resolve(?string $city, ?string $province = null, ?string $locationText = null): ?int
    {
        $this->load();

        // Most specific first: an explicit "X Regency" / "Kabupaten X" / "X City" segment, then the city
        // field, then every other segment ("Omdo, Pacitan, Indonesia": the village is not a region).
        $segments = $locationText ? array_values(array_filter(array_map('trim', explode(',', $locationText)))) : [];
        $explicit = array_filter($segments, fn ($s) => preg_match('/\b(regency|city|kabupaten|kab\.?|kota)\b/iu', $s));
        foreach (array_unique(array_filter([...$explicit, $city, ...$segments])) as $candidate) {
            $key = self::key($candidate);
            if (isset($this->cities[$key])) {
                return $this->cities[$key];
            }
        }
        foreach (array_filter([$province, $city, $locationText]) as $candidate) {
            $key = self::key($candidate);
            if (isset($this->provinces[$key])) {
                return $this->provinces[$key];
            }
        }

        return null;
    }

    /** @var array<string, int>|null alias => region id, aliases of 4+ characters only */
    private ?array $mentionIndex = null;

    /**
     * Region ids named anywhere in a text (e.g. a job title "SPG Store - Bantul").
     *
     * @return array<int, int>
     */
    public function mentioned(string $text): array
    {
        $this->load();
        if ($this->mentionIndex === null) {
            $this->mentionIndex = [];
            foreach ([$this->cities, $this->provinces] as $map) {
                foreach ($map as $alias => $id) {
                    // 'kab tangerang' is kept: it must not be read as the city 'tangerang'.
                    if (mb_strlen($alias) >= 4) {
                        $this->mentionIndex[$alias] ??= $id;
                    }
                }
            }
            uksort($this->mentionIndex, fn ($a, $b) => mb_strlen($b) <=> mb_strlen($a)); // longest first
        }

        $haystack = ' '.self::key($text).' ';
        $found = [];
        foreach ($this->mentionIndex as $alias => $id) {
            if (str_contains($haystack, ' '.$alias.' ')) {
                $found[$id] = $id;
                $haystack = str_replace(' '.$alias.' ', ' ', $haystack); // "jakarta barat" must not also count as "jakarta"
            }
        }

        return array_values($found);
    }

    public static function key(string $value): string
    {
        $value = mb_strtolower(trim($value));
        // English forms used by international ATS boards: "Jember Regency", "Kupang City", "South Jakarta".
        $value = preg_replace('/^(south|west|east|north|central)\s+jakarta(\s+city)?$/u', 'jakarta $1', $value);
        $value = strtr($value, ['jakarta south' => 'jakarta selatan', 'jakarta west' => 'jakarta barat', 'jakarta east' => 'jakarta timur', 'jakarta north' => 'jakarta utara', 'jakarta central' => 'jakarta pusat']);
        $value = preg_replace('/^(.+?)\s+regency$/u', 'kab $1', $value);
        $value = preg_replace('/^(.+?)\s+city$/u', '$1', $value);
        $value = preg_replace('/^special (capital )?region of\s+/u', '', $value);
        $value = preg_replace('/^(kota administrasi|kota adm\.?|kotamadya)\s+/u', '', $value);
        $value = preg_replace('/^(kab\.|kab|kabupaten)\s+/u', 'kab ', $value);
        $value = preg_replace('/^(kota)\s+/u', '', $value);
        $value = preg_replace('/^(provinsi|prov\.?)\s+/u', '', $value);
        $value = str_replace(['d.k.i.', 'd.i.'], ['dki', 'di'], $value);

        return trim(preg_replace('/\s+/u', ' ', preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $value)));
    }

    private function load(): void
    {
        if ($this->cities !== null) {
            return;
        }
        $this->cities = [];
        $this->provinces = [];
        foreach (Region::all(['id', 'level', 'name', 'aliases']) as $region) {
            $target = $region->level === 'city' ? 'cities' : 'provinces';
            foreach (array_merge([$region->name], $region->aliases ?? []) as $alias) {
                $this->{$target}[self::key($alias)] ??= $region->id;
            }
        }
    }
}
