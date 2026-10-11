<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\Payment;
use App\Models\UserGoal;
use App\Models\AiAnalyzerResult;
use App\Models\CvTemplate;
use App\Models\SupportTicket;
use App\Models\CoverLetter;
use App\Models\UserActivity;
use App\Models\AiPhoto;
use App\Support\DatabaseDateExpressions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Analytics extends Component
{
    public $userGrowth = [];
    public $exportStats = [];
    public $activeUsers = 0;
    public $totalExports = 0;
    public $periodFilter = 'all'; // Default
    
    // Chart data
    public $jobApplicationsOverTime = [];
    public $jobApplicationsByStatus = [];
    public $jobApplicationsByPlatform = [];
    public $userRegistrationByDay = [];
    public $premiumVsFree = [];
    public $goalsAchievement = [];
    public $verifiedVsUnverified = [];
    public $topCompanies = [];
    public $topPositions = [];

    // New analytics data
    public $aiUsageStats = [];
    public $cvTemplatePopularity = [];
    public $supportTicketStats = [];
    public $coverLetterStats = [];
    public $peakActivityHours = [];
    public $retentionCohort = [];
    public $gamificationLeaderboard = [];

    public function mount()
    {
        $this->loadAnalytics();
    }

    public function setPeriod(string $period): void
    {
        $this->periodFilter = in_array($period, ['all', '7', '30', '90'], true) ? $period : 'all';
        $this->updatedPeriodFilter();
    }

    public function updatedPeriodFilter()
    {
        if (!in_array((string) $this->periodFilter, ['all', '7', '30', '90'], true)) {
            $this->periodFilter = 'all';
        }
        $this->loadAnalytics();
        $this->dispatch('chartUpdated');
        $this->dispatch('$refresh');
    }

    public function loadAnalytics()
    {
        $cacheKey = 'admin_analytics_workspace_v2_' . $this->periodFilter;
        
        // Cache data selama 10 menit (600 detik) untuk membebani load page instan
        $data = Cache::remember($cacheKey, 600, function() {
            return [
                'userGrowth'               => $this->fetchUserGrowth(),
                'activeUsers'              => $this->fetchActiveUsers(),
                'jobApplicationsOverTime'  => $this->fetchJobApplicationsOverTime(),
                'jobApplicationsByStatus'  => $this->fetchJobApplicationsByStatus(),
                'jobApplicationsByPlatform'=> $this->fetchJobApplicationsByPlatform(),
                'userRegistrationByDay'    => $this->fetchUserRegistrationByDay(),
                'premiumVsFree'            => $this->fetchPremiumVsFree(),
                'goalsAchievement'         => $this->fetchGoalsAchievement(),
                'verifiedVsUnverified'     => $this->fetchVerifiedVsUnverified(),
                'topCompanies'             => $this->fetchTopCompanies(),
                'topPositions'             => $this->fetchTopPositions(),
                'aiUsageStats'             => $this->fetchAiUsageStats(),
                'cvTemplatePopularity'     => $this->fetchCvTemplatePopularity(),
                'supportTicketStats'       => $this->fetchSupportTicketStats(),
                'coverLetterStats'         => $this->fetchCoverLetterStats(),
                'peakActivityHours'        => $this->fetchPeakActivityHours(),
                'retentionCohort'          => $this->fetchRetentionCohort(),
                'gamificationLeaderboard'  => $this->fetchGamificationLeaderboard(),
                'statsSummary'             => $this->fetchStatsSummary(),
            ];
        });

        $this->userGrowth = $data['userGrowth'];
        $this->activeUsers = $data['activeUsers'];
        $this->jobApplicationsOverTime = $data['jobApplicationsOverTime'];
        $this->jobApplicationsByStatus = $data['jobApplicationsByStatus'];
        $this->jobApplicationsByPlatform = $data['jobApplicationsByPlatform'];
        $this->userRegistrationByDay = $data['userRegistrationByDay'];
        $this->premiumVsFree = $data['premiumVsFree'];
        $this->goalsAchievement = $data['goalsAchievement'];
        $this->verifiedVsUnverified = $data['verifiedVsUnverified'];
        $this->topCompanies = $data['topCompanies'];
        $this->topPositions = $data['topPositions'];
        $this->aiUsageStats = $data['aiUsageStats'];
        $this->cvTemplatePopularity = $data['cvTemplatePopularity'];
        $this->supportTicketStats = $data['supportTicketStats'];
        $this->coverLetterStats = $data['coverLetterStats'];
        $this->peakActivityHours = $data['peakActivityHours'];
        $this->retentionCohort = $data['retentionCohort'];
        $this->gamificationLeaderboard = $data['gamificationLeaderboard'];
        $this->exportStats = [
            'today' => 0,
            'week' => 0,
            'month' => 0,
        ];
        $this->totalExports = 0;
    }

    // --- OPTIMIZED AGGREGATION FETCHERS ---

    private function fetchUserGrowth()
    {
        $days = $this->periodFilter === 'all' ? 30 : (int) $this->periodFilter;
        $startDate = Carbon::now('Asia/Jakarta')->subDays($days - 1)->startOfDay();
        
        $dateRange = [];
        $totalData = [];
        $premiumData = [];
        
        // Ambil baseline count sebelum range tanggal filter dimulai (hanya 2 query count)
        $baseTotal = User::where('role', '!=', 'admin')
            ->where('created_at', '<', $startDate)
            ->count();
            
        $basePremium = User::where('role', '!=', 'admin')
            ->where('is_premium', true)
            ->where('created_at', '<', $startDate)
            ->count();

        // Ambil rekap harian dalam range tanggal filter (hanya 1 query GROUP BY)
        $registrations = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', $startDate)
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total, SUM(CASE WHEN is_premium = 1 THEN 1 ELSE 0 END) as premium")
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $runningTotal = $baseTotal;
        $runningPremium = $basePremium;

        for ($i = $days - 1; $i >= 0; $i--) {
            $d = Carbon::now('Asia/Jakarta')->subDays($i);
            $dateStr = $d->format('Y-m-d');
            $dateRange[] = $d->format('M d');
            
            if (isset($registrations[$dateStr])) {
                $runningTotal += $registrations[$dateStr]->total;
                $runningPremium += $registrations[$dateStr]->premium;
            }
            
            $totalData[] = $runningTotal;
            $premiumData[] = $runningPremium;
        }

        return [
            'labels' => $dateRange,
            'total' => $totalData,
            'premium' => $premiumData,
        ];
    }

    private function fetchActiveUsers()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();
        
        return User::where('role', '!=', 'admin')
            ->where('created_at', '>=', $startDate)
            ->where(function($query) {
                $query->whereHas('experiences')
                    ->orWhereHas('educations')
                    ->orWhereHas('skills');
            })
            ->count();
    }

    private function fetchJobApplicationsOverTime()
    {
        $days = $this->periodFilter === 'all' ? 30 : (int) $this->periodFilter;
        $startDate = Carbon::now('Asia/Jakarta')->subDays($days - 1)->startOfDay();
        $dateRange = [];
        $data = [];
        
        // Ambil statistik per hari menggunakan GROUP BY (hanya 1 query)
        $applications = JobApplication::where('created_at', '>=', $startDate)
            ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
            ->groupBy('date')
            ->get()
            ->keyBy('date');
            
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = Carbon::now('Asia/Jakarta')->subDays($i);
            $dateStr = $d->format('Y-m-d');
            $dateRange[] = $d->format('M d');
            
            $data[] = isset($applications[$dateStr]) ? $applications[$dateStr]->count : 0;
        }
        
        return [
            'labels' => $dateRange,
            'data' => $data,
        ];
    }

    private function fetchJobApplicationsByStatus()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();
        
        $allJobs = JobApplication::where('created_at', '>=', $startDate)->get();
        
        $applied = $allJobs->count();
        
        $interview = $allJobs->filter(function($job) {
            return !is_null($job->interview_date) || in_array($job->recruitment_stage, [
                'Assessment Test', 'Psychotest', 'HR - Interview', 
                'User - Interview', 'LGD', 'Presentation Round'
            ]);
        })->count();
        
        $accepted = $allJobs->filter(function($job) {
            return $job->application_status === 'Accepted';
        })->count();
        
        $rejected = $allJobs->filter(function($job) {
            return $job->application_status === 'Rejected';
        })->count();
        
        $pending = $allJobs->filter(function($job) {
            $isAccepted = $job->application_status === 'Accepted';
            $isRejected = $job->application_status === 'Rejected';
            return !$isAccepted && !$isRejected;
        })->count();
        
        return [
            'labels' => ['Applied', 'Interview', 'Accepted', 'Rejected', 'Pending'],
            'data' => [$applied, $interview, $accepted, $rejected, $pending],
        ];
    }

    private function fetchJobApplicationsByPlatform()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();
        
        $platformData = JobApplication::where('created_at', '>=', $startDate)
            ->selectRaw('platform, COUNT(*) as count')
            ->groupBy('platform')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        return [
            'labels' => $platformData->pluck('platform')->toArray(),
            'data' => $platformData->pluck('count')->toArray(),
        ];
    }

    private function fetchUserRegistrationByDay()
    {
        $days = $this->periodFilter === 'all' ? 30 : (int) $this->periodFilter;
        $startDate = Carbon::now('Asia/Jakarta')->subDays($days - 1)->startOfDay();
        $dateRange = [];
        $data = [];
        
        // Ambil group registrasi harian
        $registrations = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', $startDate)
            ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
            ->groupBy('date')
            ->get()
            ->keyBy('date');
            
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = Carbon::now('Asia/Jakarta')->subDays($i);
            $dateStr = $d->format('Y-m-d');
            $dateRange[] = $d->format('M d');
            
            $data[] = isset($registrations[$dateStr]) ? $registrations[$dateStr]->count : 0;
        }
        
        return [
            'labels' => $dateRange,
            'data' => $data,
        ];
    }

    private function fetchPremiumVsFree()
    {
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $premiumUsers = User::where('role', '!=', 'admin')->where('is_premium', true)->where('payment_status', 'paid')->where(fn ($q) => $q->whereNull('premium_until')->orWhere('premium_until', '>', now()))->count();
        $freeUsers = $totalUsers - $premiumUsers;
        
        return [
            'labels' => ['Premium', 'Free'],
            'data' => [$premiumUsers, $freeUsers],
        ];
    }

    private function fetchGoalsAchievement()
    {
        $totalGoals = UserGoal::count();
        $achievedGoals = UserGoal::where('is_achieved', true)->count();
        $pendingGoals = $totalGoals - $achievedGoals;
        
        return [
            'labels' => ['Achieved', 'Pending'],
            'data' => [$achievedGoals, $pendingGoals],
        ];
    }

    private function fetchVerifiedVsUnverified()
    {
        $verifiedUsers = User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::where('role', '!=', 'admin')->whereNull('email_verified_at')->count();
        
        return [
            'labels' => ['Verified', 'Unverified'],
            'data' => [$verifiedUsers, $unverifiedUsers],
        ];
    }

    private function fetchTopCompanies()
    {
        $query = JobApplication::query();
        if ($this->periodFilter !== 'all') {
            $startDate = Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();
            $query->where('created_at', '>=', $startDate);
        }
        
        return $query->select('company_name', DB::raw('count(*) as count'))
            ->groupBy('company_name')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function fetchTopPositions()
    {
        $query = JobApplication::query();
        if ($this->periodFilter !== 'all') {
            $startDate = Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();
            $query->where('created_at', '>=', $startDate);
        }
        
        return $query->select('position', DB::raw('count(*) as count'))
            ->groupBy('position')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function fetchAiUsageStats()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();

        $aiAnalyzer = AiAnalyzerResult::where('created_at', '>=', $startDate)->count();
        $aiPhotos   = AiPhoto::where('created_at', '>=', $startDate)->count();
        $aiCvGen    = User::where('role', '!=', 'admin')->sum('cv_generated_this_month');

        // Weekly trend (GROUP BY tanggal, hanya 1 query)
        $trend = [];
        $trendLabels = [];
        
        $analyzerTrend = AiAnalyzerResult::where('created_at', '>=', Carbon::now('Asia/Jakarta')->subDays(7)->startOfDay())
            ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now('Asia/Jakarta')->subDays($i);
            $dateStr = $d->format('Y-m-d');
            $trendLabels[] = $d->format('D');
            $trend[] = isset($analyzerTrend[$dateStr]) ? $analyzerTrend[$dateStr]->count : 0;
        }

        return [
            'ai_analyzer'  => $aiAnalyzer,
            'ai_photos'    => $aiPhotos,
            'ai_cv_gen'    => $aiCvGen,
            'trend'        => $trend,
            'trend_labels' => $trendLabels,
        ];
    }

    private function fetchCvTemplatePopularity()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();

        $templates = CvTemplate::where('created_at', '>=', $startDate)
            ->select('template_key', DB::raw('COUNT(*) as usage_count'), DB::raw('SUM(is_premium_template) as premium_count'))
            ->groupBy('template_key')
            ->orderByDesc('usage_count')
            ->limit(6)
            ->get()
            ->toArray();

        $totalCv = CvTemplate::where('created_at', '>=', $startDate)->count();

        return [
            'templates' => $templates,
            'total'     => $totalCv,
        ];
    }

    private function fetchSupportTicketStats()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();

        $total    = SupportTicket::where('created_at', '>=', $startDate)->count();
        $pending  = SupportTicket::where('created_at', '>=', $startDate)->where('status', 'pending')->count();
        $replied  = SupportTicket::where('created_at', '>=', $startDate)->where('status', 'replied')->count();
        $resolved = SupportTicket::where('created_at', '>=', $startDate)->where('status', 'completed')->count();

        // Avg resolution time (from created_at to replied_at)
        $avgResolutionHours = SupportTicket::where('created_at', '>=', $startDate)
            ->whereNotNull('replied_at')
            ->selectRaw('AVG('.DatabaseDateExpressions::wholeHoursBetween('created_at', 'replied_at').') as avg_hours')
            ->value('avg_hours');

        // By category
        $byCategory = SupportTicket::where('created_at', '>=', $startDate)
            ->select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        return [
            'total'              => $total,
            'pending'            => $pending,
            'replied'            => $replied,
            'resolved'           => $resolved,
            'avg_resolution_hrs' => round($avgResolutionHours ?? 0, 1),
            'by_category'        => $byCategory,
        ];
    }

    private function fetchCoverLetterStats()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();

        $total       = CoverLetter::where('created_at', '>=', $startDate)->count();
        $uniqueUsers = CoverLetter::where('created_at', '>=', $startDate)->distinct('user_id')->count('user_id');
        $totalUsers  = User::where('role', '!=', 'admin')->count();
        $adoptionRate = $totalUsers > 0 ? round(($uniqueUsers / $totalUsers) * 100, 1) : 0;

        // Daily trend (last 14 days, GROUP BY untuk performa instan)
        $trend = [];
        $labels = [];
        
        $clTrend = CoverLetter::where('created_at', '>=', Carbon::now('Asia/Jakarta')->subDays(14)->startOfDay())
            ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        for ($i = 13; $i >= 0; $i--) {
            $d = Carbon::now('Asia/Jakarta')->subDays($i);
            $dateStr = $d->format('Y-m-d');
            $labels[] = $d->format('M d');
            $trend[]  = isset($clTrend[$dateStr]) ? $clTrend[$dateStr]->count : 0;
        }

        return [
            'total'         => $total,
            'unique_users'  => $uniqueUsers,
            'adoption_rate' => $adoptionRate,
            'trend'         => $trend,
            'trend_labels'  => $labels,
        ];
    }

    private function fetchPeakActivityHours()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter - 1)->startOfDay();

        $hourly = UserActivity::where('created_at', '>=', $startDate)
            ->selectRaw(DatabaseDateExpressions::hour('created_at').' as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $hours  = array_fill(0, 24, 0);
        $labels = [];
        foreach ($hourly as $row) {
            $hours[(int) $row->hour] = (int) $row->count;
        }
        for ($h = 0; $h < 24; $h++) {
            $labels[] = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
        }

        $maxHour = array_search(max($hours), $hours);
        return [
            'labels'   => $labels,
            'data'     => array_values($hours),
            'peak_hour'=> $labels[$maxHour] ?? '00:00',
            'peak_count'=> max($hours),
        ];
    }

    private function fetchRetentionCohort()
    {
        // Simple monthly retention: users who registered in month X and were still active (activity) in month X+1
        $cohorts = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now('Asia/Jakarta')->subMonths($i)->startOfMonth();
            $monthEnd   = Carbon::now('Asia/Jakarta')->subMonths($i)->endOfMonth();

            $registered = User::where('role', '!=', 'admin')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->pluck('id');

            $total = $registered->count();

            if ($total === 0) {
                $cohorts[] = ['month' => $monthStart->format('M Y'), 'total' => 0, 'retained' => 0, 'rate' => 0];
                continue;
            }

            // Count how many of those users had any activity in the next month
            $nextMonthStart = $monthEnd->copy()->addDay()->startOfMonth();
            $nextMonthEnd   = $nextMonthStart->copy()->endOfMonth();

            $retained = UserActivity::whereIn('user_id', $registered)
                ->whereBetween('created_at', [$nextMonthStart, $nextMonthEnd])
                ->distinct('user_id')
                ->count('user_id');

            $cohorts[] = [
                'month'    => $monthStart->format('M Y'),
                'total'    => $total,
                'retained' => $retained,
                'rate'     => $total > 0 ? round(($retained / $total) * 100, 1) : 0,
            ];
        }

        return $cohorts;
    }

    private function fetchGamificationLeaderboard()
    {
        $top = User::where('role', '!=', 'admin')
            ->orderByDesc('xp')
            ->take(5)
            ->get(['id', 'name', 'email', 'logo', 'avatar_id', 'xp', 'level', 'is_premium'])
            ->map(function ($u, $index) {
                $levelTitles = User::LEVEL_TITLES;
                return [
                    'rank'       => $index + 1,
                    'name'       => $u->name,
                    'email'      => $u->email,
                    'avatar_url' => $u->avatar_url,
                    'logo'       => $u->logo,
                    'xp'         => $u->xp,
                    'level'      => $u->level,
                    'title'      => $levelTitles[$u->level] ?? 'Rookie Applicant',
                    'is_premium' => $u->is_premium,
                ];
            })
            ->toArray();

        return $top;
    }

    private function fetchStatsSummary()
    {
        $isAllTime = $this->periodFilter === 'all';
        $days = $isAllTime ? 30 : (int) $this->periodFilter;
        $startDate = $isAllTime ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays($days - 1)->startOfDay();

        $totalUsers = User::where('role', '!=', 'admin')->where('created_at', '>=', $startDate)->count();
        $premiumUsers = User::where('role', '!=', 'admin')->where('is_premium', true)->where('created_at', '>=', $startDate)->count();
        $activeUsers = $this->fetchActiveUsers();

        $totalGoals = UserGoal::count();
        $achievedGoals = UserGoal::where('is_achieved', true)->count();

        $totalRevenue = Payment::where('status', 'SUCCESS')->where('created_at', '>=', $startDate)->sum('amount');

        return [
            'totalUsers' => $totalUsers,
            'premiumUsers' => $premiumUsers,
            'freeUsers' => $totalUsers - $premiumUsers,
            'activeUsers' => $activeUsers,
            'totalExports' => UserActivity::whereIn('activity_type', ['csv_export', 'cv_export'])->where('status', 'success')->where('created_at', '>=', $startDate)->count(),
            'newUsersToday' => User::where('role', '!=', 'admin')->whereDate('created_at', Carbon::today('Asia/Jakarta'))->count(),
            'newUsersWeek' => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now('Asia/Jakarta')->subWeek())->count(),
            'newUsersMonth' => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now('Asia/Jakarta')->subMonth())->count(),
            'totalJobApplications' => JobApplication::where('created_at', '>=', $startDate)->count(),
            'totalGoals' => $totalGoals,
            'achievedGoals' => $achievedGoals,
            'totalPayments' => Payment::where('status', 'SUCCESS')->where('created_at', '>=', $startDate)->count(),
            'totalRevenue' => $totalRevenue,
            'periodDays' => $days,
            'goalsAchievementRate' => $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100, 1) : 0,
        ];
    }

    public function render()
    {
        $cacheKey = 'admin_analytics_workspace_v2_' . $this->periodFilter;
        $data = Cache::get($cacheKey);
        
        $stats = $data ? $data['statsSummary'] : $this->fetchStatsSummary();

        return view('livewire.admin.analytics', [
            'stats'                    => $stats,
            'userGrowth'               => $this->userGrowth,
            'jobApplicationsOverTime'  => $this->jobApplicationsOverTime,
            'jobApplicationsByStatus'  => $this->jobApplicationsByStatus,
            'jobApplicationsByPlatform'=> $this->jobApplicationsByPlatform,
            'userRegistrationByDay'    => $this->userRegistrationByDay,
            'premiumVsFree'            => $this->premiumVsFree,
            'goalsAchievement'         => $this->goalsAchievement,
            'verifiedVsUnverified'     => $this->verifiedVsUnverified,
            'topCompanies'             => $this->topCompanies,
            'topPositions'             => $this->topPositions,
            'aiUsageStats'             => $this->aiUsageStats,
            'cvTemplatePopularity'     => $this->cvTemplatePopularity,
            'supportTicketStats'       => $this->supportTicketStats,
            'coverLetterStats'         => $this->coverLetterStats,
            'peakActivityHours'        => $this->peakActivityHours,
            'retentionCohort'          => $this->retentionCohort,
            'gamificationLeaderboard'  => $this->gamificationLeaderboard,
        ]);
    }
}
