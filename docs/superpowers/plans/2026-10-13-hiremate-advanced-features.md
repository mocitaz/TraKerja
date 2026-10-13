# HireMate Advanced Features Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Upgrade HireMate AI Career Coach with One-Click Quick Copy, Interactive Action Navigation Cards, STAR Framework Scorecard, and Real-Time SSE Streaming Output.

**Architecture:** Extend `KawanKerjaService` with streaming support (`streamGenerateContent`) and structured tokens for action cards and STAR evaluation. Add an SSE streaming endpoint in `KawanKerjaChatController`, and enhance the Alpine.js chat widget in `kawankerja-chat.blade.php` and `kawankerja.css` with real-time token rendering, clipboard copy animations, interactive deep-link cards, and a visual STAR scorecard.

**Tech Stack:** PHP 8.2+, Laravel 11, Livewire 3 / Alpine.js, Vanilla CSS, Gemini 2.5 Flash / 1.5 Flash SSE REST API, PHPUnit 11.

**Spec:** `docs/superpowers/specs/2026-10-13-kawankerja-ai-chat-assistant-design.md`

## Global Constraints
- Keep styling 100% aligned with TraKerja's theme (lavender `#e5e0f5`, deep purple `#644c87`, dark text `#18181b`, emerald `#10b981`).
- Zero console errors; preserve backwards compatibility with non-streaming fallback.
- No raw markdown or unescaped HTML injection: all user-generated content must be HTML-escaped before markdown token expansion.
- All tests must pass: `php artisan test --filter=KawanKerja`.

---

### Task 1: One-Click Quick Copy Button on Assistant Messages

**Files:**
- Modify: `resources/views/components/kawankerja-chat.blade.php:90-115`
- Modify: `resources/views/components/kawankerja-chat.blade.php:220-240`
- Modify: `resources/css/kawankerja.css:360-380`
- Test: `tests/Feature/KawanKerjaChatTest.php`

**Interfaces:**
- Consumes: `msg.content` in Alpine.js message loops
- Produces: `copyMessage(content, buttonEl)` helper on Alpine data instance that strips internal markup and writes clean text to `navigator.clipboard`.

- [ ] **Step 1: Write test verifying copy markup and clean response content**

```php
// in tests/Feature/KawanKerjaChatTest.php
public function test_assistant_bubble_includes_copy_action_attributes(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('kawankerja-copy-btn');
}
```

- [ ] **Step 2: Run test to verify initial state**

Run: `php artisan test --filter=KawanKerjaChatTest::test_assistant_bubble_includes_copy_action_attributes`
Expected: FAIL (assertSee `kawankerja-copy-btn` fails).

- [ ] **Step 3: Implement copy button and copyMessage method**

In `resources/views/components/kawankerja-chat.blade.php`:
1. Add copy button to bot bubbles:
```blade
<div class="kawankerja-bubble-footer" x-show="msg.role !== 'user'">
    <button type="button"
            class="kawankerja-copy-btn"
            @click="copyMessage(msg.content, $el)"
            title="Copy message">
        <i class="ph ph-copy" aria-hidden="true"></i>
        <span>Copy</span>
    </button>
</div>
```
2. Add `copyMessage` to Alpine script:
```javascript
cleanTextForCopy(raw) {
    if (!raw) return '';
    return raw
        .replace(/\[action:[^\]]+\]/g, '')
        .replace(/\[\/?star_scorecard\]/g, '')
        .trim();
},
async copyMessage(raw, btnEl) {
    const text = this.cleanTextForCopy(raw);
    if (!text) return;
    try {
        await navigator.clipboard.writeText(text);
        const icon = btnEl.querySelector('i');
        const label = btnEl.querySelector('span');
        if (icon) icon.className = 'ph-bold ph-check text-emerald-600';
        if (label) label.textContent = 'Copied!';
        btnEl.classList.add('is-copied');
        setTimeout(() => {
            if (icon) icon.className = 'ph ph-copy';
            if (label) label.textContent = 'Copy';
            btnEl.classList.remove('is-copied');
        }, 2000);
    } catch (_) {}
}
```

- [ ] **Step 4: Style copy button in `resources/css/kawankerja.css`**

```css
.kawankerja-bubble-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 6px;
    padding-top: 4px;
    border-top: 1px solid #f4f0f8;
}

.kawankerja-copy-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: transparent;
    border: none;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    color: #8b7d9f;
    cursor: pointer;
    transition: all 0.15s ease;
}

.kawankerja-copy-btn:hover {
    color: #644c87;
    background: #f5f2fa;
}

.kawankerja-copy-btn.is-copied {
    color: #059669;
}
```

