<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

final class JobListingExtractor
{
    private const PORTALS = ['linkedin.com' => 'LinkedIn', 'jobstreet.com' => 'JobStreet', 'jobstreet.co.id' => 'JobStreet',
        'seek.com' => 'JobStreet', 'glints.com' => 'Glints', 'kalibrr.com' => 'Kalibrr', 'dealls.com' => 'Dealls',
        'usedeall.com' => 'Dealls', 'talentics.id' => 'Talentics'];

    public function extract(string $html, string $url): array
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $platform = '';
        foreach (self::PORTALS as $domain => $name) {
            if ($host === $domain || str_ends_with($host, '.'.$domain)) {
                $platform = $name;
                break;
            }
        }
        $dom = new Crawler($html);
        $result = ['job_title' => '', 'company_name' => '', 'location' => '', 'description' => '', 'career_level' => '',
            'platform' => $platform, 'work_mode' => '', 'sources' => []];
        $put = function (string $field, mixed $value, string $source) use (&$result) {
            if ($result[$field] !== '') {
                return;
            }
            $text = $this->text($value, $field === 'description');
            if ($field === 'company_name' && in_array(mb_strtolower($text), array_map('mb_strtolower', array_unique(self::PORTALS)), true)) {
                return;
            }
            if ($text !== '') {
                $result[$field] = mb_substr($text, 0, $field === 'description' ? 12000 : 255);
                $result['sources'][$field] = $source;
            }
        };

        $postings = [];
        foreach ($dom->filter('script[type="application/ld+json"]') as $script) {
            $this->collectPostings(json_decode($script->textContent, true), $postings);
        }
        $job = $this->selectPosting($postings, $url, $this->first($dom, ['h1']));
        if ($job !== null) {
            $put('job_title', $job['title'] ?? $job['jobTitle'] ?? '', 'structured_data');
            $org = $job['hiringOrganization'] ?? [];
            if (is_array($org) && isset($org['@id']) && ! isset($org['name'])) {
                foreach ($dom->filter('script[type="application/ld+json"]') as $script) {
                    $org = $this->findReference(json_decode($script->textContent, true), $org['@id']) ?? $org;
                }
            }
            $put('company_name', is_array($org) ? ($org['name'] ?? '') : $org, 'structured_data');
            $put('location', $this->locations($job['jobLocation'] ?? []), 'structured_data');
            $put('description', $job['description'] ?? '', 'structured_data');
            $put('career_level', $this->employment($job['employmentType'] ?? ''), 'structured_data');
            if (strtoupper((string) ($job['jobLocationType'] ?? '')) === 'TELECOMMUTE') {
                $put('work_mode', 'Remote', 'structured_data');
                if ($result['location'] === '') {
                    $put('location', 'Remote', 'structured_data');
                }
            }
        }

        // Only known detail payloads are eligible; recommended-job lists are not traversed.
        foreach ($dom->filter('script#__NEXT_DATA__') as $script) {
            $next = json_decode($script->textContent, true);
            $page = $next['props']['pageProps'] ?? [];
            $candidates = [$page['job'] ?? null, $page['jobDetail'] ?? null, $page['initialData']['data'] ?? null];
            foreach ($page['dehydratedState']['queries'] ?? [] as $query) {
                $key = json_encode($query['queryKey'] ?? []);
                if (preg_match('/recommend|similar|list|search/i', $key)) {
                    continue;
                }
                $data = $query['state']['data'] ?? null;
                if (is_array($data) && ! array_is_list($data)) {
                    $candidates[] = $data['data']['job'] ?? $data['data'] ?? $data['job'] ?? $data;
                }
            }
            $candidates = array_values(array_filter($candidates, fn ($item) => is_array($item) && ! array_is_list($item)
                && (isset($item['title']) || isset($item['name'],$item['companyInfo']))));
            $candidates = array_values(array_reduce($candidates, function ($unique, $candidate) {
                $unique[hash('sha256', json_encode($candidate))] = $candidate;

                return $unique;
            }, []));
            // Avoid picking an unrelated query when a page contains several possible jobs.
            $state = count($candidates) === 1 ? $candidates[0] : null;
            if ($state) {
                $this->fromState($state, $put, 'page_state');
            }
        }
        foreach ($dom->filter('script') as $script) {
            $text = $script->textContent;
            if (str_contains($text, 'window.SEEK_REDUX_DATA')) {
                $start = strpos($text, '=', strpos($text, 'window.SEEK_REDUX_DATA'));
                $data = $this->objectAt($text, $start === false ? 0 : $start + 1);
                $state = $data['jobdetails']['result']['job'] ?? null;
                if (is_array($state)) {
                    $this->fromState($state, $put, 'jobstreet_state');
                }
            }
        }

