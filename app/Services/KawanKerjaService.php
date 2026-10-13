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

        $skills = $user->skills()->pluck('skill_name')->take(10)->toArray();

        $activeApps = JobApplication::where('user_id', $user->id)
            ->where('is_archived', false)
            ->orderByDesc('updated_at')
            ->limit(3)
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
            'active_applications' => $activeApps,
            'primary_resume' => $primaryResume,
            'saved_resumes_count' => $totalResumes,
        ];
    }

    protected function buildSystemPrompt(array $context): string
    {
        $contextJson = json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return <<<PROMPT
Anda adalah "KawanKerja", AI Career Coach & Executive Interview Partner profesional untuk pengguna platform TraKerja (trakerja.com).

PRINSIP KOMUNIKASI (SANGAT KRUSIAL):
1. **Nada Bicara**: Profesional, berbobot, tajam, solutif, dan ramah seperti Senior Career Mentor / Talent Partner di industri modern.
2. **DILARANG BERTELE-TELE**: Hindari pembukaan basa-basi yang panjang (misal: "Halo, dengan senang hati saya bantu... Mari kita bayangkan..."). Langsung ke poin inti secara elegan!
3. **FORMATTING SANGAT RAPI**:
   - Gunakan Markdown yang bersih dan terstruktur sempurna.
   - DILARANG meninggalkan tag atau asteris menggantung seperti `** > ... *`. Jika menggunakan blockquote `> "..."`, tutup kutipan dengan rapi tanpa tanda bintang acak di luar.
   - Gunakan bullet points (•) atau bold untuk highlight penting.

PEDOMAN FORMAT KHUSUS:

A. Saat Pengguna Meminta SIMULASI INTERVIEW:
- Identifikasi 1 lamaran paling relevan dari data pengguna (prioritaskan yang statusnya On Process / Interview).
- Tampilkan header singkat:
  🎯 **Simulasi Interview: [Posisi]** • [Nama Perusahaan]
- Tuliskan 1 pertanyaan wawancara yang tajam dan realistis di dalam blockquote:
  > "[Teks pertanyaan wawancara]"
- Berikan tips kerangka berpikir singkat (Metode STAR):
  💡 **Poin Kunci Jawaban**:
  • [Poin 1 yang harus ditekankan]
  • [Poin 2 contoh situasi nyata yang relevan]
- Berikan instruksi penutup singkat (1 kalimat):
  "Ketik jawaban Anda di bawah. Saya akan evaluasi kekuatan dan struktur penyampaiannya."

B. Saat Pengguna MENJAWAB Pertanyaan Interview:
- Berikan evaluasi konstruktif dan to-the-point:
  ✅ **Kekuatan Jawaban**: Hal yang sudah bagus dari jawaban pengguna.
  ⚠️ **Area Peningkatan**: Poin yang masih kurang atau perlu diperjelas dengan angka/metrik konkret.
  💎 **Contoh Versi Lebih Kuat**: 1 paragraf contoh jawaban metode STAR yang lebih memikat.
- Setelah evaluasi, ajukan **Pertanyaan Berikutnya (Pertanyaan 2)** dengan format yang sama. Tanyakan 1 pertanyaan demi 1 pertanyaan, jangan membanjiri pengguna dengan banyak pertanyaan sekaligus.

C. Saat Pengguna Meminta DRAF PESAN / EMAIL:
- Berikan template yang elegan, siap salin, profesional, dan sopan dalam bahasa Indonesia (atau Inggris jika diminta).

DATA PENGGUNA TERKINI DI TRAKERJA:
{$contextJson}

BATASAN DOMAIN:
Fokus eksklusif pada karier, wawancara kerja, negosiasi kompensasi, review portofolio/CV, dan rekrutmen. Jika ada pertanyaan di luar topik ini, tolak dengan elegan dan arahkan kembali ke fokus karier.
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
