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
     * Create a saved recruiter outreach template.
     */
    public function generateOutreach(Request $request)
    {
        abort_if($request->user()->isAdmin(), 403);
        $request->validate([
            'recruiter_name' => 'nullable|string|max:100',
            'target_company' => 'required|string|max:100',
            'job_title' => 'required|string|max:100',
            'channel' => 'required|string|in:LinkedIn InMail,Email,Direct Message',
            'tone' => 'required|string|in:Professional,Friendly,Persuasive',
        ]);

        $recruiter = $request->input('recruiter_name') ?: 'Hiring Team';
        $company = $request->input('target_company');
        $job = $request->input('job_title');
        $channel = $request->input('channel');
        $tone = $request->input('tone');
        $user = Auth::user();

        $greeting = ($tone === 'Friendly' ? 'Hi ' : 'Dear ').$recruiter.",\n\n";
        $body = $tone === 'Persuasive'
            ? "I would like to discuss the {$job} role at {$company} and how my background could support your team."
            : "I’m interested in the {$job} role at {$company} and would appreciate the opportunity to learn more.";
        $body .= "\n\nWould you be open to a brief conversation about the role and the application process?\n\nThank you for your time.\n".$user->name;
        $fullMessage = $channel === 'Email' || $channel === 'LinkedIn InMail'
            ? "Subject: {$job} opportunity — {$user->name}\n\n".$greeting.$body
            : $greeting.$body;

        $draft = \App\Models\OutreachDraft::create([
            'user_id' => $user->id, 'company_name' => $company, 'job_title' => $job,
            'recruiter_name' => $request->input('recruiter_name'),
            'channel' => $channel, 'tone' => $tone, 'content' => $fullMessage,
        ]);
        return response()->json([
            'success' => true, 'message' => $fullMessage, 'is_template' => true,
            'url' => route('ai-studio.index', ['task' => 'outreach', 'id' => $draft->id]),
        ]);
    }
}
