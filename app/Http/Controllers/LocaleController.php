<?php

namespace App\Http\Controllers;

use App\Support\AppLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class LocaleController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate(['locale' => ['required', Rule::in(array_keys(AppLocale::OPTIONS))]]);
        $locale = $data['locale'];
        if ($request->user() && Schema::hasColumn('users', 'locale')) {
            $request->user()->forceFill(['locale' => $locale])->save();
        }
        $request->session()->put('locale', $locale);
        $target = (string) $request->input('return_to', '/');
        if (!str_starts_with($target, '/') || str_starts_with($target, '//') || preg_match('/[\\\\\r\n]/', $target)) $target = '/';
        $response = $request->expectsJson() ? response()->json(['success' => true, 'locale' => $locale]) : redirect()->to($target);
        return $response->withCookie(cookie('locale', $locale, 525600, '/', null, $request->isSecure(), true, false, 'lax'));
    }
}
