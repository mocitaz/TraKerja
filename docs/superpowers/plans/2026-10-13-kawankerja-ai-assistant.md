# KawanKerja AI Career Assistant Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build an in-app interactive AI Career Assistant named "KawanKerja" powered by Gemini API (Gemini 3.5 Flash Lite + 3.8 Flash fallback) with a bottom-right floating chat pop-up, replacing the legacy "Prepare with AI" button.

**Architecture:** A context-aware service compiles the user's profile, recent tracker jobs, and saved resumes into Gemini system instructions. A lightweight controller manages session-based chat history via JSON endpoints. A modern Alpine.js floating chat component provides an interactive pop-up drawer in the bottom right corner with quick starter prompts, typing indicators, and markdown formatting.

**Tech Stack:** Laravel 11 / PHP 8.2+, Google Gemini API (REST v1beta), Alpine.js, Tailwind CSS / Vanilla CSS, Phosphor Icons.

**Spec:** [`docs/superpowers/specs/2026-10-13-kawankerja-ai-chat-assistant-design.md`](file:///Users/Luthfi/Project/PT.%20Teknalogi%20Transformasi%20Digital/TraKerja/docs/superpowers/specs/2026-10-13-kawankerja-ai-chat-assistant-design.md)

## Global Constraints

- Primary AI Model: `gemini-3.5-flash-lite`
- Fallback AI Model: `gemini-3.8-flash`
- Avatar Asset: `public/images/kawankerja.png` (already placed from Option 2 vector)
- Target Persona: KawanKerja (warm, supportive, sharp, professional Indonesian career mentor)
- Access: Available to all authenticated users without quotas
- Design: Harmonious with TraKerja's slate/zinc aesthetic, Plus Jakarta Sans, rounded-2xl cards, clean typography

---

### Task 1: Backend Service (`KawanKerjaService`)

**Files:**
- Create: `app/Services/KawanKerjaService.php`
- Test: `tests/Unit/KawanKerjaServiceTest.php`

**Interfaces:**
- Consumes: `App\Models\User`, `App\Models\JobApplication`, `App\Models\UserResume`, `config('services.gemini.key')`
- Produces: `KawanKerjaService::chat(string $message, User $user, array $history = []): array`
- Produces: `KawanKerjaService::buildUserContext(User $user): array`

- [ ] **Step 1: Write unit tests for context building and Gemini query handling**

Create `tests/Unit/KawanKerjaServiceTest.php`:
```php
<?php

namespace Tests\Unit;

use App\Models\JobApplication;
use App\Models\User;
use App\Models\UserResume;
use App\Models\UserSkill;
use App\Services\KawanKerjaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KawanKerjaServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_compiles_rich_user_context(): void
    {
        $user = User::factory()->create(['name' => 'Budi Santoso']);
        UserSkill::create([
            'user_id' => $user->id,
            'skill_name' => 'Laravel',
            'category' => 'Backend',
        ]);
        JobApplication::create([
            'user_id' => $user->id,
            'company_name' => 'Tokopedia',
            'position' => 'Senior Backend Engineer',
            'application_status' => 'On Process',
            'recruitment_stage' => 'User - Interview',
            'application_date' => now()->subDays(5),
            'interview_date' => now()->addDays(2),
        ]);
        UserResume::create([
            'user_id' => $user->id,
            'title' => 'CV Backend Tech',
            'file_path' => 'resumes/1/test.pdf',
            'file_name' => 'test.pdf',
            'file_size' => 1024,
            'file_type' => 'application/pdf',
            'is_primary' => true,
        ]);

        $service = new KawanKerjaService();
        $context = $service->buildUserContext($user);

        $this->assertSame('Budi Santoso', $context['user_name']);
        $this->assertContains('Laravel', $context['skills']);
        $this->assertCount(1, $context['active_applications']);
        $this->assertSame('Tokopedia', $context['active_applications'][0]['company']);
        $this->assertSame('CV Backend Tech', $context['primary_resume']);
    }

    public function test_it_calls_gemini_api_and_returns_clean_response(): void
    {
        config(['services.gemini.key' => 'fake-test-key']);

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Halo! Tentu, untuk interview di Tokopedia kamu bisa fokus pada arsitektur database dan scaling.']
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $user = User::factory()->create();
        $service = new KawanKerjaService();
        $result = $service->chat('Tips interview Tokopedia dong', $user);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('Tokopedia', $result['reply']);
    }

    public function test_it_falls_back_to_secondary_model_on_failure(): void
    {
        config(['services.gemini.key' => 'fake-test-key']);

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent*' => Http::response(['error' => 'quota'], 429),
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Jawaban dari fallback model 3.8 flash.']
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $user = User::factory()->create();
        $service = new KawanKerjaService();
        $result = $service->chat('Halo', $user);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('fallback model', $result['reply']);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=KawanKerjaServiceTest`
Expected: FAIL with class `App\Services\KawanKerjaService` not found.

- [ ] **Step 3: Implement `KawanKerjaService`**

Create `app/Services/KawanKerjaService.php`:
```php
<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\User;
use App\Models\UserResume;
use Carbon\Carbon;
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
                'temperature' => 0.6,
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
            ->get(['company_name', 'position', 'application_status', 'recruitment_stage', 'interview_date', 'notes'])
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
Anda adalah "KawanKerja", asisten karier dan pencarian kerja pribadi berbasis AI untuk pengguna platform TraKerja (trakerja.com).

PERAN & KARAKTER ANDA:
1. Anda adalah sahabat karier yang ramah, hangat, empatik, cerdas, dan suportif ("Kawan").
2. Berbahasa Indonesia yang baik, santun, profesional, dan natural (bukan kaku seperti robot, bukan gaul berlebihan).
3. Anda fokus membantu pengguna sukses dalam perjalanan karier mereka di Indonesia: persiapan interview (metode STAR), review kualifikasi, simulasi tanya-jawab HR/User, draf pesan follow-up/outreach yang sopan, tips negosiasi gaji (Gross vs THP/PPh 21), dan manajemen stres mencari kerja.
4. Jawaban harus terstruktur rapi dengan Markdown: gunakan bold untuk poin utama, bullet point untuk langkah, dan ringkas tanpa basa-basi berbelit-belit.
5. Anda MENGETAHUI data konteks pengguna yang sedang bertanya. Manfaatkan informasi tersebut secara personal tanpa perlu meminta ulang hal yang sudah ada di data pengguna.

DATA PENGGUNA SAAT INI:
{$contextJson}

BATASAN DOMAIN:
Fokuskan jawaban Anda pada topik karier, rekrutmen, interview, lamaran kerja, pengembangan skill profesional, dan penggunaan fitur TraKerja. Jika pengguna menanyakan hal yang sama sekali di luar topik karier (misal: resep memasak, politik praktis), arahkan kembali dengan sopan bahwa fokus Anda adalah membantu karier mereka.
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
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter=KawanKerjaServiceTest`
Expected: PASS (3 passed).

- [ ] **Step 5: Commit Task 1**

Run:
```bash
git add app/Services/KawanKerjaService.php tests/Unit/KawanKerjaServiceTest.php
git commit -m "feat(ai): implement KawanKerjaService with context-awareness and Gemini fallback"
```

---

### Task 2: Controller & Routes (`KawanKerjaChatController`)

**Files:**
- Create: `app/Http/Controllers/KawanKerjaChatController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/KawanKerjaChatTest.php`

**Interfaces:**
- Consumes: `KawanKerjaService::chat`
- Produces:
  - `POST /kawankerja/chat`
  - `GET /kawankerja/history`
  - `POST /kawankerja/clear`

- [ ] **Step 1: Write feature tests for KawanKerja endpoints**

Create `tests/Feature/KawanKerjaChatTest.php`:
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KawanKerjaChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_kawankerja_endpoints(): void
    {
        $this->postJson(route('kawankerja.chat'), ['message' => 'Halo'])
            ->assertUnauthorized();

        $this->getJson(route('kawankerja.history'))
            ->assertUnauthorized();

        $this->postJson(route('kawankerja.clear'))
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_chat_and_receive_reply(): void
    {
        config(['services.gemini.key' => 'fake-test-key']);

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Halo! Saya KawanKerja. Ada yang bisa dibantu?']
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('kawankerja.chat'), [
                'message' => 'Halo KawanKerja',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'reply' => 'Halo! Saya KawanKerja. Ada yang bisa dibantu?',
        ]);

        // Verify history endpoint returns the conversation
        $historyRes = $this->actingAs($user)->getJson(route('kawankerja.history'));
        $historyRes->assertOk();
        $this->assertCount(2, $historyRes->json('history'));
    }

    public function test_user_can_clear_chat_history(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withSession([
            'kawankerja_history' => [
                ['role' => 'user', 'content' => 'Hai'],
                ['role' => 'model', 'content' => 'Halo'],
            ]
        ]);

        $response = $this->actingAs($user)->postJson(route('kawankerja.clear'));
        $response->assertOk();
        $response->assertJson(['success' => true]);

        $historyRes = $this->actingAs($user)->getJson(route('kawankerja.history'));
        $this->assertEmpty($historyRes->json('history'));
    }

    public function test_chat_validates_message_presence_and_length(): void
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user)->postJson(route('kawankerja.chat'), [
            'message' => '',
        ]);
        $res->assertUnprocessable();

        $oversized = str_repeat('a', 1001);
        $resOver = $this->actingAs($user)->postJson(route('kawankerja.chat'), [
            'message' => $oversized,
        ]);
        $resOver->assertUnprocessable();
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=KawanKerjaChatTest`
Expected: FAIL with Route not defined / 404.

- [ ] **Step 3: Implement `KawanKerjaChatController` & register routes**

Create `app/Http/Controllers/KawanKerjaChatController.php`:
```php
<?php

