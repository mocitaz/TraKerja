<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Services\ActivityLogger;

class CoverLetterController extends Controller
{
    private const VERCEL_API_URL = 'https://ai-analyzer-seven.vercel.app/generate-cl';

    /**
     * Display the Cover Letter Generator landing page.
     */
    public function index(): View
    {
        // Redirect admin users
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }

        $user = Auth::user();
        $skills = $user ? $user->skills()->ordered()->take(5)->get() : collect();
        $experiences = $user ? $user->experiences()->ordered()->take(3)->get() : collect();
        $projects = $user ? $user->projects()->ordered()->take(3)->get() : collect();

        $isPremium = $user ? $user->isPremium() : false;
        $remainingUses = $user ? $user->getRemainingCoverLetter() : 3;

        return view('cover-letters.index', compact('skills', 'experiences', 'projects', 'isPremium', 'remainingUses'));
    }

    /**
     * Generate Cover Letter with AI by merging user profile and job requirements.
     */
    public function generate(Request $request): JsonResponse
    {
        // Validate request
        $request->validate([
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string|min:50|max:2500',
            'language' => 'required|string|in:en,id',
            'tone' => 'required|string|in:professional,creative,bold,warm',
            'length' => 'required|string|in:standard,short',
            'highlight_focus' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Check if monetization is enabled and credits are depleted
        if (\App\Models\Setting::isMonetizationEnabled()) {
            if (!$user->canAccessCoverLetterWithLimit()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kredit Cover Letter habis! Silakan upgrade ke Premium atau lakukan top up.'
                ], 403);
            }
        }

        // 1. Gather all profile context from Laravel DB Relations
        $profile = $user->profile;

        $experiences = $user->experiences()->ordered()->get()->map(function ($exp) {
            return [
                'company_name' => $exp->company_name,
                'position' => $exp->position,
                'employment_type' => $exp->employment_type,
                'location' => $exp->location,
                'duration' => $exp->duration ?? '',
                'is_current' => $exp->is_current,
                'description' => $exp->description,
            ];
        })->toArray();

        $educations = $user->educations()->ordered()->get()->map(function ($edu) {
            return [
                'institution_name' => $edu->institution_name,
                'degree' => $edu->degree,
                'major' => $edu->major,
                'gpa' => $edu->gpa,
                'location' => $edu->location,
                'is_current' => $edu->is_current,
            ];
        })->toArray();

        $skills = $user->skills()->ordered()->get()->map(function ($skill) {
            return [
                'skill_name' => $skill->skill_name,
                'category' => $skill->category,
                'proficiency_level' => $skill->proficiency_level,
                'years_of_experience' => $skill->years_of_experience,
            ];
        })->toArray();

        $projects = $user->projects()->ordered()->get()->map(function ($proj) {
            return [
                'project_name' => $proj->project_name,
                'role' => $proj->role,
                'description' => $proj->description,
                'technologies' => $proj->technologies,
            ];
        })->toArray();

        // 2. Construct unified background payload matching database structure
        $candidateContext = [
            'candidate_name' => $user->name,
            'candidate_email' => $user->email,
            'personal_profile' => [
                'phone' => $profile->phone_number ?? '',
                'domicile' => $profile->domicile ?? '',
                'linkedin_url' => $profile->linkedin_url ?? '',
                'portfolio_url' => $profile->website_url ?? '',
                'bio' => $profile->bio ?? '',
            ],
            'experiences' => $experiences,
            'educations' => $educations,
            'skills' => $skills,
            'projects' => $projects,
        ];

        // 3. Construct the exact JSON payload going to the AI backend
        $aiPayload = [
            'company_name' => $request->input('company_name'),
            'job_title' => $request->input('job_title'),
            'job_description' => $request->input('job_description'),
            'language' => $request->input('language'),
            'tone' => $request->input('tone'),
            'length' => $request->input('length'),
            'highlight_focus' => $request->input('highlight_focus'),
            'candidate_context' => $candidateContext,
        ];

        // Log outgoing payload for the backend / AI model team to monitor
        \Log::info('AI Cover Letter Outgoing Payload', [
            'user_id' => $user->id,
            'company_name' => $aiPayload['company_name'],
            'job_title' => $aiPayload['job_title'],
            'candidate_context_summary' => [
                'experiences_count' => count($experiences),
                'skills_count' => count($skills),
                'projects_count' => count($projects)
            ]
        ]);

        try {
            $response = Http::timeout(60)->post(self::VERCEL_API_URL, $aiPayload);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->fallbackResponse($aiPayload);
        }
        $generatedContent = $response->json('cover_letter') ?? $response->json('result');
        if (!$response->successful() || !is_string($generatedContent) || trim($generatedContent) === '') {
            return $this->fallbackResponse($aiPayload);
        }
        $coverLetter = \Illuminate\Support\Facades\DB::transaction(function () use ($user, $aiPayload, $generatedContent) {
            $locked = \App\Models\User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            if (\App\Models\Setting::isMonetizationEnabled() && !$locked->canAccessCoverLetterWithLimit()) {
                abort(403, 'Cover letter credits are no longer available.');
            }
            $letter = \App\Models\CoverLetter::create([
                'user_id' => $locked->id, 'company_name' => $aiPayload['company_name'],
                'job_title' => $aiPayload['job_title'], 'job_description' => $aiPayload['job_description'],
                'language' => $aiPayload['language'], 'tone' => $aiPayload['tone'], 'content' => $generatedContent,
            ]);
            if (\App\Models\Setting::isMonetizationEnabled()) $locked->incrementCoverLetterCount();
            return $letter;
        });
        try {
            ActivityLogger::log('cover_letter', 'User generated a cover letter', 'success', ['company' => $aiPayload['company_name'], 'role' => $aiPayload['job_title']], $user->id);
        } catch (\Throwable $exception) {
            report($exception);
        }
        return response()->json(['success' => true, 'cover_letter' => $generatedContent, 'id' => $coverLetter->id]);
    }

    private function fallbackResponse(array $payload): JsonResponse
    {
        return response()->json(['success' => true, 'cover_letter' => $this->generateLocalFallback($payload), 'is_fallback' => true]);
    }

    /**
     * Create an unsaved template from the supplied role and actual profile skills.
     */
    private function generateLocalFallback(array $payload): string
    {
        $candidate = $payload['candidate_context'];
        $skills = implode(', ', array_slice(array_column($candidate['skills'], 'skill_name'), 0, 4));
        if ($payload['language'] === 'id') {
            $letter = "Kepada Tim Rekrutmen {$payload['company_name']},\n\nSaya tertarik untuk melamar posisi {$payload['job_title']}.";
            if ($skills !== '') $letter .= " Keahlian yang tercatat dalam profil saya mencakup {$skills}.";
            $letter .= "\n\nSaya berharap dapat mendiskusikan kebutuhan posisi ini dan latar belakang saya lebih lanjut. Terima kasih atas waktu dan pertimbangannya.\n\nHormat saya,\n".$candidate['candidate_name'];
        } else {
            $letter = "Dear Hiring Team at {$payload['company_name']},\n\nI am interested in applying for the {$payload['job_title']} position.";
            if ($skills !== '') $letter .= " My saved profile lists experience with {$skills}.";
            $letter .= "\n\nI would welcome the opportunity to discuss the role requirements and my background. Thank you for your time and consideration.\n\nSincerely,\n".$candidate['candidate_name'];
        }
        return $letter;
    }
    /**
     * Display a specific Cover Letter result
     */
    public function show(\App\Models\CoverLetter $coverLetter): View
    {
        // Check if user owns this result
        if ($coverLetter->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to cover letter');
        }

        return app(\App\Support\AiStudioWorkspace::class)->render(Auth::user(), 'cover-letter', $coverLetter);
    }

    /**
     * Display user's Cover Letter history
     */
    public function history(): View
    {
        $user = Auth::user();
        $coverLetters = $user->coverLetters()->orderBy('created_at', 'desc')->paginate(10);

        return view('cover-letters.history', compact('coverLetters'));
    }
}
