<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\User;
use App\Models\UserResume;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class KawanKerjaService
{
    protected ?string $apiKey;
    protected string $primaryModel;
    protected string $fallbackModel;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        $this->primaryModel = env('GEMINI_MODEL', 'gemini-3.5-flash-lite');
        $this->fallbackModel = 'gemini-3.8-flash';
    }

    /**
     * Send a user message to KawanKerja and return the reply.
     */
    public function chat(string $message, User $user, array $history = []): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'reply' => 'Maaf, layanan KawanKerja sedang belum terkonfigurasi API Key Gemini. Hubungi administrator.',
            ];
        }

        $context = $this->buildUserContext($user);
        $systemPrompt = $this->buildSystemPrompt($context);

        // Format conversation history for Gemini API
        $contents = [];
        foreach ($history as $item) {
            if (!empty($item['role']) && !empty($item['content'])) {
                $contents[] = [
                    'role' => $item['role'] === 'user' ? 'user' : 'model',
                    'parts' => [['text' => $item['content']]],
                ];
            }
        }

        // Append current message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]],
        ];

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.4,
                'maxOutputTokens' => 1000,
            ],
        ];

        // 1. Try Primary Model
        $response = $this->executeRequest($this->primaryModel, $payload);
        if ($response !== null) {
            return ['success' => true, 'reply' => $response];
        }

        // 2. Try Fallback Model
        $response = $this->executeRequest($this->fallbackModel, $payload);
        if ($response !== null) {
            return ['success' => true, 'reply' => $response];
        }

        // 3. Fallback generic error message
        return [
            'success' => false,
            'reply' => 'Maaf, KawanKerja sedang ramai atau mengalami kendala koneksi ke server AI. Silakan coba tanyakan kembali sebentar ya!',
        ];
    }

    /**
     * Compile rich, live context about the user's career state.
     */
    public function buildUserContext(User $user): array
    {
        $profile = $user->profile;

        $skills = $user->skills()->pluck('skill_name')->take(12)->toArray();

        // Top past experiences
        $experiences = $user->experiences()
            ->latest('start_date')
            ->take(3)
            ->get(['job_title', 'company_name', 'description'])
            ->map(fn($exp) => [
                'title' => $exp->job_title,
                'company' => $exp->company_name,
                'summary' => $exp->description ? \Illuminate\Support\Str::limit($exp->description, 120) : null,
            ])
            ->toArray();

        // Applications breakdown
        $appsQuery = JobApplication::where('user_id', $user->id)->where('is_archived', false);
        
        $totalActive = (clone $appsQuery)->count();
        $onProcessCount = (clone $appsQuery)->where('application_status', 'On Process')->count();
        $interviewCount = (clone $appsQuery)->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview', 'LGD', 'Presentation Round'])->count();
        $offeringCount = (clone $appsQuery)->where('recruitment_stage', 'Offering')->count();

        // Upcoming interview (future or within last 2 days)
        $upcomingInterviews = (clone $appsQuery)
            ->whereNotNull('interview_date')
            ->where('interview_date', '>=', now()->subDays(1))
            ->orderBy('interview_date')
            ->take(3)
            ->get(['company_name', 'position', 'interview_date', 'recruitment_stage', 'interview_type'])
            ->map(fn($app) => [
                'company' => $app->company_name,
                'position' => $app->position,
                'date' => $app->interview_date?->format('d M Y H:i'),
                'stage' => $app->recruitment_stage,
                'type' => $app->interview_type ?? 'Online/Offline',
            ])
            ->toArray();

        $activeApps = (clone $appsQuery)
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get(['company_name', 'position', 'location', 'application_status', 'recruitment_stage', 'interview_date', 'notes'])
            ->map(function ($app) {
                return [
                    'company' => $app->company_name,
                    'position' => $app->position,
                    'status' => $app->application_status,
                    'stage' => $app->recruitment_stage,
                    'interview_date' => $app->interview_date?->format('d M Y H:i') ?? null,
                ];
            })
            ->toArray();

        $primaryResume = UserResume::where('user_id', $user->id)
            ->where('is_primary', true)
            ->value('title');

        $totalResumes = UserResume::where('user_id', $user->id)->count();

        return [
            'user_name' => $user->name,
            'headline' => $profile?->headline,
            'domicile' => $profile?->domicile,
            'skills' => $skills,
            'experiences' => $experiences,
            'pipeline_summary' => [
                'total_active' => $totalActive,
                'on_process' => $onProcessCount,
                'in_interview_stages' => $interviewCount,
                'offering_received' => $offeringCount,
            ],
            'upcoming_interviews' => $upcomingInterviews,
            'active_applications' => $activeApps,
            'primary_resume' => $primaryResume,
            'saved_resumes_count' => $totalResumes,
        ];
    }

    protected function buildSystemPrompt(array $context): string
    {
        $contextJson = json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $userName = $context['user_name'] ?? 'Kawan';

        return <<<PROMPT
Anda adalah "KawanKerja", AI Executive Career Coach & Intelligent Platform Navigator di TraKerja (trakerja.com).

ATURAN RESPON BERDASARKAN INTENSI PENGGUNA (SANGAT KRUSIAL):

1. **JIKA PENGGUNA HANYA MENYAPA (misal: "hai", "halo", "selamat pagi", "pagi", "sore", "oi", "hey", dll.)**:
   - DILARANG KERAS langsung melempar pertanyaan interview, menodongkan simulasi wawancara, atau mendikte jadwal!
   - Jawab sapaan secara ramah, natural, dan ringkas (1-2 kalimat saja):
     Contoh: "Halo {$userName}! Siap bantu persiapan kariermu hari ini. Mau simulasi interview, cek status pipeline lamaran, atau butuh bantuan fitur TraKerja?"

2. **JIKA PENGGUNA MEMINTA SIMULASI / LATIHAN INTERVIEW**:
   - Ambil 1 posisi paling krusial dari pipeline pengguna (prioritaskan yang statusnya On Process / tahap Interview atau Assessment).
   - Tampilkan target ringkas:
     🎯 **Simulasi Interview: [Posisi]** • [Perusahaan]
   - Ajukan HANYA 1 pertanyaan wawancara realistis di dalam blockquote:
     > "[Teks pertanyaan wawancara langsung dari HR/User]"
   - Berikan tips kerangka berpikir singkat (STAR Method: Situation, Task, Action, Result).
   - Minta pengguna mengetik jawabannya. HANYA ajukan 1 pertanyaan per waktu!

3. **JIKA PENGGUNA MENJAWAB PERTANYAAN INTERVIEW**:
   - Berikan evaluasi to-the-point: apa yang sudah kuat, area perbaikan (misal: tambahkan metrik konkret), dan 1 contoh versi jawaban yang lebih impresif (*executive level*).
   - Setelah evaluasi, ajukan pertanyaan lanjutan berikutnya.

4. **JIKA PENGGUNA BERTANYA SEPUTAR FITUR / ALUR TRAKERJA**:
   - Jawab secara to-the-point dan sebutkan nama fitur serta menu halamannya:
     • **Job Tracker & Kanban** (`/tracker` & `/dashboard`): Papan funnel lamaran (Applied ➔ Follow Up ➔ Assessment ➔ Interview ➔ Offering).
     • **AI Studio** (`/ai-studio`): CV Analyzer (`tab=analyzer`), Cover Letter (`tab=cover-letter`), Pasfoto Formal AI (`tab=photo`), Draf Outreach HR.
     • **Kalender Wawancara** (`/interviews`): Pantau jadwal wawancara & reminder meeting.
     • **Eksplorasi Loker & UMK** (`/explore`): Portal loker terverifikasi & referensi gaji UMK 2026.
     • **Chrome Extension** (`/automation`): 1-klik simpan loker dari LinkedIn/Jobstreet langsung ke tracker.
     • **Resume Vault & Profil** (`/profile`): Kelola multi-versi CV dan portofolio.
     • **Analitik Funnel** (`/summary`): Rasio konversi lamaran ke interview.

5. **JIKA PENGGUNA BERTANYA PROGRESS / PIPELINE**:
   - Baca data `pipeline_summary` dan `upcoming_interviews` di bawah, rangkum dengan jelas (jumlah lamaran aktif, jadwal terdekat), lalu sarankan aksi taktis berikutnya.

6. **GAYA BAHASA & FORMAT**:
   - Cerdas, to the point, ramah, dan tidak bertele-tele.
   - Gunakan format Markdown bersih (bold, bullet points •, dan blockquote > "..." hanya jika mengutip pertanyaan wawancara langsung).

DATA PENGGUNA TERKINI DI TRAKERJA:
{$contextJson}

BATASAN DOMAIN:
Fokus eksklusif pada karier, persiapan interview, sistem TraKerja, review CV/jawaban, dan rekrutmen. Tolak topik di luar karier secara sopan dan singkat.
PROMPT;
    }

    protected function executeRequest(string $model, array $payload): ?string
    {
        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}";
            $res = Http::timeout(12)->post($url, $payload);

            if ($res->successful()) {
                $parts = $res->json('candidates.0.content.parts') ?? [];
                $text = '';
                foreach ($parts as $part) {
                    if (isset($part['text'])) {
                        $text .= $part['text'];
                    }
                }

                $clean = trim($text);
                return !empty($clean) ? $clean : null;
            }

            Log::warning("Gemini model {$model} response status {$res->status()}: " . $res->body());
        } catch (Throwable $e) {
            Log::warning("Gemini model {$model} error: " . $e->getMessage());
        }

        return null;
    }
}
