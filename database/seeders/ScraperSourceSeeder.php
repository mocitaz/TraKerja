<?php

namespace Database\Seeders;

use App\Models\ScraperSource;
use Illuminate\Database\Seeder;

class ScraperSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScraperSource::updateOrCreate(
            ['target_domain' => 'linkedin.com'],
            [
                'name' => 'LinkedIn Indonesia',
                'seed_url' => 'https://id.linkedin.com/jobs/search?keywords=Laravel',
                'is_active' => true,
                'selectors_config' => [
                    'title' => '.base-search-card__title',
                    'company' => '.base-search-card__subtitle',
                    'description' => '.show-more-less-html__markup',
                    'link' => 'a.base-card__full-link',
                ],
                'frequency_minutes' => 360,
                'delay_between_requests_ms' => 1500,
                'max_concurrency' => 5,
            ]
        );

        ScraperSource::updateOrCreate(
            ['target_domain' => 'jobstreet.co.id'],
            [
                'name' => 'JobStreet Indonesia',
                'seed_url' => 'https://www.jobstreet.co.id/id/laravel-jobs',
                'is_active' => true,
                'selectors_config' => [
                    'title' => 'h1[data-automation="job-detail-title"]',
                    'company' => 'span[data-automation="advertiser-name"]',
                    'description' => 'div[data-automation="jobDescription"]',
                    'link' => 'a[data-automation="job-card-title"]',
                ],
                'frequency_minutes' => 360,
                'delay_between_requests_ms' => 2000,
                'max_concurrency' => 3,
            ]
        );

        ScraperSource::updateOrCreate(
            ['target_domain' => 'kalibrr.com'],
            [
                'name' => 'Kalibrr Indonesia',
                'seed_url' => 'https://www.kalibrr.com/job-board/te/laravel/1',
                'is_active' => true,
                'selectors_config' => [
                    'title' => 'h1.k-text-title',
                    'company' => 'a.k-text-subdued',
                    'description' => 'div.k-description',
                    'link' => 'a.k-card-title',
                ],
                'frequency_minutes' => 360,
                'delay_between_requests_ms' => 1000,
                'max_concurrency' => 4,
            ]
        );
    }
}
