<?php

namespace App\Support;

class PlatformCatalog
{
    public const OPTIONS = [
        '9cv9', 'Cake: Cari Lowongan', 'Dealls', 'Disnakerja.com', 'Email', 'Fiverr', 'Freelancer',
        'Glassdoor', 'Glints', 'Google Forms', 'Indeed', 'JobStreet', 'Jobseeker App', 'JobsDB',
        'Jora', 'Kalibrr', 'Karir.com', 'Karirhub (SIAPkerja)', 'KitaLulus', 'LinkedIn', 'Loker.id',
        'Microsoft Forms', 'NusaCrowd', 'Pintarnya.com', 'SkillAcademy', 'SkillTrade', 'Talentics',
        'Tech in Asia', 'Urbanhire', 'Website Company', 'Other',
    ];

    private const SLUGS = [
        '9cv9' => '9cv9', 'cake: cari lowongan' => 'cake', 'cake' => 'cake', 'cakeresume' => 'cake',
        'dealls' => 'dealls', 'disnakerja.com' => 'disnakerja', 'disnakerja' => 'disnakerja',
        'fiverr' => 'fiverr', 'freelancer' => 'freelancer', 'glassdoor' => 'glassdoor',
        'glints' => 'glints', 'google forms' => 'google-forms', 'indeed' => 'indeed',
        'jobstreet' => 'jobstreet', 'jobstreet by seek' => 'jobstreet', 'jobseeker app' => 'jobseeker',
        'jobsdb' => 'jobsdb', 'jora' => 'jora', 'kalibrr' => 'kalibrr', 'karir.com' => 'karir',
        'karirhub (siapkerja)' => 'karirhub', 'karirhub' => 'karirhub', 'kitalulus' => 'kitalulus',
        'linkedin' => 'linkedin', 'loker.id' => 'loker', 'microsoft forms' => 'microsoft-forms',
        'pintarnya.com' => 'pintarnya', 'pintarnya' => 'pintarnya', 'skillacademy' => 'skillacademy',
        'talentics' => 'talentics', 'tech in asia' => 'tech-in-asia', 'urbanhire' => 'urbanhire',
        // Job-engine source names (TraKerja 2.0) and the boards they read.
        'jobstreet (seek)' => 'jobstreet', 'karirhub kemnaker' => 'karirhub', 'tech in asia jobs' => 'tech-in-asia',
        'himalayas' => 'himalayas', 'jobicy' => 'jobicy', 'we work remotely' => 'weworkremotely',
        'greenhouse' => 'greenhouse', 'greenhouse job boards' => 'greenhouse', 'lever' => 'lever', 'lever postings' => 'lever',
        'workable' => 'workable', 'workable job boards' => 'workable', 'ashby' => 'ashby', 'ashby job boards' => 'ashby',
        'smartrecruiters' => 'smartrecruiters', 'smartrecruiters postings' => 'smartrecruiters',
        'careerjet' => 'careerjet', 'careerjet (official api)' => 'careerjet', 'jooble' => 'jooble', 'jooble (official api)' => 'jooble',
        'remoteok' => 'remoteok', 'remotive' => 'remotive',
    ];

    public static function nameForDomain(?string $domain): ?string
    {
        $host = strtolower(parse_url(str_contains($domain ?? '', '://') ? $domain : 'https://'.($domain ?? ''), PHP_URL_HOST) ?? '');
        foreach ([
            'linkedin.com' => 'LinkedIn', 'jobstreet.co.id' => 'JobStreet', 'jobstreet.com' => 'JobStreet',
            'kalibrr.com' => 'Kalibrr', 'glints.com' => 'Glints', 'dealls.com' => 'Dealls',
            'indeed.com' => 'Indeed', 'glassdoor.com' => 'Glassdoor', 'jobsdb.com' => 'JobsDB',
            'jora.com' => 'Jora', 'karir.com' => 'Karir.com', 'kitalulus.com' => 'KitaLulus',
            'loker.id' => 'Loker.id', 'disnakerja.com' => 'Disnakerja.com', 'cakeresume.com' => 'Cake: Cari Lowongan',
            'cake.me' => 'Cake: Cari Lowongan', 'pintarnya.com' => 'Pintarnya.com',
            'himalayas.app' => 'Himalayas', 'jobicy.com' => 'Jobicy', 'weworkremotely.com' => 'We Work Remotely',
            'greenhouse.io' => 'Greenhouse', 'lever.co' => 'Lever', 'workable.com' => 'Workable', 'ashbyhq.com' => 'Ashby',
            'smartrecruiters.com' => 'SmartRecruiters', 'jooble.org' => 'Jooble', 'careerjet.co.id' => 'Careerjet',
            'remoteok.com' => 'RemoteOK', 'remotive.com' => 'Remotive',
        ] as $knownHost => $name) {
            if ($host === $knownHost || str_ends_with($host, '.'.$knownHost)) return $name;
        }
        return null;
    }

    public static function logoPath(?string $name): ?string
    {
        $slug = self::SLUGS[mb_strtolower(trim($name ?? ''))] ?? null;
        if (!$slug) return null;
        foreach (($slug === 'indeed' ? ['svg', 'png'] : ['png', 'svg']) as $extension) {
            $path = "images/platforms/{$slug}.{$extension}";
            if (is_file(public_path($path))) return $path;
        }
        return null;
    }

    public static function fallbackIcon(?string $name): ?string
    {
        return match(mb_strtolower(trim($name ?? ''))) {
            'email' => 'ph-envelope-simple',
            'website company' => 'ph-globe',
            '', 'other' => 'ph-dots-three',
            default => null,
        };
    }
}
