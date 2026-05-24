<?php

namespace App\Http\Controllers;

use App\Models\AiPhoto;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityLogger;

class AiPhotoController extends Controller
{
    /**
     * Display the AI Photo Studio upload form.
     */
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_generated' => $user->aiPhotos()->count(),
            'remaining_credits' => $user->getRemainingPhoto(),
        ];

        return view('ai-photo.index', compact('stats'));
    }

    /**
     * Process the photo (remove background or enhance with AI).
     */
    public function process(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:20480', // Max 20MB
            'type' => 'required|in:remove_bg,enhance',
            'style' => 'nullable|string',
            'background' => 'nullable|string',
            'mode' => 'nullable|string|in:portrait,headshot',
            'size' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Check if monetization is enabled and check credits
        if (Setting::isMonetizationEnabled() && !$user->canAccessPhotoWithLimit()) {
            return back()->with('error', 'Kredit Photo AI Anda telah habis. Silakan upgrade Premium atau beli Add-on untuk menambah kredit.');
        }

        $type = $request->input('type');
        $baseUrl = env('PHOTO_API_URL', 'https://aiphoto.apitrakerja.online');

        try {
            $endpoint = $type === 'remove_bg' ? '/remove-bg' : '/enhance-photo-ai';
            
            $payload = [];
            if ($type === 'remove_bg') {
                $payload['background'] = $request->input('background', 'transparan');
                $payload['size'] = $request->input('size', 'original');
            } else {
                $payload['style'] = $request->input('style', 'auto');
                $payload['background'] = $request->input('background', 'studio_plain');
                $payload['mode'] = $request->input('mode', 'portrait');
            }

            // Using timeout(300) since AI process takes time
            $response = Http::timeout(300)->attach(
                'photo',
                fopen($request->file('photo')->getRealPath(), 'r'),
                $request->file('photo')->getClientOriginalName()
            )->post($baseUrl . $endpoint, $payload);

            if (!$response->successful()) {
                $errorMsg = $response->json('error') ?? 'Terjadi kesalahan pada server AI.';
                return back()->with('error', 'Gagal memproses gambar: ' . $errorMsg);
            }

            $data = $response->json();

            if (!isset($data['success']) || !$data['success']) {
                $errorMsg = $data['error'] ?? 'Terjadi kesalahan pada AI processing.';
                return back()->with('error', 'Gagal memproses gambar: ' . $errorMsg);
            }

            // Deduct credit after successful API call
            $user->incrementPhotoCount();

            // Save to database
            $aiPhoto = AiPhoto::create([
                'user_id' => $user->id,
                'type' => $type,
                'style_used' => $data['style_used'] ?? null,
                'background_used' => $data['background'] ?? $data['background_used'] ?? null,
                'mode' => $data['mode'] ?? null,
                'original_photo_name' => $request->file('photo')->getClientOriginalName(),
                'result_url' => $data['result_url'],
                'photo_analysis' => $data['photo_analysis'] ?? null,
            ]);

            $typeLabel = $type === 'remove_bg' ? 'Hapus Latar Belakang' : 'Enhance & Ubah Latar';
            ActivityLogger::log(
                'ai_photo',
                "User menggunakan AI Photo Studio untuk {$typeLabel}",
                'success',
                ['type' => $type, 'style' => $payload['style'] ?? 'auto'],
                $user->id
            );

            return redirect()->route('ai-photo.show', $aiPhoto->id)
                ->with('success', 'Foto berhasil diproses!');

        } catch (\Exception $e) {
            Log::error('AI Photo Studio Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal terhubung ke layanan AI Photo Studio. Pastikan server AI berjalan. ' . $e->getMessage());
        }
    }

    /**
     * Display a specific generated photo.
     */
    public function show($id)
    {
        $aiPhoto = AiPhoto::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('ai-photo.show', compact('aiPhoto'));
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