        $selectors = match ($platform) {
            'LinkedIn' => [
                'job_title' => ['.top-card-layout__title', '.topcard__title', 'h1'],
                'company_name' => ['.topcard__org-name-link', '.topcard__flavor a', '.job-details-jobs-unified-top-card__company-name a'],
                'location' => ['.topcard__flavor--bullet', '.job-details-jobs-unified-top-card__primary-description-container .tvm__text'],
                'description' => ['.show-more-less-html__markup', '.description__text'],
            ],
            'JobStreet' => [
                'job_title' => ['[data-automation="job-detail-title"]', 'h1[data-automation="jobTitle"]', 'h1'],
                'company_name' => ['[data-automation="advertiser-name"]', '[data-automation="jobCompany"]'],
                'location' => ['[data-automation="job-detail-location"]', '[data-automation="jobLocation"]'],
                'description' => ['[data-automation="jobAdDetails"]', '[data-automation="jobDescription"]'],
                'career_level' => ['[data-automation="job-detail-work-type"]'],
            ],
            default => ['job_title' => ['[itemprop="title"]', 'h1'],
                'company_name' => ['[itemprop="hiringOrganization"] [itemprop="name"]'],
                'location' => ['[itemprop="jobLocation"]'],
                'description' => ['[itemprop="description"]', '[data-testid="job-description"]', '.job-description']],
        };
        foreach ($selectors as $field => $list) {
            $value = $this->first($dom, $list, $field === 'description');
            $put($field, $field === 'career_level' ? $this->employment($value) : $value, 'page_content');
        }
        if ($platform === 'LinkedIn') {
            foreach ($dom->filter('.description__job-criteria-item') as $item) {
                $criterion = new Crawler($item);
                $label = $this->first($criterion, ['.description__job-criteria-subheader']);
                if (preg_match('/employment type|jenis pekerjaan/i', $label)) {
                    $put('career_level', $this->employment($this->first($criterion, ['.description__job-criteria-text'])), 'page_content');
                }
            }
        }
        if ($platform === 'Talentics') {
            foreach ($dom->filter('script') as $script) {
                if (! str_contains($script->textContent, 'new Vue')) {
                    continue;
                }
                foreach (['job', 'organization'] as $key) {
                    if (! preg_match('/["\']?\b'.$key.'["\']?\s*:\s*\{/', $script->textContent, $match, PREG_OFFSET_CAPTURE)) {
                        continue;
                    }
                    $state = $this->objectAt($script->textContent, $match[0][1]);
                    if (! $state) {
                        continue;
                    }
                    if ($key === 'job') {
                        $this->fromState($state, $put, 'talentics_state');
                    } else {
                        $put('company_name', $state['company_name'] ?? $state['name'] ?? '', 'page_state');
                    }
                }
            }
        }
        $meta = fn ($name) => $this->meta($dom, $name);
        $title = $meta('og:title') ?: $meta('twitter:title') ?: $this->first($dom, ['title']);
        $title = preg_replace('/\s*[|–-]\s*(LinkedIn|Glints|Jobstreet|SEEK|Dealls|Kalibrr).*$/iu', '', $title);
        if (preg_match('/^(.+?)\s+hiring\s+(.+?)(?:\s+in\s+(.+))?$/iu', $title, $m)) {
            $put('company_name', $m[1], 'page_title');
            $put('job_title', $m[2], 'page_title');
            $put('location', $m[3] ?? '', 'page_title');
        } elseif (preg_match('/^(.+?)\s+(?:at|di|@)\s+(.+)$/iu', $title, $m)) {
            $put('job_title', $m[1], 'page_title');
            $put('company_name', $m[2], 'page_title');
        } else {
            $put('job_title', $title, 'page_title');
        }
        $put('description', $meta('og:description') ?: $meta('description'), 'metadata');
        if ($result['job_title'] !== '' && in_array(mb_strtolower($result['job_title']), array_map('mb_strtolower', array_unique(self::PORTALS)), true)) {
            $result['job_title'] = '';
            unset($result['sources']['job_title']);
        }
        if (preg_match('/^(?:just a moment|access denied|sign in|login|lowongan kerja|jobs? search|security verification)/iu', $result['job_title'])) {
            $result['job_title'] = '';
            unset($result['sources']['job_title']);
        }
        if ($result['job_title'] === '' && $result['company_name'] === '') {
            return ['success' => false, 'message' => 'Could not read this listing. It may require login, block access, or be a search page. Enter details manually or use the extension.'];
        }
        $missing = array_values(array_filter(['job_title', 'company_name', 'location', 'career_level'], fn ($field) => $result[$field] === ''));

