<?php

namespace App\JobEngine;

/**
 * One vacancy as a source shows it, before normalization. Adapters fill what the source gives;
 * everything else stays null for the normalizers (and later the AI) to fill.
 */
final class ScrapedJob
{
    public function __construct(
        public string $source,
        public string $url,
        public ?string $externalId = null,
        public ?string $title = null,
        public ?string $company = null,
        public ?string $companyDomain = null,
        public ?string $companyLogo = null,
        public ?string $locationText = null,
        public ?string $city = null,
        public ?string $province = null,
        public ?string $country = null,
        public ?string $employmentType = null,
        public ?string $workMode = null,
        public ?int $salaryMin = null,
        public ?int $salaryMax = null,
        public ?string $salaryCurrency = null,
        public ?string $salaryPeriod = null,
        public ?string $salaryText = null,
        public ?string $datePosted = null,
        public ?string $validThrough = null,
        public ?string $description = null,
        public ?string $applyUrl = null,
        public ?bool $isOpen = null,
        public ?string $educationMin = null,
        public string $extractionMethod = 'api',
        public array $raw = [],
    ) {}

    /** Stable fingerprint of what the source says, used to skip unchanged listings. */
    public function contentHash(): string
    {
        $fields = get_object_vars($this);
        unset($fields['raw']);

        return hash('sha256', json_encode($fields, JSON_UNESCAPED_UNICODE));
    }
}
