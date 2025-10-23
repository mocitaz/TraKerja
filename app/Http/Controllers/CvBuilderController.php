<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class CvBuilderController extends Controller
{
    /**
     * Show the CV Builder page
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Load all profile data
        $profile = $user->profile;
        $experiences = $user->experiences()->orderBy('display_order')->get();
        $educations = $user->educations()->orderBy('display_order')->get();
        $organizations = $user->organizations()->orderBy('display_order')->get();
        $skills = $user->skills()->orderBy('display_order')->get();
        $achievements = $user->achievements()->orderBy('display_order')->get();
        $projects = $user->projects()->orderBy('display_order')->get();
        
        return view('cv-builder.index', compact(
            'profile',
            'experiences',
            'educations',
            'organizations',
            'skills',
            'achievements',
            'projects'
        ));
    }
    
    /**
     * Show CV Generator page with template selection
     */
    public function generator()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get available templates count based on user tier
        $templatesCount = $user->getCvTemplatesCount();
        
        // Get user's saved CV configurations
        $savedConfigs = $user->cvTemplates()->latest()->get();
        
        // Check remaining exports for free tier
        $remainingExports = $user->getRemainingExports();
        
        return view('cv-builder.generator', compact(
            'templatesCount',
            'savedConfigs',
            'remainingExports'
        ));
    }
    
    /**
     * Export CV to PDF
     */
    public function export(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Check export limit for free tier (5 exports/month for free, unlimited for premium)
        if (!$user->is_premium) {
            $monthlyExports = $user->cv_exports_this_month ?? 0;
            if ($monthlyExports >= 5) {
                return back()->with('error', 'You have reached your monthly export limit (5/month). Upgrade to Premium for unlimited exports!');
            }
        }
        
        // Get template
        $template = $request->input('template', 'modern');
        
        // Load all user CV data
        $experiences = $user->experiences()->orderBy('display_order')->get();
        $educations = $user->educations()->orderBy('display_order')->get();
        $skills = $user->skills()->orderBy('display_order')->get();
        $organizations = $user->organizations()->orderBy('display_order')->get();
        $achievements = $user->achievements()->orderBy('display_order')->get();
        $projects = $user->projects()->orderBy('display_order')->get();
        
        // Generate PDF using DomPDF
        $pdf = Pdf::loadView("cv-templates.{$template}", [
            'user' => $user,
            'experiences' => $experiences,
            'educations' => $educations,
            'skills' => $skills,
            'organizations' => $organizations,
            'achievements' => $achievements,
            'projects' => $projects,
        ]);
        
        // Track export count (increment counter)
        if (!$user->is_premium) {
            $user->increment('cv_exports_this_month');
        }
        
        // Log export activity
        Log::info("CV exported", [
            'user_id' => $user->id,
            'template' => $template,
            'is_premium' => $user->is_premium,
        ]);
        
        // Generate filename
        $filename = str_replace(' ', '_', $user->name) . '_CV_' . now()->format('Y-m-d') . '.pdf';
        
        // Download PDF
        return $pdf->download($filename);
    }
}
