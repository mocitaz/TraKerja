<?php

namespace App\Http\Controllers;

use App\Models\AiPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiPhotoController extends Controller
{
    /**
     * Display the AI Photo Studio upload form.
     */
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_generated' => $user->aiPhotos()->where('status', 'completed')->count(),
            'remaining_credits' => $user->getRemainingPhoto(),
        ];

        return view('ai-photo.index', compact('stats'));
    }

    /**
     * Process the photo (remove background or enhance with AI).
     */
    public function process(Request $request, \App\Services\AiPhotoProcessingService $service)
    {
        $validated = $request->validate([
            'photo' => 'required|image|max:20480',
            'request_key' => 'required|uuid',
            'type' => 'required|in:remove_bg,enhance',
            'style' => 'nullable|string|max:100',
            'background' => 'nullable|string|max:100',
            'mode' => 'nullable|in:portrait,headshot',
            'size' => 'nullable|string|max:50',
        ]);
        $payload = $validated['type'] === 'remove_bg'
            ? ['type' => 'remove_bg', 'background' => $validated['background'] ?? 'transparan', 'size' => $validated['size'] ?? 'original']
            : ['type' => 'enhance', 'style' => $validated['style'] ?? 'auto', 'background' => $validated['background'] ?? 'studio_plain', 'mode' => $validated['mode'] ?? 'portrait'];
        $photo = $service->submit($request->user(), $request->file('photo'), $payload, $validated['request_key']);
        if ($request->expectsJson()) return response()->json(['success' => true, 'status' => $photo->status, 'url' => route('ai-photo.show', $photo->id)], 202);
        return redirect()->route('ai-photo.show', $photo->id)->with('success', 'Permintaan foto tersimpan. Anda dapat memantau prosesnya di halaman ini.');
    }

    public function status(Request $request, int $aiPhoto)
    {
        $photo = $request->user()->aiPhotos()->findOrFail($aiPhoto);
        return response()->json([
            'status' => $photo->status,
            'message' => $photo->error_message,
            'credit_state' => $photo->credit_state,
        ])->header('Cache-Control', 'private, no-store');
    }

    public function retry(Request $request, int $aiPhoto, \App\Services\AiPhotoProcessingService $service)
    {
        $photo = $request->user()->aiPhotos()->findOrFail($aiPhoto);
        $validated = $request->validate(['request_key' => 'required|uuid']);
        $attempt = $service->retry($request->user(), $photo, $validated['request_key']);
        return redirect()->route('ai-photo.show', $attempt->id)->with('success', 'Percobaan baru tersimpan.');
    }

    /**
     * Display a specific generated photo.
     */
    public function show($id)
    {
        $aiPhoto = AiPhoto::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return app(\App\Support\AiStudioWorkspace::class)->render(Auth::user(), 'photo', $aiPhoto);
    }

    /**
     * Display generation history.
     */
    public function history()
    {
        $photos = AiPhoto::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('ai-photo.history', compact('photos'));
    }
}
