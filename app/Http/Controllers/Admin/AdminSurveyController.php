<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\SatisfactionSurvey;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AdminSurveyController extends Controller
{
    /**
     * Display the admin survey control panel.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }

        $surveyEnabled = Setting::get('survey_enabled', false);

        // Calculate statistics
        $totalRespondents = SatisfactionSurvey::count();
        
        $avgScore = SatisfactionSurvey::avg('score') ?: 0.0;
        $avgEaseOfUse = SatisfactionSurvey::avg('ease_of_use') ?: 0.0;
        $avgFeaturesHelpful = SatisfactionSurvey::avg('features_helpful') ?: 0.0;

        // Calculate score distributions (1-5)
        $distributions = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        ];

        if ($totalRespondents > 0) {
            $rawDistributions = SatisfactionSurvey::select('score', \DB::raw('count(*) as count'))
                ->groupBy('score')
                ->pluck('count', 'score')
                ->toArray();

            foreach ($rawDistributions as $score => $count) {
                $distributions[$score] = $count;
            }
        }

        // List responses paginated
        $responses = SatisfactionSurvey::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.survey', [
            'surveyEnabled' => $surveyEnabled,
            'totalRespondents' => $totalRespondents,
            'avgScore' => number_format($avgScore, 1),
            'avgEaseOfUse' => number_format($avgEaseOfUse, 1),
            'avgFeaturesHelpful' => number_format($avgFeaturesHelpful, 1),
            'distributions' => $distributions,
            'responses' => $responses
        ]);
    }

    /**
     * Toggle the survey active status.
     */
    public function toggle(Request $request): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }

        $validated = $request->validate([
            'enabled' => 'required|boolean'
        ]);

        $newState = (bool) $validated['enabled'];
        Setting::set('survey_enabled', $newState);
        Setting::clearCache();

        $message = $newState 
            ? 'User Satisfaction Survey berhasil DIAKTIFKAN! Pengguna akan dipaksa mengisi survey setelah login.' 
            : 'User Satisfaction Survey berhasil DINONAKTIFKAN!';

        return redirect()->route('admin.survey.index')->with('success', $message);
    }
}
