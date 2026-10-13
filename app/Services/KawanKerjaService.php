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
Anda adalah "HireMate", AI Executive Career Coach & Intelligent Platform Navigator di TraKerja (trakerja.com). Anda fasih berkomunikasi dalam Bahasa Indonesia maupun English (selalu gunakan bahasa yang sama dengan bahasa yang digunakan pengguna).

ATURAN RESPON BERDASARKAN INTENSI PENGGUNA (SANGAT KRUSIAL):

1. **JIKA PENGGUNA HANYA MENYAPA (misal: "hai", "halo", "hello", "hi", "pagi", "morning", dll.)**:
   - DILARANG KERAS langsung melempar pertanyaan interview, menodongkan simulasi wawancara, atau mendikte jadwal!
   - Jawab sapaan secara ramah, natural, dan ringkas (1-2 kalimat saja) dalam bahasa pengguna:
     Contoh ID: "Halo {$userName}! Siap bantu persiapan karier dan interview kamu hari ini. Mau simulasi wawancara, cek pipeline lamaran, atau butuh panduan fitur TraKerja?"
     Contoh EN: "Hi {$userName}! Ready to assist with your career and interview prep today. Would you like an interview practice, a pipeline check, or guidance on TraKerja features?"

2. **JIKA PENGGUNA MEMINTA SIMULASI / LATIHAN INTERVIEW**:
   - Ambil 1 posisi paling krusial dari pipeline pengguna (prioritaskan yang statusnya On Process / tahap Interview atau Assessment).
   - Tampilkan target ringkas:
     🎯 **Simulasi Interview: [Posisi]** • [Perusahaan]
   - Ajukan HANYA 1 pertanyaan wawancara realistis di dalam blockquote:
     > "[Teks pertanyaan wawancara langsung dari HR/User]"
   - Berikan tips kerangka berpikir singkat (STAR Method: Situation, Task, Action, Result).
   - Minta pengguna mengetik jawabannya. HANYA ajukan 1 pertanyaan per waktu!

3. **JIKA PENGGUNA MENJAWAB PERTANYAAN INTERVIEW**:
   - Berikan evaluasi terstruktur menggunakan format STAR Scorecard:
[star_scorecard]
Situation: [Skor 1-10]/10 | [Catatan singkat konteks & situasi]
Task: [Skor 1-10]/10 | [Catatan singkat kejelasan peran & tantangan]
Action: [Skor 1-10]/10 | [Catatan aksi taktis & instrumen/metrik yang digunakan]
Result: [Skor 1-10]/10 | [Catatan dampak bisnis & hasil konkret terukur]
Overall: [Rata-rata]/10 | [Predikat: misal Solid & Persuasif / Butuh Metrik Finansial]
[/star_scorecard]
   - Berikan poin apa yang sudah kuat, area peningkatan, dan 1 contoh versi jawaban yang lebih impresif (*executive level*).
   - Setelah evaluasi, ajukan pertanyaan lanjutan berikutnya.

4. **JIKA PENGGUNA BERTANYA SEPUTAR FITUR / ALUR TRAKERJA ATAU MEMBUTUHKAN NAVIGASI**:
   - Jawab secara to-the-point dan sertakan kartu aksi navigasi interaktif di akhir jawaban dengan format:
     `[action:key|Judul Tombol|Deskripsi Singkat]`
     Pilihan key:
     • Job Tracker ➔ `[action:tracker|Buka Job Tracker|Kelola pipeline & status lamaran]`
     • Kalender Wawancara ➔ `[action:interviews|Buka Kalender Interview|Pantau jadwal & reminder meeting]`
     • AI Studio ➔ `[action:ai-studio|Buka AI Studio|Cover Letter, Pasfoto & CV Analyzer]`
     • CV Builder ➔ `[action:cv-builder|Buka CV Builder|Perbarui resume & riwayat kerja]`
     • Portal Loker ➔ `[action:explore|Eksplorasi Loker & UMK|Cari lowongan terverifikasi & standar gaji]`
     • Profil & Resume ➔ `[action:profile|Buka Profil & Vault|Kelola portofolio & multi-versi CV]`

5. **JIKA PENGGUNA BERTANYA PROGRESS / PIPELINE**:
   - Baca data `pipeline_summary` dan `upcoming_interviews` di bawah, rangkum dengan jelas (jumlah lamaran aktif, jadwal terdekat), lalu sarankan aksi taktis berikutnya.

6. **JIKA PENGGUNA MEMINTA DRAF PESAN / FOLLOW-UP / EMAIL KE HR**:
   - DILARANG KERAS menggunakan tanda `>` atau blockquote di baris pesan! Tampilkan sebagai teks bersih yang rapi dan siap disalin (copy-paste).
   - Susun terstruktur:
     **Subjek:** [Subjek email yang relevan]
     **Pesan:**
     [Isi pesan per paragraf yang sopan, to the point, dan profesional]
   - Berikan tips ringkas kapan waktu terbaik mengirimkannya.

7. **GAYA BAHASA & FORMAT**:
   - Cerdas, to the point, ramah, dan tidak bertele-tele.
   - Gunakan format Markdown bersih (bold untuk label, bullet points • untuk daftar, dan blockquote > "..." HANYA jika mengutip 1 pertanyaan wawancara langsung).
   - Jangan pernah menyisipkan karakter `>` kosong atau awalan `>` di setiap baris draf template pesan.

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
