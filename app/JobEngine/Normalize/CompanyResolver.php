<?php

namespace App\JobEngine\Normalize;

use App\Models\Company;
use App\Models\CompanyAlias;
use Illuminate\Support\Str;

/**
 * Finds or creates the canonical company for a scraped name/domain.
 * Order: verified domain, then a known alias, then a new company. Name matching alone is
 * deliberately conservative ("Flip" on Greenhouse is a US company, not Flip Indonesia), so a
 * domain always wins and fuzzy merging is left to dedup review.
 */
class CompanyResolver
{
    public function resolve(string $name, ?string $domain = null, ?string $logo = null): Company
    {
        $normalized = self::normalize($name);

        if ($domain && ($company = Company::where('domain', $domain)->first())) {
            $this->remember($company, $normalized);

            return $company;
        }

        $alias = CompanyAlias::where('alias_normalized', $normalized)->first();
        // A name match is only trusted when it does not contradict a known domain:
        // "Flip" with flip.com must not merge into the "Flip" that owns flip.id.
        if ($alias && (! $domain || ! $alias->company->domain || $alias->company->domain === $domain)) {
            $company = $alias->company;
            if ($domain && ! $company->domain && ! Company::where('domain', $domain)->exists()) {
                $company->update(['domain' => $domain]);
            }

            return $company;
        }

        $company = Company::create([
            'name' => self::displayName($name),
            'slug' => $this->uniqueSlug(self::displayName($name)),
            'name_normalized' => $normalized,
            'domain' => $domain && ! Company::where('domain', $domain)->exists() ? $domain : null,
            'logo_url' => $logo,
        ]);
        $this->remember($company, $normalized);

        return $company;
    }

    /** Lowercase, no legal-entity words, branch suffixes or punctuation: the alias key. */
    public static function normalize(string $name): string
    {
        $value = mb_strtolower(html_entity_decode($name));
        $value = preg_replace('/\((?:cab(?:ang)?|branch)[^)]*\)|\bcab(?:ang)?\.?\s+\S+.*$/u', ' ', $value);
        $value = preg_replace('/\b(pt|tbk|persero|cv|ltd|inc|corp|co|llc|pte)\b\.?/u', ' ', $value);

        return trim(preg_replace('/[^\p{L}\p{N}]+/u', ' ', $value));
    }

    /** Readable name without "PT." prefix, Tbk suffix or branch note. */
    public static function displayName(string $name): string
    {
        $value = trim(html_entity_decode($name));
        $value = preg_replace('/\s*\((?:cab(?:ang)?|branch)[^)]*\)/iu', '', $value);
        $value = preg_replace('/^(pt\.?|cv\.?)\s+/iu', '', $value);
        $value = preg_replace('/,?\s+tbk\.?$/iu', '', $value);

        return trim($value) ?: trim($name);
    }

    private function remember(Company $company, string $normalized): void
    {
        if ($normalized !== '') {
            CompanyAlias::firstOrCreate(['alias_normalized' => $normalized], ['company_id' => $company->id]);
        }
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'perusahaan';
        $slug = $base;
        for ($i = 2; Company::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }
}
