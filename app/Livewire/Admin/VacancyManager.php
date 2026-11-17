<?php

namespace App\Livewire\Admin;

use App\JobEngine\Intelligence\VacancyMerger;
use App\JobEngine\Verify\VerificationService;
use App\Models\JobSource;
use App\Models\Vacancy;
use App\Models\VacancyListing;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin · Kelola Loker (TraKerja 2.0): one place to keep the job catalogue clean.
 * A review queue that explains why each vacancy needs a look, the full list with bulk actions,
 * a detail panel (listings, checks, reports, merges), duplicate review and the weekly QA sample.
 */
class VacancyManager extends Component
{
    use WithPagination;

    public const TABS = ['review', 'all', 'pairs', 'qa'];

    public const QA_VERDICTS = [
        'correct' => 'Correct',
        'wrong_status' => 'Wrong status (already closed / still open)',
        'wrong_data' => 'Wrong data (title, location, salary, etc.)',
        'duplicate' => 'Duplicate that slipped through',
    ];

    public const REASONS = [
        'reported' => ['Reported by users', 'flag'],
        'scam' => ['Suspected scams', 'shield-warning'],
        'salary' => ['Unreasonable salary', 'money'],
        'caps' => ['Title in all caps', 'text-aa'],
        'region' => ['Region missing', 'map-pin'],
        'stale' => ['Live > 60 days with no deadline', 'hourglass'],
        'raw' => ['Not yet enriched by AI', 'sparkle'],
    ];

    #[Url]
    public string $tab = 'review';

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $status = 'open';

    #[Url]
    public string $source = '';

    #[Url]
    public string $flag = '';

    /** Vacancy open in the detail panel. */
    #[Url(as: 'v')]
    public ?int $detailId = null;

    /** @var array<int, string> ids ticked for a bulk action */
    public array $selected = [];

    public array $qaNotes = [];

