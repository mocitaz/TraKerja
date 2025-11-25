<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $timeFilter = $request->get('timeFilter', 'monthly'); // Default to monthly

        // Get date range based on time filter
        $dateRange = $this->getDateRange($timeFilter);

        // Basic KPI Metrics with time filtering
        $onProcessCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'On Process')
            ->where('is_archived', false)
            ->when($dateRange['start'], function ($query) use ($dateRange) {
                return $query->where('application_date', '>=', $dateRange['start']);
            })
            ->count();

        $offeringAcceptedCount = JobApplication::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('recruitment_stage', 'Offering')
                    ->orWhere('application_status', 'Accepted');
            })
            ->where('is_archived', false)
            ->when($dateRange['start'], function ($query) use ($dateRange) {
                return $query->where('application_date', '>=', $dateRange['start']);
            })
            ->count();

        // Declined count should include archived jobs
        $declinedCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'Declined')
            ->when($dateRange['start'], function ($query) use ($dateRange) {
                return $query->where('application_date', '>=', $dateRange['start']);
            })
            ->count();


        // Timeline Activity Data (Weekly/Monthly)
        $timelineData = $this->getTimelineData($userId, $timeFilter);
        
        // Conversion Funnel Data
        $funnelData = $this->getFunnelData($userId, $dateRange);
        
        // Status Distribution Data
        $statusDistribution = $this->getStatusDistribution($userId, $dateRange);
        
        // Platform Effectiveness Data
        $platformEffectiveness = $this->getPlatformEffectiveness($userId, $dateRange);
        
        // Position Analysis Data
        $positionAnalysis = $this->getPositionAnalysis($userId, $dateRange);
        
        // Career Level Analysis Data
        $careerLevelAnalysis = $this->getCareerLevelAnalysis($userId, $dateRange);
        
        // Remove sample data - use real data only
        
        // Top Companies Data
        $topCompanies = $this->getTopCompanies($userId, $dateRange);
        
        // Location Analysis Data
        $locationAnalysis = $this->getLocationAnalysis($userId, $dateRange);
        
        // Daily Streak Data
        $dailyStreak = $this->getDailyStreak($userId);
        
        // This Week's Progress Data
        $weeklyProgress = $this->getWeeklyProgress($userId);
        
        // The Cadence Effect Data
        $cadenceEffect = $this->getCadenceEffect($userId);

        return view('summary.index', [
            'timeFilter' => $timeFilter,
            'onProcessCount' => $onProcessCount,
            'offeringAcceptedCount' => $offeringAcceptedCount,
            'declinedCount' => $declinedCount,
            'timelineData' => $timelineData,
            'funnelData' => $funnelData,
            'statusDistribution' => $statusDistribution,
            'platformEffectiveness' => $platformEffectiveness,
            'positionAnalysis' => $positionAnalysis,
            'careerLevelAnalysis' => $careerLevelAnalysis,
            'topCompanies' => $topCompanies,
            'locationAnalysis' => $locationAnalysis,
            'dailyStreak' => $dailyStreak,
            'weeklyProgress' => $weeklyProgress,
            'cadenceEffect' => $cadenceEffect,
        ]);
    }

    private function getDateRange($timeFilter)
    {
        $now = Carbon::now('Asia/Jakarta');
        
        switch ($timeFilter) {
            case 'weekly':
                return [
                    'start' => $now->copy()->subWeek()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'monthly':
                return [
                    'start' => $now->copy()->subMonth()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'all':
            default:
                return [
                    'start' => null,
                    'end' => null
                ];
        }
    }

    private function getTimelineData($userId, $timeFilter)
    {
        $dateRange = $this->getDateRange($timeFilter);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        
        // Determine grouping and labels based on time filter
        if ($timeFilter === 'weekly') {
            $groupBy = 'DATE(application_date)';
            $labels = [];
            for ($i = 6; $i >= 0; $i--) {
                $labels[] = Carbon::now('Asia/Jakarta')->subDays($i)->format('M d');
            }
        } elseif ($timeFilter === 'monthly') {
            $groupBy = 'DATE(application_date)';
            $labels = [];
            for ($i = 29; $i >= 0; $i--) {
                $labels[] = Carbon::now('Asia/Jakarta')->subDays($i)->format('M d');
            }
        } else {
            $groupBy = 'DATE_FORMAT(application_date, "%Y-%m")';
            $labels = [];
            for ($i = 11; $i >= 0; $i--) {
                $labels[] = Carbon::now('Asia/Jakarta')->subMonths($i)->format('M Y');
            }
        }
        
        $applications = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->when($startDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('application_date', [$startDate, $endDate]);
            })
            ->selectRaw($groupBy . ' as period, COUNT(*) as applications')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $interviews = JobApplication::where('user_id', $userId)
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->where('is_archived', false)
            ->when($startDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('application_date', [$startDate, $endDate]);
            })
            ->selectRaw($groupBy . ' as period, COUNT(*) as interviews')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Create arrays for the timeline
        $applicationData = [];
        $interviewData = [];
        
        if ($timeFilter === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now('Asia/Jakarta')->subDays($i)->format('Y-m-d');
                $appCount = $applications->where('period', $date)->first();
                $intCount = $interviews->where('period', $date)->first();
                
                $applicationData[] = $appCount ? $appCount->applications : 0;
                $interviewData[] = $intCount ? $intCount->interviews : 0;
            }
        } elseif ($timeFilter === 'monthly') {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now('Asia/Jakarta')->subDays($i)->format('Y-m-d');
                $appCount = $applications->where('period', $date)->first();
                $intCount = $interviews->where('period', $date)->first();
                
                $applicationData[] = $appCount ? $appCount->applications : 0;
                $interviewData[] = $intCount ? $intCount->interviews : 0;
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $period = Carbon::now('Asia/Jakarta')->subMonths($i)->format('Y-m');
                $appCount = $applications->where('period', $period)->first();
                $intCount = $interviews->where('period', $period)->first();
                
                $applicationData[] = $appCount ? $appCount->applications : 0;
                $interviewData[] = $intCount ? $intCount->interviews : 0;
            }
        }

        return [
            'applications' => collect($applicationData),
            'interviews' => collect($interviewData),
            'labels' => $labels
        ];
    }

    private function getFunnelData($userId, $dateRange)
    {
        $baseQuery = JobApplication::where('user_id', $userId)
            ->where('is_archived', false);
        
        if ($dateRange['start']) {
            $baseQuery->where('application_date', '>=', $dateRange['start']);
        }
        
        $stages = [
            'Applied' => (clone $baseQuery)->count(),
            'Follow Up' => (clone $baseQuery)->where('recruitment_stage', 'Follow Up')->count(),
            'Assessment Test' => (clone $baseQuery)->where('recruitment_stage', 'Assessment Test')->count(),
            'Psychotest' => (clone $baseQuery)->where('recruitment_stage', 'Psychotest')->count(),
            'HR - Interview' => (clone $baseQuery)->where('recruitment_stage', 'HR - Interview')->count(),
            'User - Interview' => (clone $baseQuery)->where('recruitment_stage', 'User - Interview')->count(),
            'LGD' => (clone $baseQuery)->where('recruitment_stage', 'LGD')->count(),
            'Presentation Round' => (clone $baseQuery)->where('recruitment_stage', 'Presentation Round')->count(),
            'Offering' => (clone $baseQuery)->where('recruitment_stage', 'Offering')->count(),
        ];

        return $stages;
    }

    private function getStatusDistribution($userId, $dateRange)
    {
        $query = JobApplication::where('user_id', $userId)
            ->where('is_archived', false);
        
        if ($dateRange['start']) {
            $query->where('application_date', '>=', $dateRange['start']);
        }
        
        $statuses = $query->selectRaw('application_status, COUNT(*) as count')
            ->groupBy('application_status')
            ->get();

        return $statuses;
    }

    private function getPlatformEffectiveness($userId, $dateRange)
    {
        $baseQuery = JobApplication::where('user_id', $userId)
            ->where('is_archived', false);
        
        if ($dateRange['start']) {
            $baseQuery->where('application_date', '>=', $dateRange['start']);
        }
        
        $platforms = (clone $baseQuery)->selectRaw('platform, COUNT(*) as total_applications')
            ->groupBy('platform')
            ->get();

        $platformsWithInterviews = (clone $baseQuery)->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->selectRaw('platform, COUNT(*) as interviews')
            ->groupBy('platform')
            ->get();

        $effectiveness = [];
        foreach ($platforms as $platform) {
            $interviews = $platformsWithInterviews->where('platform', $platform->platform)->first();
            $interviewCount = $interviews ? $interviews->interviews : 0;
            $conversionRate = $platform->total_applications > 0 ? ($interviewCount / $platform->total_applications) * 100 : 0;
            
            $effectiveness[] = [
                'platform' => $platform->platform ?: 'Unknown',
                'total_applications' => $platform->total_applications,
                'interviews' => $interviewCount,
                'conversion_rate' => round($conversionRate, 2)
            ];
        }

        return collect($effectiveness)->sortByDesc('conversion_rate')->values();
    }

    private function getPositionAnalysis($userId, $dateRange)
    {
        $baseQuery = JobApplication::where('user_id', $userId)
            ->where('is_archived', false);
        
        if ($dateRange['start']) {
            $baseQuery->where('application_date', '>=', $dateRange['start']);
        }
        
        $positions = (clone $baseQuery)->selectRaw('position, COUNT(*) as total_applications')
            ->groupBy('position')
            ->get();

        $positionsWithInterviews = (clone $baseQuery)->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->selectRaw('position, COUNT(*) as interviews')
            ->groupBy('position')
            ->get();

        $analysis = [];
        foreach ($positions as $position) {
            $interviews = $positionsWithInterviews->where('position', $position->position)->first();
            $interviewCount = $interviews ? $interviews->interviews : 0;
            
            $analysis[] = [
                'position' => $position->position,
                'total_applications' => $position->total_applications,
                'interviews' => $interviewCount
            ];
        }

        return collect($analysis)->sortByDesc('total_applications')->take(10)->values();
    }

    private function getCareerLevelAnalysis($userId, $dateRange)
    {
        $baseQuery = JobApplication::where('user_id', $userId)
            ->where('is_archived', false);
        
        if ($dateRange['start']) {
            $baseQuery->where('application_date', '>=', $dateRange['start']);
        }
        
        $levels = (clone $baseQuery)->selectRaw('career_level, COUNT(*) as total_applications')
            ->groupBy('career_level')
            ->get();

        $levelsWithInterviews = (clone $baseQuery)->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->selectRaw('career_level, COUNT(*) as interviews')
            ->groupBy('career_level')
            ->get();

        $analysis = [];
        foreach ($levels as $level) {
            $interviews = $levelsWithInterviews->where('career_level', $level->career_level)->first();
            $interviewCount = $interviews ? $interviews->interviews : 0;
            $conversionRate = $level->total_applications > 0 ? ($interviewCount / $level->total_applications) * 100 : 0;
            
            $analysis[] = [
                'career_level' => $level->career_level ?: 'Unknown',
                'total_applications' => $level->total_applications,
                'interviews' => $interviewCount,
                'conversion_rate' => round($conversionRate, 2)
            ];
        }

        return collect($analysis)->sortByDesc('conversion_rate')->values();
    }

    private function getTopCompanies($userId, $dateRange)
    {
        $query = JobApplication::where('user_id', $userId)
            ->where('is_archived', false);
        
        if ($dateRange['start']) {
            $query->where('application_date', '>=', $dateRange['start']);
        }
        
        $companies = $query->selectRaw('company_name, COUNT(*) as applications, MIN(application_date) as first_application, MAX(application_date) as last_application')
            ->groupBy('company_name')
            ->orderByDesc('applications')
            ->take(5)
            ->get();

        return $companies;
    }

    private function getLocationAnalysis($userId, $dateRange)
    {
        $baseQuery = JobApplication::where('user_id', $userId)
            ->where('is_archived', false);
        
        if ($dateRange['start']) {
            $baseQuery->where('application_date', '>=', $dateRange['start']);
        }
        
        // Get location distribution by province
        $provinceData = (clone $baseQuery)->whereNotNull('location')
            ->where('location', '!=', '')
            ->get()
            ->map(function ($job) {
                // Parse location to get province
                $location = $job->location;
                if (strpos($location, ',') !== false) {
                    $parts = explode(',', $location);
                    return trim($parts[1]); // Province is usually the second part
                }
                return $location; // If no comma, assume it's province only
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(10); // Top 10 provinces

        // Get location distribution by city (if available)
        $cityData = (clone $baseQuery)->whereNotNull('location')
            ->where('location', '!=', '')
            ->get()
            ->map(function ($job) {
                // Parse location to get city
                $location = $job->location;
                if (strpos($location, ',') !== false) {
                    $parts = explode(',', $location);
                    return trim($parts[0]); // City is usually the first part
                }
                return null; // If no comma, we can't determine city
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(15); // Top 15 cities

        // Get location success rate (Accepted vs Total by location)
        $locationSuccess = (clone $baseQuery)->whereNotNull('location')
            ->where('location', '!=', '')
            ->get()
            ->groupBy(function ($job) {
                $location = $job->location;
                if (strpos($location, ',') !== false) {
                    $parts = explode(',', $location);
                    return trim($parts[1]); // Group by province
                }
                return $location;
            })
            ->map(function ($jobs) {
                $total = $jobs->count();
                $accepted = $jobs->where('application_status', 'Accepted')->count();
                $successRate = $total > 0 ? round(($accepted / $total) * 100, 1) : 0;
                
                return [
                    'total' => $total,
                    'accepted' => $accepted,
                    'success_rate' => $successRate
                ];
            })
            ->sortByDesc('success_rate')
            ->take(10);

        return [
            'provinces' => $provinceData,
            'cities' => $cityData,
            'success_rates' => $locationSuccess
        ];
    }

    private function getDailyStreak($userId)
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $streak = 0;
        $currentDate = $today->copy();
        
        // Check consecutive days starting from today
        while (true) {
            $hasApplication = JobApplication::where('user_id', $userId)
                ->where('is_archived', false)
                ->whereDate('application_date', $currentDate)
                ->exists();
                
            if ($hasApplication) {
                $streak++;
                $currentDate->subDay();
            } else {
                break;
            }
        }
        
        // Get streak history for the last 30 days
        $streakHistory = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now('Asia/Jakarta')->subDays($i);
            $hasApplication = JobApplication::where('user_id', $userId)
                ->where('is_archived', false)
                ->whereDate('application_date', $date)
                ->exists();
                
            $streakHistory[] = [
                'date' => $date->format('M d'),
                'has_application' => $hasApplication,
                'day_of_week' => $date->format('D')
            ];
        }
        
        // Get best streak ever
        $bestStreak = $this->calculateBestStreak($userId);
        
        return [
            'current_streak' => $streak,
            'best_streak' => $bestStreak,
            'streak_history' => $streakHistory,
            'is_active' => $streak > 0
        ];
    }

    private function getWeeklyProgress($userId)
    {
        $startOfWeek = Carbon::now('Asia/Jakarta')->startOfWeek();
        $endOfWeek = Carbon::now('Asia/Jakarta')->endOfWeek();
        
        // This week's applications
        $thisWeekApplications = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->whereBetween('application_date', [$startOfWeek, $endOfWeek])
            ->count();
            
        // Last week's applications for comparison
        $lastWeekStart = $startOfWeek->copy()->subWeek();
        $lastWeekEnd = $endOfWeek->copy()->subWeek();
        
        $lastWeekApplications = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->whereBetween('application_date', [$lastWeekStart, $lastWeekEnd])
            ->count();
            
        // Weekly goal (assuming 5 applications per week as a reasonable goal)
        $weeklyGoal = 5;
        $progressPercentage = $weeklyGoal > 0 ? min(($thisWeekApplications / $weeklyGoal) * 100, 100) : 0;
        
        // Daily breakdown for this week
        $dailyBreakdown = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $applications = JobApplication::where('user_id', $userId)
                ->where('is_archived', false)
                ->whereDate('application_date', $date)
                ->count();
                
            $dailyBreakdown[] = [
                'day' => $date->format('D'),
                'date' => $date->format('M d'),
                'applications' => $applications,
                'is_today' => $date->isToday()
            ];
        }
        
        // Calculate week-over-week change
        $weekOverWeekChange = 0;
        if ($lastWeekApplications > 0) {
            $weekOverWeekChange = (($thisWeekApplications - $lastWeekApplications) / $lastWeekApplications) * 100;
        } elseif ($thisWeekApplications > 0) {
            $weekOverWeekChange = 100; // 100% increase if last week was 0
        }
        
        return [
            'this_week_applications' => $thisWeekApplications,
            'last_week_applications' => $lastWeekApplications,
            'weekly_goal' => $weeklyGoal,
            'progress_percentage' => round($progressPercentage, 1),
            'week_over_week_change' => round($weekOverWeekChange, 1),
            'daily_breakdown' => $dailyBreakdown,
            'is_on_track' => $thisWeekApplications >= $weeklyGoal
        ];
    }

    private function getCadenceEffect($userId)
    {
        // Get applications from the last 90 days
        $startDate = Carbon::now('Asia/Jakarta')->subDays(90);
        $applications = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->where('application_date', '>=', $startDate)
            ->orderBy('application_date')
            ->get();
            
        if ($applications->isEmpty()) {
            return [
                'average_daily' => 0,
                'average_weekly' => 0,
                'consistency_score' => 0,
                'peak_days' => [],
                'application_patterns' => [],
                'recommendations' => []
            ];
        }
        
        // Calculate daily application counts
        $dailyCounts = $applications->groupBy(function ($app) {
            return Carbon::parse($app->application_date)->format('Y-m-d');
        })->map->count();
        
        // Calculate averages
        $totalDays = $dailyCounts->count();
        $totalApplications = $applications->count();
        $averageDaily = $totalDays > 0 ? round($totalApplications / $totalDays, 2) : 0;
        $averageWeekly = round($averageDaily * 7, 2);
        
        // Calculate consistency score (lower standard deviation = higher consistency)
        $values = $dailyCounts->values()->toArray();
        $mean = $averageDaily;
        $variance = array_sum(array_map(function($x) use ($mean) { return pow($x - $mean, 2); }, $values)) / count($values);
        $stdDev = sqrt($variance);
        $consistencyScore = $mean > 0 ? max(0, 100 - ($stdDev / $mean) * 100) : 0;
        
        // Find peak days (days with above-average applications)
        $peakDays = $dailyCounts->filter(function ($count) use ($averageDaily) {
            return $count > $averageDaily;
        })->sortDesc()->take(5);
        
        // Analyze application patterns by day of week
        $dayOfWeekPatterns = $applications->groupBy(function ($app) {
            return Carbon::parse($app->application_date)->dayOfWeek;
        })->map->count();
        
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $applicationPatterns = [];
        foreach ($dayOfWeekPatterns as $dayOfWeek => $count) {
            $applicationPatterns[] = [
                'day' => $dayNames[$dayOfWeek],
                'count' => $count,
                'percentage' => round(($count / $totalApplications) * 100, 1)
            ];
        }
        
        // Generate recommendations
        $recommendations = [];
        if ($averageDaily < 0.5) {
            $recommendations[] = "Consider increasing your application frequency to at least 1 per day";
        }
        if ($consistencyScore < 50) {
            $recommendations[] = "Try to maintain a more consistent application schedule";
        }
        if ($peakDays->isEmpty()) {
            $recommendations[] = "Set specific days for job applications to build a routine";
        }
        if (empty($recommendations)) {
            $recommendations[] = "Great job! You're maintaining a consistent application cadence";
        }
        
        return [
            'average_daily' => $averageDaily,
            'average_weekly' => $averageWeekly,
            'consistency_score' => round($consistencyScore, 1),
            'peak_days' => $peakDays->map(function ($count, $date) {
                return [
                    'date' => Carbon::parse($date)->format('M d'),
                    'applications' => $count
                ];
            })->values(),
            'application_patterns' => collect($applicationPatterns)->sortByDesc('count')->values(),
            'recommendations' => $recommendations
        ];
    }

    private function calculateBestStreak($userId)
    {
        $applications = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->orderBy('application_date')
            ->get();
            
        if ($applications->isEmpty()) {
            return 0;
        }
        
        $bestStreak = 0;
        $currentStreak = 0;
        $lastDate = null;
        
        foreach ($applications as $app) {
            $appDate = Carbon::parse($app->application_date)->startOfDay();
            
            if ($lastDate === null) {
                $currentStreak = 1;
            } elseif ($appDate->diffInDays($lastDate) === 1) {
                $currentStreak++;
            } else {
                $bestStreak = max($bestStreak, $currentStreak);
                $currentStreak = 1;
            }
            
            $lastDate = $appDate;
        }
        
        return max($bestStreak, $currentStreak);
    }
}