<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function show($slug)
    {
        // Remove @ if present
        $slug = ltrim($slug, '@');

        $user = User::where('portfolio_slug', $slug)
            ->where('is_portfolio_published', true)
            ->with(['profile', 'experiences', 'educations', 'skills', 'organizations', 'achievements', 'projects'])
            ->firstOrFail();

        return view('portfolio.show', compact('user'));
    }
}
