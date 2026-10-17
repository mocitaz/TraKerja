<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\SatisfactionSurvey;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SatisfactionSurveyController extends Controller
{
    public function dismiss(Request $request): RedirectResponse
    {
        $request->session()->put('survey_deferred_until.'.$request->user()->id, now()->addDays(7)->timestamp);

        return redirect()->route('tracker');
    }

    /**
     * Show the survey form to the user.
     */
    public function show(): View|RedirectResponse
    {
        $user = Auth::user();

        // If user is admin, they don't need to fill the survey
        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        }

        // If survey is not enabled, redirect to home/tracker
        if (!Setting::get('survey_enabled', false)) {
            return redirect()->route('tracker');
        }

        $hasCompleted = SatisfactionSurvey::where('user_id', $user->id)->exists();

        return view('survey', [
            'hasCompleted' => $hasCompleted
        ]);
    }

    /**
     * Store the user survey response.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        }

        if (!Setting::get('survey_enabled', false)) {
            return redirect()->route('tracker');
        }

        $hasCompleted = SatisfactionSurvey::where('user_id', $user->id)->exists();
        if ($hasCompleted) {
            return redirect()->route('tracker');
        }

        $validated = $request->validate([
            'q1_overall' => 'required|integer|min:1|max:5',
            'q2_navigation' => 'required|integer|min:1|max:5',
            'q3_speed' => 'required|integer|min:1|max:5',
            'q4_cv_builder' => 'required|integer|min:1|max:5',
            'q5_ai_analyzer' => 'required|integer|min:1|max:5',
            'q6_job_tracker' => 'required|integer|min:1|max:5',
            'q7_cover_letter' => 'required|integer|min:1|max:5',
            'q8_interviews' => 'required|integer|min:1|max:5',
            'q9_premium' => 'required|integer|min:1|max:5',
            'q10_recommend' => 'required|integer|min:1|max:5',
            'q11_design' => 'required|integer|min:1|max:5',
            'q12_cv_templates' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ], [
            'q1_overall.required' => 'Choose a rating for overall satisfaction.',
            'q2_navigation.required' => 'Choose a rating for navigation.',
            'q3_speed.required' => 'Choose a rating for speed and performance.',
            'q4_cv_builder.required' => 'Choose a rating for CV Builder.',
            'q5_ai_analyzer.required' => 'Choose a rating for CV analysis.',
            'q6_job_tracker.required' => 'Choose a rating for application tracker.',
            'q7_cover_letter.required' => 'Choose a rating for cover letters.',
            'q8_interviews.required' => 'Choose a rating for interview planning.',
            'q9_premium.required' => 'Choose a rating for Premium value.',
            'q10_recommend.required' => 'Choose a rating for recommendation likelihood.',
            'q11_design.required' => 'Choose a rating for visual design.',
            'q12_cv_templates.required' => 'Choose a rating for CV templates and exports.',
        ]);

        SatisfactionSurvey::create([
            'user_id' => $user->id,
            'q1_overall' => $validated['q1_overall'],
            'q2_navigation' => $validated['q2_navigation'],
            'q3_speed' => $validated['q3_speed'],
            'q4_cv_builder' => $validated['q4_cv_builder'],
            'q5_ai_analyzer' => $validated['q5_ai_analyzer'],
            'q6_job_tracker' => $validated['q6_job_tracker'],
            'q7_cover_letter' => $validated['q7_cover_letter'],
            'q8_interviews' => $validated['q8_interviews'],
            'q9_premium' => $validated['q9_premium'],
            'q10_recommend' => $validated['q10_recommend'],
            'q11_design' => $validated['q11_design'],
            'q12_cv_templates' => $validated['q12_cv_templates'],
            'feedback' => $validated['feedback'] ?? null,
        ]);

        return redirect()->route('survey.show')->with('success', __('Your feedback has been saved. Thank you for sharing.'));
    }
}
