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
use App\Support\AvatarCatalog;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $activities = $user->activities()->latest()->take(10)->get();
        $resumes = $user->resumes()->orderByDesc('is_primary')->latest()->get();

        return view('profile.edit', [
            'user' => $user,
            'activities' => $activities,
            'resumes' => $resumes,
            'membership' => $user->isAdmin() ? null : \App\Support\MembershipOverview::forUser($user),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        
        if (!$user->isAdmin()) {
            $user->auto_archive_rejected = $request->boolean('auto_archive_rejected');
        }

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
            'phone_number' => ['nullable', 'string', 'max:20'],
            'domicile'     => ['nullable', 'string', 'max:255'],
            'headline'     => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'website_url'  => ['nullable', 'url', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        // Update or create user profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone_number' => $validated['phone_number'] ?? null,
                'domicile'     => $validated['domicile'] ?? null,
                'headline'     => $validated['headline'] ?? null,
                'linkedin_url' => $validated['linkedin_url'] ?? null,
                'website_url'  => $validated['website_url'] ?? null,
                'bio'          => $validated['bio'] ?? null,
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

    public function updateAvatar(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate(['avatar_id' => ['required', 'string', Rule::in(array_keys(AvatarCatalog::all()))]]);
        $user = $request->user();
        $oldPhoto = $user->logo;
        $user->update(['avatar_id' => $validated['avatar_id'], 'logo' => null]);
        $this->deleteStoredPhoto($oldPhoto);

        ActivityLogger::log('profile_update', 'User memilih avatar profil', 'success', [], $user->id);

        return $request->expectsJson()
            ? response()->json(['success' => true, 'status' => 'avatar-updated', 'logo_url' => $user->avatar_url])
            : Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    public function updatePhoto(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate(['logo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']]);
        $user = $request->user();
        $oldPhoto = $user->logo;
        $path = $request->file('logo')->store('logos', 'public');
        abort_unless($path, 500, 'Could not store profile photo.');
        try {
            $user->update(['logo' => $path]);
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);
            throw $exception;
        }
        $this->deleteStoredPhoto($oldPhoto);
        ActivityLogger::log('profile_update', 'User mengubah foto profil', 'success', [], $user->id);

        return $request->expectsJson()
            ? response()->json(['success' => true, 'status' => 'photo-updated', 'logo_url' => $user->avatar_url])
            : Redirect::route('profile.edit')->with('status', 'photo-updated');
    }

    public function removePhoto(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $oldPhoto = $user->logo;
        $user->update(['logo' => null]);
        $this->deleteStoredPhoto($oldPhoto);

        return $request->expectsJson()
            ? response()->json(['success' => true, 'status' => 'photo-removed', 'logo_url' => $user->avatar_url])
            : Redirect::route('profile.edit')->with('status', 'photo-removed');
    }

    private function deleteStoredPhoto(?string $path): void
    {
        if ($path && str_starts_with($path, 'logos/') && !str_contains($path, '..')) {
            Storage::disk('public')->delete($path);
        }
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

        try {
            $user->getConnection()->transaction(function () use ($user) {
                $user->newQuery()->whereKey($user->id)->lockForUpdate()->firstOrFail();
                app(\App\Services\AccountResumeCleanup::class)->clean($user);
                $user->delete();
            });
        } catch (\Throwable $e) {
            \Log::error('Account deletion cleanup failed', ['user_id' => $user->id]);
            return back()->withErrors(['password' => 'Penghapusan akun belum selesai karena file belum dapat dibersihkan. Silakan coba lagi atau hubungi support.'], 'userDeletion');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