namespace App\Http\Controllers;

use App\Services\KawanKerjaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KawanKerjaChatController extends Controller
{
    protected KawanKerjaService $kawanKerja;

    public function __construct(KawanKerjaService $kawanKerja)
    {
        $this->kawanKerja = $kawanKerja;
    }

    /**
     * Send a message to KawanKerja.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        $user = Auth::user();
        $history = $request->session()->get('kawankerja_history', []);

        $result = $this->kawanKerja->chat($validated['message'], $user, $history);

        if ($result['success']) {
            $history[] = ['role' => 'user', 'content' => $validated['message']];
            $history[] = ['role' => 'model', 'content' => $result['reply']];

            // Retain only the last 10 exchanges (20 messages) to preserve memory efficiency
            if (count($history) > 20) {
                $history = array_slice($history, -20);
            }

            $request->session()->put('kawankerja_history', $history);
        }

        return response()->json([
            'success' => $result['success'],
            'reply' => $result['reply'],
            'history' => $history,
        ]);
    }

    /**
     * Get conversation history for current session.
     */
    public function history(Request $request): JsonResponse
    {
        $history = $request->session()->get('kawankerja_history', []);

        return response()->json([
            'history' => $history,
        ]);
    }

    /**
     * Clear the conversation history.
     */
    public function clear(Request $request): JsonResponse
    {
        $request->session()->forget('kawankerja_history');

        return response()->json([
            'success' => true,
        ]);
    }
}
```

Modify `routes/web.php` under `Route::middleware('auth')->group(function () { ... })`:
```php
    // KawanKerja AI Assistant routes
    Route::prefix('kawankerja')->name('kawankerja.')->group(function () {
        Route::post('/chat', [\App\Http\Controllers\KawanKerjaChatController::class, 'chat'])->name('chat');
        Route::get('/history', [\App\Http\Controllers\KawanKerjaChatController::class, 'history'])->name('history');
        Route::post('/clear', [\App\Http\Controllers\KawanKerjaChatController::class, 'clear'])->name('clear');
    });
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter=KawanKerjaChatTest`
Expected: PASS (4 passed).

- [ ] **Step 5: Commit Task 2**

Run:
```bash
git add app/Http/Controllers/KawanKerjaChatController.php routes/web.php tests/Feature/KawanKerjaChatTest.php
git commit -m "feat(ai): add KawanKerjaChatController and web routes"
```

---

### Task 3: Floating Chat Widget UI (`kawankerja-chat.blade.php` & `kawankerja.css`)

**Files:**
- Create: `resources/views/components/kawankerja-chat.blade.php`
- Create: `resources/css/kawankerja.css`
- Modify: `resources/css/app.css`

**Interfaces:**
- Consumes: `route('kawankerja.chat')`, `route('kawankerja.history')`, `route('kawankerja.clear')`, `asset('images/kawankerja.png')`
- Produces: Floating pop-up chat widget with Alpine.js reactivity and markdown formatting.

- [ ] **Step 1: Create `resources/css/kawankerja.css`**

Define floating launcher, chat drawer window, avatar badges, chat bubbles, typing dots animation, starter chips, and responsive mobile bottom sheet:
```css
/* KawanKerja Floating Chat UI */
.kawankerja-widget {
    position: fixed;
    right: max(24px, env(safe-area-inset-right));
    bottom: max(20px, env(safe-area-inset-bottom));
    z-index: 95;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}

