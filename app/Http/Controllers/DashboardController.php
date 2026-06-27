<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\UserGoal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. Core counters (non-archived only except for declined/rejected)
        $totalApplications = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->count();

        $onProcessCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'On Process')
            ->where('is_archived', false)
            ->count();

        $offeringAcceptedCount = JobApplication::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('recruitment_stage', 'Offering')
                    ->orWhere('application_status', 'Accepted');
            })
            ->where('is_archived', false)
            ->count();

        // Declined counts (include archived jobs since declined jobs usually get archived)
        $declinedCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'Declined')
            ->count();

        $totalInterviewsCount = JobApplication::where('user_id', $userId)
            ->whereIn('recruitment_stage', [
                'HR - Interview', 'User - Interview', 'Psychotest', 
                'Assessment Test', 'LGD', 'Presentation Round'
            ])
            ->where('is_archived', false)
            ->count();

        // 2. Recent applications list (latest 5)
        $recentApplications = JobApplication::where('user_id', $userId)
            ->orderBy('application_date', 'desc')
            ->limit(5)
            ->get();

        // 3. Upcoming interviews list (future interview dates)
        $upcomingInterviews = JobApplication::where('user_id', $userId)
            ->whereNotNull('interview_date')
            ->where('interview_date', '>=', Carbon::now())
            ->orderBy('interview_date', 'asc')
            ->limit(5)
            ->get();

        $careerLevelBreakdown = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->selectRaw('career_level, COUNT(*) as count')
            ->groupBy('career_level')
            ->get();

        return view('dashboard', [
            'totalApplications' => $totalApplications,
            'recentApplications' => $recentApplications,
            'upcomingInterviews' => $upcomingInterviews,
            'onProcessCount' => $onProcessCount,
            'offeringAcceptedCount' => $offeringAcceptedCount,
            'declinedCount' => $declinedCount,
            'totalInterviewsCount' => $totalInterviewsCount,
            'careerLevelBreakdown' => $careerLevelBreakdown,
        ]);
    }
}
