<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;

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
     * Preview CV before export
     */
    public function preview(Request $request)
    {
        $user = Auth::user();
        $template = $request->input('template', 'minimal');
        
        // Validate template exists
        $allowedTemplates = ['minimal', 'professional', 'creative', 'elegant'];
        if (!in_array($template, $allowedTemplates)) {
            return redirect()->back()->with('error', 'Invalid template selected.');
        }
        
        // Check if template file exists
        if (!view()->exists("cv-templates.{$template}")) {
            return redirect()->back()->with('error', 'Template file not found.');
        }
        
        // Check template access based on monetization phase
        $premiumTemplates = ['professional', 'creative', 'elegant'];
        
        // Phase 2 & 3: Premium templates are locked for non-premium users
        if (Setting::getMonetizationPhase() >= 2) {
            if (in_array($template, $premiumTemplates) && !$user->is_premium) {
                return redirect()->back()->with('error', 'This template is only available for premium users. Upgrade to access premium templates!');
            }
        }
        // Phase 1: All templates are free and accessible to everyone
        
        // Load all user CV data
        $experiences = $user->experiences()->orderBy('display_order')->get();
        $educations = $user->educations()->orderBy('display_order')->get();
        $skills = $user->skills()->orderBy('display_order')->get();
        $organizations = $user->organizations()->orderBy('display_order')->get();
        $achievements = $user->achievements()->orderBy('display_order')->get();
        $projects = $user->projects()->orderBy('display_order')->get();
        
        return view('cv-builder.preview', compact(
            'user',
            'template',
            'experiences',
            'educations',
            'skills',
            'organizations',
            'achievements',
            'projects'
        ));
    }
    
    /**
     * Export CV to PDF
     */
    public function export(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // All users (free and premium) have unlimited exports
        
        // Get template and validate
        $template = $request->input('template', 'minimal');
        $validTemplates = ['minimal', 'professional', 'creative', 'elegant'];
        
        if (!in_array($template, $validTemplates)) {
            return redirect()->back()->with('error', 'Invalid template selected.');
        }
        
        // Check if template file exists
        $templatePath = resource_path("views/cv-templates/{$template}.blade.php");
        if (!file_exists($templatePath)) {
            Log::error("Template file not found for export: {$template}");
            return redirect()->back()->with('error', 'Template file not found.');
        }
        
        // Check template access based on monetization phase
        $premiumTemplates = ['professional', 'creative', 'elegant'];
        
        // Phase 2 & 3: Premium templates are locked for non-premium users
        if (Setting::getMonetizationPhase() >= 2) {
            if (in_array($template, $premiumTemplates) && !$user->is_premium) {
                return redirect()->back()->with('error', 'This template is only available for premium users. Upgrade to access premium templates!');
            }
        }
        // Phase 1: All templates are free and accessible to everyone
        
        // Load all user CV data
        $experiences = $user->experiences()->orderBy('display_order')->get();
        $educations = $user->educations()->orderBy('display_order')->get();
        $skills = $user->skills()->orderBy('display_order')->get();
        $organizations = $user->organizations()->orderBy('display_order')->get();
        $achievements = $user->achievements()->orderBy('display_order')->get();
        $projects = $user->projects()->orderBy('display_order')->get();
        
        try {
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
        } catch (\Exception $e) {
            Log::error("Error generating PDF: " . $e->getMessage(), [
                'user_id' => $user->id,
                'template' => $template,
            ]);
            return redirect()->back()->with('error', 'Error generating PDF. Please try again or contact support.');
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