- [ ] **Step 5: Run tests and verify**

Run: `php artisan test --filter=KawanKerjaChatTest`
Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add resources/views/components/kawankerja-chat.blade.php resources/css/kawankerja.css tests/Feature/KawanKerjaChatTest.php
git commit -m "feat(hiremate): add one-click copy button on assistant message bubbles"
```

---

### Task 2: Action Cards & Deep Navigation Linking

**Files:**
- Modify: `app/Services/KawanKerjaService.php:200-220`
- Modify: `resources/views/components/kawankerja-chat.blade.php:240-270`
- Modify: `resources/css/kawankerja.css:420-460`
- Test: `tests/Unit/KawanKerjaServiceTest.php`

**Interfaces:**
- Consumes: Route name mappings (`tracker`, `interviews`, `ai-studio`, `cv-builder`, `explore`, `profile`)
- Produces: `formatText` expands `[action:key|Title|Subtitle]` into `<a href="..." class="kawankerja-action-card" wire:navigate>...</a>`.

- [ ] **Step 1: Write test verifying action card parsing**

```php
// in tests/Unit/KawanKerjaServiceTest.php
public function test_system_prompt_instructs_action_cards_for_navigation(): void
{
    $service = app(KawanKerjaService::class);
    $reflection = new \ReflectionClass($service);
    $method = $reflection->getMethod('buildSystemPrompt');
    $method->setAccessible(true);
    
    $prompt = $method->invoke($service, ['user_name' => 'Luthfi']);
    
    $this->assertStringContainsString('[action:', $prompt);
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=KawanKerjaServiceTest::test_system_prompt_instructs_action_cards_for_navigation`
Expected: FAIL.

- [ ] **Step 3: Update system prompt in `KawanKerjaService.php`**

Instruct HireMate to append action tags when recommending features:
```text
Saat merekomendasikan fitur TraKerja, sertakan tombol aksi di akhir jawaban menggunakan format:
[action:tracker|Buka Job Tracker|Kelola pipeline lamaran]
[action:interviews|Buka Kalender Interview|Pantau jadwal & reminder]
[action:ai-studio|Buka AI Studio|Cover letter, Pasfoto & CV Analyzer]
[action:cv-builder|Buka CV Builder|Perbarui resume & profil]
[action:explore|Eksplorasi Loker|Cari lowongan & cek standar UMK]
```

- [ ] **Step 4: Implement action card expansion in `kawankerja-chat.blade.php`**

Add map of routes in Alpine:
```javascript
const actionRoutes = {
    'tracker': { url: '{{ route('tracker') }}', icon: 'ph-kanban' },
    'interviews': { url: '{{ route('interviews') }}', icon: 'ph-calendar' },
    'ai-studio': { url: '{{ route('ai-studio.index') }}', icon: 'ph-sparkle' },
    'cv-builder': { url: '{{ route('cv.builder') }}', icon: 'ph-file-text' },
    'explore': { url: '{{ route('explore') }}', icon: 'ph-compass' },
    'profile': { url: '{{ route('profile') }}', icon: 'ph-user' },
};
```
In `formatText(raw)`:
```javascript
esc = esc.replace(/\[action:([a-z-]+)\|([^|\]]+)\|?([^\]]*)\]/g, function(match, key, title, sub) {
    const routeInfo = actionRoutes[key] || { url: '#', icon: 'ph-arrow-up-right' };
    return `<div class="kawankerja-action-wrap"><a href="${routeInfo.url}" class="kawankerja-action-card" wire:navigate><div class="kawankerja-action-icon"><i class="ph ${routeInfo.icon}" aria-hidden="true"></i></div><div class="kawankerja-action-text"><strong>${title}</strong>${sub ? `<small>${sub}</small>` : ''}</div><i class="ph ph-arrow-up-right kawankerja-action-arrow" aria-hidden="true"></i></a></div>`;
});
```

- [ ] **Step 5: Add styling in `resources/css/kawankerja.css`**

```css
.kawankerja-action-wrap {
    margin: 8px 0 4px;
}

.kawankerja-action-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    background: #fbf9fd;
    border: 1px solid #e7dfef;
    border-radius: 10px;
    text-decoration: none;
    color: #2b1f3c;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(67, 50, 90, 0.03);
}

.kawankerja-action-card:hover {
    background: #f3ecfa;
    border-color: #cfc1e4;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px -2px rgba(100, 76, 135, 0.12);
}

