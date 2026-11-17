<?php

namespace App\JobEngine\Parsing;

use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/** Finds a schema.org JobPosting in a page and maps it to a ScrapedJob. */
final class JobPostingJsonLd
{
    public static function find(string $html): ?array
    {
        preg_match_all('#<script[^>]+application/ld\+json[^>]*>(.*?)</script>#s', $html, $m);
        foreach ($m[1] as $block) {
            $data = json_decode(trim($block), true);
            if (! is_array($data)) {
                continue;
            }
            $items = isset($data['@type']) ? [$data] : ($data['@graph'] ?? $data);
            foreach ((array) $items as $item) {
                $type = $item['@type'] ?? null;
                if (is_array($item) && ($type === 'JobPosting' || (is_array($type) && in_array('JobPosting', $type, true)))) {
                    return $item;
                }
            }
        }

        return null;
    }

    public static function toScrapedJob(array $p, string $source, string $url): ScrapedJob
    {
        $location = Arr::get($p, 'jobLocation');
        $address = is_array($location) && array_is_list($location) ? ($location[0]['address'] ?? []) : ($location['address'] ?? []);
        $address = is_string($address) ? ['streetAddress' => $address] : (array) $address;
        $salary = Arr::get($p, 'baseSalary.value', []);
        $salary = is_array($salary) ? $salary : ['value' => $salary];
        $country = $address['addressCountry'] ?? null;
        $country = is_array($country) ? ($country['name'] ?? null) : $country;
        $identifier = Arr::get($p, 'identifier.value', is_scalar($p['identifier'] ?? null) ? $p['identifier'] : null);

        return new ScrapedJob(
            source: $source,
            url: $url,
            externalId: $identifier !== null ? (string) $identifier : null,
            title: self::text($p['title'] ?? null),
            company: self::text(Arr::get($p, 'hiringOrganization.name')),
            companyDomain: self::domain(Arr::get($p, 'hiringOrganization.sameAs') ?: Arr::get($p, 'hiringOrganization.url')),
            companyLogo: is_string($logo = Arr::get($p, 'hiringOrganization.logo')) ? $logo : null,
            locationText: implode(', ', array_filter([$address['addressLocality'] ?? null, $address['addressRegion'] ?? null])) ?: null,
            city: self::text($address['addressLocality'] ?? null),
            province: self::text($address['addressRegion'] ?? null),
            country: is_string($country) && strlen($country) === 2 ? strtoupper($country) : (stripos((string) $country, 'indonesia') !== false ? 'ID' : null),
            employmentType: Vocabulary::employmentType(implode(' ', (array) ($p['employmentType'] ?? []))),
            workMode: ($p['jobLocationType'] ?? null) === 'TELECOMMUTE' ? 'remote' : null,
            salaryMin: self::int($salary['minValue'] ?? $salary['value'] ?? null),
            salaryMax: self::int($salary['maxValue'] ?? null),
            salaryCurrency: Arr::get($p, 'baseSalary.currency'),
            salaryPeriod: Vocabulary::salaryPeriod($salary['unitText'] ?? null),
            datePosted: self::date($p['datePosted'] ?? null),
            validThrough: self::date($p['validThrough'] ?? null),
            description: isset($p['description']) ? trim(strip_tags(html_entity_decode((string) $p['description']))) : null,
            applyUrl: is_string($p['url'] ?? null) ? $p['url'] : null,
            extractionMethod: 'jsonld',
            raw: $p,
        );
    }

    private static function text(mixed $value): ?string
    {
        return is_string($value) && trim($value) !== '' ? trim(html_entity_decode($value)) : null;
    }

    private static function int(mixed $value): ?int
    {
        return is_numeric($value) && (float) $value > 0 ? (int) round((float) $value) : null;
    }

    private static function date(mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }
        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    public static function domain(mixed $url): ?string
    {
        if (is_array($url)) {
            $url = $url[0] ?? null;
        }
        if (! is_string($url) || $url === '') {
            return null;
        }
        $host = parse_url(str_contains($url, '://') ? $url : "https://{$url}", PHP_URL_HOST);

        return $host ? preg_replace('/^www\./', '', strtolower($host)) : null;
    }
}
