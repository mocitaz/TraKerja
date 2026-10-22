<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CustomEmailBlastMail;
use App\Models\EmailBlastTemplate;
use App\Services\{GeminiEmailTemplateService, StudioProviderException};
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailBlastTemplateController extends Controller
{
    public function generate(Request $request, GeminiEmailTemplateService $gemini)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate(['prompt' => 'required|string|min:15|max:3000', 'language' => 'required|in:id,en']);
        try {
            return response()->json(['draft' => $gemini->generate($data['prompt'], $data['language'])]);
        } catch (StudioProviderException $e) {
            return response()->json(['message' => __(match ($e->reason) {
                'configuration' => 'Gemini is not configured. Ask the administrator to check the API key and model.',
                'rate_limit' => 'Gemini is busy. Wait a moment before trying again.',
                'invalid_result', 'incomplete' => 'Gemini returned an incomplete draft. Try again or adjust your brief.',
                default => 'Gemini could not complete this draft. Your message has not been changed.',
            })], 503);
        }
    }

    private function draft(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:100', 'language' => 'required|in:id,en',
            'subject' => 'required|string|max:255', 'content' => 'required|string|max:5000',
            'button_text' => 'nullable|required_with:button_url|string|max:100',
            'button_url' => 'nullable|required_with:button_text|url:http,https|max:500',
        ]);
    }

    private function token(array $draft, int $admin): string
    {
        $fields = [];
        foreach (['name', 'language', 'subject', 'content', 'button_text', 'button_url'] as $key) $fields[$key] = $draft[$key] ?? '';
        return hash_hmac('sha256', $admin.'|'.json_encode($fields), config('app.key'));
    }

    public function preview(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $draft = $this->draft($request);
        $html = (new CustomEmailBlastMail(
            $request->user(), $draft['subject'], $draft['content'], $draft['button_text'] ?? null, $draft['button_url'] ?? null
        ))->locale($draft['language'])->render();
        return response()->json(['html' => $html, 'review_token' => $this->token($draft, $request->user()->id)]);
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $draft = $this->draft($request);
        $request->validate(['reviewed' => 'required|accepted', 'review_token' => 'required|string', 'source_prompt' => 'nullable|string|max:3000']);
        if (!hash_equals($this->token($draft, $request->user()->id), $request->input('review_token'))) {
            throw ValidationException::withMessages(['review_token' => __('The draft changed. Preview it again before saving.')]);
        }
        $template = EmailBlastTemplate::create($draft + [
            'source_prompt' => $request->input('source_prompt'), 'reviewed_by' => $request->user()->id, 'reviewed_at' => now(),
        ]);
        return response()->json(['template' => $template->only(['id', 'name', 'language', 'subject', 'content', 'button_text', 'button_url'])], 201);
    }
}