.kawankerja-action-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #ffffff;
    border: 1px solid #eae2f2;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #644c87;
    font-size: 15px;
    flex-shrink: 0;
}

.kawankerja-action-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    line-height: 1.25;
}

.kawankerja-action-text strong {
    font-size: 12.5px;
    font-weight: 700;
    color: #18181b;
}

.kawankerja-action-text small {
    font-size: 11px;
    color: #71717a;
}

.kawankerja-action-arrow {
    color: #9d8db3;
    font-size: 13px;
    transition: transform 0.15s ease, color 0.15s ease;
}

.kawankerja-action-card:hover .kawankerja-action-arrow {
    color: #644c87;
    transform: translate(1px, -1px);
}
```

- [ ] **Step 6: Run tests and commit**

Run: `php artisan test --filter=KawanKerja`
Expected: PASS.

```bash
git add app/Services/KawanKerjaService.php resources/views/components/kawankerja-chat.blade.php resources/css/kawankerja.css tests/Unit/KawanKerjaServiceTest.php
git commit -m "feat(hiremate): add interactive action cards with deep navigation links"
```

---

### Task 3: STAR Framework Scorecard Component

**Files:**
- Modify: `app/Services/KawanKerjaService.php:195-215`
- Modify: `resources/views/components/kawankerja-chat.blade.php:240-275`
- Modify: `resources/css/kawankerja.css:460-520`
- Test: `tests/Unit/KawanKerjaServiceTest.php`

**Interfaces:**
- Consumes: Tagged response `[star_scorecard]...[/star_scorecard]`
- Produces: Beautiful structured STAR evaluation card with progress meters, pillar breakdown, and overall score pill.

- [ ] **Step 1: Write test verifying prompt has STAR scorecard instructions**

```php
// in tests/Unit/KawanKerjaServiceTest.php
public function test_system_prompt_instructs_star_scorecard_syntax(): void
{
    $service = app(KawanKerjaService::class);
    $reflection = new \ReflectionClass($service);
    $method = $reflection->getMethod('buildSystemPrompt');
    $method->setAccessible(true);
    
    $prompt = $method->invoke($service, ['user_name' => 'Luthfi']);
    
    $this->assertStringContainsString('[star_scorecard]', $prompt);
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=KawanKerjaServiceTest::test_system_prompt_instructs_star_scorecard_syntax`
Expected: FAIL.

- [ ] **Step 3: Update `KawanKerjaService.php` with scorecard syntax rule**

In rule 3 (evaluating interview answers):
```text
3. **JIKA PENGGUNA MENJAWAB PERTANYAAN INTERVIEW**:
   - Berikan evaluasi terukur menggunakan format kartu STAR Scorecard:
[star_scorecard]
Situation: [Skor 1-10]/10 | [Catatan singkat latar belakang masalah]
Task: [Skor 1-10]/10 | [Catatan singkat kejelasan peran & tanggung jawab]
Action: [Skor 1-10]/10 | [Catatan aksi nyata & metrik/tools yang digunakan]
Result: [Skor 1-10]/10 | [Catatan dampak/hasil bisnis terukur]
Overall: [Rata-rata]/10 | [Predikat: misal Solid & Executive / Perlu Metrik Nyata]
[/star_scorecard]
   - Berikan 1 contoh versi jawaban yang lebih impresif (*executive level*).
   - Ajukan pertanyaan lanjutan berikutnya.
```

- [ ] **Step 4: Implement scorecard parser in `kawankerja-chat.blade.php`**

```javascript
esc = esc.replace(/\[star_scorecard\]([\s\S]*?)\[\/star_scorecard\]/g, function(match, inner) {
    const lines = inner.trim().split('\n');
    let overallBadge = '';
    let itemsHtml = '';
    
    lines.forEach(line => {
        const parts = line.split('|');
        if (parts.length < 2) return;
        const left = parts[0].trim();
        const note = parts[1].trim();
        const colonIdx = left.indexOf(':');
        if (colonIdx === -1) return;
        const label = left.substring(0, colonIdx).trim();
        const scoreStr = left.substring(colonIdx + 1).trim();
        const numMatch = scoreStr.match(/([0-9.]+)/);
        const scoreNum = numMatch ? parseFloat(numMatch[1]) : 7;
        const pct = Math.min(100, Math.max(10, scoreNum * 10));

        if (label.toLowerCase() === 'overall') {
            overallBadge = `<div class="kawankerja-star-overall"><strong>${scoreStr}</strong><span>${note}</span></div>`;
        } else {
            const letter = label.charAt(0).toUpperCase();
            itemsHtml += `
                <div class="kawankerja-star-item">
                    <div class="kawankerja-star-item-top">
                        <span class="kawankerja-star-badge">${letter}</span>
                        <strong class="kawankerja-star-label">${label}</strong>
                        <span class="kawankerja-star-score">${scoreStr}</span>
                    </div>
                    <div class="kawankerja-star-bar"><div class="kawankerja-star-fill" style="width: ${pct}%"></div></div>
                    <p class="kawankerja-star-note">${note}</p>
                </div>`;
        }
    });

    return `
        <div class="kawankerja-star-card">
            <div class="kawankerja-star-header">
                <h4><i class="ph-fill ph-chart-polar" aria-hidden="true"></i> STAR Assessment Scorecard</h4>
                ${overallBadge}
            </div>
            <div class="kawankerja-star-body">
                ${itemsHtml}
            </div>
        </div>`;
});
```

- [ ] **Step 5: Style STAR Scorecard in `resources/css/kawankerja.css`**

Add responsive, clean layout with progress meters, purple badge icons, and subtle cards.

- [ ] **Step 6: Run tests and commit**

Run: `php artisan test --filter=KawanKerja`
Expected: PASS.

```bash
git add app/Services/KawanKerjaService.php resources/views/components/kawankerja-chat.blade.php resources/css/kawankerja.css tests/Unit/KawanKerjaServiceTest.php
git commit -m "feat(hiremate): add visual STAR framework assessment scorecard"
```

---

### Task 4: Real-Time SSE Streaming Output (Word-by-Word)

**Files:**
- Modify: `routes/web.php:260-275`
- Modify: `app/Services/KawanKerjaService.php:220-270`
- Modify: `app/Http/Controllers/KawanKerjaChatController.php:30-65`
- Modify: `resources/views/components/kawankerja-chat.blade.php:170-220`
- Test: `tests/Feature/KawanKerjaStreamTest.php`

**Interfaces:**
- Consumes: Gemini API `streamGenerateContent?alt=sse&key=...`
- Produces: Route `route('kawankerja.stream')` returning `text/event-stream` with chunk events.
- Client: Alpine.js uses `fetch()` with `ReadableStream` reader, progressively appending to `messages[last].content` while auto-scrolling.

- [ ] **Step 1: Write test for streaming endpoint**

```php
// tests/Feature/KawanKerjaStreamTest.php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KawanKerjaStreamTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_stream(): void
    {
        $response = $this->post(route('kawankerja.stream'), ['message' => 'Hello']);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_stream_route(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('kawankerja.stream'), [
            'message' => 'Test message',
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('text/event-stream', $response->headers->get('Content-Type'));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=KawanKerjaStreamTest`
Expected: FAIL (route `kawankerja.stream` not defined).

- [ ] **Step 3: Define route in `routes/web.php`**

```php
Route::post('/kawankerja/stream', [KawanKerjaChatController::class, 'stream'])->name('kawankerja.stream');
```

- [ ] **Step 4: Implement `chatStream` in `KawanKerjaService.php`**

Implement streaming using Gemini API `streamGenerateContent?alt=sse`:
```php
public function chatStream(string $message, User $user, array $history, callable $onChunk): array
```
Reads SSE chunks line-by-line, decodes candidates text, and calls `$onChunk($text)`.

- [ ] **Step 5: Implement `stream` method in `KawanKerjaChatController.php`**

Returns `response()->stream(...)` with `Content-Type: text/event-stream`:
```php
public function stream(Request $request): StreamedResponse
```
Sends SSE lines: `data: {"chunk": "..."}\n\n` and `data: [DONE]\n\n`. Updates session history at completion.

- [ ] **Step 6: Update Alpine.js `send()` in `kawankerja-chat.blade.php` to stream chunks**

Use `ReadableStreamDefaultReader` with text decoder.
If streaming fails or response is not SSE, fall back to standard `chatUrl` POST request.

- [ ] **Step 7: Run tests and verify**

Run: `php artisan test --filter=KawanKerja`
Expected: PASS.

- [ ] **Step 8: Build frontend and commit**

```bash
npm run build
git add routes/web.php app/Services/KawanKerjaService.php app/Http/Controllers/KawanKerjaChatController.php resources/views/components/kawankerja-chat.blade.php tests/Feature/KawanKerjaStreamTest.php
git commit -m "feat(hiremate): add real-time SSE streaming response output"
```
