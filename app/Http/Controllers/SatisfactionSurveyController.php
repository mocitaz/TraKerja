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

        // If user has already completed the survey, redirect to home/tracker
        $hasCompleted = SatisfactionSurvey::where('user_id', $user->id)->exists();
        if ($hasCompleted) {
            return redirect()->route('tracker');
        }

        return view('survey');
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
            'score' => 'required|integer|min:1|max:5',
            'ease_of_use' => 'required|integer|min:1|max:5',
            'features_helpful' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ], [
            'score.required' => 'Mohon isi penilaian kepuasan keseluruhan.',
            'ease_of_use.required' => 'Mohon isi penilaian kemudahan penggunaan.',
            'features_helpful.required' => 'Mohon isi penilaian kegunaan fitur AI.',
        ]);

        SatisfactionSurvey::create([
            'user_id' => $user->id,
            'score' => $validated['score'],
            'ease_of_use' => $validated['ease_of_use'],
            'features_helpful' => $validated['features_helpful'],
            'feedback' => $validated['feedback'],
        ]);

        return redirect()->route('tracker')->with('success', 'Terima kasih atas tanggapan Anda! Masukan Anda sangat berarti bagi pengembangan TraKerja.');
    }
}
