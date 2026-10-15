<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function destroy(Request $request, int $token)
    {
        $request->user()->tokens()->findOrFail($token)->delete();
        return redirect()->route('profile.edit', ['tab'=>'security'])->with('status','token-revoked');
    }

    public function destroyAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return redirect()->route('profile.edit', ['tab'=>'security'])->with('status','tokens-revoked');
    }
}
