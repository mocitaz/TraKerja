<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    private function loadContent(User $user): User
    {
        $relations = ['profile'];
        foreach (['experiences', 'educations', 'skills', 'organizations', 'achievements', 'projects'] as $relation) {
            $relations[$relation] = fn ($query) => $query->orderBy('display_order')->orderBy('id');
        }
        return $user->load($relations);
    }

    public function show(string $slug)
    {
        $user = User::where('portfolio_slug', ltrim($slug, '@'))->where('is_portfolio_published', true)->firstOrFail();
        $this->loadContent($user);
        $preview = false;
        $theme = in_array($user->portfolio_theme, ['slate', 'dark', 'emerald', 'violet'], true) ? $user->portfolio_theme : 'slate';
        return view('portfolio.show', compact('user', 'preview', 'theme'));
    }

    public function preview(Request $request)
    {
        $user = $this->loadContent($request->user()->fresh());
        $preview = true;
        $theme = $request->input('theme', $user->portfolio_theme ?? 'slate');
        abort_unless(in_array($theme, ['slate', 'dark', 'emerald', 'violet'], true), 422);
        return response()->view('portfolio.show', compact('user', 'preview', 'theme'))->header('Cache-Control', 'private, no-store');
    }
}
