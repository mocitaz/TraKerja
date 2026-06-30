<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Services\ActivityLogger;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $activities = $user->activities()->latest()->take(10)->get();

        return view('profile.edit', [
            'user' => $user,
            'activities' => $activities,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        
        $user->auto_archive_rejected = $request->has('auto_archive_rejected') ? $request->boolean('auto_archive_rejected') : false;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        ActivityLogger::log(
            'profile_update',
            "User memperbarui informasi akun dasar (Nama/Email)",
            'success',
            [],
            $request->user()->id
        );
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => 'profile-updated']);
        }
        
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's personal information.
     */
    public function updatePersonalInfo(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        // Update or create user profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone_number' => $validated['phone'] ?? null,
                'domicile' => $validated['location'] ?? null,
                'linkedin_url' => $validated['linkedin'] ?? null,
                'website_url' => $validated['website'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ]
        );

        ActivityLogger::log(
            'profile_update',
            "User memperbarui detail profil personal (Telepon/Bio/Lokasi)",
            'success',
            [],
            $user->id
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => 'personal-info-updated']);
        }

        return Redirect::route('profile.edit')->with('status', 'personal-info-updated');
    }

    /**
     * Update the user's notification preferences.
     */
    public function updateNotifications(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        // Check if user has premium access to notifications (if restricted)
        if (!$user->canAccessEmailNotifications()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Fitur ini hanya tersedia untuk pengguna Premium.'], 403);
            }
            return Redirect::route('profile.edit')->with('error', 'upgrade-required');
        }

        $user->update([
            'email_notifications_enabled' => $request->boolean('email_notifications_enabled'),
            'notify_goal_reminders' => $request->boolean('notify_goal_reminders'),
            'notify_interview_reminders' => $request->boolean('notify_interview_reminders'),
            'notify_goal_achieved' => $request->boolean('notify_goal_achieved'),
        ]);

        ActivityLogger::log(
            'profile_update',
            "User memperbarui preferensi notifikasi email",
            'success',
            [],
            $user->id
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => 'notifications-updated']);
        }

        return Redirect::route('profile.edit')->with('status', 'notifications-updated');
    }

    /**
     * Verify the user's current password.
     */
    public function verifyPassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
        ]);

        $user = $request->user();
        $isValid = Hash::check($request->current_password, $user->password);

        return response()->json([
            'valid' => $isValid,
            'message' => $isValid ? 'Password is correct' : 'Password is incorrect'
        ]);
    }

    /**
     * Update profile photo
     */
    public function updatePhoto(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = $request->user();

        // Delete old photo if exists
        if ($user->logo && \Storage::disk('public')->exists($user->logo)) {
            \Storage::disk('public')->delete($user->logo);
        }

        // Store new photo
        $logoPath = $request->file('logo')->store('logos', 'public');
        $user->update(['logo' => $logoPath]);

        ActivityLogger::log(
            'profile_update',
            "User mengubah foto profil",
            'success',
            [],
            $user->id
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => 'photo-updated', 'message' => 'Profile photo updated successfully']);
        }

        return Redirect::route('profile.edit')->with('status', 'photo-updated');
    }

    /**
     * Remove profile photo
     */
    public function removePhoto(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        if ($user->logo) {
            \Storage::disk('public')->delete($user->logo);
            $user->update(['logo' => null]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => 'photo-removed', 'message' => 'Profile photo removed successfully']);
        }

        return Redirect::route('profile.edit')->with('status', 'photo-removed');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        ActivityLogger::log(
            'account_delete',
            "User menghapus akun secara permanen",
            'success',
            [],
            $user->id
        );

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
