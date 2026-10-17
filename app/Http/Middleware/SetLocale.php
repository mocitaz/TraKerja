<?php

namespace App\Http\Middleware;

use App\Support\AppLocale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $previous = app()->getLocale();
        $dateLocale = Carbon::getLocale();
        $locale = $request->user()?->getAttribute('locale');
        if (!AppLocale::valid($locale)) {
            $fallback = $request->cookie('locale', $request->hasSession() ? 'en' : ($request->getPreferredLanguage(['en', 'id']) ?? 'en'));
            $locale = $request->hasSession() ? $request->session()->get('locale', $fallback) : $fallback;
        }
        if (!AppLocale::valid($locale)) $locale = 'en';
        app()->setLocale($locale);
        Carbon::setLocale($locale);
        try {
            $response = $next($request);
            $response->headers->set('Content-Language', $locale);
            return $response;
        } finally {
            app()->setLocale($previous);
            Carbon::setLocale($dateLocale);
        }
    }
}
