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
            'q8_summary' => 'required|integer|min:1|max:5',
            'q9_premium' => 'required|integer|min:1|max:5',
            'q10_recommend' => 'required|integer|min:1|max:5',
            'q11_design' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ], [
            'q1_overall.required' => 'Mohon isi penilaian kepuasan keseluruhan.',
            'q2_navigation.required' => 'Mohon isi penilaian kemudahan navigasi.',
            'q3_speed.required' => 'Mohon isi penilaian performa & kecepatan.',
            'q4_cv_builder.required' => 'Mohon isi penilaian kegunaan Resume Builder.',
            'q5_ai_analyzer.required' => 'Mohon isi penilaian kegunaan AI Analyzer.',
            'q6_job_tracker.required' => 'Mohon isi penilaian kegunaan Job Tracker.',
            'q7_cover_letter.required' => 'Mohon isi penilaian kegunaan Cover Letter.',
            'q8_summary.required' => 'Mohon isi penilaian kesesuaian halaman Summary.',
            'q9_premium.required' => 'Mohon isi penilaian nilai ekonomis premium.',
            'q10_recommend.required' => 'Mohon isi tingkat rekomendasi layanan.',
            'q11_design.required' => 'Mohon isi penilaian estetika desain visual.',
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
            'q8_summary' => $validated['q8_summary'],
            'q9_premium' => $validated['q9_premium'],
            'q10_recommend' => $validated['q10_recommend'],
            'q11_design' => $validated['q11_design'],
            'feedback' => $validated['feedback'],
        ]);

        return redirect()->route('survey.show')->with('success', 'Terima kasih atas tanggapan Anda! Masukan Anda sangat berarti bagi pengembangan TraKerja.');
    }
}
