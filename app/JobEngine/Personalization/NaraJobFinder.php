<?php

namespace App\JobEngine\Personalization;

use App\Models\JobApplication;
use App\Models\Region;
use App\Models\SavedJobSearch;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Connects Nara to the TraKerja 2.0 job catalogue. Reads a chat message such as "carikan loker data analyst di Bandung
 * yang remote", turns it into filters and returns real open vacancies (best CV match first for premium users).
 * Nara may only recommend vacancies from this list; the reply refers to them as [job:ID] and expand() turns
 * those tags into cards with data from the database, so a job, company or link can never be invented.
 */
class NaraJobFinder
{
    public const LIMIT = 5;

    private const SEARCH_VERBS = '(cari(kan|in)?|carii|rekomendasi(kan)?|saran(kan)?|ada( ga| gak| nggak| tidak)?|lihat(in)?|tampilkan|tunjuk(kan|in)|kasih|butuh|mau|pengen|ingin|find|search|recommend|suggest|show|any|looking)';

    private const JOB_NOUNS = '(loker|lowongan|lowker|job|jobs|vacanc\w*|posisi|kerjaan|pekerjaan|magang|internship|intern|hiring|openings?)';

    private const STOPWORDS = ['yang', 'di', 'ke', 'dan', 'atau', 'untuk', 'buat', 'aku', 'saya', 'gue', 'gw', 'kamu', 'nara', 'tolong', 'dong', 'ya', 'yg', 'dgn', 'dengan',
        'ini', 'itu', 'ada', 'apa', 'aja', 'saja', 'lagi', 'baru', 'terbaru', 'cocok', 'sesuai', 'bagus', 'masih', 'buka', 'yang', 'bidang', 'sebagai', 'jadi', 'daerah', 'area',
        'sekitar', 'dekat', 'kota', 'kabupaten', 'provinsi', 'the', 'a', 'an', 'in', 'at', 'for', 'me', 'my', 'i', 'jobs', 'job', 'role', 'roles', 'position', 'positions', 'open',
        'opening', 'openings', 'near', 'please', 'some', 'with', 'that', 'fit', 'fits', 'match', 'profile', 'profil', 'cv', 'gaji', 'tinggi', 'kerja', 'bekerja', 'lowongan', 'loker',
        'lowker', 'posisi', 'kerjaan', 'pekerjaan', 'vacancy', 'vacancies', 'hiring', 'carikan', 'cari', 'carii', 'cariin', 'rekomendasi', 'rekomendasikan', 'saran', 'sarankan',
        'lihat', 'lihatin', 'tampilkan', 'kasih', 'butuh', 'mau', 'pengen', 'ingin', 'find', 'search', 'recommend', 'suggest', 'show', 'any', 'looking', 'ga', 'gak', 'nggak',
        'tidak', 'remote', 'wfh', 'wfa', 'magang', 'internship', 'intern', 'hybrid', 'onsite', 'fulltime', 'full', 'time', 'parttime', 'part', 'freelance', 'kontrak', 'contract',
        'indonesia', 'lain', 'lainnya', 'more', 'other', 'others', 'lagi', 'dong', 'nih', 'sih', 'deh', 'kak', 'min', 'banget', 'paling', 'beberapa', 'semua', 'all',
        'gimana', 'bagaimana', 'kalau', 'kalo', 'aja', 'how', 'about', 'what', 'selain', 'berikutnya', 'next', 'yang', 'sekarang', 'coba', 'try', 'instead', 'only', 'just', 'hanya', 'cuma', 'onsite', 'wfo', 'new', 'latest', 'recent', 'today', 'hari', 'like', 'these', 'those', 'similar', 'mirip', 'serupa', 'ini', 'itu', 'tunjukkan', 'tunjukin', 'kayak', 'seperti'];

    public function __construct(private MatchScorer $scorer) {}

    /** Filters for a job request, or null when the message is not asking for vacancies. */
    public function criteria(string $message): ?array
    {
        $text = mb_strtolower($message);
        $asks = preg_match('/\b'.self::SEARCH_VERBS.'\b.{0,60}\b'.self::JOB_NOUNS.'\b/u', $text)
            || preg_match('/\b'.self::JOB_NOUNS.'\b.{0,40}\b(di|in|untuk|buat|yang|for)\b/u', $text)
            || preg_match('/^\s*'.self::JOB_NOUNS.'\b/u', $text);
        if (! $asks) {
            return null;
        }

        return $this->parse($text);
    }