/* Launcher Pill */
.kawankerja-launcher {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    height: 48px;
    padding: 6px 18px 6px 6px;
    background: #ffffff;
    border: 1px solid #e4e4e7;
    border-radius: 9999px;
    box-shadow: 0 4px 20px -2px rgba(24, 24, 27, 0.1), 0 2px 6px -1px rgba(24, 24, 27, 0.06);
    color: #18181b;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    user-select: none;
}

.kawankerja-launcher:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 26px -4px rgba(24, 24, 27, 0.14), 0 4px 10px -2px rgba(24, 24, 27, 0.08);
    border-color: #d4d4d8;
}

.kawankerja-launcher-avatar {
    position: relative;
    width: 36px;
    height: 36px;
    border-radius: 9999px;
    overflow: hidden;
    background: #f8ded3;
    flex-shrink: 0;
}

.kawankerja-launcher-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.kawankerja-online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    border-radius: 9999px;
    background: #10b981;
    border: 2px solid #ffffff;
}

.kawankerja-launcher-label {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    line-height: 1.2;
}

.kawankerja-launcher-label strong {
    font-size: 13px;
    color: #18181b;
}

.kawankerja-launcher-label small {
    font-size: 11px;
    font-weight: 500;
    color: #71717a;
}

/* Chat Pop-up Window */
.kawankerja-window {
    position: fixed;
    right: max(24px, env(safe-area-inset-right));
    bottom: max(80px, env(safe-area-inset-bottom));
    width: 380px;
    height: 540px;
    max-height: calc(100vh - 100px);
    background: #ffffff;
    border: 1px solid #e4e4e7;
    border-radius: 20px;
    box-shadow: 0 16px 40px -10px rgba(24, 24, 27, 0.2), 0 0 1px 1px rgba(24, 24, 27, 0.04);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    z-index: 96;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s ease;
}

