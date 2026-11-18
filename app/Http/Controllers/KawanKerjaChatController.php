<?php

namespace App\Http\Controllers;

use App\Models\NaraMessage;
use App\Services\KawanKerjaService;
use App\Services\NaraClientStopped;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KawanKerjaChatController extends Controller
{
    protected KawanKerjaService $kawanKerja;

    public function __construct(KawanKerjaService $kawanKerja)
    {
        $this->kawanKerja = $kawanKerja;
    }

    /**
     * Send a message to Nara. The conversation is stored per user (NaraMessage), so it survives logout and devices.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:1000',
            'mode' => 'sometimes|string|in:auto,career,interview,writing', // the widget sends auto; the rest stay for older clients
        ]);
        $user = Auth::user();
        $mode = $validated['mode'] ?? 'auto';
        $this->importSessionHistory($request);

        $result = $this->kawanKerja->chat($validated['message'], $user, NaraMessage::contextFor($user), $mode);
        $saved = $result['success'] ? NaraMessage::record($user, $validated['message'], $result['reply'], $mode) : null;

        return response()->json([
            'success' => $result['success'],
            'reply' => $result['reply'],
            'message_id' => $saved?->id,
        ]);
    }

    /**
     * Stream a response to the user via SSE (Server-Sent Events).
     */
    public function stream(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:1000',
            'mode' => 'sometimes|string|in:auto,career,interview,writing', // the widget sends auto; the rest stay for older clients
        ]);
        $user = Auth::user();
        $mode = $validated['mode'] ?? 'auto';
        $this->importSessionHistory($request);
        $history = NaraMessage::contextFor($user);

        return response()->stream(function () use ($validated, $user, $history, $mode) {
            @set_time_limit(120);
            if (function_exists('apache_setenv')) {
                @apache_setenv('no-gzip', '1');
            }
            @ini_set('zlib.output_compression', 'Off');
            @ini_set('output_buffering', 'Off');
            @ini_set('implicit_flush', '1');

            // Send every chunk immediately (tests capture the stream in their own buffer, so keep it there).
            while (ob_get_level() > 0 && ! app()->runningUnitTests()) {
                @ob_end_flush();
            }

            // Keep running when the browser disconnects (the Stop button), so the part already written can be saved.
            ignore_user_abort(true);
            $fullReply = '';
            try {
                $result = $this->kawanKerja->chatStream($validated['message'], $user, $history, function (string $chunk) use (&$fullReply) {
                    $fullReply .= $chunk;
                    echo 'data: '.json_encode(['chunk' => $chunk])."\n\n";
                    @flush();
                    if (connection_aborted()) {
                        throw new NaraClientStopped($fullReply);
                    }
                }, $mode, function (string $status) {
                    // Progress while Nara works with tools ("Searching the TraKerja job catalog…").
                    echo 'data: '.json_encode(['status' => $status])."\n\n";
                    @flush();
                });
            } catch (NaraClientStopped $stopped) {
                // Stopped by the user: keep what was written (without a half-written tag) so the history matches the screen.
                $partial = trim(preg_replace('/\[[^\]]*$/', '', $stopped->partial));
                if ($partial !== '') {
                    NaraMessage::record($user, $validated['message'], $partial, $mode);
                }

                return;
            }

            if ($result['success'] && $fullReply !== '') {
                // Job cards are expanded only in the final reply; the client swaps it in for the streamed text.
                $saved = NaraMessage::record($user, $validated['message'], $result['reply'] ?: $fullReply, $mode);
                echo 'data: '.json_encode(['final' => $saved->content, 'id' => $saved->id])."\n\n";
            }
            if (! $result['success']) {
                echo "event: error\ndata: ".json_encode(['success' => false, 'message' => __('Response interrupted. Please try again.')])."\n\n";
            }
            echo "data: [DONE]\n\n";
            if (ob_get_level() > 0 && ! app()->runningUnitTests()) {
                ob_flush();
            }
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-transform',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }

    /**
     * Take back the last exchange (the user's last message and everything after it) and return the user's text, so the
     * chat can send it again ("Regenerate") or let the user edit it.
     */
    public function undoLast(Request $request): JsonResponse
    {
        $last = NaraMessage::where('user_id', Auth::id())->where('role', 'user')->latest('id')->first();
        if (! $last) {
            return response()->json(['success' => true, 'text' => null]);
        }
        NaraMessage::where('user_id', Auth::id())->where('id', '>=', $last->id)->delete();

        return response()->json(['success' => true, 'text' => $last->content]);
    }

    /** Rate one of Nara's replies: 1 helpful, -1 not helpful, 0 to take the rating back. */
    public function feedback(Request $request): JsonResponse
    {
        $validated = $request->validate(['message_id' => 'required|integer', 'rating' => 'required|integer|in:-1,0,1']);
        $message = NaraMessage::where('user_id', Auth::id())->where('role', 'model')->findOrFail($validated['message_id']);
        $message->update(['rating' => $validated['rating'] ?: null, 'rated_at' => $validated['rating'] ? now() : null]);

        return response()->json(['success' => true, 'rating' => $message->rating]);
    }

    /** Save a job from a Nara card to the user's saved jobs (no application is recorded). */
    public function saveJob(Request $request): JsonResponse
    {
        $validated = $request->validate(['vacancy_id' => 'required|integer']);
        $vacancy = \App\Models\Vacancy::visible()->find($validated['vacancy_id']);
        if (! $vacancy) {
            return response()->json(['success' => false, 'message' => __('This job is no longer open.')], 404);
        }
        \App\Models\JobBookmark::firstOrCreate(['user_id' => Auth::id(), 'vacancy_id' => $vacancy->id]);

        return response()->json(['success' => true, 'saved' => true, 'message' => __('Saved for later. No application has been recorded.')]);
    }

    /** Save Nara's latest job search as an Explore saved search with daily email alerts. */
    public function saveSearch(Request $request): JsonResponse
    {
        $user = Auth::user();
        $latest = \Illuminate\Support\Facades\Cache::get(KawanKerjaService::searchKey($user));
        $finder = app(\App\JobEngine\Personalization\NaraJobFinder::class);
        $filters = $latest ? $finder->savedSearchFilters($latest['criteria']) : [];
        if (! \App\JobEngine\Personalization\VacancyFilters::narrows($filters)) {
            return response()->json(['success' => false, 'message' => __('Search for a role or a place first, then save it.')], 422);
        }
        $saved = $finder->saveAlert($user, $latest['criteria'], $latest['label']);
        if (! $saved) {
            return response()->json(['success' => false, 'message' => __('You can save up to 20 searches. Remove one to make room.')], 422);
        }

        return response()->json(['success' => true, 'message' => __('Alerts on. We will tell you about new jobs for :name once a day.', ['name' => $saved->name])]);
    }

    /** The stored conversation (newest 40 messages) and a few things worth bringing up when the chat opens. */
    public function history(Request $request): JsonResponse
    {
        $user = Auth::user();
        $this->importSessionHistory($request);
        $messages = NaraMessage::where('user_id', $user->id)->latest('id')->limit(40)->get(['id', 'role', 'content', 'rating'])->reverse()->values();

        return response()->json([
            'history' => $messages->map(fn ($m) => ['id' => $m->id, 'role' => $m->role, 'content' => $m->content, 'rating' => $m->rating])->all(),
            'nudges' => rescue(fn () => app(\App\JobEngine\Personalization\NaraNudges::class)->for($user), [], false),
        ]);
    }

    /** Clear the conversation. */
    public function clear(Request $request): JsonResponse
    {
        NaraMessage::where('user_id', Auth::id())->delete();
        $request->session()->forget('kawankerja_history');

        return response()->json(['success' => true]);
    }

    /** Conversations from before NaraMessage lived in the session; copy them once. */
    private function importSessionHistory(Request $request): void
    {
        $old = $request->session()->pull('kawankerja_history', []);
        if (! $old || NaraMessage::where('user_id', Auth::id())->exists()) {
            return;
        }
        foreach ($old as $item) {
            if (in_array($item['role'] ?? null, ['user', 'model'], true) && filled($item['content'] ?? null)) {
                NaraMessage::create(['user_id' => Auth::id(), 'role' => $item['role'], 'content' => (string) $item['content'], 'has_jobs' => str_contains((string) $item['content'], '[job:')]);
            }
        }
    }
}
