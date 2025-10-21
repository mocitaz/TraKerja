<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        // On Process (Aktif): Hitung jumlah lamaran dengan application_status = 'On Process'
        $onProcessCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'On Process')
            ->count();

        // Offering / Accepted: Hitung jumlah lamaran yang memiliki recruitment_stage = 'Offering' ATAU application_status = 'Accepted'
        $offeringAcceptedCount = JobApplication::where('user_id', $userId)
            ->where(function($query) {
                $query->where('recruitment_stage', 'Offering')
                      ->orWhere('application_status', 'Accepted');
            })
            ->count();

        // Declined: Hitung jumlah lamaran dengan application_status = 'Declined'
        $declinedCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'Declined')
            ->count();


        // Breakdown: Tampilkan ringkasan singkat total hitungan per career_level
        $careerLevelBreakdown = JobApplication::where('user_id', $userId)
            ->selectRaw('career_level, COUNT(*) as count')
            ->groupBy('career_level')
            ->orderBy('count', 'desc')
            ->get();

        // Total Applications
        $totalApplications = JobApplication::where('user_id', $userId)->count();

        return view('tracker.index', compact(
            'onProcessCount',
            'offeringAcceptedCount', 
            'declinedCount',
            'careerLevelBreakdown',
            'totalApplications'
        ));
    }
}