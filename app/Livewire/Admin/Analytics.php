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
        
        $statusData = JobApplication::where('created_at', '>=', $startDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        $labels = $statusData->pluck('status')->toArray();
        $data = $statusData->pluck('count')->toArray();
        
        $this->jobApplicationsByStatus = [
            'labels' => $labels,
            'data' => $data,
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
}
