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
        $premiumTemplates = ['creative', 'elegant'];
        
        // Phase 2 & 3: Premium templates are locked for non-premium users
        if (Setting::isMonetizationEnabled()) {
            if (in_array($template, $premiumTemplates) && !$user->isPremium()) {
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
        
        // Check CV generation limit for free tier
        if (!$user->incrementCvGenerationCount()) {
            $remaining = $user->getRemainingCvGenerations();
            return redirect()->back()->with('error', 
                "You've reached your CV generation limit (3 per month). Upgrade to Premium for unlimited CV generations!"
            );
        }
        
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
        $premiumTemplates = ['creative', 'elegant'];
        
        // Phase 2 & 3: Premium templates are locked for non-premium users
        if (Setting::isMonetizationEnabled()) {
            if (in_array($template, $premiumTemplates) && !$user->isPremium()) {
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
        
        $margin = $request->input('margin');
        $fontSize = $request->input('font_size');

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
                'margin' => $margin,
                'fontSize' => $fontSize,
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
        
        // Generate clean and professional uppercase filename: CV_NAMA_LENGKAP.pdf
        $cleanName = strtoupper(preg_replace('/[^a-zA-Z0-9]+/', '_', $user->name));
        $cleanName = trim($cleanName, '_');
        $filename = 'CV_' . $cleanName . '.pdf';
        
        // Download PDF
        return $pdf->download($filename);
    }

    /**
     * Simulate ATS pre-check scoring
     */
    public function simulateAts(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
        $experiences = $user->experiences;
        $educations = $user->educations;
        $skills = $user->skills;

        $checks = [];
        $totalScore = 0;

        // Check 1: Struktur & Format (25 pts)
        $hasExp = $experiences->count() > 0;
        $hasEdu = $educations->count() > 0;
        $hasSkills = $skills->count() > 0;
        $structScore = ($hasExp ? 10 : 0) + ($hasEdu ? 8 : 0) + ($hasSkills ? 7 : 0);
        $totalScore += $structScore;
        $checks[] = [
            'name' => 'Struktur & Hierarki Bagian',
            'score' => $structScore,
            'max' => 25,
            'status' => $structScore >= 20 ? 'passed' : 'warning',
            'message' => $structScore >= 20 ? 'Format pembagian seksi (Pengalaman, Pendidikan, Keahlian) terstruktur dengan baik untuk mesin parser ATS.' : 'Beberapa bagian inti belum lengkap. Pastikan mengisi Pengalaman, Pendidikan, dan Keahlian.'
        ];

        // Check 2: Kuantifikasi Pengalaman (25 pts)
        $quantScore = 0;
        if ($hasExp) {
            $hasMetrics = false;
            foreach ($experiences as $exp) {
                if (preg_match('/(\d+|\%|\$|rupiah|idr|meningkat|pertumbuhan)/i', $exp->description ?? '')) {
                    $hasMetrics = true;
                    break;
                }
            }
            $quantScore = $hasMetrics ? 25 : 12;
        }
        $totalScore += $quantScore;
        $checks[] = [
            'name' => 'Kuantifikasi & Metrik Pencapaian',
            'score' => $quantScore,
            'max' => 25,
            'status' => $quantScore == 25 ? 'passed' : ($quantScore > 0 ? 'warning' : 'danger'),
            'message' => $quantScore == 25 ? 'Poin pengalaman kerja menggunakan metrik kuantitatif (angka/persentase) yang sangat dicari oleh recruiter dan ATS.' : 'Deskripsi pengalaman kerja masih bersifat naratif. Tambahkan angka atau persentase pencapaian (misal: "meningkatkan efisiensi 20%").'
        ];

        // Check 3: Kelengkapan Kontak & Lokasi (25 pts)
        $hasPhone = !empty($profile->phone_number);
        $hasEmail = !empty($user->email);
        $hasLoc = !empty($profile->domicile);
        $contactScore = ($hasPhone ? 10 : 0) + ($hasEmail ? 10 : 0) + ($hasLoc ? 5 : 0);
        $totalScore += $contactScore;
        $checks[] = [
            'name' => 'Kelengkapan Kontak Valid',
            'score' => $contactScore,
            'max' => 25,
            'status' => $contactScore == 25 ? 'passed' : 'warning',
            'message' => $contactScore == 25 ? 'Informasi kontak (Email, Telepon, Domisili) mudah dideteksi oleh bot ATS.' : 'Lengkapi nomor telepon dan domisili agar mesin ATS dapat memetakan lokasi kerja dengan akurat.'
        ];

        // Check 4: Kepadatan Kata Kunci (25 pts)
        $skillCount = $skills->count();
        $keywordScore = min(25, $skillCount * 4);
        $totalScore += $keywordScore;
        $checks[] = [
            'name' => 'Kepadatan Kata Kunci (Keywords)',
            'score' => $keywordScore,
            'max' => 25,
            'status' => $keywordScore >= 20 ? 'passed' : 'warning',
            'message' => $keywordScore >= 20 ? 'Daftar keahlian teknis kaya akan kata kunci relevan untuk pencocokan otomatis (Auto-Matching).' : 'Tambahkan lebih banyak keahlian spesifik (hard skills) agar mudah terjaring filter kata kunci ATS.'
        ];

        // Tips
        $tips = [];
        if ($quantScore < 25) {
            $tips[] = 'Gunakan rumus Google: "Accomplished [X] as measured by [Y], by doing [Z]" pada poin pengalaman.';
        }
        if ($skillCount < 6) {
            $tips[] = 'Pisahkan hard skills dan tools ke dalam kategori spesifik agar mudah diindeks oleh ATS.';
        }
        if (empty($profile->bio)) {
            $tips[] = 'Tambahkan Professional Summary yang padat di profil untuk meningkatkan skor relevansi.';
        }
        if (empty($tips)) {
            $tips[] = 'CV Anda sudah dalam kondisi prima! Pastikan posisi lamaran memiliki kata kunci yang senada dengan keahlian Anda.';
        }

        return response()->json([
            'success' => true,
            'score' => $totalScore,
            'checks' => $checks,
            'tips' => $tips
        ]);
    }
}
