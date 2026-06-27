<?php

namespace App\Http\Controllers;

use App\Models\AiAnalyzerResult;
use App\Models\AiPhoto;
use App\Models\CoverLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiStudioController extends Controller
{
    /**
     * Display the unified AI Studio dashboard.
     */
    public function index(Request $request)
    {
        // Redirect admin users
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }

        $user = Auth::user();
        $userId = $user->id;

        // --- 1. AI Resume Analyzer Data ---
        $analyzerCanAccess = $user->canAccessAiAnalyzerWithLimit();
        $analyzerHasUsedTrial = $user->hasUsedAiAnalyzerTrial();
        $analyzerIsPremium = $user->isPremium();
        $analyzerRemainingUses = $user->getRemainingAiAnalyzer();
        $analyzerHistory = AiAnalyzerResult::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // --- 2. Cover Letter Data ---
        $clSkills = $user->skills()->ordered()->take(5)->get();
        $clExperiences = $user->experiences()->ordered()->take(3)->get();
        $clProjects = $user->projects()->ordered()->take(3)->get();
        $clIsPremium = $user->isPremium();
        $clRemainingUses = $user->getRemainingCoverLetter();
        $clHistory = $user->coverLetters()->orderBy('created_at', 'desc')->get();

        // --- 3. AI Photo Studio Data ---
        $photoStats = [
            'total_generated' => $user->aiPhotos()->count(),
            'remaining_credits' => $user->getRemainingPhoto(),
        ];
        $photoHistory = AiPhoto::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Active tab configuration from query param
        $activeTab = $request->get('tab', 'analyzer');
        if (!in_array($activeTab, ['analyzer', 'cover-letter', 'photo', 'outreach'])) {
            $activeTab = 'analyzer';
        }

        return view('ai-studio.index', compact(
            'analyzerCanAccess', 'analyzerHasUsedTrial', 'analyzerIsPremium', 'analyzerRemainingUses', 'analyzerHistory',
            'clSkills', 'clExperiences', 'clProjects', 'clIsPremium', 'clRemainingUses', 'clHistory',
            'photoStats', 'photoHistory', 'activeTab'
        ));
    }

    /**
     * Generate Recruiter Outreach message using AI.
     */
    public function generateOutreach(Request $request)
    {
        $request->validate([
            'recruiter_name' => 'nullable|string|max:100',
            'target_company' => 'required|string|max:100',
            'job_title' => 'required|string|max:100',
            'channel' => 'required|string|in:LinkedIn InMail,Email,Direct Message',
            'tone' => 'required|string|in:Professional,Friendly,Persuasive',
        ]);

        $recruiter = $request->input('recruiter_name') ?: 'Hiring Team';
        $company = $request->input('target_company');
        $job = $request->input('job_title');
        $channel = $request->input('channel');
        $tone = $request->input('tone');
        $user = Auth::user();

        // Sample template fallback or Gemini API call
        $greeting = "Halo " . $recruiter . ",\n\n";
        $body = "Saya melihat lowongan posisi " . $job . " di " . $company . " dan sangat tertarik dengan pertumbuhan perusahaan Anda. Dengan latar belakang saya sebagai profesional yang berpengalaman dalam bidang ini, saya yakin dapat memberikan kontribusi nyata bagi tim " . $company . ".\n\nApakah ada waktu luang dalam minggu ini untuk berdiskusi singkat mengenai kualifikasi saya untuk posisi tersebut?\n\nTerima kasih atas waktu dan perhatiannya.\n\nSalam hangat,\n" . $user->name;

        if ($channel === 'LinkedIn InMail') {
            $subject = "Aplikasi Posisi " . $job . " - " . $user->name;
            $fullMessage = "Subjek: " . $subject . "\n\n" . $greeting . $body;
        } else {
            $fullMessage = $greeting . $body;
        }

        return response()->json([
            'success' => true,
            'message' => $fullMessage,
        ]);
    }
}
