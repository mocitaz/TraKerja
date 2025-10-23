<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use App\Models\JobApplication;
use Carbon\Carbon;

class CareerSummaryPro extends Component
{
    public $timeFilter = 'monthly'; // weekly, monthly, all

    protected $listeners = ['timeFilterUpdated' => 'updateTimeFilter'];

    public function mount()
    {
        // Initialize with default time filter
    }

    public function updateTimeFilter($filter)
    {
        $this->timeFilter = $filter;
    }

    public function getTimelineDataProperty()
    {
        $userId = auth()->id();
        $startDate = $this->getStartDate();

        $applications = JobApplication::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->selectRaw($this->getDateGrouping() . ' as period, COUNT(*) as applications')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $interviews = JobApplication::where('user_id', $userId)
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->selectRaw($this->getDateGrouping() . ' as period, COUNT(*) as interviews')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'applications' => $applications,
            'interviews' => $interviews,
            'labels' => $this->getTimelineLabels()
        ];
    }

    public function getFunnelDataProperty()
    {
        $userId = auth()->id();
        $startDate = $this->getStartDate();

        $stages = [
            'Applied' => JobApplication::where('user_id', $userId)
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->where('application_date', '>=', $startDate);
                })
                ->count(),
            'Follow Up' => JobApplication::where('user_id', $userId)
                ->where('recruitment_stage', 'Follow Up')
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->where('application_date', '>=', $startDate);
                })
                ->count(),
            'Assessment Test' => JobApplication::where('user_id', $userId)
                ->where('recruitment_stage', 'Assessment Test')
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->where('application_date', '>=', $startDate);
                })
                ->count(),
            'Psychotest' => JobApplication::where('user_id', $userId)
                ->where('recruitment_stage', 'Psychotest')
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->where('application_date', '>=', $startDate);
                })
                ->count(),
            'HR - Interview' => JobApplication::where('user_id', $userId)
                ->where('recruitment_stage', 'HR - Interview')
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->where('application_date', '>=', $startDate);
                })
                ->count(),
            'User - Interview' => JobApplication::where('user_id', $userId)
                ->where('recruitment_stage', 'User - Interview')
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->where('application_date', '>=', $startDate);
                })
                ->count(),
            'LGD' => JobApplication::where('user_id', $userId)
                ->where('recruitment_stage', 'LGD')
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->where('application_date', '>=', $startDate);
                })
                ->count(),
            'Presentation Round' => JobApplication::where('user_id', $userId)
                ->where('recruitment_stage', 'Presentation Round')
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->where('application_date', '>=', $startDate);
                })
                ->count(),
            'Offering' => JobApplication::where('user_id', $userId)
                ->where('recruitment_stage', 'Offering')
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->where('application_date', '>=', $startDate);
                })
                ->count(),
        ];

        return $stages;
    }

    public function getStatusDistributionProperty()
    {
        $userId = auth()->id();
        $startDate = $this->getStartDate();

        $statuses = JobApplication::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->selectRaw('application_status, COUNT(*) as count')
            ->groupBy('application_status')
            ->get();

        return $statuses;
    }

    public function getPlatformEffectivenessProperty()
    {
        $userId = auth()->id();
        $startDate = $this->getStartDate();

        $platforms = JobApplication::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->selectRaw('platform, COUNT(*) as total_applications')
            ->groupBy('platform')
            ->get();

        $platformsWithInterviews = JobApplication::where('user_id', $userId)
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->selectRaw('platform, COUNT(*) as interviews')
            ->groupBy('platform')
            ->get();

        $effectiveness = [];
        foreach ($platforms as $platform) {
            $interviews = $platformsWithInterviews->where('platform', $platform->platform)->first();
            $interviewCount = $interviews ? $interviews->interviews : 0;
            $conversionRate = $platform->total_applications > 0 ? ($interviewCount / $platform->total_applications) * 100 : 0;
            
            $effectiveness[] = [
                'platform' => $platform->platform,
                'total_applications' => $platform->total_applications,
                'interviews' => $interviewCount,
                'conversion_rate' => round($conversionRate, 2)
            ];
        }

        return collect($effectiveness)->sortByDesc('conversion_rate')->values();
    }

    public function getPositionAnalysisProperty()
    {
        $userId = auth()->id();
        $startDate = $this->getStartDate();

        $positions = JobApplication::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->selectRaw('position, COUNT(*) as total_applications')
            ->groupBy('position')
            ->get();

        $positionsWithInterviews = JobApplication::where('user_id', $userId)
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
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

    public function getCareerLevelAnalysisProperty()
    {
        $userId = auth()->id();
        $startDate = $this->getStartDate();

        $levels = JobApplication::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->selectRaw('career_level, COUNT(*) as total_applications')
            ->groupBy('career_level')
            ->get();

        $levelsWithInterviews = JobApplication::where('user_id', $userId)
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->selectRaw('career_level, COUNT(*) as interviews')
            ->groupBy('career_level')
            ->get();

        $analysis = [];
        foreach ($levels as $level) {
            $interviews = $levelsWithInterviews->where('career_level', $level->career_level)->first();
            $interviewCount = $interviews ? $interviews->interviews : 0;
            $conversionRate = $level->total_applications > 0 ? ($interviewCount / $level->total_applications) * 100 : 0;
            
            $analysis[] = [
                'career_level' => $level->career_level,
                'total_applications' => $level->total_applications,
                'interviews' => $interviewCount,
                'conversion_rate' => round($conversionRate, 2)
            ];
        }

        return collect($analysis)->sortByDesc('conversion_rate')->values();
    }

    public function getTopCompaniesProperty()
    {
        $userId = auth()->id();
        $startDate = $this->getStartDate();

        $companies = JobApplication::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->selectRaw('company_name, COUNT(*) as applications, MIN(application_date) as first_application, MAX(application_date) as last_application')
            ->groupBy('company_name')
            ->orderByDesc('applications')
            ->take(5)
            ->get();

        return $companies;
    }

    public function getLocationAnalysisProperty()
    {
        $userId = auth()->id();
        $startDate = $this->getStartDate();

        // Get location distribution by province
        $provinceData = JobApplication::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->whereNotNull('location')
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
        $cityData = JobApplication::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->whereNotNull('location')
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
        $locationSuccess = JobApplication::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('application_date', '>=', $startDate);
            })
            ->whereNotNull('location')
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

    private function getStartDate()
    {
        switch ($this->timeFilter) {
            case 'weekly':
                return Carbon::now()->subWeek();
            case 'monthly':
                return Carbon::now()->subMonth();
            case 'all':
            default:
                return null;
        }
    }

    private function getDateGrouping()
    {
        switch ($this->timeFilter) {
            case 'weekly':
                return 'DATE(application_date)';
            case 'monthly':
                return 'DATE_FORMAT(application_date, "%Y-%m")';
            case 'all':
            default:
                return 'DATE_FORMAT(application_date, "%Y-%m")';
        }
    }

    private function getTimelineLabels()
    {
        $startDate = $this->getStartDate();
        $labels = [];

        if ($this->timeFilter === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $labels[] = Carbon::now()->subDays($i)->format('M d');
            }
        } elseif ($this->timeFilter === 'monthly') {
            for ($i = 11; $i >= 0; $i--) {
                $labels[] = Carbon::now()->subMonths($i)->format('M Y');
            }
        } else {
            // All time - show last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $labels[] = Carbon::now()->subMonths($i)->format('M Y');
            }
        }

        return $labels;
    }

    public function render()
    {
        return view('livewire.career-summary-pro');
    }
}