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
use App\Models\EmailBlastLog;
use App\Models\UserActivity;
use App\Models\AiPhoto;
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

    public function updatedPeriodFilter()
    {
        $this->loadAnalytics();
        $this->dispatch('chartUpdated');
        $this->dispatch('$refresh');
    }

    public function loadAnalytics()
    {
        $this->getUserGrowth();
        $this->getExportStats();
        $this->getActiveUsers();
        $this->getJobApplicationsOverTime();
        $this->getJobApplicationsByStatus();
        $this->getJobApplicationsByPlatform();
        $this->getUserRegistrationByDay();
        $this->getPremiumVsFree();
        $this->getGoalsAchievement();
        $this->getVerifiedVsUnverified();
        $this->getTopCompanies();
        $this->getTopPositions();
        $this->getAiUsageStats();
        $this->getCvTemplatePopularity();
        $this->getSupportTicketStats();
        $this->getCoverLetterStats();
        $this->getPeakActivityHours();
        $this->getRetentionCohort();
        $this->getGamificationLeaderboard();
    }

    private function getUserGrowth()
    {
        $days = $this->periodFilter === 'all' ? 30 : (int) $this->periodFilter;
        $dateRange = [];
        $totalData = [];
        $premiumData = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $currentDate = Carbon::now('Asia/Jakarta')->subDays($i);
            $dateRange[] = $currentDate->format('M d');
            
            $totalCount = User::where('role', '!=', 'admin')
                ->where('created_at', '<=', $currentDate->endOfDay())
                ->count();
            
            $premiumCount = User::where('role', '!=', 'admin')
                ->where('is_premium', true)
                ->where('created_at', '<=', $currentDate->endOfDay())
                ->count();
            
            $totalData[] = $totalCount;
            $premiumData[] = $premiumCount;
        }

        $this->userGrowth = [
            'labels' => $dateRange,
            'total' => $totalData,
            'premium' => $premiumData,
        ];
    }

    private function getExportStats()
    {
        $this->totalExports = 0;
        $this->exportStats = [
            'today' => 0,
            'week' => 0,
            'month' => 0,
        ];
    }

    private function getActiveUsers()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);
        
        $this->activeUsers = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', $startDate)
            ->where(function($query) {
                $query->whereHas('experiences')
                    ->orWhereHas('educations')
                    ->orWhereHas('skills');
            })
            ->count();
    }

    public function render()
    {
        $isAllTime = $this->periodFilter === 'all';
        $days = $isAllTime ? 30 : (int) $this->periodFilter;
        $startDate = $isAllTime ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays($days);
        
        $stats = [
            'totalUsers' => User::where('role', '!=', 'admin')->where('created_at', '>=', $startDate)->count(),
            'premiumUsers' => User::where('role', '!=', 'admin')->where('is_premium', true)->where('created_at', '>=', $startDate)->count(),
            'activeUsers' => $this->activeUsers,
            'totalExports' => $this->totalExports,
            'newUsersToday' => User::where('role', '!=', 'admin')->whereDate('created_at', Carbon::today('Asia/Jakarta'))->count(),
            'newUsersWeek' => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now('Asia/Jakarta')->subWeek())->count(),
            'newUsersMonth' => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now('Asia/Jakarta')->subMonth())->count(),
            'totalJobApplications' => JobApplication::where('created_at', '>=', $startDate)->count(),
            'totalGoals' => UserGoal::count(),
            'achievedGoals' => UserGoal::where('is_achieved', true)->count(),
            'totalPayments' => Payment::where('status', 'SUCCESS')->where('created_at', '>=', $startDate)->count(),
            'totalRevenue' => Payment::where('status', 'SUCCESS')->where('created_at', '>=', $startDate)->sum('amount'),
            'periodDays' => $days,
        ];
        
        $stats['goalsAchievementRate'] = $stats['totalGoals'] > 0 ? round(($stats['achievedGoals'] / $stats['totalGoals']) * 100, 1) : 0;

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
    
    private function getJobApplicationsOverTime()
    {
        $days = $this->periodFilter === 'all' ? 30 : (int) $this->periodFilter;
        $dateRange = [];
        $data = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $currentDate = Carbon::now('Asia/Jakarta')->subDays($i);
            $dateRange[] = $currentDate->format('M d');
            
            $count = JobApplication::whereDate('created_at', $currentDate->format('Y-m-d'))->count();
            $data[] = $count;
        }
        
        $this->jobApplicationsOverTime = [
            'labels' => $dateRange,
            'data' => $data,
        ];
    }
    
    private function getJobApplicationsByStatus()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);
        
        $allJobs = JobApplication::where('created_at', '>=', $startDate)->get();
        
        $applied = $allJobs->count();
        
        $interview = $allJobs->filter(function($job) {
            return !is_null($job->interview_date) || in_array($job->recruitment_stage, [
                'Assessment Test', 'Psychotest', 'HR - Interview', 
                'User - Interview', 'LGD', 'Presentation Round'
            ]);
        })->count();
        
        $accepted = $allJobs->filter(function($job) {
            return $job->application_status === 'Accepted' || $job->recruitment_stage === 'Offering';
        })->count();
        
        $rejected = $allJobs->filter(function($job) {
            return $job->application_status === 'Declined' || $job->application_status === 'Rejected' || $job->recruitment_stage === 'Not Processed';
        })->count();
        
        $pending = $allJobs->filter(function($job) {
            $isAccepted = $job->application_status === 'Accepted' || $job->recruitment_stage === 'Offering';
            $isRejected = $job->application_status === 'Declined' || $job->application_status === 'Rejected' || $job->recruitment_stage === 'Not Processed';
            return !$isAccepted && !$isRejected;
        })->count();
        
        $this->jobApplicationsByStatus = [
            'labels' => ['Applied', 'Interview', 'Accepted', 'Rejected', 'Pending'],
            'data' => [$applied, $interview, $accepted, $rejected, $pending],
        ];
    }
    
    private function getJobApplicationsByPlatform()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);
        
        $platformData = JobApplication::where('created_at', '>=', $startDate)
            ->selectRaw('platform, COUNT(*) as count')
            ->groupBy('platform')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        $this->jobApplicationsByPlatform = [
            'labels' => $platformData->pluck('platform')->toArray(),
            'data' => $platformData->pluck('count')->toArray(),
        ];
    }
    
    private function getUserRegistrationByDay()
    {
        $days = $this->periodFilter === 'all' ? 30 : (int) $this->periodFilter;
        $dateRange = [];
        $data = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $currentDate = Carbon::now('Asia/Jakarta')->subDays($i);
            $dateRange[] = $currentDate->format('M d');
            
            $count = User::where('role', '!=', 'admin')
                ->whereDate('created_at', $currentDate->format('Y-m-d'))
                ->count();
            $data[] = $count;
        }
        
        $this->userRegistrationByDay = [
            'labels' => $dateRange,
            'data' => $data,
        ];
    }
    
    private function getPremiumVsFree()
    {
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $premiumUsers = User::where('role', '!=', 'admin')->where('is_premium', true)->count();
        $freeUsers = $totalUsers - $premiumUsers;
        
        $this->premiumVsFree = [
            'labels' => ['Premium', 'Free'],
            'data' => [$premiumUsers, $freeUsers],
        ];
    }
    
    private function getGoalsAchievement()
    {
        $totalGoals = UserGoal::count();
        $achievedGoals = UserGoal::where('is_achieved', true)->count();
        $pendingGoals = $totalGoals - $achievedGoals;
        
        $this->goalsAchievement = [
            'labels' => ['Achieved', 'Pending'],
            'data' => [$achievedGoals, $pendingGoals],
        ];
    }
    
    private function getVerifiedVsUnverified()
    {
        $verifiedUsers = User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::where('role', '!=', 'admin')->whereNull('email_verified_at')->count();
        
        $this->verifiedVsUnverified = [
            'labels' => ['Verified', 'Unverified'],
            'data' => [$verifiedUsers, $unverifiedUsers],
        ];
    }

    private function getTopCompanies()
    {
        $query = JobApplication::query();
        if ($this->periodFilter !== 'all') {
            $startDate = Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);
            $query->where('created_at', '>=', $startDate);
        }
        
        $this->topCompanies = $query->select('company_name', DB::raw('count(*) as count'))
            ->groupBy('company_name')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function getTopPositions()
    {
        $query = JobApplication::query();
        if ($this->periodFilter !== 'all') {
            $startDate = Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);
            $query->where('created_at', '>=', $startDate);
        }
        
        $this->topPositions = $query->select('position', DB::raw('count(*) as count'))
            ->groupBy('position')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function getAiUsageStats()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);

        $aiAnalyzer = AiAnalyzerResult::where('created_at', '>=', $startDate)->count();
        $aiPhotos   = AiPhoto::where('created_at', '>=', $startDate)->count();
        $aiCvGen    = User::where('role', '!=', 'admin')->sum('cv_generated_this_month');

        // Weekly trend for AI Analyzer (last 7 days)
        $trend = [];
        $trendLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now('Asia/Jakarta')->subDays($i);
            $trendLabels[] = $d->format('D');
            $trend[] = AiAnalyzerResult::whereDate('created_at', $d->format('Y-m-d'))->count();
        }

        $this->aiUsageStats = [
            'ai_analyzer'  => $aiAnalyzer,
            'ai_photos'    => $aiPhotos,
            'ai_cv_gen'    => $aiCvGen,
            'trend'        => $trend,
            'trend_labels' => $trendLabels,
        ];
    }

    private function getCvTemplatePopularity()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);

        $templates = CvTemplate::where('created_at', '>=', $startDate)
            ->select('template_key', DB::raw('COUNT(*) as usage_count'), DB::raw('SUM(is_premium_template) as premium_count'))
            ->groupBy('template_key')
            ->orderByDesc('usage_count')
            ->limit(6)
            ->get()
            ->toArray();

        $totalCv = CvTemplate::where('created_at', '>=', $startDate)->count();

        $this->cvTemplatePopularity = [
            'templates' => $templates,
            'total'     => $totalCv,
        ];
    }

    private function getSupportTicketStats()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);

        $total    = SupportTicket::where('created_at', '>=', $startDate)->count();
        $pending  = SupportTicket::where('created_at', '>=', $startDate)->where('status', 'pending')->count();
        $replied  = SupportTicket::where('created_at', '>=', $startDate)->where('status', 'replied')->count();
        $resolved = SupportTicket::where('created_at', '>=', $startDate)->where('status', 'completed')->count();

        // Avg resolution time (from created_at to replied_at)
        $avgResolutionHours = SupportTicket::where('created_at', '>=', $startDate)
            ->whereNotNull('replied_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, replied_at)) as avg_hours')
            ->value('avg_hours');

        // By category
        $byCategory = SupportTicket::where('created_at', '>=', $startDate)
            ->select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        $this->supportTicketStats = [
            'total'              => $total,
            'pending'            => $pending,
            'replied'            => $replied,
            'resolved'           => $resolved,
            'avg_resolution_hrs' => round($avgResolutionHours ?? 0, 1),
            'by_category'        => $byCategory,
        ];
    }

    private function getCoverLetterStats()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);

        $total       = CoverLetter::where('created_at', '>=', $startDate)->count();
        $uniqueUsers = CoverLetter::where('created_at', '>=', $startDate)->distinct('user_id')->count('user_id');
        $totalUsers  = User::where('role', '!=', 'admin')->count();
        $adoptionRate = $totalUsers > 0 ? round(($uniqueUsers / $totalUsers) * 100, 1) : 0;

        // Daily trend (last 14 days)
        $trend = [];
        $labels = [];
        for ($i = 13; $i >= 0; $i--) {
            $d = Carbon::now('Asia/Jakarta')->subDays($i);
            $labels[] = $d->format('M d');
            $trend[]  = CoverLetter::whereDate('created_at', $d->format('Y-m-d'))->count();
        }

        $this->coverLetterStats = [
            'total'         => $total,
            'unique_users'  => $uniqueUsers,
            'adoption_rate' => $adoptionRate,
            'trend'         => $trend,
            'trend_labels'  => $labels,
        ];
    }


    private function getPeakActivityHours()
    {
        $startDate = $this->periodFilter === 'all' ? Carbon::create(2000, 1, 1) : Carbon::now('Asia/Jakarta')->subDays((int) $this->periodFilter);

        $hourly = UserActivity::where('created_at', '>=', $startDate)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
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
        $this->peakActivityHours = [
            'labels'   => $labels,
            'data'     => array_values($hours),
            'peak_hour'=> $labels[$maxHour] ?? '00:00',
            'peak_count'=> max($hours),
        ];
    }

    private function getRetentionCohort()
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

        $this->retentionCohort = $cohorts;
    }

    private function getGamificationLeaderboard()
    {
        $top = User::where('role', '!=', 'admin')
            ->orderByDesc('xp')
            ->take(5)
            ->get(['id', 'name', 'email', 'logo', 'xp', 'level', 'is_premium'])
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

        $this->gamificationLeaderboard = $top;
    }
}
