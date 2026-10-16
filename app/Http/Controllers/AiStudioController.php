<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiStudioController extends Controller
{
    /**
     * Display the unified AI Studio dashboard.
     */
    public function index(Request $request)
    {
        // Redirect admin users
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }

        return app(\App\Support\AiStudioWorkspace::class)->render($request->user(), $request->query('task', $request->query('tab', 'analyzer')));
    }

    /**
     * Generate recruiter outreach using the Studio processing pipeline.
     */
    public function generateOutreach(Request $request)
    {
        return app(StudioRequestController::class)->submit($request, 'outreach');
    }
}
