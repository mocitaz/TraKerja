# Design Specification: KawanKerja AI Career Assistant Chatbot

**Date:** 2026-10-13  
**Status:** In Review  
**Component:** User Workspace / Floating AI Companion  
**Author:** TraKerja Team  

---

## 1. Executive Summary & Goals

TraKerja currently features an executive AI Copilot for Administrators on Telegram, alongside asynchronous single-action tools in AI Studio (Cover Letter and AI Photo processing). However, jobseekers currently lack an interactive, in-app conversational companion to assist them throughout their daily job-hunting process.

**KawanKerja** is an in-app interactive AI career companion powered by the Gemini API (`gemini-3.5-flash-lite` with fallback to `gemini-3.8-flash`). It appears as an elegant floating chat widget in the bottom-right corner across all authenticated workspace pages. 

Unlike generic chatbots, KawanKerja is **context-aware**: it understands the user's career profile, their active job applications in the Tracker, and their saved resumes in the Resume Vault, enabling personalized mock interviews, STAR-method answer drafting, follow-up messages, and career advice.

### Key Success Criteria:
1. **Persona & Brand Alignment**: Uses the selected "Smart Vector with Glasses" avatar (`public/images/kawankerja.png`), projecting a warm, highly professional, supportive career mentor persona.
2. **Context-Aware Intelligence**: Injects the user's profile, active applications, and resume vault context into Gemini's system instructions.
3. **High Reliability & Low Latency**: Uses `gemini-3.5-flash-lite` as the primary engine with automatic fallback to `gemini-3.8-flash`.
4. **Seamless UI Replacement**: Replaces the old static `Prepare with AI` pill button with an interactive floating drawer/modal.
5. **Universal Access**: Available to all logged-in users without artificial paywall gates for this release.

---

## 2. Architecture & Data Flow

```
[User on Any Workspace Page]
       │
       ▼ (Clicks Floating Widget or Dashboard Next Move)
[KawanKerja Pop-up Chat Window]
       │
       ▼ (Submits Question / Prompt via AJAX)
[POST /kawankerja/chat] -> KawanKerjaChatController
       │
       ▼
[KawanKerjaService]
       ├─ Step 1: Extracts User Context (Name, Headline, Skills, Tracker Jobs, Resumes)
       ├─ Step 2: Assembles System Instruction + Chat History (Session)
       ├─ Step 3: Queries Gemini API (gemini-3.5-flash-lite)
       │          └─ Fallback to gemini-3.8-flash if primary encounters error
       └─ Step 4: Updates Session History and formats Markdown response
       │
       ▼
[JSON Response { success: true, reply: "...", history: [...] }]
       │
       ▼
[Chat Window Displays Response with Typing Animation & Auto-scroll]
```

---

## 3. Detailed Component Design

### 3.1. Frontend: Floating Chat Widget (`resources/views/components/kawankerja-chat.blade.php`)
- **Placement**: Fixed bottom-right corner (`bottom: 24px; right: 24px; z-index: 90`), responsive on mobile (`bottom: 76px; right: 16px`).
- **Trigger State (Collapsed)**:
  - Pill button featuring KawanKerja avatar (32px), glowing green "Online" indicator, label "KawanKerja", and a subtle sparkle badge.
  - Micro-interactions: Soft hover lift, glassmorphism border (`rgba(255,255,255,0.85)` with backdrop-filter blur).
- **Expanded State (Chat Window)**:
  - Width: 380px, Height: 540px, rounded-2xl (16px), subtle border (`#e4e4e7`), shadow-2xl.
  - On screens <= 640px, transforms into a slide-up bottom sheet with swipe-down handle.
  - **Header**:
    - KawanKerja avatar (36px).
    - Title: "KawanKerja" + subtitle "Asisten Karier TraKerja".
    - Controls: "Clear Chat" (reset session) and "Close" (collapse).
  - **Message Container**:
    - Welcome message when empty, with quick starter chips:
      - 🎯 *"Simulasi interview untuk lamaran terbaru"*
      - ⭐ *"Bantu susun jawaban metode STAR"*
      - ✉️ *"Buatkan draft follow-up pesan ke HR"*
      - 💡 *"Review kelemahan di daftar lamaran saya"*
    - User message: dark theme bubble (`#18181b` text white, aligned right).
    - KawanKerja message: light theme bubble (`#f4f4f5` text `#18181b`, aligned left) with Markdown parsing (bold, lists, code block styling).
    - Pulsing 3-dot typing indicator when waiting for API response.
  - **Input Bar**:
    - Auto-resizing textarea (1 to 4 lines).
    - Send button (disabled while generating or empty).
    - Helper hint: "Enter to send, Shift+Enter for new line".
  - **Global Event Hook**:
    - Listens to `window.addEventListener('open-kawankerja', (e) => ...)` so other components (e.g. Dashboard) can programmatically pop open the chat and insert a prompt.

