# Gemini-Inspired Dark Mode Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a comprehensive, Gemini-inspired dark mode across TraKerja featuring deep obsidian canvases, elevated matte surfaces, an ambient aurora glow, and a seamless 1-click header toggle.

**Architecture:** We use Tailwind CSS `darkMode: 'class'` combined with semantic CSS custom properties and Alpine.js state management. An anti-FOUC script in the HTML `<head>` prevents white flashes on load, while an ambient fixed radial backdrop renders Gemini's cosmic depth behind elevated matte UI cards.

**Tech Stack:** Laravel 11, Blade, Tailwind CSS v3, Alpine.js, Livewire, Vite.

**Spec:** [docs/superpowers/specs/2026-10-14-gemini-dark-mode-design.md](file:///Users/Luthfi/Project/PT.%20Teknalogi%20Transformasi%20Digital/TraKerja/docs/superpowers/specs/2026-10-14-gemini-dark-mode-design.md)

## Global Constraints
- Canvas background must strictly use Deep Obsidian `#131314` (never harsh pure `#000000`).
- Card/surface background must use `#1e1f20` with elevated hover/input states at `#282a2c`.
- Subtle borders must use `rgba(255, 255, 255, 0.07)` in dark mode.
- Primary text must use `#f0f4f9` and secondary text `#94a3b8`.
- Dark mode preference must persist in `localStorage.theme` and auto-sync with `prefers-color-scheme`.
- No FOUC (flash of unstyled content) allowed on page refresh or navigation.

---

### Task 1: Enable Tailwind Dark Mode & Define Core Gemini CSS Tokens

**Files:**
- Modify: `tailwind.config.js:1-86`
- Create: `resources/css/gemini-dark.css`
- Modify: `resources/css/app.css:48-60`

**Interfaces:**
- Consumes: Tailwind v3 compiler
- Produces: CSS utility classes with `dark:` prefix and `.gemini-ambient-glow` class

- [ ] **Step 1: Update tailwind.config.js with darkMode: 'class'**
Add `darkMode: 'class'` to `tailwind.config.js` and extend colors with obsidian palette:
```javascript
export default {
  darkMode: 'class',
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        gemini: {
          canvas: "#131314",
          surface: "#1e1f20",
          elevated: "#282a2c",
          border: "rgba(255, 255, 255, 0.07)",
          text: "#f0f4f9",
          muted: "#94a3b8",
        },
        // ... existing colors
```

- [ ] **Step 2: Create resources/css/gemini-dark.css**
Define semantic dark variables, scrollbar styling, and the Gemini ambient radial aura:
```css
/* Gemini Dark Mode Tokens & Ambient Backdrop */
:root {
    --gemini-canvas: #fafafa;
    --gemini-surface: #ffffff;
    --gemini-elevated: #f1f5f9;
    --gemini-border: #e2e8f0;
    --gemini-text-primary: #0f172a;
    --gemini-text-muted: #64748b;
}

html.dark {
    --gemini-canvas: #131314;
    --gemini-surface: #1e1f20;
    --gemini-elevated: #282a2c;
    --gemini-border: rgba(255, 255, 255, 0.07);
    --gemini-text-primary: #f0f4f9;
    --gemini-text-muted: #94a3b8;
    color-scheme: dark;
}

/* Gemini Ambient Aurora Glow */
.dark .gemini-ambient-glow {
    position: fixed;
    top: -150px;
    left: 50%;
    transform: translateX(-50%);
    width: 1000px;
    height: 650px;
    background: radial-gradient(ellipse at center, rgba(30, 58, 138, 0.16) 0%, rgba(88, 28, 135, 0.12) 40%, transparent 75%);
    filter: blur(90px);
    pointer-events: none;
    z-index: 0;
}

/* Dark Scrollbar */
html.dark ::-webkit-scrollbar-thumb {
    background: #282a2c;
}
html.dark ::-webkit-scrollbar-thumb:hover {
    background: #3b3d40;
}
```

- [ ] **Step 3: Import gemini-dark.css in resources/css/app.css**
Add `@import './gemini-dark.css';` at line 48 before `@tailwind base;`.

- [ ] **Step 4: Verify build compilation**
Run: `npm run build`
Expected: Output built without CSS parsing errors.

- [ ] **Step 5: Commit**
```bash
git add tailwind.config.js resources/css/gemini-dark.css resources/css/app.css
git commit -m "feat(theme): configure tailwind darkMode and gemini dark tokens"
```

---

### Task 2: Layout Foundation, Anti-FOUC Script & Ambient Glow

**Files:**
- Modify: `resources/views/layouts/app.blade.php:1-125`

**Interfaces:**
- Consumes: `localStorage.theme` and `gemini-dark.css`
- Produces: Seamless anti-FOUC dark initialization and background glow element

- [ ] **Step 1: Add inline anti-FOUC script to <head>**
In `resources/views/layouts/app.blade.php`, insert inside `<head>` right before CSS links:
```html
<script>
    (function() {
        try {
            var theme = localStorage.getItem('theme');
            var supportDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (!theme && supportDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {}
    })();
</script>
```

- [ ] **Step 2: Update <body> and workspace container classes**
Update `body` in `resources/views/layouts/app.blade.php`:
Change:
```html
<body class="font-sans antialiased bg-[#fafafa] text-slate-900">
```
To:
```html
<body class="font-sans antialiased bg-[#fafafa] dark:bg-[#131314] text-slate-900 dark:text-[#f0f4f9] transition-colors duration-200">
    <div class="gemini-ambient-glow" aria-hidden="true"></div>
```

- [ ] **Step 3: Verify no visual regression in browser**
Inspect `http://127.0.0.1:8000/dashboard` in browser and confirm light mode remains unchanged, and adding class `dark` manually via DevTools toggles background to `#131314`.

- [ ] **Step 4: Commit**
```bash
git add resources/views/layouts/app.blade.php
git commit -m "feat(theme): add anti-FOUC script and gemini ambient glow to layout"
```

---

### Task 3: Theme Toggle Pill Component in Navigation Header

**Files:**
- Modify: `resources/views/layouts/navigation.blade.php:20-54`

**Interfaces:**
- Consumes: Alpine.js & `localStorage`
- Produces: Reactive toggle button triggering `theme-changed` DOM event

- [ ] **Step 1: Add toggle button next to profile menu in navigation.blade.php**
In `resources/views/layouts/navigation.blade.php` inside `<div class="workspace-account-actions">`:
```html
<!-- Theme Toggle Button -->
<div x-data="{
    isDark: document.documentElement.classList.contains('dark'),
    toggle() {
        this.isDark = !this.isDark;
        if (this.isDark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: this.isDark } }));
    }
}" class="flex items-center">
    <button type="button" 
            @click="toggle()" 
            class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-200 text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-amber-300 dark:hover:text-amber-200 dark:bg-[#1e1f20] dark:hover:bg-[#282a2c] dark:border dark:border-white/10 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20"
            :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
            :title="isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'">
        <i x-show="!isDark" class="ph ph-moon text-lg transition-transform duration-200 hover:scale-110" aria-hidden="true"></i>
        <i x-show="isDark" x-cloak class="ph ph-sun text-lg transition-transform duration-200 hover:scale-110" aria-hidden="true"></i>
    </button>
</div>
```

- [ ] **Step 2: Test toggle functionality in browser**
Click the Sun/Moon toggle button.
Confirm:
1. `document.documentElement` gains and loses the `dark` class instantly.
2. Value in `localStorage.getItem('theme')` toggles between `'dark'` and `'light'`.
3. Refreshing the browser preserves the dark mode without flashing.

- [ ] **Step 3: Commit**
```bash
git add resources/views/layouts/navigation.blade.php
git commit -m "feat(theme): add 1-click theme toggle pill to navigation header"
```

---

### Task 4: Workspace Navigation, Topbar & Account Dropdown Dark Styling

**Files:**
- Modify: `resources/css/workspace.css:1-120`
- Modify: `resources/css/account-dialogs.css:1-80`
- Modify: `resources/css/workspace-footer.css:1-50`

**Interfaces:**
- Consumes: `.dark` class on root
- Produces: Sleek `#1e1f20` / `#282a2c` matte surfaces for navigation bars, menus, and footers

- [ ] **Step 1: Add dark rules to workspace.css**
Add styles for `.dark .workspace-topbar`, `.dark .workspace-nav-link`, `.dark .workspace-brand`, `.dark .workspace-dropdown`, and `.dark .workspace-menu-link`:
```css
.dark .workspace-topbar {
    background: rgba(19, 19, 20, 0.85);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.07);
}
.dark .workspace-brand span {
    color: #f0f4f9;
}
.dark .workspace-nav-link {
    color: #94a3b8;
}
.dark .workspace-nav-link:hover {
    color: #f0f4f9;
    background: #1e1f20;
}
.dark .workspace-nav-link[aria-current="page"] {
    color: #f0f4f9;
    background: #1e1f20;
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.dark .workspace-profile-trigger {
    border-color: rgba(255, 255, 255, 0.07);
    background: #1e1f20;
}
.dark .workspace-profile-trigger:hover {
    background: #282a2c;
}
.dark .workspace-account-copy strong {
    color: #f0f4f9;
}
.dark .workspace-account-copy span {
    color: #94a3b8;
}
.dark .workspace-dropdown {
    background: #1e1f20;
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}
.dark .workspace-menu-link {
    color: #cbd5e1;
}
.dark .workspace-menu-link:hover {
    background: #282a2c;
    color: #f0f4f9;
}
```

- [ ] **Step 2: Add dark rules to workspace-footer.css and dialogs**
Ensure footer and confirmation modal dialogs use `#1e1f20` with subtle border and crisp `#f0f4f9` text.

- [ ] **Step 3: Verify navigation bar and profile dropdown appearance**
Open profile dropdown in dark mode. Confirm smooth borders, solid elevated surface, and legible text.

- [ ] **Step 4: Commit**
```bash
git add resources/css/workspace.css resources/css/account-dialogs.css resources/css/workspace-footer.css
git commit -m "feat(theme): style navigation topbar, dropdown, and footer for dark mode"
```

---

### Task 5: Kanban Job Tracker, Cards & Summary Views Dark Mode

**Files:**
- Modify: `resources/css/tracker.css`
- Modify: `resources/css/tracker-density.css`
- Modify: `resources/css/dashboard-welcome.css`
- Modify: `resources/css/dashboard-panels.css`

**Interfaces:**
- Consumes: `.dark` class
- Produces: Matte `#1e1f20` Kanban columns & draggable cards with elevated hover states

- [ ] **Step 1: Add dark rules for Kanban board & columns in tracker.css**
```css
.dark .tracker-column,
.dark .kanban-column {
    background: rgba(30, 31, 32, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.06);
}
.dark .tracker-card,
.dark .job-card {
    background: #1e1f20;
    border: 1px solid rgba(255, 255, 255, 0.07);
    color: #f0f4f9;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}
.dark .tracker-card:hover,
.dark .job-card:hover {
    background: #282a2c;
    border-color: rgba(255, 255, 255, 0.12);
}
.dark .tracker-card-title {
    color: #f0f4f9;
}
.dark .tracker-card-company {
    color: #94a3b8;
}
```

- [ ] **Step 2: Add dark rules for Dashboard Panels & Metrics**
Style metric summary cards, welcome hero cards, and action buttons in `dashboard-welcome.css` and `dashboard-panels.css`.

- [ ] **Step 3: Verify Kanban Tracker page**
Visit `/tracker` in dark mode. Test dragging cards, inspecting badges, and clicking lowongan cards.

- [ ] **Step 4: Commit**
```bash
git add resources/css/tracker.css resources/css/tracker-density.css resources/css/dashboard-welcome.css resources/css/dashboard-panels.css
git commit -m "feat(theme): add dark mode styling for kanban tracker and dashboard panels"
```

---

### Task 6: Final Verification & Production Build Validation

**Files:**
- Verify: Full visual check on `/dashboard`, `/tracker`, `/summary`, and `/extension`

- [ ] **Step 1: Verify all key views in dark mode**
Visit each core route in browser:
- `http://127.0.0.1:8000/dashboard`
- `http://127.0.0.1:8000/tracker`
- `http://127.0.0.1:8000/summary`
- `http://127.0.0.1:8000/extension`

- [ ] **Step 2: Verify zero FOUC on page refresh**
Hard refresh with Cmd+Shift+R while in dark mode. Confirm no white flash occurs.

- [ ] **Step 3: Run production build check**
Run: `npm run build`
Expected: Production build compiles cleanly with all CSS assets.

- [ ] **Step 4: Commit final polish if needed**
```bash
git add -A
git commit -m "chore: finalize gemini dark mode integration"
```
