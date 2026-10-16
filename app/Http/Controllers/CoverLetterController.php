<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CoverLetterController extends Controller
{

    /**
     * Display the Cover Letter Generator landing page.
     */
    public function index(): View
    {
        // Redirect admin users
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }

        $user = Auth::user();
        $skills = $user ? $user->skills()->ordered()->take(5)->get() : collect();
        $experiences = $user ? $user->experiences()->ordered()->take(3)->get() : collect();
        $projects = $user ? $user->projects()->ordered()->take(3)->get() : collect();

        $isPremium = $user ? $user->isPremium() : false;
        $remainingUses = $user ? $user->getRemainingCoverLetter() : 3;

        return view('cover-letters.index', compact('skills', 'experiences', 'projects', 'isPremium', 'remainingUses'));
    }

    /**
     * Generate Cover Letter with AI by merging user profile and job requirements.
     */
    public function generate(Request $request)
    {
        return app(StudioRequestController::class)->submit($request, 'cover-letter');
    }

    /**
     * Display a specific Cover Letter result
     */
    public function show(\App\Models\CoverLetter $coverLetter): View
    {
        // Check if user owns this result
        if ($coverLetter->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to cover letter');
        }

        return app(\App\Support\AiStudioWorkspace::class)->render(Auth::user(), 'cover-letter', $coverLetter);
    }

    /**
     * Display user's Cover Letter history
     */
    public function history(): View
    {
        $user = Auth::user();
        $coverLetters = $user->coverLetters()->orderBy('created_at', 'desc')->paginate(10);

        return view('cover-letters.history', compact('coverLetters'));
    }
}
