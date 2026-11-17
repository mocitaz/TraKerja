<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Collects real job samples for the golden set (TraKerja 2.0 Blueprint, Fase 0).
 *
 * For every sample it stores the structured raw data (API item, JSON-LD JobPosting or
 * Kalibrr __NEXT_DATA__) and a DRAFT label derived from that structured data. Labels stay
 * `reviewed: false` until a person checks and corrects them; only reviewed labels count
 * when measuring extractors.
 *
 * Output lives in storage/app/private/golden (gitignored): the TraKerja repository is public, and
 * raw posts may contain third-party content and recruiters' personal contact details.
 */
class CollectGoldenJobs extends Command
{
    protected $signature = 'jobs:golden-collect
        {--source=* : Only these source keys (default: all enabled sources with a collector)}
        {--limit= : Override the per-source sample count}
        {--delay=2 : Seconds to wait between page requests to the same site}';

    protected $description = 'Collect real job samples and draft labels for the golden set (storage/app/private/golden)';

    /** Default samples per source, roughly 290 in total. */
    private const QUOTA = [
        'dealls' => 60, 'kalibrr' => 60, 'greenhouse' => 25, 'lever' => 30,
        'arbeitnow' => 20, 'jobicy' => 20, 'himalayas' => 20, 'remotive' => 20, 'weworkremotely' => 20,
    ];

    private const DISK_ROOT = 'golden';

    public function handle(): int
    {
        $sources = config('job_sources.sources');
        $wanted = $this->option('source') ?: array_keys(self::QUOTA);

        $collected = 0;
        foreach ($wanted as $key) {
            // Disabled sources are only collected when asked for by name (--source).
            $disabled = ($sources[$key]['enabled'] ?? true) === false && ! $this->option('source');
            if (! isset(self::QUOTA[$key], $sources[$key]) || $disabled) {
                $this->warn("Lewati {$key}: tidak ada collector atau sumber dinonaktifkan.");

                continue;
            }

            $limit = (int) ($this->option('limit') ?: self::QUOTA[$key]);
            $this->info("== {$key} (target {$limit})");

            try {
                $samples = $this->{'collect'.Str::studly($key)}($sources[$key], $limit);
            } catch (Throwable $e) {
                $this->error("   gagal: ".Str::limit($e->getMessage(), 200));

                continue;
            }

            $this->store($key, $samples);
            $collected += count($samples);
            $this->line('   tersimpan: '.count($samples));
        }

        $this->writeDuplicateCandidates();
        $this->newLine();
        $this->info("Selesai: {$collected} sampel di storage/app/private/".self::DISK_ROOT);
        $this->comment('Label masih draf (reviewed=false). Periksa dan koreksi sebelum dipakai mengukur.');

        return self::SUCCESS;
    }

    // ---------------------------------------------------------------- sources

    private function collectDealls(array $source, int $limit): array
    {
        $sitemap = $this->http()->get($source['discovery'])->throw()->body();
        preg_match_all('#<loc>(https://dealls\.com/loker/[^<]+)</loc>#', $sitemap, $m);
        $urls = $this->spread(array_values(array_unique($m[1])), $limit * 2);

        $samples = [];
        foreach ($urls as $url) {
            if (count($samples) >= $limit) {
                break;
            }
            $this->pause();
            $response = $this->http()->get($url);
            $posting = $response->successful() ? $this->jsonLdJobPosting($response->body()) : null;
            if (! $posting) {
                continue;
            }
            $id = Arr::get($posting, 'identifier.value') ?: md5($url);
            $samples[] = $this->sample('dealls', (string) $id, $url, $response->status(), $posting, $this->labelFromJsonLd($posting));
        }

        return $samples;
    }