/* Header */
.kawankerja-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border-bottom: 1px solid #f4f4f5;
    background: #fafafa;
}

.kawankerja-header-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.kawankerja-header-avatar {
    width: 34px;
    height: 34px;
    border-radius: 9999px;
    overflow: hidden;
    background: #f8ded3;
    flex-shrink: 0;
}

.kawankerja-header-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.kawankerja-header-text h3 {
    font-size: 13px;
    font-weight: 700;
    color: #18181b;
    margin: 0;
    line-height: 1.2;
}

.kawankerja-header-text p {
    font-size: 11px;
    color: #71717a;
    margin: 0;
}

.kawankerja-header-actions {
    display: flex;
    align-items: center;
    gap: 4px;
}

.kawankerja-header-btn {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: #71717a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
}

.kawankerja-header-btn:hover {
    background: #f4f4f5;
    color: #18181b;
}

/* Messages Scroll Area */
.kawankerja-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #ffffff;
}

.kawankerja-welcome {
    text-align: center;
    padding: 12px 8px 16px;
}

.kawankerja-welcome-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 600;
    color: #4f46e5;
    background: #eef2ff;
    padding: 4px 10px;
    border-radius: 9999px;
    margin-bottom: 8px;
}

.kawankerja-welcome p {
    font-size: 13px;
    color: #52525b;
    line-height: 1.5;
    margin: 0;
}

/* Starter Chips */
.kawankerja-chips {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-top: 12px;
}

.kawankerja-chip {
    text-align: left;
    font-size: 12px;
    color: #3f3f46;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 8px 12px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.15s;
}

