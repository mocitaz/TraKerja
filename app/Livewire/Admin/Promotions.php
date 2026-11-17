<?php

namespace App\Livewire\Admin;

use App\JobEngine\Promotion\PromoWriter;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Admin · Promosi Media Sosial (TraKerja 2.0): pick an open vacancy, let AI write the pamphlet headline and
 * one caption per platform (Threads, Instagram, LinkedIn, X, WhatsApp/Telegram), edit, download the pamphlet
 * as a feed post (1080×1350) or a story (1080×1920), copy each caption, then mark the platforms it was posted
 * on. Promotions are kept so the same job is not promoted twice by accident.
 */
class Promotions extends Component
{
    #[Url(as: 'vacancy')]
    public ?int $vacancyId = null;

    public ?int $promotionId = null;

    public string $search = '';

    public string $headline = '';

    /** @var array<int, string> */
    public array $highlights = ['', '', ''];

    /** @var array<string, string> platform => caption */
    public array $captions = [];

    public string $platform = 'threads';

    public string $format = 'feed';

    /** @var array<string, string> platform => post URL ('' = posted without a link) */
    public array $postUrls = [];

    /** @var array<string, string> platform => posted at (ISO) */
    public array $postedOn = [];

    public string $flash = '';

    public bool $aiUsed = false;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
        if ($this->vacancyId) {
            $this->choose($this->vacancyId);
        }
    }

    public function choose(int $id): void
    {
        $vacancy = Vacancy::visible()->findOrFail($id);
        $this->vacancyId = $vacancy->id;
        $this->search = '';
        $this->flash = '';
        $this->platform = 'threads';
        // Reopen the latest draft for this job instead of starting over.
        $draft = DB::table('job_promotions')->where('vacancy_id', $vacancy->id)->latest('id')->first();
        $saved = $draft ? (array) json_decode((string) $draft->captions, true) : [];
        if ($draft && ! $saved && $draft->caption) {
            $saved = ['threads' => $draft->caption]; // drafts from before multi-platform
        }
        $this->promotionId = $draft?->id;
        $this->headline = $draft?->headline ?? $vacancy->title;
        $this->highlights = array_pad($draft ? (array) json_decode($draft->highlights, true) : [], 3, '');
        $this->captions = [];
        foreach (array_keys(PromoWriter::PLATFORMS) as $key) {
            $this->captions[$key] = (string) ($saved[$key] ?? PromoWriter::finish('', $vacancy, $key));
        }
        $posted = $draft ? (array) json_decode((string) $draft->posted_on, true) : [];
        if ($draft && ! $posted && $draft->status === 'posted') {
            $posted = ['threads' => ['at' => $draft->posted_at, 'url' => $draft->post_url]];
        }
        $this->postedOn = array_map(fn ($p) => (string) ($p['at'] ?? ''), $posted);
        $this->postUrls = array_merge(array_fill_keys(array_keys(PromoWriter::PLATFORMS), ''), array_map(fn ($p) => (string) ($p['url'] ?? ''), $posted));
    }

    public function clearVacancy(): void
    {
        $this->reset(['vacancyId', 'promotionId', 'headline', 'captions', 'postUrls', 'postedOn', 'flash', 'aiUsed']);
        $this->highlights = ['', '', ''];
    }

    public function setPlatform(string $platform): void
    {
        if (isset(PromoWriter::PLATFORMS[$platform])) {
            $this->platform = $platform;
        }
    }

    public function setFormat(string $format): void
    {
        $this->format = $format === 'story' ? 'story' : 'feed';
    }

    public function generate(PromoWriter $writer): void
    {
        $vacancy = Vacancy::visible()->findOrFail($this->vacancyId);
        $copy = $writer->write($vacancy);
        $this->headline = $copy['headline'];
        $this->highlights = array_pad($copy['highlights'], 3, '');
        $this->captions = $copy['captions'];
        $this->aiUsed = $copy['ai'];
        $this->flash = $copy['ai'] ? __('Captions for 5 platforms written by AI. Review before posting.') : __('AI is unavailable; captions were built from the job data. Please polish them.');
    }

    public function save(): void
    {
        $rules = ['headline' => 'required|string|max:80', 'highlights' => 'array|max:5', 'highlights.*' => 'nullable|string|max:80'];
        $names = [];
        foreach (PromoWriter::PLATFORMS as $key => [$label, $limit]) {
            $rules["captions.{$key}"] = 'nullable|string|max:'.$limit;
            $names["captions.{$key}"] = 'caption '.$label;
        }
        $this->validate($rules, [], $names);
        $captions = array_map(fn ($c) => trim((string) $c), array_intersect_key($this->captions, PromoWriter::PLATFORMS));
        $row = [
            'vacancy_id' => $this->vacancyId, 'headline' => trim($this->headline),
            'highlights' => json_encode(array_values(array_filter(array_map('trim', $this->highlights)))),
            'caption' => $captions['threads'] ?? '', 'captions' => json_encode($captions), 'updated_at' => now(),
        ];
        if ($this->promotionId) {
            DB::table('job_promotions')->where('id', $this->promotionId)->update($row);
        } else {
            $this->promotionId = DB::table('job_promotions')->insertGetId($row + ['status' => 'draft', 'created_by' => Auth::id(), 'created_at' => now()]);
        }
        $this->flash = __('Draft saved.');
    }

    /** Mark the current platform as posted (or undo it). */
    public function togglePosted(?string $platform = null): void
    {
        $platform ??= $this->platform;
        abort_unless(isset(PromoWriter::PLATFORMS[$platform]), 404);
        $this->validate(["postUrls.{$platform}" => 'nullable|url|max:255'], [], ["postUrls.{$platform}" => __('post link')]);
        $this->save();
        if (isset($this->postedOn[$platform])) {
            unset($this->postedOn[$platform]);
        } else {
            $this->postedOn[$platform] = now()->toIso8601String();
        }
        $posted = [];
        foreach ($this->postedOn as $key => $at) {
            $posted[$key] = ['at' => $at, 'url' => ($this->postUrls[$key] ?? '') ?: null];
        }
        DB::table('job_promotions')->where('id', $this->promotionId)->update([
            'posted_on' => json_encode($posted ?: null),
            'status' => $posted ? 'posted' : 'draft',
            'posted_at' => $posted ? max(array_column($posted, 'at')) : null,
            'post_url' => $posted[$platform]['url'] ?? collect($posted)->pluck('url')->filter()->first(),
            'updated_at' => now(),
        ]);
        $label = PromoWriter::PLATFORMS[$platform][0];
        $this->flash = isset($this->postedOn[$platform]) ? __('Marked as posted on :platform.', ['platform' => $label]) : __('Posted mark on :platform removed.', ['platform' => $label]);
    }

    public function render()
    {
        $vacancy = $this->vacancyId ? Vacancy::with(['company', 'region', 'primaryListing.source', 'listings.source'])->find($this->vacancyId) : null;
        $promoted = DB::table('job_promotions')->where('created_at', '>=', now()->subDays(14))->pluck('vacancy_id')->all();
        $base = Vacancy::visible()->with(['company', 'region']);
        $term = trim($this->search);

        return view('livewire.admin.promotions', [
            'vacancy' => $vacancy,
            'platforms' => PromoWriter::PLATFORMS,
            'results' => mb_strlen($term) >= 2
                ? (clone $base)->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhereHas('company', fn ($c) => $c->where('name', 'like', "%{$term}%")))
                    ->orderByRaw('COALESCE(date_posted, first_seen_at) desc')->orderByDesc('id')->limit(8)->get()
                : collect(),
            // Good candidates: recent, with a salary or a company logo, not promoted in the last two weeks.
            'suggestions' => (clone $base)->whereNotIn('id', $promoted)->when($this->vacancyId, fn ($q) => $q->whereKeyNot($this->vacancyId))
                ->where(fn ($q) => $q->whereNotNull('salary_min')->orWhereHas('company', fn ($c) => $c->whereNotNull('logo_url')))
                ->orderByRaw('COALESCE(date_posted, first_seen_at) desc')->orderByDesc('id')->limit(6)->get(),
            'history' => DB::table('job_promotions')->join('vacancies', 'vacancies.id', '=', 'job_promotions.vacancy_id')
                ->select('job_promotions.*', 'vacancies.title as vacancy_title')->latest('job_promotions.updated_at')->limit(12)->get(),
            'pamphlet' => $vacancy ? $this->pamphletData($vacancy) : null,
            'captionLength' => $this->captionLength($this->captions[$this->platform] ?? ''),
            'limit' => PromoWriter::PLATFORMS[$this->platform][1],
        ]);
    }

    /** Length as the platform counts it (X counts every link as 23 characters). */
    private function captionLength(string $caption): int
    {
        $cost = PromoWriter::PLATFORMS[$this->platform][3] ?? null;
        if ($cost) {
            return mb_strlen(preg_replace('/https?:\/\/\S+/u', '', $caption)) + $cost * preg_match_all('/https?:\/\/\S+/u', $caption);
        }

        return mb_strlen($caption);
    }

    /** Everything the canvas needs, as plain JSON. */
    private function pamphletData(Vacancy $v): array
    {
        $types = ['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Contract', 'internship' => 'Internship', 'freelance' => 'Freelance'];
        $modes = ['onsite' => 'Onsite', 'hybrid' => 'Hybrid', 'remote' => 'Remote'];
        $levels = ['intern' => 'Internship', 'entry' => 'Entry level', 'mid' => 'Mid level', 'senior' => 'Senior level', 'lead' => 'Lead', 'manager' => 'Manager'];

        return [
            'headline' => self::tidyTitle($this->headline ?: $v->title),
            'company' => (string) $v->company?->name,
            'location' => (string) ($v->location ?: ($v->work_mode === 'remote' ? 'Remote' : '')),
            'chips' => array_values(array_filter([$modes[$v->work_mode] ?? null, $types[$v->employment_type] ?? null, $levels[$v->seniority] ?? null])),
            'workMode' => $modes[$v->work_mode] ?? null,
            'employmentType' => $types[$v->employment_type] ?? null,
            'level' => $levels[$v->seniority] ?? null,
            'deadline' => $v->valid_through?->locale(app()->getLocale())->translatedFormat('d M Y'),
            'logo' => $v->company?->logo_url ? route('admin.promotions.logo', $v->id) : null,
            'brandIcon' => asset('images/icon.png'),
            'format' => $this->format,
            'filename' => 'trakerja-loker-'.\Illuminate\Support\Str::slug($v->title).'-'.$v->id.($this->format === 'story' ? '-story' : '').'.png',
        ];
    }

    /**
     * ALL-CAPS titles read as shouting on a poster: turn them into title case and keep short acronyms
     * ("SALES INTERNET RAKYAT (IRA) - C" becomes "Sales Internet Rakyat (IRA) - C").
     */
    public static function tidyTitle(string $title): string
    {
        $letters = preg_replace('/[^\p{L}]/u', '', $title);
        if ($letters === '' || mb_strtoupper($letters) !== $letters) {
            return $title;
        }

        return preg_replace_callback('/\p{L}+/u', function ($m) use ($title) {
            $word = $m[0];
            $inParens = (bool) preg_match('/\(\s*'.preg_quote($word, '/').'\s*\)/u', $title);

            return mb_strlen($word) === 1 || ($inParens && mb_strlen($word) <= 5) || in_array($word, ['IT', 'HR', 'HRD', 'QA', 'UI', 'UX', 'PT', 'CV', 'SPG', 'SPB', 'CS', 'BD', 'AI', 'SEO', 'CEO', 'CFO', 'CTO', 'B2B', 'F&B'], true)
                ? $word : mb_convert_case(mb_strtolower($word), MB_CASE_TITLE);
        }, $title);
    }
}
