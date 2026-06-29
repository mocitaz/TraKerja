<?php

namespace Database\Seeders;

use App\Models\ScraperSource;
use App\Models\JobPosting;
use Illuminate\Database\Seeder;

class DummyJobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $linkedin = ScraperSource::where('target_domain', 'linkedin.com')->first();
        $jobstreet = ScraperSource::where('target_domain', 'jobstreet.co.id')->first();
        $kalibrr = ScraperSource::where('target_domain', 'kalibrr.com')->first();

        // Fallbacks if seeder runs before sources seeder
        if (!$linkedin) {
            $linkedin = ScraperSource::create([
                'name' => 'LinkedIn Indonesia',
                'target_domain' => 'linkedin.com',
                'seed_url' => 'https://id.linkedin.com/jobs/search?keywords=Laravel',
                'selectors_config' => ['title' => '.job-title'],
            ]);
        }
        if (!$jobstreet) {
            $jobstreet = ScraperSource::create([
                'name' => 'JobStreet Indonesia',
                'target_domain' => 'jobstreet.co.id',
                'seed_url' => 'https://www.jobstreet.co.id/id/laravel-jobs',
                'selectors_config' => ['title' => '.job-title'],
            ]);
        }
        if (!$kalibrr) {
            $kalibrr = ScraperSource::create([
                'name' => 'Kalibrr Indonesia',
                'target_domain' => 'kalibrr.com',
                'seed_url' => 'https://www.kalibrr.com/job-board/te/laravel/1',
                'selectors_config' => ['title' => '.job-title'],
            ]);
        }

        // 1. LinkedIn Jobs
        JobPosting::updateOrCreate(
            ['unique_hash' => md5('https://linkedin.com/jobs/view/1001')],
            [
                'scraper_source_id' => $linkedin->id,
                'title' => 'Senior Laravel Developer',
                'company_name' => 'Gojek Tokopedia (GoTo)',
                'description' => 'Kami sedang mencari Senior Laravel Developer berpengalaman untuk merancang, membangun, dan memelihara sistem core backend berskala besar.',
                'raw_url' => 'https://linkedin.com/jobs/view/1001',
                'status' => 'active',
                'last_validated_at' => now(),
            ]
        );
        JobPosting::updateOrCreate(
            ['unique_hash' => md5('https://linkedin.com/jobs/view/1002')],
            [
                'scraper_source_id' => $linkedin->id,
                'title' => 'Backend Engineer (PHP / Laravel)',
                'company_name' => 'Bukalapak',
                'description' => 'Bergabunglah bersama tim engineering Bukalapak untuk mengembangkan platform marketplace terdepan menggunakan arsitektur microservices PHP Laravel.',
                'raw_url' => 'https://linkedin.com/jobs/view/1002',
                'status' => 'active',
                'last_validated_at' => now(),
            ]
        );

        // 2. JobStreet Jobs
        JobPosting::updateOrCreate(
            ['unique_hash' => md5('https://jobstreet.co.id/view/2001')],
            [
                'scraper_source_id' => $jobstreet->id,
                'title' => 'Full Stack Web Developer (Laravel & Vue)',
                'company_name' => 'PT. Telkom Indonesia',
                'description' => 'Membangun aplikasi internal perusahaan berbasis Laravel 10 dan Vue.js. Memerlukan pemahaman kuat tentang REST API, database relasional, dan clean code.',
                'raw_url' => 'https://jobstreet.co.id/view/2001',
                'status' => 'active',
                'last_validated_at' => now(),
            ]
        );
        JobPosting::updateOrCreate(
            ['unique_hash' => md5('https://jobstreet.co.id/view/2002')],
            [
                'scraper_source_id' => $jobstreet->id,
                'title' => 'PHP Programmer (Laravel)',
                'company_name' => 'PT. Astra International',
                'description' => 'Kandidat akan bertanggung jawab untuk memelihara aplikasi web otomotif Astra, menulis unit testing PHPUnit, dan berkolaborasi dengan QA team.',
                'raw_url' => 'https://jobstreet.co.id/view/2002',
                'status' => 'active',
                'last_validated_at' => now(),
            ]
        );

        // 3. Kalibrr Jobs
        JobPosting::updateOrCreate(
            ['unique_hash' => md5('https://kalibrr.com/view/3001')],
            [
                'scraper_source_id' => $kalibrr->id,
                'title' => 'Junior Web Developer (Laravel)',
                'company_name' => 'KoinWorks',
                'description' => 'Posisi terbuka untuk lulusan baru yang bersemangat mempelajari ekosistem fintech modern. Kami menggunakan Laravel, Docker, dan CI/CD pipeline.',
                'raw_url' => 'https://kalibrr.com/view/3001',
                'status' => 'active',
                'last_validated_at' => now(),
            ]
        );
        JobPosting::updateOrCreate(
            ['unique_hash' => md5('https://kalibrr.com/view/3002')],
            [
                'scraper_source_id' => $kalibrr->id,
                'title' => 'Senior Backend Developer (Laravel)',
                'company_name' => 'Bibit.id',
                'description' => 'Memimpin tim backend beranggotakan 4 engineer. Mengoptimalkan performa kueri database SQL, Redis cache, dan integrasi payment gateway Midtrans.',
                'raw_url' => 'https://kalibrr.com/view/3002',
                'status' => 'active',
                'last_validated_at' => now(),
            ]
        );
    }
}