.kawankerja-chip:hover {
    background: #f1f5f9;
    color: #18181b;
    border-color: #cbd5e1;
}

/* Chat Bubbles */
.kawankerja-msg {
    display: flex;
    flex-direction: column;
    max-width: 86%;
    font-size: 13px;
    line-height: 1.5;
    word-break: break-word;
}

.kawankerja-msg--user {
    align-self: flex-end;
    background: #18181b;
    color: #ffffff;
    padding: 10px 14px;
    border-radius: 16px 16px 4px 16px;
}

.kawankerja-msg--bot {
    align-self: flex-start;
    background: #f4f4f5;
    color: #18181b;
    padding: 10px 14px;
    border-radius: 16px 16px 16px 4px;
}

.kawankerja-msg--bot p {
    margin: 0 0 6px;
}
.kawankerja-msg--bot p:last-child {
    margin: 0;
}

.kawankerja-msg--bot ul, .kawankerja-msg--bot ol {
    margin: 4px 0 6px 16px;
    padding: 0;
}

.kawankerja-msg--bot li {
    margin-bottom: 3px;
}

.kawankerja-msg--bot strong {
    font-weight: 700;
    color: #09090b;
}

/* Typing Indicator */
.kawankerja-typing {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 10px 14px;
    background: #f4f4f5;
    border-radius: 16px 16px 16px 4px;
    align-self: flex-start;
}

.kawankerja-typing span {
    width: 6px;
    height: 6px;
    background: #a1a1aa;
    border-radius: 9999px;
    animation: kawankerja-bounce 1.4s infinite ease-in-out both;
}

.kawankerja-typing span:nth-child(1) { animation-delay: -0.32s; }
.kawankerja-typing span:nth-child(2) { animation-delay: -0.16s; }

@keyframes kawankerja-bounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

/* Input Bar */
.kawankerja-footer {
    padding: 12px 14px;
    border-top: 1px solid #f4f4f5;
    background: #ffffff;
}

.kawankerja-input-row {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    background: #f4f4f5;
    border: 1px solid #e4e4e7;
    border-radius: 14px;
    padding: 6px 8px 6px 12px;
    transition: border-color 0.15s;
}

.kawankerja-input-row:focus-within {
    border-color: #18181b;
    background: #ffffff;
}

.kawankerja-textarea {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 13px;
    line-height: 1.4;
    color: #18181b;
    resize: none;
    max-height: 90px;
    padding: 4px 0;
    outline: none;
}

.kawankerja-send-btn {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    border: none;
    background: #18181b;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.15s;
}

.kawankerja-send-btn:disabled {
    background: #e4e4e7;
    color: #a1a1aa;
    cursor: not-allowed;
}

.kawankerja-input-hint {
    font-size: 10px;
    color: #a1a1aa;
    margin-top: 4px;
    text-align: right;
}

