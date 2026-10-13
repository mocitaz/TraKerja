<?php

namespace App\Http\Controllers;

use App\Services\KawanKerjaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
