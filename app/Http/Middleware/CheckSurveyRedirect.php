<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use App\Models\SatisfactionSurvey;

class CheckSurveyRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Only enforce for normal users (non-admins)
            if (!$user->isAdmin()) {
                
                // Check if survey is globally enabled
                if (Setting::get('survey_enabled', false)) {
                    
                    // Check if they have already completed the survey
                    $hasCompleted = SatisfactionSurvey::where('user_id', $user->id)->exists();

                    if (!$hasCompleted) {
                        // Exempt routes to prevent infinite redirection loops
                        $exemptRoutes = [
                            'survey.show',
                            'survey.submit',
                            'logout',
                            'logout.force',
                        ];

                        $isExemptRoute = $request->routeIs($exemptRoutes);
                        $isAdminPath = $request->is('admin*');
                        $isWebhookPath = $request->is('payment/webhook*');
                        $isLivewireAsset = $request->is('livewire*');

                        if (!$isExemptRoute && !$isAdminPath && !$isWebhookPath && !$isLivewireAsset) {
                            return redirect()->route('survey.show');
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