/* Mobile Responsive */
@media (max-width: 640px) {
    .kawankerja-widget {
        right: 16px;
        bottom: calc(72px + env(safe-area-inset-bottom));
    }

    .kawankerja-window {
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 82vh;
        max-height: 85vh;
        border-radius: 20px 20px 0 0;
        border-bottom: none;
        box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.2);
    }
}
```

- [ ] **Step 2: Create `resources/views/components/kawankerja-chat.blade.php`**

```blade
@if(auth()->check() && !auth()->user()->isAdmin())
<div class="kawankerja-widget"
     x-data="{
         open: false,
         loading: false,
         input: '',
         messages: [],
         async init() {
             window.addEventListener('open-kawankerja', (e) => {
                 this.open = true;
                 if (e.detail?.prompt) {
                     this.input = e.detail.prompt;
                     this.$nextTick(() => this.send());
                 }
             });
             try {
                 const res = await fetch('{{ route('kawankerja.history') }}');
                 if (res.ok) {
                     const data = await res.json();
                     this.messages = data.history || [];
                 }
             } catch (_) {}
         },
         async send() {
             const text = this.input.trim();
             if (!text || this.loading) return;
             this.input = '';
             this.messages.push({ role: 'user', content: text });
             this.loading = true;
             this.scrollToBottom();

             try {
                 const res = await fetch('{{ route('kawankerja.chat') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json',
                     },
                     body: JSON.stringify({ message: text })
                 });
                 const data = await res.json();
                 if (data.success && data.reply) {
                     this.messages.push({ role: 'model', content: data.reply });
                 } else {
                     this.messages.push({ role: 'model', content: data.reply || 'Maaf, terjadi kesalahan saat menghubungi server.' });
                 }
             } catch (e) {
                 this.messages.push({ role: 'model', content: 'Gagal terhubung ke KawanKerja. Periksa koneksi internet Anda.' });
             } finally {
                 this.loading = false;
                 this.scrollToBottom();
             }
         },
         async clearChat() {
             if (!confirm('Hapus riwayat obrolan dengan KawanKerja?')) return;
             try {
                 await fetch('{{ route('kawankerja.clear') }}', {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json',
                     }
                 });
                 this.messages = [];
             } catch (_) {}
         },
         scrollToBottom() {
             this.$nextTick(() => {
                 const el = this.$refs.msgContainer;
                 if (el) el.scrollTop = el.scrollHeight;
             });
         },
         formatText(raw) {
             if (!raw) return '';
             let esc = raw
                 .replace(/&/g, '&amp;')
                 .replace(/</g, '&lt;')
                 .replace(/>/g, '&gt;');
             esc = esc.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
             esc = esc.replace(/\*([^\*]+)\*/g, '<em>$1</em>');
             esc = esc.replace(/\n\n/g, '<br><br>');
             esc = esc.replace(/\n/g, '<br>');
             return esc;
         }
     }"
     x-cloak>

    <!-- Floating Launcher Pill -->
    <button type="button"
            class="kawankerja-launcher"
            @click="open = !open; if(open) scrollToBottom()"
            aria-label="Buka KawanKerja AI Assistant"
            :aria-expanded="open.toString()">
        <div class="kawankerja-launcher-avatar">
            <img src="{{ asset('images/kawankerja.png') }}" alt="KawanKerja Avatar">
            <span class="kawankerja-online-dot"></span>
        </div>
        <div class="kawankerja-launcher-label">
            <strong>KawanKerja</strong>
            <small>Tanya AI</small>
        </div>
    </button>

    <!-- Chat Pop-up Window -->
    <div class="kawankerja-window"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         @click.outside="open = false">

        <!-- Header -->
        <header class="kawankerja-header">
            <div class="kawankerja-header-info">
                <div class="kawankerja-header-avatar">
                    <img src="{{ asset('images/kawankerja.png') }}" alt="KawanKerja Avatar">
                </div>
                <div class="kawankerja-header-text">
                    <h3>KawanKerja</h3>
                    <p>Asisten Karier TraKerja</p>
                </div>
            </div>
            <div class="kawankerja-header-actions">
                <button type="button"
                        class="kawankerja-header-btn"
                        @click="clearChat()"
                        title="Hapus percakapan">
                    <i class="ph ph-trash text-sm" aria-hidden="true"></i>
                </button>
                <button type="button"
                        class="kawankerja-header-btn"
                        @click="open = false"
                        title="Tutup">
                    <i class="ph ph-x text-sm" aria-hidden="true"></i>
                </button>
            </div>
        </header>

        <!-- Message Body -->
        <div class="kawankerja-messages" x-ref="msgContainer">
            <div class="kawankerja-welcome" x-show="messages.length === 0">
                <span class="kawankerja-welcome-badge">
                    <i class="ph-fill ph-sparkle" aria-hidden="true"></i>
                    <span>Gemini AI Career Companion</span>
                </span>
                <p>Halo, <strong>{{ auth()->user()->name }}</strong>! Butuh persiapan interview, review jawaban STAR, atau draft pesan HR hari ini?</p>

                <div class="kawankerja-chips">
                    <button type="button" class="kawankerja-chip" @click="input = 'Bantu simulasi interview untuk lamaran terbaru saya dong'; send()">
                        🎯 Simulasi interview lamaran terbaru
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Bagaimana cara menjawab pertanyaan interview tentang kelemahan diri?'; send()">
                        ⭐ Cara jawab pertanyaan kelemahan diri
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Tolong buatkan draf chat WhatsApp follow-up sopan ke HR'; send()">
                        ✉️ Draf pesan follow-up HR
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Tips negosiasi gaji saat tahap offering agar tetap sopan'; send()">
                        💡 Tips negosiasi gaji saat offering
                    </button>
                </div>
            </div>

            <!-- Chat History -->
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'kawankerja-msg kawankerja-msg--user' : 'kawankerja-msg kawankerja-msg--bot'">
                    <div x-html="formatText(msg.content)"></div>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div class="kawankerja-typing" x-show="loading">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Input Bar -->
        <footer class="kawankerja-footer">
            <div class="kawankerja-input-row">
                <textarea class="kawankerja-textarea"
                          rows="1"
                          placeholder="Ketik pertanyaan untuk KawanKerja..."
                          x-model="input"
                          @keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); send(); }"
                          :disabled="loading"></textarea>
                <button type="button"
                        class="kawankerja-send-btn"
                        @click="send()"
                        :disabled="!input.trim() || loading"
                        aria-label="Kirim pesan">
                    <i class="ph-fill ph-paper-plane-tilt" aria-hidden="true"></i>
                </button>
            </div>
            <div class="kawankerja-input-hint">Enter untuk kirim · Shift+Enter baris baru</div>
        </footer>
    </div>
