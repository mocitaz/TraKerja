<?php

/*
|--------------------------------------------------------------------------
| Job source registry (TraKerja 2.0 Blueprint, Fase 0)
|--------------------------------------------------------------------------
|
| Priority sources for the job-data engine, with their risk tier and the result of
| the robots.txt check done on 2026-11-17. In Fase 1 this list seeds the `sources`
| table; until then it drives the golden-set collector (`php artisan jobs:golden-collect`).
|
| tier:  A = official/partner, B = public API/ATS meant for sharing,
|        C = public pages allowed by robots.txt (summary only, link back),
|        D = do not collect.
| kind:  api | ats | rss | jsonld | next_data | html
|
*/

return [

    'user_agent' => env('JOB_BOT_USER_AGENT', 'TraKerjaBot/0.1 (+https://trakerja.com/bot)'),

    // Automatic discovery/verification via the Laravel scheduler. Off until explicitly enabled,
    // so deploying the code never starts scraping (or AI spending) by itself.
    'schedule_enabled' => (bool) env('JOB_ENGINE_SCHEDULE_ENABLED', false),

    // Source-health alerts to the admin Telegram channel. Off by default; otherwise only logged.
    'telegram_alerts' => (bool) env('JOB_ENGINE_TELEGRAM_ALERTS', false),

    // New vacancies flow straight into AI enrichment and embedding (queue "ai"). On with the schedule by default.
    'auto_pipeline' => (bool) env('JOB_ENGINE_AUTO_PIPELINE', env('JOB_ENGINE_SCHEDULE_ENABLED', false)),

    // Verifier: how many listings to check per run.
    'verify_budget' => (int) env('JOB_ENGINE_VERIFY_BUDGET', 300),

    'sources' => [

        'dealls' => [
            'name' => 'Dealls',
            'tier' => 'C',
            'kind' => 'jsonld',
            'region' => 'id',
            'discovery' => 'https://dealls.com/sitemap-dynamic.xml',
            'robots' => 'allow-all',
            'notes' => 'Sitemap lists ~16.5k /loker/ URLs; each page embeds a full schema.org JobPosting '
                .'(identifier, IDR salary, hiringOrganization.sameAs domain). URLs can be reused for a different title.',
        ],

        'kalibrr' => [
            'name' => 'Kalibrr',
            'tier' => 'C',
            'kind' => 'next_data',
            'region' => 'id',
            'discovery' => 'https://www.kalibrr.id/home/all-jobs',
            // The server-rendered listing ignores ?page= (always offset 0, 15 jobs), so discovery
            // walks keyword listings instead. Each returns its own first 15 jobs.
            'discovery_paths' => [
                '/home/all-jobs', '/home/te/admin', '/home/te/sales', '/home/te/marketing', '/home/te/finance',
                '/home/te/engineer', '/home/te/customer-service', '/home/te/design', '/home/te/hr',
                '/home/te/magang', '/home/te/data', '/home/te/operations',
            ],
            'robots' => 'allowed (only /root and /candidate/profile disallowed)',
            'notes' => 'No JSON-LD. Job pages carry structured data in __NEXT_DATA__ (pageProps.job) including '
                .'applicationEndDate, salary, education and an isExpired flag.',
        ],

        'greenhouse' => [
            'name' => 'Greenhouse job boards',
            'tier' => 'B',
            'kind' => 'ats',
            'region' => 'mixed',
            'endpoint' => 'https://boards-api.greenhouse.io/v1/boards/{token}/jobs?content=true',
            'robots' => 'allowed',
            // Verified Indonesian boards only, filtered to Indonesian locations (Xendit is regional).
            // Not Indonesian despite the name: "flip" (US), "kargo" (US, San Francisco).
            'boards' => ['xendit'],
        ],

        'lever' => [
            'name' => 'Lever postings',
            'tier' => 'B',
            'kind' => 'ats',
            'region' => 'mixed',
            'endpoint' => 'https://api.lever.co/v0/postings/{slug}?mode=json',
            'robots' => 'allowed (crawl-delay 1)',
            // Ninja Van is regional; keep Indonesian locations only.
            'boards' => ['ninjavan', 'lalamove'],
            'location_filter' => '/indonesia|jakarta|surabaya|bandung|medan|bekasi|tangerang|semarang|makassar/i',
        ],

        'workable' => [
            'name' => 'Workable job boards',
            'tier' => 'B',
            'kind' => 'ats',
            'region' => 'id',
            'endpoint' => 'https://apply.workable.com/api/v1/widget/accounts/{account}?details=true',
            'robots' => 'public widget API',
            // Verified 2026-11-17: Ajaib (60 jobs in Indonesia), Funding Societies | Modalku (18).
            'boards' => ['ajaib', 'fundingsocieties', 'amartha'],
        ],

        'smartrecruiters' => [
            'name' => 'SmartRecruiters postings',
            'tier' => 'B',
            'kind' => 'ats',
            'region' => 'id',
            'endpoint' => 'https://api.smartrecruiters.com/v1/companies/{company}/postings',
            'enabled' => false,
            'robots' => 'api.smartrecruiters.com robots.txt allows only LinkedInBot (User-agent: * Disallow: /)',
            'notes' => 'Disabled: robots.txt refuses our bot. Pursue the jobs via the companies\' own career pages or a partnership.',
            // Verified 2026-11-17 (company names and Jakarta locations checked).
            'boards' => ['stockbit', 'sayurbox', 'julo', 'bukuwarung', 'kataai'],
        ],

        'ashby' => [
            'name' => 'Ashby job boards',
            'tier' => 'B',
            'kind' => 'ats',
            'region' => 'id',
            'enabled' => false,
            'endpoint' => 'https://api.ashbyhq.com/posting-api/job-board/{org}',
            // No verified Indonesian board yet: "flip" is a German company (Stuttgart), "kargo" is US.
            'boards' => [],
        ],

        'arbeitnow' => [
            'name' => 'Arbeitnow',
            'tier' => 'B',
            'kind' => 'api',
            'region' => 'global',
            'endpoint' => 'https://www.arbeitnow.com/api/job-board-api',
            'robots' => 'allowed',
            'enabled' => false,
            'notes' => 'Mostly Germany-based roles; not relevant for Indonesian job seekers. Kept for the golden set only.',
        ],

        'jobicy' => [
            'name' => 'Jobicy',
            'tier' => 'B',
            'kind' => 'api',
            'region' => 'remote',
            'endpoint' => 'https://jobicy.com/api/v2/remote-jobs?count=50',
            'robots' => 'allowed (query-parameter paths disallowed)',
            'notes' => 'API terms ask for attribution and a link back to Jobicy.',
        ],

        'himalayas' => [
            'name' => 'Himalayas',
            'tier' => 'B',
            'kind' => 'api',
            'region' => 'remote',
            'endpoint' => 'https://himalayas.app/jobs/api?limit=20',
            'robots' => 'allowed (paginated ?page= paths disallowed)',
        ],

        'remotive' => [
            'name' => 'Remotive',
            'tier' => 'B',
            'kind' => 'api',
            'region' => 'remote',
            'endpoint' => 'https://remotive.com/api/remote-jobs?limit=50',
            'enabled' => false,
            'robots' => 'Disallow: /api/* (checked 2026-11-17)',
            'notes' => 'API terms: at most a few requests per day, link back to Remotive.',
        ],

        'weworkremotely' => [
            'name' => 'We Work Remotely',
            'tier' => 'B',
            'kind' => 'rss',
            'region' => 'remote',
            'endpoint' => 'https://weworkremotely.com/remote-jobs.rss',
            'robots' => 'allowed',
        ],

        'karir' => [
            'name' => 'Karir.com',
            'tier' => 'C',
            'kind' => 'html',
            'region' => 'id',
            'enabled' => false,
            'robots' => '/jobs/ and /karir/ disallowed; /opportunities/ allowed',
            'notes' => 'Not collected. Compliant path found 2026-11-17: /opportunities/{id} pages are allowed, listed in '
                .'sitemap.xml (165 URLs) and carry __NEXT_DATA__ with company, location, posted_at and is_expired. '
                .'Blocked on terms: section 8.5 forbids reproducing or commercial use of content without written permission. '
                .'Ask Karir.com for permission or a feed before enabling. (The asep gateway API needs spoofed headers: never.)',
        ],

        'remoteok' => [
            'name' => 'RemoteOK',
            'tier' => 'C',
            'kind' => 'api',
            'region' => 'remote',
            'enabled' => false,
            'robots' => 'allows generic bots but explicitly blocks AI agents (GPTBot, ClaudeBot, ...)',
            'notes' => 'Disabled pending a decision: TraKerja processes text with AI, which conflicts with the site\'s opt-out.',
        ],

        'linkedin' => [
            'name' => 'LinkedIn',
            'tier' => 'D',
            'kind' => 'html',
            'enabled' => false,
            'notes' => 'Not collected: terms of service prohibit scraping (hiQ v. LinkedIn). Users can still save LinkedIn jobs manually.',
        ],

        'glints' => [
            'name' => 'Glints',
            'tier' => 'D',
            'kind' => 'html',
            'enabled' => false,
            'notes' => 'robots.txt now allows /opportunities/jobs/ detail pages, but glints.com answers our bot with 403 '
                .'(2026-11-17). Active blocking is a refusal: no workaround. Partnership only.',
        ],

        // ---------------------------------------------------------- Indonesian survey, 2026-11-17

        'pintarnya' => [
            'name' => 'Pintarnya',
            'tier' => 'C',
            'kind' => 'jsonld',
            'region' => 'id',
            'discovery' => 'https://pintarnya.com/sitemap/lowongan_1.xml',
            'robots' => 'allowed (/lowongan/ explicitly allowed)',
            'notes' => 'Job pages embed schema.org JobPosting with salary, organization and address. Strong blue-collar coverage.',
        ],

        'kitalulus' => [
            'name' => 'KitaLulus',
            'tier' => 'C',
            'kind' => 'html',
            'region' => 'id',
            'enabled' => false,
            'discovery' => 'https://www.kitalulus.com/sitemap/sitemap-jobs/job-detail-1.xml',
            'robots' => 'generic bots allowed; GPTBot disallowed',
            'notes' => 'Sitemaps list job-detail URLs (1,000 per file). No JSON-LD, but the RSC payload has structured fields '
                .'(company, city, province, education, googleType, work site, status). Blocked on terms: users may not '
                .'reproduce, copy, distribute or use content commercially without written permission. Ask for permission first.',
        ],

        'karirhub' => [
            'name' => 'Karirhub Kemnaker',
            'tier' => 'A',
            'kind' => 'partner',
            'region' => 'id',
            'enabled' => false,
            'robots' => 'pages allowed, /api/ disallowed',
            'notes' => 'Government job portal; single-page app whose data comes from a disallowed API. Pursue an official data partnership.',
        ],

        'disnakerja' => [
            'name' => 'Disnakerja',
            'tier' => 'C',
            'kind' => 'html',
            'region' => 'id',
            'enabled' => false,
            'robots' => 'allowed',
            'notes' => 'WordPress blog of reposted vacancies (2,000+ posts per sitemap), no structured data. '
                .'Mostly duplicates of employer posts; low priority, AI extraction only.',
        ],

        'jobstreet' => [
            'name' => 'JobStreet (SEEK)',
            'tier' => 'D',
            'kind' => 'partner',
            'region' => 'id',
            'enabled' => false,
            'robots' => 'job detail pages (*/job/) and all query strings disallowed',
            'notes' => 'No public read API: the SEEK API is for posting jobs via ATS partners. Covered indirectly by '
                .'collecting the same jobs at their origin (ATS boards, company career pages) and via the Careerjet/Jooble APIs.',
        ],

        'indeed' => [
            'name' => 'Indeed',
            'tier' => 'D',
            'kind' => 'partner',
            'enabled' => false,
            'notes' => 'Publisher API closed; Job Sync API is for employers/ATS posting jobs. Developer terms forbid scraping.',
        ],

        'careerjet' => [
            'name' => 'Careerjet (official API)',
            'tier' => 'B',
            'kind' => 'api',
            'region' => 'id',
            'enabled' => false,
            'endpoint' => 'https://search.api.careerjet.net/v4/query',
            'robots' => 'site /job/ pages disallowed; use the partner API instead',
            'notes' => 'Aggregator with an official partner API (publisher key, basic auth). Requires registering at '
                .'careerjet.com/partners; results must link back to Careerjet. Enable once CAREERJET_API_KEY is set.',
        ],

        'jooble' => [
            'name' => 'Jooble (official API)',
            'tier' => 'B',
            'kind' => 'api',
            'region' => 'id',
            'enabled' => false,
            'endpoint' => 'https://jooble.org/api/{key}',
            'notes' => 'Aggregator with a free official API covering Indonesia. Enable once JOOBLE_API_KEY is set.',
        ],

        'glassdoor' => ['name' => 'Glassdoor', 'tier' => 'D', 'kind' => 'html', 'enabled' => false, 'notes' => '403 to bots.'],
        'techinasia' => ['name' => 'Tech in Asia Jobs', 'tier' => 'D', 'kind' => 'html', 'enabled' => false, 'notes' => '403 to bots.'],
        'jobsdb' => ['name' => 'JobsDB', 'tier' => 'D', 'kind' => 'html', 'enabled' => false, 'notes' => '403 to bots; Indonesian JobsDB merged into JobStreet.'],
        'lokerindonesia' => ['name' => 'Lokerindonesia.com', 'tier' => 'C', 'kind' => 'html', 'enabled' => false, 'notes' => 'Allowed but posts are old (2019) and JSON-LD is malformed.'],
    ],
];
