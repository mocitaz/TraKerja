<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalApplications = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->count();
        $recentApplications = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->orderBy('application_date', 'desc')
            ->limit(5)
            ->get();

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

        // Declined count should include archived jobs
        $declinedCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'Declined')
            ->count();

        $totalInterviewsCount = JobApplication::where('user_id', $userId)
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->where('is_archived', false)
            ->count();

        $careerLevelBreakdown = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->selectRaw('career_level, COUNT(*) as count')
            ->groupBy('career_level')
            ->get();

        return view('dashboard', [
            'totalApplications' => $totalApplications,
            'recentApplications' => $recentApplications,
            'onProcessCount' => $onProcessCount,
            'offeringAcceptedCount' => $offeringAcceptedCount,
            'declinedCount' => $declinedCount,
            'totalInterviewsCount' => $totalInterviewsCount,
            'careerLevelBreakdown' => $careerLevelBreakdown,
        ]);
    }
}
