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
        $this->loadAnalytics();
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
        $startDate = Carbon::now()->subDays($days);

        // Get daily user registrations
        $growth = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $this->userGrowth = [
            'labels' => $growth->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'total' => $growth->pluck('count')->toArray(),
            'premium' => User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->where('is_premium', true)
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->pluck('count')
                ->toArray(),
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
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        // Users who have CV data (considered active)
        $this->activeUsers = User::whereHas('experiences')
            ->orWhereHas('educations')
            ->orWhereHas('skills')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();
    }

    public function render()
    {
        $stats = [
            'totalUsers' => User::count(),
            'premiumUsers' => User::where('is_premium', true)->count(),
            'activeUsers' => $this->activeUsers,
            'totalExports' => $this->totalExports,
            'newUsersToday' => User::whereDate('created_at', Carbon::today())->count(),
            'newUsersWeek' => User::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'newUsersMonth' => User::where('created_at', '>=', Carbon::now()->subMonth())->count(),
            'conversionRate' => User::count() > 0 
                ? round((User::where('is_premium', true)->count() / User::count()) * 100, 2) 
                : 0,
        ];

        $recentUsers = User::latest()
            ->take(10)
            ->get(['id', 'name', 'email', 'is_premium', 'is_admin', 'created_at']);

        return view('livewire.admin.analytics', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
        ]);
    }
}
