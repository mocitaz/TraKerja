<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        abort_if($request->user()->isAdmin(), 403);

        return redirect()->route('profile.edit', [
            'tab' => 'membership',
            'page' => max(1, (int) $request->query('page', 1)),
        ]);
    }
}
