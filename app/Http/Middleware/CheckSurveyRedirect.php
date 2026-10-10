<?php

namespace App\Http\Middleware;

use App\Models\SatisfactionSurvey;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSurveyRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $request->attributes->set('showSurveyInvitation', false);

        if ($request->isMethod('GET') && !$request->ajax() && !$request->pjax() && !$request->wantsJson()
            && $user && !$user->isAdmin()
            && !$request->is('auth/*', 'payment/*', 'premium', 'membership', 'admin*', 'livewire*', 'survey*')
            && !$request->routeIs('login', 'register', 'logout', 'logout.force', 'password.*', 'verification.*')
            && $user->created_at && $user->created_at->lte(now()->subDays(3))
            && Setting::get('survey_enabled', false)
            && (int) $request->session()->get('survey_deferred_until.'.$user->id, 0) <= now()->timestamp
            && !SatisfactionSurvey::where('user_id', $user->id)->exists()) {
            $request->attributes->set('showSurveyInvitation', true);
        }

        return $next($request);
    }
}
