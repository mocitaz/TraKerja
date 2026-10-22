<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SatisfactionSurvey;
use App\Models\Setting;
use App\Support\SurveyQuestions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminSurveyController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        $request->validate([
            'search' => 'nullable|string|max:100',
            'score' => 'nullable|integer|between:1,5',
            'comments' => 'nullable|in:written,none',
        ]);
        $query = SatisfactionSurvey::query();
        if ($request->filled('search')) {
            $search = '%'.$request->string('search').'%';
            $query->where(fn ($q) => $q->where('feedback', 'like', $search)
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $search)->orWhere('email', 'like', $search)));
        }
        if ($request->filled('score')) $query->where('q1_overall', $request->integer('score'));
        if ($request->input('comments') === 'written') $query->whereNotNull('feedback')->where('feedback', '!=', '');
        if ($request->input('comments') === 'none') $query->where(fn ($q) => $q->whereNull('feedback')->orWhere('feedback', ''));

        $sections = SurveyQuestions::sections();
        $averages = [];
        foreach ($sections as $section) {
            foreach ($section['questions'] as $field => $question) {
                $averages[$field] = (clone $query)->avg($field);
            }
        }
        $totalRespondents = (clone $query)->count();
        $distributions = array_replace(array_fill(1, 5, 0), (clone $query)->selectRaw('q1_overall, count(*) as aggregate')->groupBy('q1_overall')->pluck('aggregate', 'q1_overall')->all());
        $positiveCount = ($distributions[4] ?? 0) + ($distributions[5] ?? 0);
        $writtenCount = (clone $query)->whereNotNull('feedback')->where('feedback', '!=', '')->count();
        return view('admin.survey', [
            'surveyEnabled' => (bool) Setting::get('survey_enabled', false),
            'sections' => $sections, 'averages' => $averages,
            'avgQ1Overall' => number_format($averages['q1_overall'] ?? 0, 1),
            'totalRespondents' => $totalRespondents, 'distributions' => $distributions,
            'positiveCount' => $positiveCount, 'writtenCount' => $writtenCount,
            'positiveRate' => $totalRespondents ? round($positiveCount / $totalRespondents * 100) : null,
            'responses' => (clone $query)->with('user')->latest()->orderByDesc('id')->paginate(15)->withQueryString(),
        ]);
    }

    public function toggle(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        $validated = $request->validate(['enabled' => 'required|boolean']);
        Setting::set('survey_enabled', (bool) $validated['enabled']);
        Setting::clearCache();
        return redirect()->route('admin.survey.index')->with('success', $validated['enabled']
            ? __('Survey invitations enabled. Eligible members can choose whether to respond.')
            : __('Survey invitations paused. Existing responses are kept.'));
    }
}
