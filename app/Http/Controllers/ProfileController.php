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

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's personal information.
     */
    public function updatePersonalInfo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        // Update user fields directly
        $user->update([
            'phone' => $validated['phone'] ?? null,
            'location' => $validated['location'] ?? null,
            'linkedin' => $validated['linkedin'] ?? null,
            'website' => $validated['website'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        return Redirect::route('profile.edit')->with('status', 'personal-info-updated');
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
    public function updatePhoto(Request $request): RedirectResponse
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

        return Redirect::route('profile.edit')->with('status', 'photo-updated');
    }

    /**
     * Remove profile photo
     */
    public function removePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->logo) {
            \Storage::disk('public')->delete($user->logo);
            $user->update(['logo' => null]);
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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
