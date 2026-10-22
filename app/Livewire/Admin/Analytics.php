<?php

namespace App\Livewire\Admin;

use App\Models\{AiAnalyzerResult, AiPhoto, CoverLetter, CvTemplate, JobApplication, SupportTicket, User, UserActivity, UserGoal};
use App\Support\DatabaseDateExpressions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Analytics extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Url(as: 'period', except: '30')]
    public string $periodFilter = '30';
    #[Locked] public array $report = [];
    #[Locked] public array $userGrowth = [];
    #[Locked] public array $userRegistrationByDay = [];
    public bool $detailsOpen = false;
    #[Locked] public int $reportRevision = 0;
    #[Locked] public string $detailType = 'members';
    #[Locked] public string $detailField = '';
    #[Locked] public string $detailValue = '';
    public string $detailSearch = '';

    public function boot(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    public function mount(): void
    {
        $this->loadAnalytics();
    }

    public function setPeriod(string $period): void
    {
        $this->periodFilter = $period;
        $this->updatedPeriodFilter();
    }

    public function updatedPeriodFilter(): void
    {
        $this->detailsOpen = false;
        $this->loadAnalytics();
    }

    public function loadAnalytics(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->reportRevision++;
        if (!in_array($this->periodFilter, ['all', '7', '30', '90'], true)) $this->periodFilter = '30';
        $this->userRegistrationByDay = $this->dailySeries($this->periodQuery(User::class));
        $this->userGrowth = $this->fetchUserGrowth();
        $applications = $this->periodQuery(JobApplication::class);
        $members = $this->periodQuery(User::class);
        $exports = $this->exportQuery();
        $activities = $this->periodQuery(UserActivity::class);
        $totalApplications = (clone $applications)->count();
        $totalMembers = User::where('role', '!=', 'admin')->count();
        $paidMembers = User::where('role', '!=', 'admin')->where('is_premium', true)->where('payment_status', 'paid')
            ->where(fn ($q) => $q->whereNull('premium_until')->orWhere('premium_until', '>', now()))->count();
        $verified = User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count();
        $goals = $this->periodQuery(UserGoal::class);
        $goalTotal = (clone $goals)->count();
        $goalAchieved = (clone $goals)->where('is_achieved', true)->count();
        $this->report = [
            'updatedAt' => now('Asia/Jakarta')->toIso8601String(),
            'stats' => [
                'totalUsers' => (clone $members)->count(),
                'activeUsers' => (clone $activities)->whereNotNull('user_id')->distinct()->count('user_id'),
                'totalJobApplications' => $totalApplications,
                'totalExports' => (clone $exports)->count(),
            ],
            'timeline' => $this->dailySeries($applications),
            'statuses' => $this->grouped($applications, 'application_status'),
            'stages' => $this->grouped($applications, 'recruitment_stage'),
            'sources' => $this->grouped($applications, 'platform', 5),
            'companies' => $this->grouped($applications, 'company_name', 5),
            'positions' => $this->grouped($applications, 'position', 5),
            'analysis' => $this->periodQuery(AiAnalyzerResult::class)->count(),
            'photos' => $this->periodQuery(AiPhoto::class)->count(),
            'letters' => $this->periodQuery(CoverLetter::class)->count(),
            'templates' => $this->periodQuery(CvTemplate::class)->count(),
            'templateRanks' => $this->grouped($this->periodQuery(CvTemplate::class), 'template_key', 6),
            'goals' => ['total' => $goalTotal, 'achieved' => $goalAchieved],
            'membership' => ['total' => $totalMembers, 'premium' => $paidMembers, 'verified' => $verified],
            'support' => $this->fetchSupportTicketStats(),
            'hours' => $this->fetchPeakActivityHours(),
            'cohorts' => $this->fetchRetentionCohort(),
            'leaderboard' => User::where('role', '!=', 'admin')->where('xp', '>', 0)->orderByDesc('xp')->orderBy('id')->limit(5)
                ->get()->map(fn ($user) => ['id' => $user->id, 'name' => $user->name, 'avatar' => $user->avatar_url, 'xp' => $user->xp, 'level' => $user->level])->all(),
        ];
    }

    private function startDate(): ?Carbon
    {
        return $this->periodFilter === 'all' ? null : now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();
    }

    private function periodQuery(string $model): Builder
    {
        $query = $model::query();
        if ($model === User::class) $query->where('role', '!=', 'admin');
        else $query->where(fn ($q) => $q->whereHas('user', fn ($user) => $user->where('role', '!=', 'admin'))->orWhereNull('user_id'));
        if ($start = $this->startDate()) $query->where('created_at', '>=', $start);
        return $query->where('created_at', '<=', now('Asia/Jakarta'));
    }

    private function exportQuery(): Builder
    {
        return $this->periodQuery(UserActivity::class)->whereIn('activity_type', ['csv_export', 'cv_export'])->where('status', 'success');
    }

    private function grouped(Builder $query, string $field, ?int $limit = null): array
    {
        $query = (clone $query)->select($field)->selectRaw('COUNT(*) as total')->groupBy($field)->orderByDesc('total')->orderBy($field);
        if ($limit) $query->limit($limit);
        return $query->get()->map(fn ($row) => ['name' => $row->$field, 'total' => (int) $row->total])->all();
    }

    private function dailySeries(Builder $query): array
    {
        $days = $this->periodFilter === 'all' ? 30 : (int) $this->periodFilter;
        $start = now('Asia/Jakarta')->subDays($days - 1)->startOfDay();
        $counts = (clone $query)->where('created_at', '>=', $start)->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->pluck('total', 'day');
        $labels = []; $dates = []; $values = [];
        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            $labels[] = $day->locale(app()->getLocale())->translatedFormat('d M');
            $dates[] = $day->toDateString();
            $values[] = (int) ($counts[$day->toDateString()] ?? 0);
        }
        return ['labels' => $labels, 'dates' => $dates, 'data' => $values];
    }

    private function fetchUserGrowth(): array
    {
        $series = $this->userRegistrationByDay;
        $running = User::where('role', '!=', 'admin')->where('created_at', '<', $series['dates'][0])->count();
        $totals = [];
        foreach ($series['data'] as $count) $totals[] = $running += $count;
        return ['labels' => $series['labels'], 'total' => $totals];
    }

    private function fetchSupportTicketStats(): array
    {
        $query = $this->periodQuery(SupportTicket::class);
        $average = (clone $query)->whereNotNull('replied_at')->whereColumn('replied_at', '>=', 'created_at')
            ->selectRaw('AVG('.DatabaseDateExpressions::fractionalHoursBetween('created_at', 'replied_at').') as average')->value('average');
        return [
            'total' => (clone $query)->count(),
            'statuses' => $this->grouped($query, 'status'),
            'avg_resolution_hrs' => $average !== null ? round($average, 1) : null,
            'by_category' => $this->grouped($query, 'category'),
        ];
    }

    private function fetchPeakActivityHours(): array
    {
        $counts = $this->periodQuery(UserActivity::class)->selectRaw(DatabaseDateExpressions::hour('created_at').' as hour, COUNT(*) as total')->groupBy('hour')->pluck('total', 'hour');
        $values = []; $labels = [];
        for ($hour = 0; $hour < 24; $hour++) { $labels[] = sprintf('%02d:00', $hour); $values[] = (int) ($counts[$hour] ?? 0); }
        $peak = max($values);
        return ['labels' => $labels, 'data' => $values, 'peak_count' => $peak, 'peak_hour' => $peak ? $labels[array_search($peak, $values)] : null];
    }

    private function fetchRetentionCohort(): array
    {
        $cohorts = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = now('Asia/Jakarta')->startOfMonth()->subMonths($i);
            $nextStart = $start->copy()->addMonth();
            $nextEnd = $nextStart->copy()->endOfMonth();
            $members = User::where('role', '!=', 'admin')->whereBetween('created_at', [$start, $start->copy()->endOfMonth()])->pluck('id');
            $total = $members->count();
            $retained = UserActivity::whereIn('user_id', $members)->whereBetween('created_at', [$nextStart, min($nextEnd, now())])->distinct()->count('user_id');
            $cohorts[] = ['month' => $start->locale(app()->getLocale())->translatedFormat('M Y'), 'total' => $total, 'retained' => $retained,
                'rate' => $total ? round($retained / $total * 100, 1) : 0, 'mature' => $nextEnd->isPast()];
        }
        return $cohorts;
    }

    public function showDetails(string $type, string $field = '', string $value = ''): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        abort_unless(in_array($type, ['members', 'active', 'applications', 'analysis', 'photos', 'letters', 'templates', 'support', 'exports'], true), 422);
        $allowed = match ($type) {
            'applications' => ['application_status', 'recruitment_stage', 'platform', 'company_name', 'position', 'date'],
            'support' => ['status', 'category'], 'templates' => ['template_key'], default => ['date'],
        };
        abort_unless(($field === '' && $value === '') || (in_array($field, $allowed, true) && mb_strlen($value) <= 255), 422);
        if ($field === 'date') validator(['date' => $value], ['date' => 'required|date_format:Y-m-d'])->validate();
        $this->detailType = $type; $this->detailField = $field; $this->detailValue = $value;
        $this->detailSearch = ''; $this->resetPage('analyticsDetailsPage'); $this->detailsOpen = true;
    }

    public function closeDetails(): void
    {
        $this->detailsOpen = false;
    }

    public function updatedDetailSearch(): void
    {
        $this->detailSearch = mb_substr($this->detailSearch, 0, 100);
        $this->resetPage('analyticsDetailsPage');
    }

    private function detailRecords()
    {
        $model = match ($this->detailType) {
            'members', 'active' => User::class, 'applications' => JobApplication::class,
            'analysis' => AiAnalyzerResult::class, 'photos' => AiPhoto::class, 'letters' => CoverLetter::class,
            'templates' => CvTemplate::class, 'support' => SupportTicket::class, 'exports' => UserActivity::class,
        };
        if ($this->detailType === 'active') {
            $ids = $this->periodQuery(UserActivity::class)->whereNotNull('user_id')->select('user_id');
            $query = User::whereIn('id', $ids)->where('role', '!=', 'admin');
        } else $query = $this->detailType === 'exports' ? $this->exportQuery() : $this->periodQuery($model);
        if ($model !== User::class) $query->with('user');
        if ($this->detailField === 'date') $query->whereDate('created_at', $this->detailValue);
        elseif ($this->detailField !== '') {
            $this->detailValue === '' ? $query->where(fn ($q) => $q->whereNull($this->detailField)->orWhere($this->detailField, '')) : $query->where($this->detailField, $this->detailValue);
        }
        $search = mb_substr(trim($this->detailSearch), 0, 100);
        if ($search !== '') {
            $query->where(function ($q) use ($model, $search) {
                $value = '%'.$search.'%';
                if ($model === User::class) $q->where('name', 'like', $value)->orWhere('email', 'like', $value);
                else {
                    $q->whereHas('user', fn ($user) => $user->where('name', 'like', $value)->orWhere('email', 'like', $value));
                    $fields = match ($this->detailType) { 'applications' => ['company_name', 'position'], 'support' => ['subject', 'guest_name', 'guest_email'], 'templates' => ['name', 'template_key'], 'exports' => ['activity_type'], 'analysis' => ['resume_file_name'], 'photos' => ['original_photo_name'], 'letters' => ['job_title', 'company_name'], default => [] };
                    foreach ($fields as $field) $q->orWhere($field, 'like', $value);
                }
            });
        }
        return $query->latest()->orderByDesc('id')->paginate(10, ['*'], 'analyticsDetailsPage');
    }

    public function render()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        return view('livewire.admin.analytics', ['stats' => $this->report['stats'] ?? [], 'records' => $this->detailsOpen ? $this->detailRecords() : null]);
    }
}
