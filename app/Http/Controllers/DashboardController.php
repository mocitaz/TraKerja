<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalApplications = JobApplication::where('user_id', $userId)->count();
        $recentApplications = JobApplication::where('user_id', $userId)
            ->orderBy('application_date', 'desc')
            ->limit(5)
            ->get();

        $onProcessCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'On Process')
            ->count();

        $offeringAcceptedCount = JobApplication::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('recruitment_stage', 'Offering')
                    ->orWhere('application_status', 'Accepted');
            })
            ->count();

        $declinedCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'Declined')
            ->count();

        $totalInterviewsCount = JobApplication::where('user_id', $userId)
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->count();

        $careerLevelBreakdown = JobApplication::where('user_id', $userId)
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
