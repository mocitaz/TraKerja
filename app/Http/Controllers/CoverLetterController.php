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
            // 4. Send POST request to Vercel API
            $response = Http::timeout(60) // 1 minute timeout
                ->post(self::VERCEL_API_URL, $aiPayload);

            if ($response->successful()) {
                $responseBody = $response->json();

                // Deduct credit only on successful generation
                if (\App\Models\Setting::isMonetizationEnabled()) {
                    $user->incrementCoverLetterCount();
                }

                $generatedContent = $responseBody['cover_letter'] ?? $responseBody['result'] ?? '';

                // Save to DB
                $coverLetter = \App\Models\CoverLetter::create([
                    'user_id' => $user->id,
                    'company_name' => $aiPayload['company_name'],
                    'job_title' => $aiPayload['job_title'],
                    'job_description' => $aiPayload['job_description'],
                    'language' => $aiPayload['language'],
                    'tone' => $aiPayload['tone'],
                    'content' => $generatedContent,
                ]);

                ActivityLogger::log(
                    'cover_letter',
                    "User menggunakan AI untuk membuat Cover Letter untuk {$aiPayload['company_name']} ({$aiPayload['job_title']})",
                    'success',
                    ['company' => $aiPayload['company_name'], 'role' => $aiPayload['job_title']],
                    $user->id
                );

                return response()->json([
                    'success' => true,
                    'cover_letter' => $generatedContent,
                    'id' => $coverLetter->id,
                    'payload_debug' => app()->environment('local') ? $aiPayload : null

                ]);
            }

            // Logging failure response
            \Log::error('AI Cover Letter Generation API Failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Fallback generation logic to prevent UI crashing while backend team sets up API
            $fallbackLetter = $this->generateLocalFallback($aiPayload);
            return response()->json([
                'success' => true,
                'cover_letter' => $fallbackLetter,
                'is_fallback' => true,
                'payload_debug' => $aiPayload
            ]);

        } catch (\Exception $e) {
            \Log::error('AI Cover Letter API Exception: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            // Return local fallback on exception as well, alongside debugging data
            $fallbackLetter = $this->generateLocalFallback($aiPayload);
            return response()->json([
                'success' => true,
                'cover_letter' => $fallbackLetter,
                'is_fallback' => true,
                'payload_debug' => $aiPayload
            ]);
        }
    }

    /**
     * Generate a high-fidelity local fallback cover letter matching the candidate's exact DB context.
     * This acts as an impressive offline mock and guides the AI model team on formatting.
     */
    private function generateLocalFallback(array $payload): string
    {
        $candidate = $payload['candidate_context'];
        $company = $payload['company_name'];
        $role = $payload['job_title'];
        $tone = $payload['tone'];
        $lang = $payload['language'];
        $focus = $payload['highlight_focus'];

        // Core dynamic sentences
        $skillsList = count($candidate['skills']) > 0
            ? implode(', ', array_slice(array_column($candidate['skills'], 'skill_name'), 0, 4))
            : 'various engineering principles and agile team practices';

        $latestExp = count($candidate['experiences']) > 0
            ? $candidate['experiences'][0]['position'] . ' at ' . $candidate['experiences'][0]['company_name']
            : 'experienced developer';

        if ($lang === 'id') {
            $letter = "Kepada Yth.\nTim Rekrutmen " . $company . "\n\n";

            if ($tone === 'creative') {
                $letter .= "Saya menulis surat ini dengan antusiasme yang tinggi untuk mengekspresikan ketertarikan saya pada posisi " . $role . " di " . $company . ". Melalui latar belakang saya sebagai " . $latestExp . ", saya sangat bersemangat untuk membawa energi segar dan dedikasi saya ke dalam tim Anda.\n\n";
                $letter .= "Keahlian saya mencakup " . $skillsList . ". Saya selalu berupaya menciptakan solusi kreatif yang melampaui ekspektasi konvensional. " . ($focus ? "Saya sangat senang memiliki kesempatan untuk memfokuskan kontribusi saya pada " . $focus . "." : "") . "\n\n";
                $letter .= "Saya mengagumi kultur inovatif di " . $company . " dan yakin kontribusi saya akan mempercepat pertumbuhan produk Anda. Terima kasih atas waktu dan pertimbangannya. Saya sangat menantikan kesempatan berdiskusi lebih lanjut.\n\n";
            } else if ($tone === 'bold') {
                $letter .= "Sebagai seorang profesional yang berorientasi pada pencapaian, saya sangat tertarik untuk melamar posisi " . $role . " di " . $company . ". Membawa rekam jejak yang solid sebagai " . $latestExp . ", fokus utama saya adalah menghasilkan metrik performa yang berdampak langsung bagi perusahaan Anda.\n\n";
                $letter .= "Melalui penguasaan " . $skillsList . ", saya terlatih untuk mengoptimalkan alur kerja dan memecahkan tantangan teknis yang rumit. " . ($focus ? "Mengacu pada preferensi Anda, saya siap memberikan fokus mendalam pada " . $focus . " untuk hasil maksimal." : "") . "\n\n";
                $letter .= "Saya percaya " . $company . " membutuhkan individu yang tidak hanya menyelesaikan pekerjaan, tetapi mendorong inovasi agresif. Saya siap membuktikannya dalam sesi wawancara.\n\n";
            } else { // Professional
                $letter .= "Melalui surat ini, saya bermaksud untuk mengajukan diri sebagai kandidat untuk posisi " . $role . " yang saat ini tersedia di " . $company . ". Berdasarkan pengalaman kerja saya sebagai " . $latestExp . ", saya meyakini kualifikasi saya relevan dengan kebutuhan bisnis Anda.\n\n";
                $letter .= "Saya memiliki keahlian teknis dalam " . $skillsList . " yang didukung oleh rekam jejak penyelesaian proyek secara akuntabel. " . ($focus ? "Saya juga akan memberikan perhatian khusus pada " . $focus . " sesuai kebutuhan taktis perusahaan." : "") . "\n\n";
                $letter .= "Besar harapan saya untuk mendapatkan kesempatan wawancara langsung guna memaparkan bagaimana kompetensi saya dapat bersinergi dengan visi jangka panjang " . $company . ". Terima kasih atas waktu dan perhatian Bapak/Ibu.";
            }

            $letter .= "\n\nHormat saya,\n" . $candidate['candidate_name'];
        } else { // English default
            $letter = "Dear Hiring Committee,\n\n";

            if ($tone === 'creative') {
                $letter .= "I am absolutely thrilled to submit my application for the " . $role . " position at " . $company . ". As a passion-driven professional with experience as a " . $latestExp . ", I am eager to combine my creative insights with your high-growth initiatives.\n\n";
                $letter .= "My core toolbox spans " . $skillsList . ", enabling me to build seamless interactive products and unique user solutions. " . ($focus ? "Furthermore, I am particularly excited about the chance to highlight my dedication to " . $focus . "." : "") . "\n\n";
                $letter .= "I admire " . $company . "'s forward-thinking brand and would love the opportunity to fuel your mission. Thank you for your valuable time and consideration. I look forward to connecting soon!\n\n";
            } else if ($tone === 'bold') {
                $letter .= "I am writing to express my strong interest in the " . $role . " role at " . $company . ". Backed by a solid career driving achievements as a " . $latestExp . ", I specialize in building metrics-driven systems that generate immediate value.\n\n";
                $letter .= "With expertise in " . $skillsList . ", I focus on transforming technical requirements into scalable, clean architectures. " . ($focus ? "I am especially prepared to apply my strengths toward " . $focus . " to accelerate your team's tactical goals." : "") . "\n\n";
                $letter .= "I look forward to discussing how my results-oriented mindset can benefit the growth of " . $company . " in our upcoming interview.\n\n";
            } else if ($tone === 'warm') {
                $letter .= "I wanted to personally reach out regarding the " . $role . " opening at " . $company . ". Having spent valuable time honing my skills as a " . $latestExp . ", I would love to bring my supportive nature and dedication to your incredible team.\n\n";
                $letter .= "I genuinely love working with " . $skillsList . " to solve human problems. " . ($focus ? "I would be delighted to focus my energy on " . $focus . " to support your ongoing team targets." : "") . "\n\n";
                $letter .= "It would mean a lot to chat and share more about my background. Thank you so much for reading this! Warmly,\n" . $candidate['candidate_name'];
                return $letter;
            } else { // Professional standard
                $letter .= "I am writing to express my formal interest in the " . $role . " position at " . $company . ". With a strong foundation established during my tenure as a " . $latestExp . ", I am confident in my ability to make a meaningful contribution to your organization.\n\n";
                $letter .= "My background includes hands-on experience in " . $skillsList . ", combined with a dedicated focus on quality delivery. " . ($focus ? "I plan to leverage my experience in " . $focus . " to support your company's strategic targets." : "") . "\n\n";
                $letter .= "Thank you for reviewing my application. I welcome the opportunity to discuss my qualifications with you in an interview.";
            }

            $letter .= "\n\nSincerely,\n" . $candidate['candidate_name'];
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

        return view('cover-letters.show', compact('coverLetter'));
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
