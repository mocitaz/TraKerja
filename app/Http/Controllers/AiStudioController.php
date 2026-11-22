<?php

namespace App\Http\Controllers;

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

        return app(\App\Support\AiStudioWorkspace::class)->render($request->user(), $request->query('task', $request->query('tab', 'analyzer')));
    }

    /**
     * Generate recruiter outreach using the Studio processing pipeline.
     */
    public function generateOutreach(Request $request)
    {
        return app(StudioRequestController::class)->submit($request, 'outreach');
    }

    /** Free revisions per draft (the first draft plus this many versions). */
    public const FREE_REVISIONS = 3;

    /**
     * Revise a cover letter or recruiter message from the chat ("make it shorter"). The revision is saved as a new
     * version of the first draft, so the earlier versions stay available. No credit is used for the first revisions.
     */
    public function revise(Request $request)
    {
        abort_if($request->user()->isAdmin(), 403);
        $data = $request->validate([
            'task' => 'required|in:cover-letter,outreach',
            'id' => 'required|integer',
            'instruction' => 'required|string|min:3|max:300',
        ]);
        $model = $data['task'] === 'outreach' ? \App\Models\OutreachDraft::class : \App\Models\CoverLetter::class;
        $draft = $model::where('user_id', $request->user()->id)->findOrFail($data['id']);
        $rootId = $draft->root_id ?: $draft->id;
        if ($model::where('user_id', $request->user()->id)->where('root_id', $rootId)->count() >= self::FREE_REVISIONS) {
            return response()->json(['success' => false, 'message' => __('This draft already has :count revisions. Start a new request for a fresh draft.', ['count' => self::FREE_REVISIONS])], 422);
        }
        $context = array_filter([
            'company' => $draft->company_name, 'role' => $draft->job_title, 'job_description' => $draft->job_description ?? null,
            'channel' => $draft->channel ?? null, 'recruiter' => $draft->recruiter_name ?? null, 'tone' => $draft->tone,
        ]);
        try {
            $content = app(\App\Services\StudioAiService::class)->revise($data['task'], (string) $draft->content, trim($data['instruction']), $context);
        } catch (\App\Services\StudioProviderException $e) {
            return response()->json(['success' => false, 'message' => $e->reason === 'rate_limit' ? __('The AI service is busy. Please try again later.') : __('The revision could not be completed. Your draft has not changed.')], 503);
        }
        $copy = collect($draft->getAttributes())->except(['id', 'created_at', 'updated_at', 'content', 'root_id', 'parent_id', 'revision_note'])->all();
        $revision = $model::create($copy + ['content' => $content, 'root_id' => $rootId, 'parent_id' => $draft->id, 'revision_note' => mb_substr(trim($data['instruction']), 0, 300)]);

        return response()->json(['success' => true, 'url' => route('ai-studio.index', ['task' => $data['task'], 'id' => $revision->id])]);
    }
}
