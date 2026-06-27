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
        if (!in_array($activeTab, ['analyzer', 'cover-letter', 'photo'])) {
            $activeTab = 'analyzer';
        }

        return view('ai-studio.index', compact(
            'analyzerCanAccess', 'analyzerHasUsedTrial', 'analyzerIsPremium', 'analyzerRemainingUses', 'analyzerHistory',
            'clSkills', 'clExperiences', 'clProjects', 'clIsPremium', 'clRemainingUses', 'clHistory',
            'photoStats', 'photoHistory', 'activeTab'
        ));
    }
}
