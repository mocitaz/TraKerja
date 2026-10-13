<?php

namespace App\Http\Controllers;

use App\Models\UserResume;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserResumeController extends Controller
{
    /**
     * Store a newly uploaded resume.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'resume_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'title' => 'nullable|string|max:150',
            'is_primary' => 'nullable|boolean',
        ], [
            'resume_file.required' => 'Please select a resume file to upload.',
            'resume_file.mimes' => 'Resume format must be PDF, DOC, or DOCX.',
            'resume_file.max' => 'Maximum file size is 10 MB.',
        ]);

        $user = Auth::user();
        $file = $request->file('resume_file');

        $originalName = $file->getClientOriginalName();
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $title = trim($request->input('title') ?? '') ?: $baseName;

        // If user has no existing resumes, make this one primary automatically
        $existingCount = $user->resumes()->count();
        $isPrimary = $request->boolean('is_primary') || ($existingCount === 0);

        if ($isPrimary && $existingCount > 0) {
            $user->resumes()->update(['is_primary' => false]);
        }

        // Store file privately in storage/app/resumes/{user_id}
        $storedPath = $file->store('resumes/' . $user->id, 'local');

        $resume = $user->resumes()->create([
            'title' => $title,
            'file_path' => $storedPath,
            'file_name' => $originalName,
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientMimeType() ?: 'application/octet-stream',
            'is_primary' => $isPrimary,
        ]);

        ActivityLogger::log(
            'resume_upload',
            "Uploaded resume file: {$title} ({$resume->formatted_size})",
            'success',
            ['resume_id' => $resume->id],
            $user->id
        );

        return redirect()->route('profile.edit', ['tab' => 'resumes'])
            ->with('status', 'resume-uploaded');
    }

    /**
     * Download the resume file.
     */
    public function download(UserResume $resume): StreamedResponse|BinaryFileResponse
    {
        abort_unless($resume->user_id === Auth::id(), 403);
        abort_unless(Storage::disk('local')->exists($resume->file_path), 404);

        return Storage::disk('local')->download($resume->file_path, $resume->file_name);
    }

    /**
     * Stream the resume file for inline browser preview.
     */
    public function preview(UserResume $resume): BinaryFileResponse
    {
        abort_unless($resume->user_id === Auth::id(), 403);
        abort_unless(Storage::disk('local')->exists($resume->file_path), 404);

        $path = Storage::disk('local')->path($resume->file_path);

        return response()->file($path, [
            'Content-Type' => $resume->file_type ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . addslashes($resume->file_name) . '"',
        ]);
    }

    /**
     * Update resume details (e.g., label/title).
     */
    public function update(Request $request, UserResume $resume): RedirectResponse
    {
        abort_unless($resume->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
        ]);

        $resume->update([
            'title' => trim($validated['title']),
        ]);

        return redirect()->route('profile.edit', ['tab' => 'resumes'])
            ->with('status', 'resume-updated');
    }

    /**
     * Mark this resume as the primary version.
     */
    public function setPrimary(UserResume $resume): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($resume->user_id === $user->id, 403);

        $user->resumes()->where('id', '!=', $resume->id)->update(['is_primary' => false]);
        $resume->update(['is_primary' => true]);

        return redirect()->route('profile.edit', ['tab' => 'resumes'])
            ->with('status', 'resume-primary-updated');
    }

    /**
     * Delete the resume.
     */
    public function destroy(UserResume $resume): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($resume->user_id === $user->id, 403);

        $wasPrimary = $resume->is_primary;
        $title = $resume->title;

        $resume->delete();

        // If deleted resume was primary, designate the latest remaining one as primary
        if ($wasPrimary) {
            $user->resumes()->latest()->first()?->update(['is_primary' => true]);
        }

        ActivityLogger::log(
            'resume_delete',
            "Deleted resume file: {$title}",
            'success',
            [],
            $user->id
        );

        return redirect()->route('profile.edit', ['tab' => 'resumes'])
            ->with('status', 'resume-deleted');
    }
}
