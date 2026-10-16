<?php

namespace App\Http\Controllers;

use App\Models\AiAnalyzerResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiAnalyzerController extends Controller
{

    /**
     * Display the AI Analyzer page
     */
    public function index(): View
    {
        // Redirect admin users
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }

        $user = Auth::user();
        $canAccess = $user->canAccessAiAnalyzerWithLimit();
        $hasUsedTrial = $user->hasUsedAiAnalyzerTrial();
        $isPremium = $user->isPremium();
        $remainingUses = $user->getRemainingAiAnalyzer();

        return view('ai-analyzer.index', [
            'canAccess' => $canAccess,
            'hasUsedTrial' => $hasUsedTrial,
            'isPremium' => $isPremium,
            'remainingUses' => $remainingUses,
        ]);
    }

    /**
     * Analyze resume with AI
     */
    public function analyze(Request $request)
    {
        return app(StudioRequestController::class)->submit($request, 'analyzer');
    }


    /**
     * Display a specific AI analysis result
     */
    public function show(AiAnalyzerResult $result): View
    {
        // Check if user owns this result
        if ($result->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to analysis result');
        }

        return app(\App\Support\AiStudioWorkspace::class)->render(Auth::user(), 'analyzer', $result);
    }
}
