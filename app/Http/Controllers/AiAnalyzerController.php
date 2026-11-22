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

    /**
     * Show a saved review in the member's current language: translated once on first view, then kept with the
     * result. No credit is used; only the owner can ask.
     */
    public function translate(AiAnalyzerResult $result): \Illuminate\Http\JsonResponse
    {
        abort_unless($result->user_id === Auth::id(), 403);
        $review = (array) $result->analysis_result;
        $to = \App\Support\AppLocale::valid(Auth::user()->locale) ? Auth::user()->locale : app()->getLocale();
        $sameLanguage = ($review['language'] ?? $to) === $to;
        // Translate when the setting changed, or tidy up a review that came out in two languages.
        if (($review['schema_version'] ?? null) !== 3 || isset($review['translations'][$to]) || ($sameLanguage && ! \App\Services\StudioAiService::isMixed($review, $to))) {
            return response()->json(['ok' => true]);
        }
        try {
            $translated = app(\App\Services\StudioAiService::class)->translateReview($review, $to);
        } catch (\App\Services\StudioProviderException) {
            return response()->json(['ok' => false, 'message' => __('The translation could not be completed. Please try again later.')], 503);
        }
        $review['translations'][$to] = $translated;
        $result->update(['analysis_result' => $review]);

        return response()->json(['ok' => true]);
    }
}