    private function collectKalibrr(array $source, int $limit): array
    {
        // Kalibrr's server-rendered listing ignores ?page= and always shows the first 15 jobs,
        // so diversity comes from several keyword listing pages (/home/te/{keyword}).
        $links = [];
        foreach ($source['discovery_paths'] as $path) {
            if (count($links) >= $limit) {
                break;
            }
            $this->pause();
            $html = $this->http()->get('https://www.kalibrr.id'.$path)->body();
            preg_match_all('#/c/[a-z0-9-]+/jobs/[0-9]+/[a-z0-9-]+#', $html, $m);
            $links = array_values(array_unique(array_merge($links, $m[0])));
        }
        $links = $this->spread($links, $limit);

        $samples = [];
        foreach (array_slice($links, 0, $limit) as $path) {
            $this->pause();
            $url = 'https://www.kalibrr.id'.$path;
            $response = $this->http()->get($url);
            $data = $response->successful() ? $this->nextData($response->body()) : null;
            $job = Arr::get($data, 'props.pageProps.job');
            if (! is_array($job)) {
                continue;
            }
            $raw = ['job' => $job, 'isExpired' => Arr::get($data, 'props.pageProps.isExpired')];
            $samples[] = $this->sample('kalibrr', (string) $job['id'], $url, $response->status(), $raw, $this->labelFromKalibrr($raw));
        }

        return $samples;
    }

    private function collectGreenhouse(array $source, int $limit): array
    {
        $samples = [];
        foreach ($source['boards'] as $board) {
            $jobs = $this->http()->get(str_replace('{token}', $board, $source['endpoint']))->throw()->json('jobs', []);
            foreach ($jobs as $job) {
                if (count($samples) >= $limit) {
                    break 2;
                }
                $samples[] = $this->sample('greenhouse', $board.'-'.$job['id'], $job['absolute_url'], 200, $job, [
                    'title' => $job['title'] ?? null,
                    'company' => Str::headline($board),
                    'location_text' => Arr::get($job, 'location.name'),
                    'date_posted' => $this->date($job['first_published'] ?? $job['updated_at'] ?? null),
                ]);
            }
            $this->pause();
        }

        return $samples;
    }

    private function collectLever(array $source, int $limit): array
    {
        $samples = [];
        foreach ($source['boards'] as $board) {
            $postings = $this->http()->get(str_replace('{slug}', $board, $source['endpoint']))->throw()->json() ?? [];
            foreach ($postings as $post) {
                $location = Arr::get($post, 'categories.location', '');
                if (isset($source['location_filter']) && ! preg_match($source['location_filter'], (string) $location)) {
                    continue;
                }
                if (count($samples) >= $limit) {
                    break 2;
                }
                $samples[] = $this->sample('lever', $board.'-'.$post['id'], $post['hostedUrl'], 200, $post, [
                    'title' => $post['text'] ?? null,
                    'company' => Str::headline($board),
                    'location_text' => $location,
                    'employment_type' => $this->employmentType(Arr::get($post, 'categories.commitment')),
                    'work_mode' => $this->workMode($post['workplaceType'] ?? null),
                    'date_posted' => isset($post['createdAt']) ? date('Y-m-d', intdiv((int) $post['createdAt'], 1000)) : null,
                ]);
            }
            $this->pause();
        }

        return $samples;
    }

    private function collectArbeitnow(array $source, int $limit): array
    {
        $jobs = array_slice($this->http()->get($source['endpoint'])->throw()->json('data', []), 0, $limit);

        return array_map(fn ($job) => $this->sample('arbeitnow', $job['slug'], $job['url'], 200, $job, [
            'title' => $job['title'] ?? null,
            'company' => $job['company_name'] ?? null,
            'location_text' => $job['location'] ?? null,
            'work_mode' => ($job['remote'] ?? false) ? 'remote' : null,
            'employment_type' => $this->employmentType(implode(' ', $job['job_types'] ?? [])),
            'date_posted' => isset($job['created_at']) ? date('Y-m-d', (int) $job['created_at']) : null,
        ]), $jobs);
    }

