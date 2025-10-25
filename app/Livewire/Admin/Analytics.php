<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Analytics extends Component
{
    public $userGrowth = [];
    public $exportStats = [];
    public $activeUsers = 0;
    public $totalExports = 0;
    public $periodFilter = '30'; // days

    public function mount()
    {
        $this->loadAnalytics();
    }

    public function updatedPeriodFilter()
    {
        \Log::info('Period filter updated to: ' . $this->periodFilter);
        $this->loadAnalytics();
        $this->dispatch('chartUpdated');
        $this->dispatch('$refresh');
    }

    public function loadAnalytics()
    {
        $this->getUserGrowth();
        $this->getExportStats();
        $this->getActiveUsers();
    }

    private function getUserGrowth()
    {
        $days = (int) $this->periodFilter;

        // Create a complete date range
        $dateRange = [];
        $totalData = [];
        $premiumData = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $currentDate = Carbon::now()->subDays($i);
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
                $currentDate = Carbon::now()->subDays($i);
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
        $startDate = Carbon::now()->subDays($days);
        
        // Users who have CV data (considered active) - excluding admins - within selected period
        $this->activeUsers = User::where('role', '!=', 'admin')
            ->where(function($query) {
                $query->whereHas('experiences')
                    ->orWhereHas('educations')
                    ->orWhereHas('skills');
            })
            ->where('created_at', '>=', $startDate)
            ->count();
    }

    public function render()
    {
        $days = (int) $this->periodFilter;
        $startDate = Carbon::now()->subDays($days);
        
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
            'newUsersToday' => User::where('role', '!=', 'admin')->whereDate('created_at', Carbon::today())->count(),
            'newUsersWeek' => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'newUsersMonth' => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now()->subMonth())->count(),
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

        $recentUsers = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', $startDate)
            ->latest()
            ->take(10)
            ->get(['id', 'name', 'email', 'is_premium', 'is_admin', 'created_at']);

        return view('livewire.admin.analytics', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'userGrowth' => $this->userGrowth,
        ]);
    }
}
