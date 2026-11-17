<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Serves a vacancy's company logo from our own origin so the pamphlet canvas can draw and export it
 * (cross-origin images would taint the canvas). Admin only; only http(s) image responses up to 2 MB.
 */
class PromotionAssetController extends Controller
{
    public function logo(Vacancy $vacancy)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $url = (string) $vacancy->company?->logo_url;
        abort_unless(preg_match('#^https?://#i', $url), 404);

        $image = Cache::remember('promo-logo:'.md5($url), now()->addDay(), function () use ($url) {
            $response = rescue(fn () => Http::timeout(8)->withUserAgent(config('job_sources.user_agent'))->get($url), null, false);
            $type = (string) $response?->header('Content-Type');
            if (! $response?->successful() || ! str_starts_with($type, 'image/') || strlen($response->body()) > 2_000_000) {
                return null;
            }

            return ['type' => $type, 'body' => base64_encode($response->body())];
        });
        abort_unless($image, 404);

        return response(base64_decode($image['body']), 200, ['Content-Type' => $image['type'], 'Cache-Control' => 'private, max-age=86400']);
    }
}
