<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityLogger;

class ExtensionController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }

        $token = $user->createToken('extension_token')->plainTextToken;

        ActivityLogger::log(
            'extension_login',
            "User login melalui Chrome Extension",
            'success',
            [],
            $user->id
        );

        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'is_premium' => $user->isPremium()
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function saveJob(Request $request)
    {
        $user = $request->user();
        if (\App\Models\Setting::isMonetizationEnabled() && !$user->isPremium()) {
            return response()->json([
                'message' => 'Menyimpan lowongan via Chrome Extension adalah fitur Premium. Silakan upgrade akun Anda.'
            ], 403);
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'career_level' => 'required|string',
            'platform' => 'required|string',
            'platform_link' => 'required|url',
        ]);

        try {
            $job = JobApplication::create([
                'user_id' => $request->user()->id,
                'company_name' => $request->company_name,
                'position' => $request->position,
                'location' => $request->location,
                'platform' => $request->platform,
                'application_status' => 'On Process',
                'recruitment_stage' => 'Applied',
                'career_level' => $request->career_level,
                'platform_link' => $request->platform_link,
                'application_date' => now(),
                'notes' => $request->notes ?? 'Disimpan via Chrome Extension',
            ]);

            ActivityLogger::log(
                'extension_job_save',
                "User menyimpan lowongan ({$request->company_name}) dari Chrome Extension",
                'success',
                ['company' => $request->company_name, 'platform' => $request->platform],
                $request->user()->id
            );

            return response()->json([
                'message' => 'Lamaran berhasil disimpan!',
                'job' => $job
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Error saving job from extension: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal menyimpan lamaran. ' . $e->getMessage()
            ], 500);
        }
    }
}
