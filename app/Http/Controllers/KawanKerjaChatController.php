<?php

namespace App\Http\Controllers;

use App\Services\KawanKerjaService;
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
     * Send a message to KawanKerja.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        $user = Auth::user();
        $history = $request->session()->get('kawankerja_history', []);

        $result = $this->kawanKerja->chat($validated['message'], $user, $history);

        if ($result['success']) {
            $history[] = ['role' => 'user', 'content' => $validated['message']];
            $history[] = ['role' => 'model', 'content' => $result['reply']];

            // Retain only the last 10 exchanges (20 messages) to preserve memory efficiency
            if (count($history) > 20) {
                $history = array_slice($history, -20);
            }

            $request->session()->put('kawankerja_history', $history);
        }

        return response()->json([
            'success' => $result['success'],
            'reply' => $result['reply'],
            'history' => $history,
        ]);
    }

    /**
     * Stream a response to the user via SSE (Server-Sent Events).
     */
    public function stream(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        $user = Auth::user();
        $history = $request->session()->get('kawankerja_history', []);

        return response()->stream(function () use ($validated, $user, $history, $request) {
            @set_time_limit(120);
            if (function_exists('apache_setenv')) {
                @apache_setenv('no-gzip', '1');
            }
            @ini_set('zlib.output_compression', 'Off');
            @ini_set('output_buffering', 'Off');
            @ini_set('implicit_flush', '1');

            while (ob_get_level() > 0) {
                @ob_end_flush();
            }

            $fullReply = '';

            $result = $this->kawanKerja->chatStream(
                $validated['message'],
                $user,
                $history,
                function (string $chunk) use (&$fullReply) {
                    $fullReply .= $chunk;
                    echo "data: " . json_encode(['chunk' => $chunk]) . "\n\n";
                    @flush();
                }
            );

            if ($result['success'] && !empty($fullReply)) {
                $history[] = ['role' => 'user', 'content' => $validated['message']];
                $history[] = ['role' => 'model', 'content' => $fullReply];

                if (count($history) > 20) {
                    $history = array_slice($history, -20);
                }

                $request->session()->put('kawankerja_history', $history);
                $request->session()->save();
            }

            echo "data: [DONE]\n\n";
            if (ob_get_level() > 0) {
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
     * Get conversation history for current session.
     */
    public function history(Request $request): JsonResponse
    {
        $history = $request->session()->get('kawankerja_history', []);

        return response()->json([
            'history' => $history,
        ]);
    }

    /**
     * Clear the conversation history.
     */
    public function clear(Request $request): JsonResponse
    {
        $request->session()->forget('kawankerja_history');

        return response()->json([
            'success' => true,
        ]);
    }
}