    private function collectJobicy(array $source, int $limit): array
    {
        $jobs = array_slice($this->http()->get($source['endpoint'])->throw()->json('jobs', []), 0, $limit);

        return array_map(fn ($job) => $this->sample('jobicy', (string) $job['id'], $job['url'], 200, $job, [
            'title' => isset($job['jobTitle']) ? html_entity_decode($job['jobTitle']) : null,
            'company' => $job['companyName'] ?? null,
            'location_text' => $job['jobGeo'] ?? null,
            'work_mode' => 'remote',
            'employment_type' => $this->employmentType(implode(' ', (array) ($job['jobType'] ?? []))),
            'seniority' => $this->seniority($job['jobLevel'] ?? null),
            'salary_min' => $job['annualSalaryMin'] ?? $job['salaryMin'] ?? null,
            'salary_max' => $job['annualSalaryMax'] ?? $job['salaryMax'] ?? null,
            'salary_currency' => $job['salaryCurrency'] ?? null,
            'salary_period' => isset($job['annualSalaryMin']) ? 'year' : ($job['salaryPeriod'] ?? null),
            'date_posted' => $this->date($job['pubDate'] ?? null),
        ]), $jobs);
    }

    private function collectHimalayas(array $source, int $limit): array
    {
        $jobs = array_slice($this->http()->get($source['endpoint'])->throw()->json('jobs', []), 0, $limit);

        return array_map(fn ($job) => $this->sample('himalayas', md5($job['applicationLink'] ?? $job['title']), $job['applicationLink'] ?? '', 200, $job, [
            'title' => $job['title'] ?? null,
            'company' => $job['companyName'] ?? null,
            'location_text' => implode(', ', (array) ($job['locationRestrictions'] ?? [])) ?: null,
            'work_mode' => 'remote',
            'employment_type' => $this->employmentType($job['employmentType'] ?? null),
            'seniority' => $this->seniority(implode(' ', (array) ($job['seniority'] ?? []))),
            'salary_min' => $job['minSalary'] ?? null,
            'salary_max' => $job['maxSalary'] ?? null,
            'salary_currency' => $job['currency'] ?? null,
            'salary_period' => isset($job['minSalary']) ? 'year' : null,
            'date_posted' => isset($job['pubDate']) ? date('Y-m-d', (int) $job['pubDate']) : null,
            'valid_through' => isset($job['expiryDate']) ? date('Y-m-d', (int) $job['expiryDate']) : null,
        ]), $jobs);
    }

    private function collectRemotive(array $source, int $limit): array
    {
        $jobs = array_slice($this->http()->get($source['endpoint'])->throw()->json('jobs', []), 0, $limit);

        return array_map(fn ($job) => $this->sample('remotive', (string) $job['id'], $job['url'], 200, $job, [
            'title' => $job['title'] ?? null,
            'company' => $job['company_name'] ?? null,
            'location_text' => $job['candidate_required_location'] ?? null,
            'work_mode' => 'remote',
            'employment_type' => $this->employmentType($job['job_type'] ?? null),
            'date_posted' => $this->date($job['publication_date'] ?? null),
        ]), $jobs);
    }