    public string $flash = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
        if (! in_array($this->tab, self::TABS, true)) {
            $this->tab = 'review';
        }
    }

    public function selectTab(string $tab): void
    {
        abort_unless(in_array($tab, self::TABS, true), 422);
        [$this->tab, $this->selected, $this->flash] = [$tab, [], ''];
        $this->resetPage();
    }

    public function updating($name): void
    {
        if (in_array($name, ['search', 'status', 'source', 'flag'], true)) {
            $this->resetPage();
            $this->selected = [];
        }
    }

    // ---------------------------------------------------------------- single actions

    public function open(int $id): void
    {
        $this->detailId = $id;
    }

    public function closeDetail(): void
    {
        $this->detailId = null;
    }

    /** Looked at and fine: leaves the review queue and closes its open user reports. */
    public function markChecked(int $id): void
    {
        $this->check([$id]);
        $this->flash = __('Marked as checked.');
    }

    public function archive(int $id): void
    {
        $this->archiveIds([$id]);
        $this->flash = __('Job archived.');
    }

    public function restore(int $id): void
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->update(['status' => 'closed', 'closed_reason' => null]);
        VerificationService::syncVacancyStatus($vacancy->fresh());
        $this->flash = __('":title" restored as :status.', ['title' => $vacancy->title, 'status' => $vacancy->fresh()->status]);
    }

    public function recheck(int $id): void
    {
        $n = $this->recheckIds([$id]);
        $this->flash = $n ? __(':count listings queued for the next check.', ['count' => $n]) : __('No active listings to check.');
    }

    public function toggleScam(int $id): void
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->update(['scam_flag' => $vacancy->scam_flag === 'suspected' ? 'none' : 'suspected']);
        $this->flash = $vacancy->scam_flag === 'suspected' ? __('Marked as a suspected scam and hidden.') : __('Scam flag removed.');
    }

    /** Undo a merge from the detail panel (same as php artisan jobs:unmerge). */
    public function unmerge(int $duplicateId): void
    {
        $duplicate = Vacancy::whereNotNull('merged_into_id')->findOrFail($duplicateId);
        $n = app(VacancyMerger::class)->unmerge($duplicate);
        $this->flash = __('Merge undone: :count listings returned to ":title".', ['count' => $n, 'title' => $duplicate->title]);
    }

    public function archiveOldClosed(): void
    {
        $n = Vacancy::where('status', 'closed')->where('closed_at', '<', now()->subDays(30))->update(['status' => 'archived', 'updated_at' => now()]);
        $this->flash = __(':count jobs closed for more than 30 days archived.', ['count' => $n]);
    }

    // ---------------------------------------------------------------- bulk actions

    public function bulk(string $action): void
    {
        $ids = array_map('intval', $this->selected);
        if (! $ids) {
            $this->flash = __('Select jobs first.');

            return;
        }
        $this->flash = match ($action) {
            'checked' => __(':count jobs marked as checked.', ['count' => $this->check($ids)]),
            'archive' => __(':count jobs archived.', ['count' => $this->archiveIds($ids)]),
            'recheck' => __(':count listings queued for checking.', ['count' => $this->recheckIds($ids)]),
            'scam' => __(':count jobs marked as scams.', ['count' => Vacancy::whereIn('id', $ids)->update(['scam_flag' => 'suspected', 'updated_at' => now()])]),
            default => abort(422),
        };
        $this->selected = [];
    }

    private function check(array $ids): int
    {
        DB::table('vacancy_reports')->whereIn('vacancy_id', $ids)->whereNull('resolved_at')->update(['resolved_at' => now(), 'updated_at' => now()]);

        return Vacancy::whereIn('id', $ids)->update(['admin_checked_at' => now(), 'admin_checked_by' => Auth::id()]);
    }

    private function archiveIds(array $ids): int
    {
        $n = 0;
        foreach (Vacancy::whereIn('id', $ids)->where('status', '!=', 'archived')->get() as $vacancy) {
            $vacancy->update(['status' => 'archived', 'closed_at' => $vacancy->closed_at ?? now(), 'closed_reason' => $vacancy->closed_reason ?? 'archived_by_admin']);
            $n++;
        }

        return $n;
    }

    private function recheckIds(array $ids): int
    {
        return VacancyListing::whereIn('vacancy_id', $ids)->whereIn('status', ['active', 'suspect'])->update(['next_check_at' => now()]);
    }

    // ---------------------------------------------------------------- duplicates and QA

    public function resolvePair(int $reviewId, bool $same): void
    {
        $review = DB::table('dedup_reviews')->where('id', $reviewId)->where('status', 'pending')->first();
        if (! $review) {
            return;
        }
        $listing = VacancyListing::find($review->vacancy_listing_id);
        $candidate = Vacancy::find($review->candidate_vacancy_id);
        if ($same && $listing?->vacancy && $candidate && ! $listing->vacancy->is($candidate)) {
            app(VacancyMerger::class)->merge($listing->vacancy, $candidate, 'manual', (float) $review->score);
        }
        DB::table('dedup_reviews')->where('id', $reviewId)->update([
            'status' => $same ? 'merged_manual' : 'separate', 'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'updated_at' => now(),
        ]);
        $this->flash = $same ? __('Merged.') : __('Marked as different jobs.');
    }

    public function drawQaSample(): void
    {
        $sample = now()->format('o-\WW');
        $ids = Vacancy::where('status', 'active')->inRandomOrder()->limit(20)->pluck('id')
            ->merge(Vacancy::where('status', 'closed')->where('closed_at', '>=', now()->subWeek())->inRandomOrder()->limit(5)->pluck('id'));
        foreach ($ids as $id) {
            DB::table('vacancy_qa_checks')->insertOrIgnore(['vacancy_id' => $id, 'sample' => $sample, 'created_at' => now(), 'updated_at' => now()]);
        }
        $this->flash = __('Sample :sample: :count jobs.', ['sample' => $sample, 'count' => $ids->count()]);
    }

    public function judge(int $checkId, string $verdict): void
    {
        abort_unless(array_key_exists($verdict, self::QA_VERDICTS), 422);
        DB::table('vacancy_qa_checks')->where('id', $checkId)->update([
            'verdict' => $verdict, 'note' => trim($this->qaNotes[$checkId] ?? '') ?: null,
            'checked_by' => Auth::id(), 'checked_at' => now(), 'updated_at' => now(),
        ]);
    }

    // ---------------------------------------------------------------- queries

    /** Why a vacancy needs an admin's eye. Empty when nothing looks off. */
    public static function reasons(Vacancy $v, array $reportedIds): array
    {
        $title = trim((string) $v->title);
        $period = $v->salary_period ?? 'month';
        $monthly = match ($period) { 'year' => 12, 'week' => .25, 'day' => 1 / 22, 'hour' => 1 / 170, default => 1 };

        return array_keys(array_filter([
            'reported' => in_array($v->id, $reportedIds, true),
            'scam' => $v->scam_flag === 'suspected',
            'salary' => ($v->salary_currency ?? 'IDR') === 'IDR' && $v->salary_min
                && ($v->salary_min / $monthly < 500_000 || ($v->salary_max ?? $v->salary_min) / $monthly > 150_000_000),
            'caps' => mb_strlen($title) > 8 && preg_match('/\p{Lu}{3}/u', $title) && $title === mb_strtoupper($title),
            'region' => ! $v->region_id && $v->work_mode !== 'remote',
            'stale' => ! $v->valid_through && $v->first_seen_at && $v->first_seen_at->lt(now()->subDays(60)),
            'raw' => ! $v->enriched_at,
        ]));
    }

    private function reviewQueue(): LengthAwarePaginator
    {
        $reported = DB::table('vacancy_reports')->whereNull('resolved_at')->pluck('vacancy_id')->unique()->all();
        $rows = Vacancy::with(['company', 'region', 'listings.source'])
            ->whereIn('status', ['active', 'suspect'])->whereNull('merged_into_id')
            ->where(fn ($q) => $q->whereNull('admin_checked_at')->orWhereIn('id', $reported))
            ->orderByDesc('id')->get()
            ->map(fn ($v) => tap($v, fn ($v) => $v->setAttribute('review_reasons', self::reasons($v, $reported))))
            ->filter(fn ($v) => $v->review_reasons && ($this->flag === '' || in_array($this->flag, $v->review_reasons, true)))
            ->sortByDesc(fn ($v) => (in_array('reported', $v->review_reasons) ? 100 : 0) + (in_array('scam', $v->review_reasons) ? 50 : 0) + count($v->review_reasons))
            ->values();
        $page = Paginator::resolveCurrentPage();

        return new LengthAwarePaginator($rows->forPage($page, 25)->values(), $rows->count(), 25, $page, ['path' => Paginator::resolveCurrentPath()]);
    }

    private function allQuery()
    {
        $q = Vacancy::with(['company', 'region', 'listings.source'])->whereNull('merged_into_id');
        match ($this->status) {
            'open' => $q->whereIn('status', ['active', 'suspect']),
            'active', 'suspect', 'closed', 'archived' => $q->where('status', $this->status),
            default => null,
        };
        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $q->where(fn ($w) => $w->where('title', 'like', $term)->orWhereHas('company', fn ($c) => $c->where('name', 'like', $term))
                ->orWhere('public_id', strtolower($this->search))->orWhere('id', ctype_digit($this->search) ? (int) $this->search : 0));
        }
        if ($this->source !== '') {
            $q->whereHas('listings', fn ($l) => $l->where('job_source_id', $this->source));
        }
        match ($this->flag) {
            'scam' => $q->where('scam_flag', 'suspected'),
            'region' => $q->whereNull('region_id'),
            'raw' => $q->whereNull('enriched_at'),
            'reported' => $q->whereIn('id', DB::table('vacancy_reports')->whereNull('resolved_at')->select('vacancy_id')),
            default => null,
        };

        return $q->latest('updated_at');
    }

    private function detail(): ?array
    {
        $v = $this->detailId ? Vacancy::with(['company', 'region.parent', 'sector', 'listings.source'])->find($this->detailId) : null;
        if (! $v) {
            return null;
        }

        return [
            'vacancy' => $v,
            'checks' => DB::table('vacancy_checks')->join('vacancy_listings', 'vacancy_listings.id', '=', 'vacancy_checks.vacancy_listing_id')
                ->where('vacancy_listings.vacancy_id', $v->id)->orderByDesc('vacancy_checks.checked_at')->limit(8)
                ->get(['vacancy_checks.*', 'vacancy_listings.job_source_id']),
            'reports' => DB::table('vacancy_reports')->where('vacancy_id', $v->id)->latest()->limit(10)->get(),
            'merged' => Vacancy::where('merged_into_id', $v->id)->with('listings.source')->get(),
            'promotions' => DB::table('job_promotions')->where('vacancy_id', $v->id)->latest()->limit(3)->get(),
            'reasons' => self::reasons($v, DB::table('vacancy_reports')->whereNull('resolved_at')->pluck('vacancy_id')->all()),
        ];
    }

    public function render()
    {
        $pairs = $this->tab === 'pairs' ? DB::table('dedup_reviews')->where('status', 'pending')->orderByDesc('score')->limit(50)->get()->map(function ($review) {
            $listing = VacancyListing::with(['source', 'vacancy.company', 'vacancy.region'])->find($review->vacancy_listing_id);
            $candidate = Vacancy::with(['company', 'region', 'listings.source'])->find($review->candidate_vacancy_id);

            return $listing && $candidate && $listing->vacancy && ! $listing->vacancy->is($candidate) && $candidate->status !== 'archived'
                ? ['review' => $review, 'listing' => $listing, 'candidate' => $candidate, 'detail' => json_decode((string) $review->score_detail, true)]
                : null;
        })->filter()->values() : collect();

        $sample = now()->format('o-\WW');
        $qa = $this->tab === 'qa' ? DB::table('vacancy_qa_checks')->where('sample', $sample)->orderBy('id')->get() : collect();

        return view('livewire.admin.vacancy-manager', [
            'rows' => match ($this->tab) { 'review' => $this->reviewQueue(), 'all' => $this->allQuery()->paginate(25), default => null },
            'pairs' => $pairs,
            'qa' => $qa,
            'qaVacancies' => Vacancy::with(['company', 'region', 'listings.source'])->whereIn('id', $qa->pluck('vacancy_id'))->get()->keyBy('id'),
            'sample' => $sample,
            'verdicts' => self::QA_VERDICTS,
            'sources' => JobSource::whereHas('listings')->orderBy('name')->get(['id', 'name']),
            'detail' => $this->detail(),
            'counts' => [
                'open' => Vacancy::visible()->count(),
                'reported' => DB::table('vacancy_reports')->whereNull('resolved_at')->distinct('vacancy_id')->count('vacancy_id'),
                'scam' => Vacancy::where('scam_flag', 'suspected')->whereIn('status', ['active', 'suspect'])->count(),
                'pairs' => DB::table('dedup_reviews')->where('status', 'pending')->count(),
                'archived' => Vacancy::where('status', 'archived')->count(),
            ],
        ]);
    }
}