        return ['success' => true] + $result + ['missing_fields' => $missing, 'partial' => $missing !== [],
            'message' => $missing ? 'Some details are missing. Check the unfilled fields.' : 'Job details found. Review before saving.'];
    }

    private function fromState(array $job, callable $put, string $source): void
    {
        $put('job_title', $job['title'] ?? $job['name'] ?? '', $source);
        $put('company_name', $job['company']['name'] ?? $job['companyInfo']['name'] ?? $job['advertiser']['name']
            ?? $job['organization']['company_name'] ?? $job['organization']['name'] ?? '', $source);
        $location = $job['googleLocation']['formattedAddress'] ?? $job['location']['label'] ?? null;
        if (! $location) {
            $loc = $job['location'] ?? [];
            $location = is_string($loc) ? $loc : implode(', ', array_filter([$loc['city']['name'] ?? (is_string($loc['city'] ?? null) ? $loc['city'] : ''), $loc['country']['name'] ?? (is_string($loc['country'] ?? null) ? $loc['country'] : '')]));
        }
        if (! $location) {
            $location = implode(', ', array_filter([$job['city']['name'] ?? '', $job['country']['name'] ?? '']));
        }
        $put('location', $location, $source);
        $description = $job['description'] ?? $job['content'] ?? $job['jobDescription'] ?? $job['job_description'] ?? '';
        $requirements = $job['requirements'] ?? $job['qualifications'] ?? $job['minimum_qualifications'] ?? '';
        if (is_string($description) && is_string($requirements) && trim($requirements) !== '' && $requirements !== $description) {
            $description .= '<p>Requirements</p>'.$requirements;
        }
        $put('description', $description, $source);
        $put('career_level', $this->employment($job['employmentType'] ?? $job['employment_type'] ?? $job['type'] ?? $job['workType']['label'] ?? ''), $source);
        $mode = $job['workArrangementOption'] ?? $job['workArrangement'] ?? '';
        if (is_string($mode)) {
            $mode = match (strtoupper(str_replace(['-', '_', ' '], '', $mode))) {
                'REMOTE' => 'Remote', 'HYBRID' => 'Hybrid', 'ONSITE' => 'On-site', default => '',
            };
            $put('work_mode', $mode, $source);
            if ($mode === 'Remote') {
                $put('location', 'Remote', $source);
            }
        }
    }

    private function employment(mixed $value): string
    {
        if (is_array($value)) {
            $value = implode(' ', array_filter($value, 'is_string'));
        }
        if (! is_string($value)) {
            return '';
        }
        $value = strtolower(str_replace(['_', '-'], ' ', $value));

        return match (trim($value)) {
            'full time','fulltime','penuh waktu' => 'Full Time','intern','internship','magang' => 'Intern',
            'contract','contractor','kontrak' => 'Contract','freelance','freelancer' => 'Freelance',default => ''
        };
    }

    private function locations(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }
        if (! is_array($value)) {
            return '';
        }
        if (array_is_list($value)) {
            return implode(' / ', array_unique(array_filter(array_map(fn ($v) => $this->locations($v), $value))));
        }
        $address = $value['address'] ?? $value;
        if (is_string($address)) {
            return $address;
        }
        $parts = [];
        foreach (['addressLocality', 'addressRegion', 'addressCountry'] as $key) {
            $v = $address[$key] ?? '';
            if (is_array($v)) {
                $v = $v['name'] ?? '';
            }
            if (is_string($v) && $v !== '') {
                $parts[] = $v;
            }
        }

        return implode(', ', array_unique($parts)) ?: (is_string($value['name'] ?? null) ? $value['name'] : '');
    }

    private function collectPostings(mixed $data, array &$found, int $depth = 0): void
    {
        if (! is_array($data) || $depth > 15) {
            return;
        }
        $type = $data['@type'] ?? [];
        if ($type === 'JobPosting' || is_array($type) && in_array('JobPosting', $type, true)) {
            $found[] = $data;

            return;
        }
        foreach ($data as $value) {
            if (is_array($value)) {
                $this->collectPostings($value, $found, $depth + 1);
            }
        }
    }

    private function selectPosting(array $jobs, string $url, string $heading): ?array
    {
        foreach ($jobs as $job) {
            if (isset($job['url']) && is_string($job['url']) && strtok($job['url'], '?') === strtok($url, '?')) {
                return $job;
            }
        }
        if (count($jobs) === 1) {
            return $jobs[0];
        }
        foreach ($jobs as $job) {
            if ($heading !== '' && $this->text($job['title'] ?? '') === $heading) {
                return $job;
            }
        }

        return null;
    }

    private function findReference(mixed $data, string $id, int $depth = 0): ?array
    {
        if (! is_array($data) || $depth > 15) {
            return null;
        }
        if (($data['@id'] ?? null) === $id && isset($data['name'])) {
            return $data;
        }
        foreach ($data as $value) {
            if (is_array($value) && ($found = $this->findReference($value, $id, $depth + 1))) {
                return $found;
            }
        }

        return null;
    }

    private function first(Crawler $dom, array $selectors, bool $markup = false): string
    {
        foreach ($selectors as $selector) {
            $nodes = $dom->filter($selector);
            if ($nodes->count()) {
                $value = $markup ? $nodes->first()->html() : $nodes->first()->text();
                if (trim($value) !== '') {
                    return $value;
                }
            }
        }

        return '';
    }

    private function meta(Crawler $dom, string $name): string
    {
        foreach ($dom->filter('meta') as $node) {
            if ($node->getAttribute('property') === $name || $node->getAttribute('name') === $name) {
                return $node->getAttribute('content');
            }
        }

        return '';
    }

    private function text(mixed $value, bool $paragraphs = false): string
    {
        if (! is_string($value)) {
            return '';
        }
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = preg_replace('/<(script|style|noscript)\b[^>]*>.*?<\/\1>/is', '', $value);
        if ($paragraphs) {
            $value = preg_replace('/<li\b[^>]*>/i', "\n• ", $value);
            $value = preg_replace('/<\/?(?:p|div|br|ul|ol|h[1-6])\b[^>]*>/i', "\n", $value);
        }
        $value = strip_tags($value);
        $value = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $value);
        $value = preg_replace('/[ \t\x{00a0}]+/u', ' ', $value);

        return trim($paragraphs ? preg_replace('/\n\s*\n+/', "\n\n", $value) : preg_replace('/\s+/u', ' ', $value));
    }

    private function objectAt(string $text, int $offset): ?array
    {
        $start = strpos($text, '{', $offset);
        if ($start === false) {
            return null;
        }
        $depth = 0;
        $quoted = false;
        $escaped = false;
        for ($i = $start,$length = strlen($text); $i < $length; $i++) {
            $c = $text[$i];
            if ($quoted) {
                if ($escaped) {
                    $escaped = false;
                } elseif ($c === '\\') {
                    $escaped = true;
                } elseif ($c === '"') {
                    $quoted = false;
                }

                continue;
            }
            if ($c === '"') {
                $quoted = true;
            } elseif ($c === '{') {
                $depth++;
            } elseif ($c === '}' && --$depth === 0) {
                return json_decode(substr($text, $start, $i - $start + 1), true);
            }
        }

        return null;
    }
}
