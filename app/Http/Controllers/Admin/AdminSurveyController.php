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
        
        $avgQ1Overall = SatisfactionSurvey::avg('q1_overall') ?: 0.0;
        $avgQ2Navigation = SatisfactionSurvey::avg('q2_navigation') ?: 0.0;
        $avgQ3Speed = SatisfactionSurvey::avg('q3_speed') ?: 0.0;
        $avgQ4CvBuilder = SatisfactionSurvey::avg('q4_cv_builder') ?: 0.0;
        $avgQ5AiAnalyzer = SatisfactionSurvey::avg('q5_ai_analyzer') ?: 0.0;
        $avgQ6JobTracker = SatisfactionSurvey::avg('q6_job_tracker') ?: 0.0;
        $avgQ7CoverLetter = SatisfactionSurvey::avg('q7_cover_letter') ?: 0.0;
        $avgQ8Interviews = SatisfactionSurvey::avg('q8_interviews') ?: 0.0;
        $avgQ9Premium = SatisfactionSurvey::avg('q9_premium') ?: 0.0;
        $avgQ10Recommend = SatisfactionSurvey::avg('q10_recommend') ?: 0.0;
        $avgQ11Design = SatisfactionSurvey::avg('q11_design') ?: 0.0;
        $avgQ12CvTemplates = SatisfactionSurvey::avg('q12_cv_templates') ?: 0.0;

        // Calculate score distributions (1-5) for Q1 Overall
        $distributions = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        ];

        if ($totalRespondents > 0) {
            $rawDistributions = SatisfactionSurvey::select('q1_overall', \DB::raw('count(*) as count'))
                ->groupBy('q1_overall')
                ->pluck('count', 'q1_overall')
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
            'avgQ1Overall' => number_format($avgQ1Overall, 1),
            'avgQ2Navigation' => number_format($avgQ2Navigation, 1),
            'avgQ3Speed' => number_format($avgQ3Speed, 1),
            'avgQ4CvBuilder' => number_format($avgQ4CvBuilder, 1),
            'avgQ5AiAnalyzer' => number_format($avgQ5AiAnalyzer, 1),
            'avgQ6JobTracker' => number_format($avgQ6JobTracker, 1),
            'avgQ7CoverLetter' => number_format($avgQ7CoverLetter, 1),
            'avgQ8Interviews' => number_format($avgQ8Interviews, 1),
            'avgQ9Premium' => number_format($avgQ9Premium, 1),
            'avgQ10Recommend' => number_format($avgQ10Recommend, 1),
            'avgQ11Design' => number_format($avgQ11Design, 1),
            'avgQ12CvTemplates' => number_format($avgQ12CvTemplates, 1),
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
            ? 'User Satisfaction Survey berhasil DIAKTIFKAN! Pengguna lama (akun >= 3 hari) akan dipaksa mengisi survey setelah login.' 
            : 'User Satisfaction Survey berhasil DINONAKTIFKAN!';

        return redirect()->route('admin.survey.index')->with('success', $message);
    }
}
