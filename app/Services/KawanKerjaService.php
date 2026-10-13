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

    public const UNAVAILABLE_MESSAGE = 'Maaf, layanan HireMate sedang tidak tersedia saat ini.';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        $this->primaryModel = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-flash-lite-latest'));
        $this->fallbackModel = 'gemini-3.1-flash-lite';
    }

    /**
     * Send a user message to KawanKerja and return the reply.
     */
    public function chat(string $message, User $user, array $history = []): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'reply' => self::UNAVAILABLE_MESSAGE,
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
                'maxOutputTokens' => 2048,
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
            'reply' => self::UNAVAILABLE_MESSAGE,
        ];
    }

    /**
     * Compile rich, live context about the user's career state.
     */
    public function buildUserContext(User $user): array
    {
        $profile = null;
        try {
            $profile = $user->profile;
        } catch (Throwable $e) {}

        $skills = [];
        try {
            $skills = $user->skills()->pluck('skill_name')->take(12)->toArray();
        } catch (Throwable $e) {}

        $experiences = [];
        try {
            $experiences = $user->experiences()
                ->latest('start_date')
                ->take(3)
                ->get(['position', 'company_name', 'description'])
                ->map(fn($exp) => [
                    'title' => $exp->position,
                    'company' => $exp->company_name,
                    'summary' => $exp->description ? \Illuminate\Support\Str::limit($exp->description, 120) : null,
                ])
                ->toArray();
        } catch (Throwable $e) {}

        $totalTracked = 0;
        $totalActive = 0;
        $onProcessCount = 0;
        $interviewCount = 0;
        $offeringCount = 0;
        $upcomingInterviews = [];
        $activeApps = [];

        try {
            $appsQuery = JobApplication::where('user_id', $user->id);
            $totalTracked = (clone $appsQuery)->count();

            $activeQuery = (clone $appsQuery)->where(function ($q) {
                $q->where('is_archived', false)->orWhereNull('is_archived');
            });
            
            $totalActive = (clone $activeQuery)->count();
            $onProcessCount = (clone $activeQuery)->where('application_status', 'On Process')->count();
            $interviewCount = (clone $activeQuery)->whereIn('recruitment_stage', [
                'HR - Interview', 'User - Interview', 'Psychotest', 'Assessment Test', 'LGD', 'Presentation Round'
            ])->count();
            $offeringCount = (clone $activeQuery)->where('recruitment_stage', 'Offering')->count();

            // Upcoming interview (future or within last 2 days)
            $upcomingInterviews = (clone $activeQuery)
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

            // First grab priority applications (On Process or Interview stage)
            $activeApps = (clone $activeQuery)
                ->orderByDesc('updated_at')
                ->limit(6)
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

            // If activeApps has fewer than 6 but user has more tracked applications, fill with recent overall applications
            if (count($activeApps) < 6 && $totalTracked > count($activeApps)) {
                $recentApps = (clone $appsQuery)
                    ->orderByDesc('updated_at')
                    ->limit(6)
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

                $seen = [];
                $merged = [];
                foreach (array_merge($activeApps, $recentApps) as $app) {
                    $key = $app['company'] . '|' . $app['position'];
                    if (!isset($seen[$key])) {
                        $seen[$key] = true;
                        $merged[] = $app;
                    }
                    if (count($merged) >= 6) {
                        break;
                    }
                }
                $activeApps = $merged;
            }
        } catch (Throwable $e) {
            Log::warning("KawanKerja JobApplications context error: " . $e->getMessage());
        }

        $primaryResume = null;
        $totalResumes = 0;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('user_resumes')) {
                $primaryResume = UserResume::where('user_id', $user->id)
                    ->where('is_primary', true)
                    ->value('title');
                $totalResumes = UserResume::where('user_id', $user->id)->count();
            }
        } catch (Throwable $e) {}

        return [
            'user_name' => $user->name,
            'headline' => $profile?->headline,
            'domicile' => $profile?->domicile,
            'skills' => $skills,
            'experiences' => $experiences,
            'pipeline_summary' => [
                'total_applications' => $totalTracked,
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

5. **JIKA PENGGUNA BERTANYA PROGRESS / PIPELINE ATAU MEMINTA SARAN/REKOMENDASI LOKER DARI LAMARANNYA**:
   - Periksa `pipeline_summary` (khususnya `total_applications` dan `total_active`) serta daftar `active_applications`.
   - JIKA `total_applications` > 0:
     DILARANG KERAS mengatakan data pipeline atau lamaran pengguna kosong!
     Sebutkan data konkret pengguna (contoh: "Dari total {total_applications} lamaran yang tercatat di TraKerja..."), lalu ambil contoh posisi dan nama perusahaan nyata yang ada di `active_applications` pengguna untuk diulas dan dijadikan bahan persiapan matang.
   - JIKA dan HANYA JIKA `total_applications` bernilai 0: baru sampaikan secara sopan bahwa belum ada data lamaran di sistem dan tawarkan eksplorasi loker baru.

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
            $res = Http::timeout(20)->post($url, $payload);

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

    /**
     * Stream a chat response from Gemini using SSE (Server-Sent Events).
     *
     * @param string $message
     * @param User $user
     * @param array $history
     * @param callable $onChunk Called with each string chunk as it arrives: fn(string $chunk) => void
     * @return array ['success' => bool, 'reply' => string]
     */
    public function chatStream(string $message, User $user, array $history, callable $onChunk): array
    {
        if (empty($this->apiKey)) {
            $onChunk(self::UNAVAILABLE_MESSAGE);
            return [
                'success' => false,
                'reply' => self::UNAVAILABLE_MESSAGE,
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
                'maxOutputTokens' => 2048,
            ],
        ];

        // 1. Try Primary Model
        $response = $this->executeStreamRequest($this->primaryModel, $payload, $onChunk);
        if ($response !== null) {
            return ['success' => true, 'reply' => $response];
        }

        // 2. Try Fallback Model
        $response = $this->executeStreamRequest($this->fallbackModel, $payload, $onChunk);
        if ($response !== null) {
            return ['success' => true, 'reply' => $response];
        }

        $onChunk(self::UNAVAILABLE_MESSAGE);
        return [
            'success' => false,
            'reply' => self::UNAVAILABLE_MESSAGE,
        ];
    }

    protected function executeStreamRequest(string $model, array $payload, callable $onChunk): ?string
    {
        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:streamGenerateContent?alt=sse&key={$this->apiKey}";
            
            $accumulated = '';
            $response = Http::withOptions(['stream' => true])
                ->timeout(35)
                ->post($url, $payload);

            if (!$response->successful()) {
                Log::warning("Gemini stream model {$model} error status {$response->status()}: " . $response->body());
                return null;
            }

            $body = $response->toPsrResponse()->getBody();
            $buffer = '';

            while (!$body->eof()) {
                $chunkData = $body->read(4096);
                if ($chunkData === '') {
                    break;
                }
                $buffer .= $chunkData;

                while (($newlinePos = strpos($buffer, "\n")) !== false) {
                    $line = trim(substr($buffer, 0, $newlinePos));
                    $buffer = substr($buffer, $newlinePos + 1);

                    if (str_starts_with($line, 'data: ')) {
                        $jsonStr = substr($line, 6);
                        if ($jsonStr === '[DONE]') {
                            break;
                        }
                        $decoded = json_decode($jsonStr, true);
                        if (isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
                            $chunkText = $decoded['candidates'][0]['content']['parts'][0]['text'];
                            if ($chunkText !== '') {
                                $accumulated .= $chunkText;
                                $onChunk($chunkText);
                            }
                        }
                    }
                }
            }

            // Flush any remaining data left in buffer
            if (trim($buffer) !== '' && str_starts_with(trim($buffer), 'data: ')) {
                $jsonStr = substr(trim($buffer), 6);
                $decoded = json_decode($jsonStr, true);
                if (isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
                    $chunkText = $decoded['candidates'][0]['content']['parts'][0]['text'];
                    if ($chunkText !== '') {
                        $accumulated .= $chunkText;
                        $onChunk($chunkText);
                    }
                }
            }

            // Fallback parsing if response body was not line-buffered SSE (e.g. In mocks)
            if ($accumulated === '') {
                $data = $response->json();
                $parts = $data['candidates'][0]['content']['parts'] ?? [];
                foreach ($parts as $part) {
                    if (isset($part['text']) && $part['text'] !== '') {
                        $accumulated .= $part['text'];
                        $onChunk($part['text']);
                    }
                }
            }

            $clean = trim($accumulated);
            return !empty($clean) ? $clean : null;
        } catch (Throwable $e) {
            Log::warning("Gemini stream model {$model} exception: " . $e->getMessage());
            return null;
        }
    }
}