</div>
@endif
```

- [ ] **Step 3: Import `kawankerja.css` in `resources/css/app.css`**

Add `@import './kawankerja.css';` to `resources/css/app.css`.

- [ ] **Step 4: Commit Task 3**

Run:
```bash
git add resources/views/components/kawankerja-chat.blade.php resources/css/kawankerja.css resources/css/app.css
git commit -m "feat(ui): create KawanKerja floating chat widget and stylesheet"
```

---

### Task 4: Layout & Dashboard Integration (Replace "Prepare with AI")

**Files:**
- Modify: `resources/views/layouts/app.blade.php` (replace `<x-floating-ai-link />` with `<x-kawankerja-chat />`).
- Modify: `resources/views/dashboard/next-move.blade.php` (update header action to trigger KawanKerja drawer).

- [ ] **Step 1: Replace `<x-floating-ai-link />` with `<x-kawankerja-chat />` in `layouts/app.blade.php`**

Line 131 in `resources/views/layouts/app.blade.php`:
Change:
```blade
<x-floating-ai-link />
```
To:
```blade
<x-kawankerja-chat />
```

- [ ] **Step 2: Update `dashboard/next-move.blade.php`**

In `resources/views/dashboard/next-move.blade.php` lines 4-5:
Change:
```blade
<a href="{{ route('ai-studio.index') }}" wire:navigate class="dashboard-ai-link" aria-label="Prepare with TraKerja AI" title="TraKerja AI"><x-trakerja-ai-mark class="ai-notice-mark" /><span>Prepare with AI</span><i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
```
To:
```blade
<button type="button" @click="window.dispatchEvent(new CustomEvent('open-kawankerja', { detail: { prompt: 'Tolong bantu persiapan langkah karier dan interview saya' } }))" class="dashboard-ai-link" aria-label="Konsultasi dengan KawanKerja" title="Tanya KawanKerja"><img src="{{ asset('images/kawankerja.png') }}" class="w-6 h-6 rounded-full object-cover" alt=""><span>Tanya KawanKerja</span><i class="ph-fill ph-chat-circle-dots ml-1" aria-hidden="true"></i></button>
```

- [ ] **Step 3: Commit Task 4**

Run:
```bash
git add resources/views/layouts/app.blade.php resources/views/dashboard/next-move.blade.php
git commit -m "feat(integration): mount KawanKerja widget and replace legacy prepare-with-ai link"
```

---

### Task 5: Verification & Production Asset Compilation

**Files:**
- None (execution & verification)

- [ ] **Step 1: Run complete test suite for KawanKerja**

Run: `php artisan test --filter=KawanKerja`
Expected: All Unit and Feature tests pass.

- [ ] **Step 2: Compile frontend assets with Vite**

Run: `npm run build`
Expected: Build succeeds without CSS or JS errors.

- [ ] **Step 3: Final sanity commit and git status check**

Run: `git status`
Confirm clean working tree.