    private function collectWeworkremotely(array $source, int $limit): array
    {
        $xml = simplexml_load_string($this->http()->get($source['endpoint'])->throw()->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
        $samples = [];
        foreach ($xml->channel->item ?? [] as $item) {
            if (count($samples) >= $limit) {
                break;
            }
            $titleRaw = (string) $item->title; // "Company: Job title"
            [$company, $title] = str_contains($titleRaw, ':') ? array_map('trim', explode(':', $titleRaw, 2)) : [null, $titleRaw];
            $raw = [
                'title' => $titleRaw, 'link' => (string) $item->link, 'pubDate' => (string) $item->pubDate,
                'region' => (string) $item->region, 'type' => (string) $item->type, 'category' => (string) $item->category,
                'description' => (string) $item->description,
            ];
            $samples[] = $this->sample('weworkremotely', md5($raw['link']), $raw['link'], 200, $raw, [
                'title' => $title,
                'company' => $company,
                'location_text' => $raw['region'] ?: null,
                'work_mode' => 'remote',
                'employment_type' => $this->employmentType($raw['type']),
                'date_posted' => $this->date($raw['pubDate']),
            ]);
        }

        return $samples;
    }

    // ---------------------------------------------------------------- label drafting

    private function labelFromJsonLd(array $p): array
    {
        $address = Arr::get($p, 'jobLocation.address', Arr::get($p, 'jobLocation.0.address', []));
        $salary = Arr::get($p, 'baseSalary.value', []);

        return [
            'title' => $p['title'] ?? null,
            'company' => Arr::get($p, 'hiringOrganization.name'),
            'company_domain' => $this->domain(Arr::get($p, 'hiringOrganization.sameAs')),
            'city' => $address['addressLocality'] ?? null,
            'province' => $address['addressRegion'] ?? null,
            'country' => $address['addressCountry'] ?? null,
            'employment_type' => $this->employmentType(implode(' ', (array) ($p['employmentType'] ?? []))),
            'work_mode' => ($p['jobLocationType'] ?? null) === 'TELECOMMUTE' ? 'remote' : null,
            'salary_min' => $salary['minValue'] ?? $salary['value'] ?? null,
            'salary_max' => $salary['maxValue'] ?? null,
            'salary_currency' => Arr::get($p, 'baseSalary.currency'),
            'salary_period' => isset($salary['unitText']) ? strtolower($salary['unitText']) : null,
            'date_posted' => $this->date($p['datePosted'] ?? null),
            'valid_through' => $this->date($p['validThrough'] ?? null),
        ];
    }

    private function labelFromKalibrr(array $raw): array
    {
        $job = $raw['job'];
        $location = $job['googleLocation']['addressComponents'] ?? [];

        return [
            'title' => $job['name'] ?? null,
            'company' => Arr::get($job, 'companyInfo.name', Arr::get($job, 'company.name')),
            'city' => $location['city'] ?? null,
            'province' => $location['region'] ?? null,
            'country' => $location['country'] ?? null,
            'employment_type' => $this->employmentType($job['tenure'] ?? null),
            'work_mode' => ($job['isWorkFromHome'] ?? false) ? 'remote' : (($job['isHybrid'] ?? false) ? 'hybrid' : null),
            'salary_min' => $job['baseSalary'] ?? null,
            'salary_max' => $job['maximumSalary'] ?? null,
            'salary_currency' => $job['salaryCurrency'] ?? null,
            'salary_period' => $job['salaryInterval'] ?? null,
            'date_posted' => $this->date($job['activationDate'] ?? null),
            'valid_through' => $this->date($job['applicationEndDate'] ?? null),
            'is_open' => isset($raw['isExpired']) ? ! $raw['isExpired'] : null,
        ];
    }

    // ---------------------------------------------------------------- storage

    private function sample(string $source, string $id, string $url, int $status, array $raw, array $draft): array
    {
        $label = array_merge([
            'title' => null, 'company' => null, 'company_domain' => null, 'city' => null, 'province' => null,
            'country' => null, 'location_text' => null, 'employment_type' => null, 'work_mode' => null,
            'seniority' => null, 'education_min' => null, 'is_internship' => null, 'sector' => null,
            'salary_min' => null, 'salary_max' => null, 'salary_currency' => null, 'salary_period' => null,
            'date_posted' => null, 'valid_through' => null, 'is_open' => null,
        ], $draft);

        $text = strtolower(($label['title'] ?? '').' '.json_encode($raw));
        $label['is_internship'] ??= (bool) preg_match('/\b(magang|intern(ship)?|trainee|pkl)\b/', $text);

        return [
            'id' => Str::slug($source.'-'.$id),
            'source' => $source,
            'url' => $url,
            'fetched_at' => now()->toIso8601String(),
            'http_status' => $status,
            'raw' => $raw,
            'label' => $label,
            'label_origin' => 'structured_draft',
            'reviewed' => false,
            'review_notes' => '',
        ];
    }

    private function store(string $source, array $samples): void
    {
        $disk = Storage::disk('local');
        $labels = [];
        foreach ($samples as $s) {
            $disk->put(self::DISK_ROOT."/raw/{$source}/{$s['id']}.json", json_encode($s, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $labels[] = Arr::except($s, ['raw', 'http_status', 'fetched_at']);
        }
        $disk->put(self::DISK_ROOT."/labels/{$source}.json", json_encode($labels, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /** Suggest cross-source duplicate pairs (same normalized company + title) for a reviewer to confirm. */
    private function writeDuplicateCandidates(): void
    {
        $disk = Storage::disk('local');
        $byKey = [];
        foreach ($disk->files(self::DISK_ROOT.'/labels') as $file) {
            if (str_starts_with(basename($file), '_')) {
                continue;
            }
            foreach (json_decode($disk->get($file), true) ?? [] as $row) {
                $key = $this->normalize($row['label']['company'] ?? '').'|'.$this->normalize($row['label']['title'] ?? '');
                $byKey[$key][] = $row['id'];
            }
        }
        $pairs = [];
        foreach ($byKey as $key => $ids) {
            if (count($ids) > 1) {
                $pairs[] = ['ids' => $ids, 'key' => $key, 'is_duplicate' => null, 'reviewed' => false];
            }
        }
        $disk->put(self::DISK_ROOT.'/labels/_duplicates.json', json_encode($pairs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // ---------------------------------------------------------------- helpers

    private function http(): PendingRequest
    {
        return Http::withUserAgent(config('job_sources.user_agent'))
            ->accept('application/json, text/html, application/xml;q=0.9, */*;q=0.8')
            ->timeout(30)
            ->retry(2, 2000, throw: false);
    }

    private function pause(): void
    {
        usleep((int) ((float) $this->option('delay') * 1_000_000));
    }

    /** Pick $count items spread evenly across the list, so samples are not all from one company. */
    private function spread(array $items, int $count): array
    {
        if (count($items) <= $count) {
            return $items;
        }
        $step = count($items) / $count;

        return array_map(fn ($i) => $items[(int) floor($i * $step)], range(0, $count - 1));
    }

    private function jsonLdJobPosting(string $html): ?array
    {
        preg_match_all('#<script[^>]+application/ld\+json[^>]*>(.*?)</script>#s', $html, $m);
        foreach ($m[1] as $block) {
            $data = json_decode(trim($block), true);
            foreach (isset($data['@type']) ? [$data] : (array) ($data['@graph'] ?? $data ?? []) as $item) {
                if (is_array($item) && ($item['@type'] ?? null) === 'JobPosting') {
                    return $item;
                }
            }
        }

        return null;
    }

    private function nextData(string $html): ?array
    {
        return preg_match('#<script id="__NEXT_DATA__"[^>]*>(.*?)</script>#s', $html, $m)
            ? json_decode($m[1], true)
            : null;
    }

    private function employmentType(?string $value): ?string
    {
        $v = strtolower((string) $value);

        return match (true) {
            $v === '' => null,
            str_contains($v, 'intern') || str_contains($v, 'magang') => 'internship',
            str_contains($v, 'contract') || str_contains($v, 'kontrak') || str_contains($v, 'temporary') => 'contract',
            str_contains($v, 'part') => 'part_time',
            str_contains($v, 'freelance') => 'freelance',
            str_contains($v, 'full') => 'full_time',
            default => null,
        };
    }

    private function workMode(?string $value): ?string
    {
        return match (strtolower((string) $value)) {
            'remote' => 'remote', 'hybrid' => 'hybrid', 'on-site', 'onsite' => 'onsite', default => null,
        };
    }

    private function seniority(?string $value): ?string
    {
        $v = strtolower((string) $value);

        return match (true) {
            $v === '' => null,
            str_contains($v, 'intern') => 'intern',
            str_contains($v, 'entry') || str_contains($v, 'junior') => 'entry',
            str_contains($v, 'mid') => 'mid',
            str_contains($v, 'lead') || str_contains($v, 'principal') => 'lead',
            str_contains($v, 'manager') || str_contains($v, 'director') => 'manager',
            str_contains($v, 'senior') => 'senior',
            default => null,
        };
    }

    private function date(mixed $value): ?string
    {
        if (! $value) {
            return null;
        }
        try {
            return \Illuminate\Support\Carbon::parse($value)->toDateString();
        } catch (Throwable) {
            return null;
        }
    }

    private function domain(?string $url): ?string
    {
        $host = $url ? parse_url(str_contains($url, '://') ? $url : "https://{$url}", PHP_URL_HOST) : null;

        return $host ? preg_replace('/^www\./', '', strtolower($host)) : null;
    }

    private function normalize(string $value): string
    {
        $value = mb_strtolower($value);
        $value = preg_replace('/\b(pt|tbk|persero|cv)\b\.?/u', ' ', $value);

        return preg_replace('/[^\p{L}\p{N}]+/u', '', $value) ?? '';
    }
}
