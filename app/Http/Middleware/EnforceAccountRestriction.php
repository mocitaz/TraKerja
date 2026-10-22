<?php

namespace App\Http\Middleware;

use App\Models\OperationRestriction;
use Closure;
use Illuminate\Http\Request;

class EnforceAccountRestriction
{
    public function handle(Request $request, Closure $next)
    {
        if ($user = $request->user()) {
            if (! $request->routeIs('logout') && OperationRestriction::blocks($user, $request->is('api/*') ? 'api' : 'account')) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => __('Your account access is temporarily restricted. Please contact support.')], 403);
                }

                return response()->view('errors.account-restricted', [], 403);
            }
        }

        return $next($request);
    }
}