    /**
     * A short follow-up to the previous search ("yang remote aja", "di Surabaya gimana?", "ada yang lain?",
     * "kalau marketing?"): the previous filters with this message's changes. Null when it is not a follow-up.
     */
    public function refine(string $message, array $previous): ?array
    {
        $text = mb_strtolower(trim($message));
        if (count(preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY)) > 12) {
            return null;
        }
        $parsed = $this->parse($text);
        $more = (bool) preg_match('/\b(lain|lainnya|lagi|more|other|others|selain|berikutnya|next)\b/u', $text);
        $narrows = $parsed['region'] || $parsed['remote'] || $parsed['internship'];
        $newRole = $parsed['terms'] && preg_match('/^(kalau|kalo|gimana|bagaimana|how about|what about|yang|untuk|buat|posisi|bagian|coba|try)\b/u', $text);
        if (! $more && ! $narrows && ! $newRole) {
            return null;
        }

        return [
            'terms' => $newRole ? $parsed['terms'] : $previous['terms'],
            'region' => $parsed['region'] ?? $previous['region'],
            'remote' => $parsed['remote'] || ($previous['remote'] ?? false),
            'internship' => $parsed['internship'] || ($previous['internship'] ?? false),
            'more' => $more && ! $narrows && ! $newRole,
        ];
    }

    /** Explore saved-search filters for the criteria (the internship flag has no Explore filter). */
    public function savedSearchFilters(array $criteria): array
    {
        $region = $criteria['region'] ?? null;

        return array_filter([
            'search' => $criteria['terms'] ? implode(' ', $criteria['terms']) : null,
            'selectedWorkType' => ($criteria['remote'] ?? false) ? 'Remote' : null,
            'selectedLocation' => $region && $region['level'] === 'city' ? $region['name'] : null,
            'selectedProvince' => $region && $region['level'] !== 'city' ? $region['name'] : null,
        ]);
    }

    private function parse(string $text): array
    {
        $tokens = preg_split('/[^\p{L}\p{N}+#.]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        [$region, $tokens] = $this->extractRegion($tokens);
        $terms = array_values(array_unique(array_filter($tokens, fn ($t) => mb_strlen($t) >= 2 && ! in_array($t, self::STOPWORDS, true) && ! is_numeric($t))));

        return [
            'terms' => array_slice($terms, 0, 4),
            'region' => $region,
            'remote' => (bool) preg_match('/\b(remote|wfh|wfa|work from home)\b/u', $text),
            'internship' => (bool) preg_match('/\b(magang|internship|intern)\b/u', $text),
        ];
    }

    /**
     * Open vacancies for the criteria. Without a role or place in the message the user's profile decides:
     * best CV match (premium) or their headline, latest applications and skills.
     *
     * @return array{label: string, total: int, personal: bool, jobs: array<int, array<string, mixed>>, explore_url: string}
     */
    public function find(User $user, array $criteria, array $exclude = []): array
    {
        $personal = ! $criteria['terms'] && ! $criteria['region'];
        $terms = $criteria['terms'] ?: ($personal ? $this->profileTerms($user) : []);
        $base = Vacancy::visible()->with(['company', 'region.parent'])
            ->when($criteria['region'], fn ($q) => $q->whereIn('region_id', $criteria['region']['ids']))
            ->when($criteria['remote'], fn ($q) => $q->where('work_mode', 'remote'))
            ->when($criteria['internship'], fn ($q) => $q->where('is_internship', true))
            ->when($exclude, fn ($q) => $q->whereNotIn('id', $exclude)); // "ada yang lain?" skips what was already shown

        $query = $this->matching(clone $base, $terms, true);
        // Nothing with all the words: any of them, in the title only ("data" in a backend job's skills is no match).
        // Not for "ada yang lain?": when the good matches are used up, say so instead of drifting off topic.
        if ($terms && count($terms) > 1 && empty($criteria['more']) && (clone $query)->doesntExist()) {
            $query = $this->matching(clone $base, $terms, false);
        }
        if ($personal && $terms && (clone $query)->doesntExist()) {
            $query = clone $base; // a profile with no matching words still gets something useful
        }

        $canMatch = $user->canAccessFeature('job_match');
        $ranked = $canMatch ? rescue(fn () => $this->scorer->rankedIds($user), [], false) : [];
        $total = (clone $query)->count();
        if ($ranked) {
            $position = array_flip($ranked);
            $ids = (clone $query)->pluck('id')->sortBy(fn ($id) => $position[$id] ?? PHP_INT_MAX)->take(self::LIMIT * 3)->values()->all();
            $jobs = Vacancy::with(['company', 'region.parent'])->whereIn('id', $ids)->get()->sortBy(fn ($v) => array_search($v->id, $ids, true))->values();
        } else {
            // Jobs whose title holds the first word come before matches found only in skills or the company name.
            $first = $terms[0] ?? null;
            $jobs = $query->when($first, fn ($q) => $q->orderByRaw('CASE WHEN title LIKE ? THEN 0 ELSE 1 END', ['%'.$first.'%']))
                ->orderByRaw('COALESCE(date_posted, first_seen_at) desc')->limit(self::LIMIT * 3)->get();
        }
        // The same posting listed twice (one company, one title) is shown once.
        $jobs = $jobs->unique(fn ($v) => mb_strtolower($v->title.'|'.$v->company_id))->take(self::LIMIT)->values();
        $scores = $canMatch && $jobs->isNotEmpty() ? rescue(fn () => $this->scorer->forVacancies($user, $jobs), [], false) : [];

        return [
            'label' => $this->label($criteria, $terms, $personal),
            'total' => $total,
            'personal' => $personal,
            'jobs' => $jobs->map(fn (Vacancy $v) => $this->summary($v, $scores[$v->id] ?? null))->all(),
            'explore_url' => route('jobs.explore', array_filter(['search' => $criteria['terms'] ? implode(' ', $criteria['terms']) : null]), false),
        ];
    }

    /** Job-market facts that are always useful to Nara: catalogue size, tracked jobs that closed, saved searches. */
    public function marketContext(User $user): array
    {
        $closed = JobApplication::where('user_id', $user->id)->whereNotNull('vacancy_id')
            ->where(fn ($q) => $q->where('is_archived', false)->orWhereNull('is_archived'))->where('application_status', 'On Process')
            ->whereHas('vacancy', fn ($q) => $q->whereIn('status', ['closed', 'archived']))
            ->limit(5)->get(['company_name', 'position'])->map(fn ($a) => $a->position.' · '.$a->company_name)->all();

        return [
            'open_jobs_in_catalogue' => Cache::remember('nara:open-jobs', 600, fn () => Vacancy::visible()->count()),
            'tracked_jobs_closed_at_source' => $closed,
            'saved_searches' => DB::table('saved_job_searches')->where('user_id', $user->id)->limit(5)->pluck('name')->all(),
        ];
    }

    /**
     * Replace [job:ID] in a reply with a self-contained card tag. Only ids Nara was given may become cards;
     * anything else is dropped so an invented job never reaches the user.
     *
     * @param  array<int, int>  $allowed
     */
    public function expand(string $reply, array $allowed, ?int $userId = null): string
    {
        $cards = Vacancy::visible()->with(['company', 'region.parent'])->whereIn('id', $allowed)->get()->keyBy('id');
        $saved = $userId ? \App\Models\JobBookmark::where('user_id', $userId)->whereIn('vacancy_id', $cards->keys())->pluck('vacancy_id')->flip() : collect();
        $clean = fn ($value) => trim(str_replace(['|', '[', ']', "\n", "\r"], ['/', '(', ')', ' ', ' '], (string) $value));

        $reply = preg_replace_callback('/\[job:(\d+)(?:\|[^\]]*)?\]/', function ($m) use ($cards, $clean, $saved) {
            $vacancy = $cards[(int) $m[1]] ?? null;
            if (! $vacancy) {
                return '';
            }
            $meta = implode(' · ', array_filter([$vacancy->location ?: ($vacancy->work_mode === 'remote' ? 'Remote' : null), $vacancy->salary_label]));

            // [job:id|title|company|meta|url|saved]: everything the card shows, from the database.
            return '[job:'.$vacancy->id.'|'.$clean($vacancy->title).'|'.$clean($vacancy->company?->name).'|'.$clean($meta).'|'
                .route('jobs.explore', ['job' => $vacancy->id], false).'|'.(isset($saved[$vacancy->id]) ? '1' : '0').']';
        }, $reply);

        return trim(preg_replace("/\n{3,}/", "\n\n", $reply));
    }

    private function matching($query, array $terms, bool $all)
    {
        if (! $terms) {
            return $query;
        }

        return $query->where(function ($outer) use ($terms, $all) {
            foreach ($terms as $term) {
                $like = '%'.$term.'%';
                // Short words ("it", "hr", "qa") match whole words in the title only, not "Digital" or "Security".
                $clause = mb_strlen($term) <= 3
                    ? fn ($q) => $q->where('title', 'like', $term.' %')->orWhere('title', 'like', '% '.$term.' %')->orWhere('title', 'like', '% '.$term)
                        ->orWhere('title', 'like', '%('.$term.')%')->orWhere('title', $term)
                    : fn ($q) => $q->where('title', 'like', $like)->orWhere('skills', 'like', $like)
                        ->orWhereHas('company', fn ($c) => $c->where('name', 'like', $like));
                $all ? $outer->where($clause) : $outer->orWhere(fn ($q) => $q->where('title', 'like', $like));
            }
        });
    }

    /** Words that describe what the user is after when they just ask "find me a job". */
    private function profileTerms(User $user): array
    {
        $positions = JobApplication::where('user_id', $user->id)->latest('updated_at')->limit(10)->pluck('position')
            ->map(fn ($p) => Str::of((string) $p)->lower()->before(' - ')->before('(')->trim()->value())->filter()->countBy()->sortDesc()->keys()->take(2)->all();
        $headline = Str::of((string) $user->profile?->headline)->lower()->before(' at ')->before(' di ')->trim()->value();

        return array_values(array_slice(array_filter(array_unique([$headline ?: null, ...$positions])), 0, 2));
    }

    /**
     * Longest place name first ("jakarta selatan" before "jakarta"). One name can cover several regions (Kota and
     * Kabupaten Bandung); a province covers its cities.
     *
     * @return array{0: ?array{label: string, ids: array<int, int>}, 1: array<int, string>}
     */
    private function extractRegion(array $tokens): array
    {
        $names = Cache::remember('nara:region-names:v2', 3600, fn () => Region::query()->get(['id', 'name', 'level'])->groupBy(fn ($r) => self::placeKey($r->name))
            ->map(fn ($group) => ['label' => $group->first()->name, 'ids' => $group->pluck('id')->all(), 'name' => $group->sortBy(fn ($r) => str_starts_with($r->name, 'Kota') ? 0 : 1)->first()->name,
                'level' => $group->first()->level])->all());
        $aliases = ['jogja' => 'yogyakarta', 'jogjakarta' => 'yogyakarta', 'solo' => 'surakarta', 'jkt' => 'jakarta', 'bdg' => 'bandung', 'sby' => 'surabaya'];
        for ($size = 3; $size >= 1; $size--) {
            for ($i = 0; $i + $size <= count($tokens); $i++) {
                $phrase = implode(' ', array_slice($tokens, $i, $size));
                $key = $aliases[$phrase] ?? $phrase;
                if (isset($names[$key])) {
                    array_splice($tokens, $i, $size);
                    $ids = $names[$key]['ids'];

                    return [[
                        'label' => count($ids) > 1 ? ucwords($key) : $names[$key]['label'],
                        'ids' => Region::whereIn('id', $ids)->orWhereIn('parent_id', $ids)->pluck('id')->all(),
                        'name' => $names[$key]['name'], 'level' => $names[$key]['level'],
                    ], $tokens];
                }
            }
        }

        return [null, $tokens];
    }

    private static function placeKey(string $name): string
    {
        return trim(preg_replace('/^(kota administrasi|kota|kabupaten|kab\.|provinsi|daerah istimewa|dki)\s+/u', '', mb_strtolower($name)));
    }

    private function label(array $criteria, array $terms, bool $personal): string
    {
        $parts = array_filter([
            $terms ? implode(' ', $terms) : null,
            $criteria['region']['label'] ?? null,
            $criteria['remote'] ? 'remote' : null,
            $criteria['internship'] ? __('internship') : null,
        ]);
        $label = $parts ? implode(' · ', $parts) : __('all jobs');

        return $personal ? __('based on your profile: :label', ['label' => $label]) : $label;
    }

    /**
     * Facts about one vacancy for Nara. With a CV match (premium) it also says which of the job's skills the user
     * has and which are missing, so Nara can explain a score instead of only quoting it.
     *
     * @param  array{score: int, matched: array<int, string>, missing: array<int, string>, level_gap: bool}|null  $match
     */
    private function summary(Vacancy $v, ?array $match, bool $detailed = false): array
    {
        return array_filter([
            'id' => $v->id,
            'title' => $v->title,
            'company' => $v->company?->name,
            'location' => $v->location ?: ($v->work_mode === 'remote' ? 'Remote' : null),
            'work_mode' => $v->work_mode,
            'type' => $v->employment_type,
            'level' => $v->seniority,
            'salary' => $v->salary_label,
            'skills' => array_slice((array) $v->skills, 0, $detailed ? 15 : 6),
            'posted' => ($v->date_posted ?? $v->first_seen_at)?->toDateString(),
            'apply_before' => $v->valid_through?->toDateString(),
            'education_min' => $v->education_min,
            'summary' => Str::limit((string) $v->summary, $detailed ? 700 : 220),
            'cv_match_percent' => $match['score'] ?? null,
            'skills_you_have' => array_slice((array) ($match['matched'] ?? []), 0, 6),
            'skills_you_lack' => array_slice((array) ($match['missing'] ?? []), 0, 6),
            'level_above_your_experience' => ($match['level_gap'] ?? false) ?: null,
            'still_open' => $detailed ? $v->status === 'active' : null,
        ], fn ($value) => $value !== null && $value !== '' && $value !== []);
    }

    // ------------------------------------------------------------------ structured search (Nara's tools)

    private const EMPLOYMENT = ['full_time', 'part_time', 'contract', 'internship', 'freelance'];

    private const LEVELS = ['intern', 'entry', 'mid', 'senior', 'lead', 'manager'];

    /**
     * Search the catalogue with the filters Nara chose: several keywords (any of them), place, work mode, type,
     * level, minimum salary, deadline, company. Every filter is optional; none at all means "use my profile".
     *
     * @param  array<string, mixed>  $args
     * @param  array<int, int>  $exclude  vacancy ids already shown in this conversation
     * @return array{label: string, total: int, personal: bool, jobs: array<int, array<string, mixed>>, notes: array<int, string>, explore_url: string, criteria: array<string, mixed>}
     */
    public function search(User $user, array $args, array $exclude = []): array
    {
        $keywords = array_slice(array_values(array_unique(array_filter(array_map(
            fn ($k) => Str::limit(mb_strtolower(trim((string) $k)), 40, ''), (array) ($args['keywords'] ?? []))))), 0, 8);
        $notes = [];
        $region = null;
        if (filled($args['location'] ?? null)) {
            [$region] = $this->extractRegion(preg_split('/[^\p{L}\p{N}+#.]+/u', mb_strtolower((string) $args['location']), -1, PREG_SPLIT_NO_EMPTY));
            if (! $region) {
                $notes[] = 'location_not_recognized: "'.$args['location'].'" is not a place in the catalogue, so no location filter was applied';
            }
        }
        $workMode = in_array($args['work_mode'] ?? null, ['remote', 'onsite', 'hybrid'], true) ? $args['work_mode'] : null;
        $type = in_array($args['employment_type'] ?? null, self::EMPLOYMENT, true) ? $args['employment_type'] : null;
        $level = in_array($args['level'] ?? null, self::LEVELS, true) ? $args['level'] : null;
        $internship = (bool) ($args['internship'] ?? false) || $type === 'internship';
        $minSalary = max(0, (int) ($args['min_monthly_salary_idr'] ?? 0));
        $closing = max(0, (int) ($args['closing_within_days'] ?? 0));
        $posted = max(0, (int) ($args['posted_within_days'] ?? 0));
        $company = Str::limit(trim((string) ($args['company'] ?? '')), 60, '');

        $filtered = $keywords || $region || $workMode || $type || $level || $internship || $minSalary || $closing || $posted || $company;
        $personal = ! $filtered;
        if ($personal) {
            $keywords = $this->profileTerms($user);
        }

        $query = Vacancy::visible()->with(['company', 'region.parent'])
            ->when($region, fn ($q) => $q->whereIn('region_id', $region['ids']))
            ->when($workMode, fn ($q) => $q->where('work_mode', $workMode))
            ->when($type && $type !== 'internship', fn ($q) => $q->where('employment_type', $type))
            ->when($level, fn ($q) => $q->where('seniority', $level))
            ->when($internship, fn ($q) => $q->where('is_internship', true))
            ->when($minSalary, fn ($q) => $q->whereNotNull('salary_min')
                ->where(fn ($s) => $s->where('salary_max', '>=', $minSalary)->orWhere(fn ($o) => $o->whereNull('salary_max')->where('salary_min', '>=', $minSalary)))
                ->where(fn ($s) => $s->whereNull('salary_currency')->orWhere('salary_currency', 'IDR'))
                ->where(fn ($s) => $s->whereNull('salary_period')->orWhere('salary_period', 'month')))
            ->when($closing, fn ($q) => $q->whereBetween('valid_through', [now()->toDateString(), now()->addDays($closing)->toDateString()]))
            ->when($posted, fn ($q) => $q->whereRaw('COALESCE(date_posted, first_seen_at) >= ?', [now()->subDays($posted)->toDateString()]))
            ->when($company, fn ($q) => $q->whereHas('company', fn ($c) => $c->where('name', 'like', '%'.$company.'%')))
            ->when($exclude, fn ($q) => $q->whereNotIn('id', $exclude));
        if ($keywords) {
            $query->where(function ($outer) use ($keywords) {
                foreach ($keywords as $word) {
                    $outer->orWhere($this->keywordClause($word));
                }
            });
        }
        if ($minSalary) {
            $notes[] = 'salary_filter: only jobs that state a monthly rupiah salary can match; most jobs do not state one';
            // Few jobs state a salary: with nothing left, search again without it and say so, instead of an empty answer.
            if ((clone $query)->count() === 0) {
                $without = $this->search($user, array_diff_key($args, ['min_monthly_salary_idr' => 0]), $exclude);
                $without['salary_filter_dropped'] = true;
                $without['notes'][] = 'salary_filter_dropped: no open job states a monthly salary that high, so these jobs match WITHOUT the salary filter; tell the user and do not claim they meet the salary';

                return $without;
            }
        }

        $canMatch = $user->canAccessFeature('job_match');
        $ranked = $canMatch && ($personal || ! empty($args['prefer_best_match'])) ? rescue(fn () => $this->scorer->rankedIds($user), [], false) : [];
        $total = (clone $query)->count();
        if ($ranked) {
            $position = array_flip($ranked);
            $ids = (clone $query)->pluck('id')->sortBy(fn ($id) => $position[$id] ?? PHP_INT_MAX)->take(self::LIMIT * 3)->values()->all();
            $jobs = Vacancy::with(['company', 'region.parent'])->whereIn('id', $ids)->get()->sortBy(fn ($v) => array_search($v->id, $ids, true))->values();
        } else {
            // Most keyword hits in the title first, then newest.
            $hits = $keywords ? '('.implode(' + ', array_fill(0, count($keywords), 'CASE WHEN LOWER(title) LIKE ? THEN 1 ELSE 0 END')).') DESC' : null;
            $jobs = $query->when($hits, fn ($q) => $q->orderByRaw($hits, array_map(fn ($k) => '%'.$k.'%', $keywords)))
                ->orderByRaw('COALESCE(date_posted, first_seen_at) desc')->limit(self::LIMIT * 3)->get();
        }
        $jobs = $jobs->unique(fn ($v) => mb_strtolower($v->title.'|'.$v->company_id))->take(self::LIMIT)->values();
        $scores = $canMatch && $jobs->isNotEmpty() ? rescue(fn () => $this->scorer->forVacancies($user, $jobs), [], false) : [];

        $criteria = ['terms' => array_slice($keywords, 0, 1), 'region' => $region, 'remote' => $workMode === 'remote', 'internship' => $internship];

        return [
            'label' => $this->label($criteria, $keywords, $personal),
            'total' => $total,
            'personal' => $personal,
            'jobs' => $jobs->map(fn (Vacancy $v) => $this->summary($v, $scores[$v->id] ?? null))->all(),
            'notes' => $notes,
            'explore_url' => route('jobs.explore', array_filter(['search' => $keywords[0] ?? null]), false),
            'criteria' => $criteria,
        ];
    }

    /** One keyword against the title (and skills / company for longer words). Short words ("it", "qa") match whole words in the title only. */
    private function keywordClause(string $word): \Closure
    {
        $like = '%'.$word.'%';

        return mb_strlen($word) <= 3
            ? fn ($q) => $q->where('title', 'like', $word.' %')->orWhere('title', 'like', '% '.$word.' %')->orWhere('title', 'like', '% '.$word)
                ->orWhere('title', 'like', '%('.$word.')%')->orWhere('title', $word)
            : fn ($q) => $q->where('title', 'like', $like)->orWhere('skills', 'like', $like)
                ->orWhereHas('company', fn ($c) => $c->where('name', 'like', $like));
    }

    /** Full facts for one or more vacancies (details and comparisons), each with the user's CV match for premium. @param array<int, int> $ids */
    public function details(User $user, array $ids): array
    {
        $jobs = Vacancy::with(['company', 'region.parent'])->whereIn('id', $ids)->get()->sortBy(fn ($v) => array_search($v->id, $ids, true))->values();
        $scores = $user->canAccessFeature('job_match') && $jobs->isNotEmpty() ? rescue(fn () => $this->scorer->forVacancies($user, $jobs), [], false) : [];

        return $jobs->map(fn (Vacancy $v) => $this->summary($v, $scores[$v->id] ?? null, true))->all();
    }

    /**
     * The side-by-side comparison of 2-3 jobs as a [compare:...] tag: base64 JSON with the jobs and localized rows, drawn
     * as a table by the chat. Built from the database, so it only ever holds real facts. Null with fewer than two jobs.
     *
     * @param  array<int, int>  $ids
     */
    public function comparisonTag(User $user, array $ids): ?string
    {
        $jobs = $this->details($user, array_slice(array_values(array_unique($ids)), 0, 3));
        if (count($jobs) < 2) {
            return null;
        }
        $row = fn (string $label, callable $value) => [$label, array_map(fn ($j) => (string) ($value($j) ?? ''), $jobs)];
        $list = fn ($items) => implode(', ', array_slice((array) $items, 0, 5));
        $mode = ['remote' => 'Remote', 'onsite' => 'Onsite', 'hybrid' => 'Hybrid'];
        $type = ['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Contract', 'internship' => 'Internship', 'freelance' => 'Freelance'];
        $level = ['intern' => 'Intern', 'entry' => 'Entry level', 'mid' => 'Mid level', 'senior' => 'Senior level', 'lead' => 'Lead', 'manager' => 'Manager'];
        $rows = array_values(array_filter([
            $row(__('Company'), fn ($j) => $j['company'] ?? null),
            $row(__('Location'), fn ($j) => $j['location'] ?? null),
            $row(__('Work arrangement'), fn ($j) => $mode[$j['work_mode'] ?? ''] ?? null),
            $row(__('Employment type'), fn ($j) => $type[$j['type'] ?? ''] ?? null),
            $row(__('Level'), fn ($j) => $level[$j['level'] ?? ''] ?? null),
            $row(__('Salary'), fn ($j) => $j['salary'] ?? null),
            $row(__('Apply by'), fn ($j) => $j['apply_before'] ?? null),
            $row(__('CV match'), fn ($j) => isset($j['cv_match_percent']) ? $j['cv_match_percent'].'%' : null),
            $row(__('Skills you have'), fn ($j) => $list($j['skills_you_have'] ?? [])),
            $row(__('Skills to build'), fn ($j) => $list($j['skills_you_lack'] ?? [])),
        ], fn ($r) => array_filter($r[1], fn ($v) => $v !== '') !== []));
        $payload = [
            'jobs' => array_map(fn ($j) => ['id' => $j['id'], 'title' => $j['title'], 'company' => $j['company'] ?? '', 'url' => route('jobs.explore', ['job' => $j['id']], false)], $jobs),
            'rows' => $rows,
        ];

        return '[compare:'.rtrim(strtr(base64_encode(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)), '+/', '-_'), '=').']';
    }

    /**
     * Save a search with daily email alerts (the same record Explore's "Save search" makes). Null when the filters
     * are too broad to alert on, or the user already has 20 searches.
     *
     * @param  array<string, mixed>  $criteria
     */
    public function saveAlert(User $user, array $criteria, string $label): ?SavedJobSearch
    {
        $filters = $this->savedSearchFilters($criteria);
        if (! VacancyFilters::narrows($filters)) {
            return null;
        }
        $fingerprint = hash('sha256', json_encode($filters));
        $searches = SavedJobSearch::where('user_id', $user->id);
        if (! (clone $searches)->where('fingerprint', $fingerprint)->exists() && (clone $searches)->count() >= 20) {
            return null;
        }

        return SavedJobSearch::updateOrCreate(['user_id' => $user->id, 'fingerprint' => $fingerprint],
            ['name' => Str::limit(ucfirst($label), 60, ''), 'filters' => $filters, 'alert_channel' => 'email', 'last_alerted_at' => now()]);
    }
}
