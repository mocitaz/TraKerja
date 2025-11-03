<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\Payment;
use App\Models\UserGoal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Analytics extends Component
{
    public $userGrowth = [];
    public $exportStats = [];
    public $activeUsers = 0;
    public $totalExports = 0;
    public $periodFilter = '30'; // days
    
    // Chart data
    public $jobApplicationsOverTime = [];
    public $jobApplicationsByStatus = [];
    public $jobApplicationsByPlatform = [];
    public $userRegistrationByDay = [];
    public $premiumVsFree = [];
    public $goalsAchievement = [];
    public $verifiedVsUnverified = [];

    public function mount()
    {
        $this->loadAnalytics();
    }

    public function updatedPeriodFilter()
    {
        // Clear cache when filter changes
        Cache::forget('analytics_' . $this->periodFilter);
        Cache::forget('analytics_stats_' . $this->periodFilter . '_' . Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        
        $this->loadAnalytics();
        $this->dispatch('chartUpdated');
        $this->dispatch('$refresh');
    }

    public function loadAnalytics()
    {
        $cacheKey = 'analytics_' . $this->periodFilter;
        $cachedData = Cache::get($cacheKey);
        
        if ($cachedData) {
            $this->userGrowth = $cachedData['userGrowth'];
            $this->exportStats = $cachedData['exportStats'];
            $this->activeUsers = $cachedData['activeUsers'];
            $this->jobApplicationsOverTime = $cachedData['jobApplicationsOverTime'];
            $this->jobApplicationsByStatus = $cachedData['jobApplicationsByStatus'];
            $this->jobApplicationsByPlatform = $cachedData['jobApplicationsByPlatform'];
            $this->userRegistrationByDay = $cachedData['userRegistrationByDay'];
            $this->premiumVsFree = $cachedData['premiumVsFree'];
            $this->goalsAchievement = $cachedData['goalsAchievement'];
            $this->verifiedVsUnverified = $cachedData['verifiedVsUnverified'];
            return;
        }
        
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
        
        // Cache the results for 5 minutes
        Cache::put($cacheKey, [
            'userGrowth' => $this->userGrowth,
            'exportStats' => $this->exportStats,
            'activeUsers' => $this->activeUsers,
            'jobApplicationsOverTime' => $this->jobApplicationsOverTime,
            'jobApplicationsByStatus' => $this->jobApplicationsByStatus,
            'jobApplicationsByPlatform' => $this->jobApplicationsByPlatform,
            'userRegistrationByDay' => $this->userRegistrationByDay,
            'premiumVsFree' => $this->premiumVsFree,
            'goalsAchievement' => $this->goalsAchievement,
            'verifiedVsUnverified' => $this->verifiedVsUnverified,
        ], 300); // 5 minutes
    }

    private function getUserGrowth()
    {
        $days = (int) $this->periodFilter;

        // Create a complete date range
        $dateRange = [];
        $totalData = [];
        $premiumData = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $currentDate = Carbon::now('Asia/Jakarta')->subDays($i);
            $dateRange[] = $currentDate->format('M d');
            
            // Count total users registered up to this date (excluding admins)
            $totalCount = User::where('role', '!=', 'admin')
                ->where('created_at', '<=', $currentDate->endOfDay())
                ->count();
            
            // Count premium users up to this date (excluding admins)
            $premiumCount = User::where('role', '!=', 'admin')
                ->where('is_premium', true)
                ->where('created_at', '<=', $currentDate->endOfDay())
                ->count();
            
            $totalData[] = $totalCount;
            $premiumData[] = $premiumCount;
        }

        // If no data, create some dummy data for testing
        if (empty($totalData) || array_sum($totalData) == 0) {
            $dateRange = [];
            $totalData = [];
            $premiumData = [];
            
            for ($i = $days - 1; $i >= 0; $i--) {
                $currentDate = Carbon::now('Asia/Jakarta')->subDays($i);
                $dateRange[] = $currentDate->format('M d');
                
                // Generate some dummy data
                $totalData[] = rand(1, 10) + $i;
                $premiumData[] = rand(0, 3) + ($i / 2);
            }
        }

        $this->userGrowth = [
            'labels' => $dateRange,
            'total' => $totalData,
            'premium' => $premiumData,
        ];
    }

    private function getExportStats()
    {
        // Placeholder - will be updated when PDF export tracking is implemented
        $this->totalExports = 0;
        $this->exportStats = [
            'today' => 0,
            'week' => 0,
            'month' => 0,
        ];
    }

    private function getActiveUsers()
    {
        $days = (int) $this->periodFilter;
        $startDate = Carbon::now('Asia/Jakarta')->subDays($days);
        
        // Users who have CV data (considered active) - excluding admins - within selected period
        // Optimized with eager loading
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
        $days = (int) $this->periodFilter;
        $startDate = Carbon::now('Asia/Jakarta')->subDays($days);
        
        $stats = [
            'totalUsers' => User::where('role', '!=', 'admin')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'premiumUsers' => User::where('role', '!=', 'admin')
                ->where('is_premium', true)
                ->where('created_at', '>=', $startDate)
                ->count(),
            'activeUsers' => $this->activeUsers,
            'totalExports' => $this->totalExports,
            'newUsersToday' => User::where('role', '!=', 'admin')->whereDate('created_at', Carbon::today('Asia/Jakarta'))->count(),
            'newUsersWeek' => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now('Asia/Jakarta')->subWeek())->count(),
            'newUsersMonth' => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now('Asia/Jakarta')->subMonth())->count(),
            'conversionRate' => User::where('role', '!=', 'admin')
                ->where('created_at', '>=', $startDate)
                ->count() > 0 
                ? round((User::where('role', '!=', 'admin')
                    ->where('is_premium', true)
                    ->where('created_at', '>=', $startDate)
                    ->count() / User::where('role', '!=', 'admin')
                    ->where('created_at', '>=', $startDate)
                    ->count()) * 100, 2) 
                : 0,
            'periodDays' => $days,
        ];

        // Additional stats for Quick Stats - optimized with caching
        $statsCacheKey = 'analytics_stats_' . $this->periodFilter . '_' . $startDate->format('Y-m-d');
        $cachedStats = Cache::remember($statsCacheKey, 300, function() use ($startDate) {
            return [
                'totalJobApplications' => JobApplication::where('created_at', '>=', $startDate)->count(),
                'totalGoals' => UserGoal::count(),
                'achievedGoals' => UserGoal::where('is_achieved', true)->count(),
                'totalPayments' => Payment::where('status', 'SUCCESS')->where('created_at', '>=', $startDate)->count(),
                'totalRevenue' => Payment::where('status', 'SUCCESS')->where('created_at', '>=', $startDate)->sum('amount'),
            ];
        });
        
        $totalJobApplications = $cachedStats['totalJobApplications'];
        $totalGoals = $cachedStats['totalGoals'];
        $achievedGoals = $cachedStats['achievedGoals'];
        $totalPayments = $cachedStats['totalPayments'];
        $totalRevenue = $cachedStats['totalRevenue'];
        
        $stats['totalJobApplications'] = $totalJobApplications;
        $stats['totalGoals'] = $totalGoals;
        $stats['achievedGoals'] = $achievedGoals;
        $stats['totalPayments'] = $totalPayments;
        $stats['totalRevenue'] = $totalRevenue;
        $stats['goalsAchievementRate'] = $totalGoals > 0 ? round(($achievedGoals / $totalGoals) * 100, 1) : 0;

        return view('livewire.admin.analytics', [
            'stats' => $stats,
            'userGrowth' => $this->userGrowth,
            'jobApplicationsOverTime' => $this->jobApplicationsOverTime,
            'jobApplicationsByStatus' => $this->jobApplicationsByStatus,
            'jobApplicationsByPlatform' => $this->jobApplicationsByPlatform,
            'userRegistrationByDay' => $this->userRegistrationByDay,
            'premiumVsFree' => $this->premiumVsFree,
            'goalsAchievement' => $this->goalsAchievement,
        ]);
    }
    
    private function getJobApplicationsOverTime()
    {
        $days = (int) $this->periodFilter;
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
        $days = (int) $this->periodFilter;
        $startDate = Carbon::now('Asia/Jakarta')->subDays($days);
        
        $statusData = JobApplication::where('created_at', '>=', $startDate)
            ->selectRaw('COALESCE(application_status, "Unknown") as status, COUNT(*) as count')
            ->groupBy('application_status')
            ->get();
        
        $labels = [];
        $data = [];
        $colors = [
            'Applied' => '#3b82f6',
            'Interview' => '#10b981',
            'Rejected' => '#ef4444',
            'Accepted' => '#22c55e',
            'Pending' => '#f59e0b',
            'Unknown' => '#6b7280',
        ];
        
        foreach ($statusData as $item) {
            $status = $item->status ?: 'Unknown';
            $labels[] = $status;
            $data[] = $item->count;
        }
        
        $this->jobApplicationsByStatus = [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_map(function($label) use ($colors) {
                return $colors[$label] ?? '#6b7280';
            }, $labels),
        ];
    }
    
    private function getJobApplicationsByPlatform()
    {
        $days = (int) $this->periodFilter;
        $startDate = Carbon::now('Asia/Jakarta')->subDays($days);
        
        $platformData = JobApplication::where('created_at', '>=', $startDate)
            ->selectRaw('COALESCE(platform, "Unknown") as platform, COUNT(*) as count')
            ->groupBy('platform')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        $labels = [];
        $data = [];
        
        foreach ($platformData as $item) {
            $labels[] = $item->platform ?: 'Unknown';
            $data[] = $item->count;
        }
        
        $this->jobApplicationsByPlatform = [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    
    private function getUserRegistrationByDay()
    {
        $days = (int) $this->periodFilter;
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
            'colors' => ['#8b5cf6', '#10b981'],
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
            'colors' => ['#22c55e', '#f59e0b'],
        ];
    }
    
    private function getVerifiedVsUnverified()
    {
        $verifiedUsers = User::where('role', '!=', 'admin')
            ->whereNotNull('email_verified_at')
            ->count();
        
        $unverifiedUsers = User::where('role', '!=', 'admin')
            ->whereNull('email_verified_at')
            ->count();
        
        $this->verifiedVsUnverified = [
            'labels' => ['Verified', 'Unverified'],
            'data' => [$verifiedUsers, $unverifiedUsers],
            'colors' => ['#10b981', '#ef4444'],
        ];
    }
}
