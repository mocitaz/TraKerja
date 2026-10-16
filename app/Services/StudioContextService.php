<?php

namespace App\Services;

use App\Models\{JobApplication, UserResume};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class StudioContextService
{
    public function prepare(Request $request, string $task): array
    {
        abort_if($request->user()->isAdmin(), 403);
        $user = $request->user();
        $data = $request->validate([
            'request_key' => 'required|uuid',
            'application_id' => ['nullable', 'integer', Rule::exists('job_applications', 'id')->where('user_id', $user->id)],
            'resume_id' => ['nullable', 'integer', Rule::exists('user_resumes', 'id')->where('user_id', $user->id)],
            'resume' => 'nullable|file|mimes:pdf|max:10240',
            'company_name' => 'nullable|string|max:255', 'target_company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255', 'job_description' => 'nullable|string|max:10000',
            'language' => 'nullable|in:en,id', 'tone' => 'nullable|string|in:professional,creative,bold,warm,Professional,Friendly,Persuasive',
            'length' => 'nullable|in:standard,short', 'highlight_focus' => 'nullable|string|max:1000',
            'recruiter_name' => 'nullable|string|max:100', 'channel' => 'nullable|in:LinkedIn InMail,Email,Direct Message',
            'purpose' => 'nullable|in:introduction,follow-up,thank-you',
        ]);
        $application = !empty($data['application_id']) ? JobApplication::where('user_id', $user->id)->findOrFail($data['application_id']) : null;
        $data['company_name'] = trim($data['company_name'] ?? $data['target_company'] ?? '') ?: ($application?->company_name ?? '');
        $data['job_title'] = trim($data['job_title'] ?? '') ?: ($application?->position ?? '');
        $data['job_description'] = trim($data['job_description'] ?? '');
        if ($task !== 'analyzer' && (!$data['company_name'] || !$data['job_title'])) throw ValidationException::withMessages(['company_name' => 'Choose an application or provide the company and role.']);
        if ($task !== 'outreach' && mb_strlen($data['job_description']) < 50) throw ValidationException::withMessages(['job_description' => 'Add at least 50 characters of actual job requirements. Tracker notes are not used as the job description.']);
        $resume = !empty($data['resume_id']) ? UserResume::where('user_id', $user->id)->findOrFail($data['resume_id']) : null;
        if ($resume && (!$resume->is_pdf || !$this->validPath($resume->file_path, $user->id) || !Storage::disk('local')->exists($resume->file_path)
            || Storage::disk('local')->size($resume->file_path) > 10 * 1024 * 1024)) throw ValidationException::withMessages(['resume_id' => 'This saved resume is unavailable or is not a PDF under 10 MB. Upload a PDF instead.']);
        if ($task === 'analyzer' && !$request->hasFile('resume') && !$resume) throw ValidationException::withMessages(['resume' => 'Upload a PDF or choose a saved PDF resume.']);
        $profile = $user->profile;
        $candidate = ['name' => $user->name, 'bio' => $profile?->bio,
            'experiences' => $user->experiences()->ordered()->limit(20)->get(['company_name', 'position', 'description'])->toArray(),
            'education' => $user->educations()->ordered()->limit(10)->get(['institution_name', 'degree', 'major'])->toArray(),
            'skills' => $user->skills()->ordered()->limit(80)->pluck('skill_name')->all(),
            'projects' => $user->projects()->ordered()->limit(15)->get(['project_name', 'role', 'description', 'technologies'])->toArray(),
        ];
        unset($data['request_key'], $data['resume'], $data['resume_id'], $data['application_id'], $data['target_company']);
        $data += ['language' => $task === 'analyzer' ? 'id' : 'en', 'tone' => 'professional', 'length' => 'standard', 'purpose' => 'introduction', 'channel' => 'Email'];
        $data['candidate'] = $candidate;
        $data['application'] = $application ? ['company' => $application->company_name, 'role' => $application->position,
            'stage' => $application->recruitment_stage, 'status' => $application->application_status] : null;
        // The attached CV is authoritative for analysis; profile facts are only writing context.
        if ($task === 'analyzer') unset($data['candidate']);
        return ['payload' => $data, 'file' => $request->file('resume'), 'resume' => $resume];
    }

    private function validPath(string $path, int $userId): bool
    {
        return str_starts_with($path, 'resumes/'.$userId.'/') && !str_contains($path, '..') && !str_contains($path, '\\');
    }
}