### 3.2. Dashboard Integration (`resources/views/dashboard/next-move.blade.php`)
- Replace the legacy `<a href="{{ route('ai-studio.index') }}">Prepare with AI</a>` link with a button that triggers `window.dispatchEvent(new CustomEvent('open-kawankerja', { detail: { prompt: 'Tolong bantu persiapan interview saya' } }))`.

### 3.3. Backend: `KawanKerjaService` (`app/Services/KawanKerjaService.php`)
- **System Instruction**:
  - Persona: Friendly, empathetic, sharp, professional Indonesian career mentor ("KawanKerja").
  - Scope: Interview prep, behavioral questions (STAR), compensation negotiation, resume advice, cover letters, and recruitment guidance in Indonesia.
  - Format: Structured Markdown, bullet points, warm tone without unnecessary fluff.
- **Context Builder (`buildUserContext(User $user)`)**:
  - Profile: Full Name, Headline, Domicile, Skills (from `UserSkill`), Recent Experience.
  - Tracker Snapshot: Top 3 active applications (`company_name`, `position`, `recruitment_stage`, `interview_date`, `notes`).
  - Resumes Snapshot: Total count and primary resume title from `UserResume`.
- **API Call & Fallback Chain**:
  - Primary URL: `https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key={key}`
  - Fallback URL: `https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key={key}`
  - Timeout: 12 seconds.
  - Configuration: `temperature: 0.6`, `maxOutputTokens: 1000`.

### 3.4. Controller & Routes (`app/Http/Controllers/KawanKerjaChatController.php`)
- `POST /kawankerja/chat`:
  - Request validation: `message` (required, string, max 1000).
  - Rate limiting: Throttle 20 requests per minute per user.
  - Calls `KawanKerjaService::chat()`.
  - Saves message pair into `session('kawankerja_history')`.
  - Returns JSON `{ success: true, reply: "...", history: [...] }`.
- `GET /kawankerja/history`:
  - Returns current session history array.
- `POST /kawankerja/clear`:
  - Clears `session('kawankerja_history')`.
  - Returns JSON `{ success: true }`.

---

## 4. Error Handling & Edge Cases

1. **Gemini API Down / Rate Limited**:
   - Primary model fails -> automatically tries secondary model (`gemini-3.8-flash`).
   - If both fail or key is missing -> returns user-friendly Indonesian fallback message: *"Maaf, KawanKerja sedang mengalami kendala jaringan ke server AI. Coba tanyakan kembali beberapa saat lagi ya!"*
2. **Empty or Spammed Input**:
   - Front-end disables submit button when textarea is whitespace-only.
   - Server validates length (min 1, max 1000 characters).
3. **Session Loss**:
   - If session expires, chat gracefully resets with welcome state; does not break UI.
4. **Mobile Responsive Viewport**:
   - Adapts to high-z-index slide-up bottom drawer, leaving bottom navigation accessible or overlaying cleanly.

---

## 5. Testing & Verification

1. **Feature Tests (`tests/Feature/KawanKerjaChatTest.php`)**:
   - `test_unauthenticated_user_cannot_access_kawankerja_endpoints`: returns 401/302.
   - `test_authenticated_user_can_send_message_and_receive_response`: mocks Gemini HTTP endpoint, validates JSON structure and session storage.
   - `test_user_can_clear_chat_history`: validates session history reset.
   - `test_user_context_is_correctly_compiled`: verifies tracker and resume details are included in the prompt payload.
2. **Visual & Browser Verification**:
   - Verify floating widget on desktop and mobile viewports.
   - Verify trigger from Dashboard Next Move.
   - Verify asset compilation via `npm run build`.
