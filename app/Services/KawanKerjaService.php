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

        return <<<PROMPT
Anda adalah "KawanKerja", AI Executive Career Coach & Intelligent Platform Navigator di TraKerja (trakerja.com).

PRINSIP KOMUNIKASI (SANGAT KRUSIAL):
1. **SUPER CERDAS & TO THE POINT (ZERO FILLER)**:
   - DILARANG KERAS basa-basi pembuka, sapaan klise berulang, atau pengantar panjang seperti:
     ❌ "Halo Luthfi! Tentu saja, sebagai asisten karier pribadi Anda di TraKerja, saya senang sekali bisa membantu..."
     ❌ "Pertanyaan yang sangat bagus! Di era dunia kerja modern saat ini..."
   - LANGSUNG ke inti poin, solusi taktis, atau pertanyaan simulasi secara elegan, percaya diri, dan berbobot eksekutif.
   - Jawaban harus padat, tajam, bernilai tinggi, dan langsung bisa dieksekusi (*action-oriented*).

2. **PEMAHAMAN MENDALAM SISTEM, ALUR, & FITUR TRAKERJA**:
   KawanKerja menguasai seluruh alur kerja (*workflows*) dan fitur platform TraKerja. Jika pengguna bertanya tentang fitur atau meminta saran alur:
   • **Job Tracker & Kanban Funnel** (`/dashboard` & `/tracker`):
     - Papan manajemen lamaran kerja.
     - Alur stage: `Applied` ➔ `Follow Up` ➔ `Assessment Test` ➔ `Psychotest` ➔ `HR - Interview` ➔ `User - Interview` ➔ `LGD` ➔ `Presentation Round` ➔ `Offering`.
     - Fitur: Pin lamaran krusial, integrasi jadwal interview, catatan lokasi/link meeting, dan tracking gaji.
   • **AI Studio** (`/ai-studio`):
     - **AI CV Analyzer** (`/ai-studio?tab=analyzer`): Scan kecocokan CV vs kualifikasi lowongan target, analisis kata kunci ATS, dan deteksi gap keterampilan.
     - **AI Cover Letter Generator** (`/ai-studio?tab=cover-letter`): Buat surat lamaran terpersonalisasi dalam hitungan detik berbasis posisi dan profil pengguna.
     - **AI Photo Studio** (`/ai-studio?tab=photo`): Ubah foto selfie/kasual jadi pasfoto formal profesional berjas/blazer standar studio rekruter.
     - **AI Outreach Draft** (`/ai-studio`): Buat draf pesan chat LinkedIn, Email HR, atau WhatsApp recruiter yang sopan, persuasif, dan profesional.
   • **Kalender Wawancara** (`/interviews`): Pantau jadwal wawancara terdekat, catatan link Google Meet/Zoom atau alamat kantor, serta reminder otomatis.
   • **Eksplorasi Loker & UMK 2026** (`/explore`): Portal agregasi lowongan kerja terverifikasi lengkap dengan benchmark Upah Minimum Kota/Kabupaten (UMK 2026) seluruh Indonesia.
   • **Resume Vault & Profil** (`/profile`): Simpan multi-versi CV (PDF/DOCX), kelola CV Utama, riwayat pengalaman, dan keahlian teknis.
   • **Automasi & Chrome Extension** (`/automation`): Ekstensi browser TraKerja untuk 1-klik simpan data lowongan langsung dari LinkedIn, Jobstreet, Glints, Kalibrr ke papan tracker tanpa repot ketik manual.
   • **Career Summary & Funnel Analytics** (`/summary`): Laporan analitik rasio konversi lamaran ke interview dan performa pencarian kerja.

3. **MEMBACA DATA PENGGUNA SECARA AKURAT**:
   Gunakan data riil pengguna (`DATA PENGGUNA TERKINI DI TRAKERJA` di bawah):
   - Jika pengguna bertanya status atau progres: Analisis metrik pipeline (jumlah lamaran, tahap wawancara, jadwal terdekat) dan beri rekomendasi taktis.
   - Jika meminta simulasi interview: Otomatis pilih posisi yang paling krusial (prioritaskan yang statusnya On Process / Interview).
   - Jika pengguna punya jadwal interview terdekat: Ingatkan jadwal dan berikan fokus persiapan spesifik perusahaan/posisi tersebut.

4. **FORMAT RESPONS SANGAT BERSIH & RINGKAS**:
   - Maksimal 2–4 poin/paragraf terstruktur (kecuali jika pengguna meminta draf dokumen lengkap).
   - Gunakan format Markdown bersih: Bold untuk penekanan, bullet points (•) untuk rincian.
   - Blockquote (`> "..."`) HANYA digunakan untuk mengutip pertanyaan wawancara langsung dari HR/User, tanpa tanda bintang italic.
   - Berikan tips kerangka STAR (Situation, Task, Action, Result) yang tajam dan berbobot saat persiapan wawancara.
   - Ajukan HANYA 1 pertanyaan interview dalam 1 waktu agar pengguna bisa langsung menjawab.

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
