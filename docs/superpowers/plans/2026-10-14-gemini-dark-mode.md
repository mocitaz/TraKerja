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

- [x] **Step 1: Update tailwind.config.js with darkMode: 'class'**
- [x] **Step 2: Create resources/css/gemini-dark.css**
- [x] **Step 3: Import gemini-dark.css in resources/css/app.css**
- [x] **Step 4: Verify build compilation**
- [x] **Step 5: Commit**

---

### Task 2: Layout Foundation, Anti-FOUC Script & Ambient Glow

**Files:**
- Modify: `resources/views/layouts/app.blade.php:1-125`

**Interfaces:**
- Consumes: `localStorage.theme` and `gemini-dark.css`
- Produces: Seamless anti-FOUC dark initialization and background glow element

- [x] **Step 1: Add inline anti-FOUC script to <head>**
- [x] **Step 2: Update <body> and workspace container classes**
- [x] **Step 3: Verify no visual regression in browser**
- [x] **Step 4: Commit**

---

### Task 3: Theme Toggle Pill Component in Navigation Header

**Files:**
- Modify: `resources/views/layouts/navigation.blade.php:20-54`

**Interfaces:**
- Consumes: Alpine.js & `localStorage`
- Produces: Reactive toggle button triggering `theme-changed` DOM event

- [x] **Step 1: Add toggle button next to profile menu in navigation.blade.php**
- [x] **Step 2: Test toggle functionality in browser**
- [x] **Step 3: Commit**

---

### Task 4: Workspace Navigation, Topbar & Account Dropdown Dark Styling

**Files:**
- Modify: `resources/css/workspace.css:1-120`
- Modify: `resources/css/account-dialogs.css:1-80`
- Modify: `resources/css/workspace-footer.css:1-50`

**Interfaces:**
- Consumes: `.dark` class on root
- Produces: Sleek `#1e1f20` / `#282a2c` matte surfaces for navigation bars, menus, and footers

- [x] **Step 1: Add dark rules to workspace.css**
- [x] **Step 2: Add dark rules to workspace-footer.css and dialogs**
- [x] **Step 3: Verify navigation bar and profile dropdown appearance**
- [x] **Step 4: Commit**

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

- [x] **Step 1: Add dark rules for Kanban board & columns in tracker.css**
- [x] **Step 2: Add dark rules for Dashboard Panels & Metrics**
- [x] **Step 3: Verify Kanban Tracker page**
- [x] **Step 4: Commit**


---

### Task 6: Final Verification & Production Build Validation

**Files:**
- Verify: Full visual check on `/dashboard`, `/tracker`, `/summary`, and `/extension`

- [x] **Step 1: Verify all key views in dark mode**
- [x] **Step 2: Verify zero FOUC on page refresh**
- [x] **Step 3: Run production build check**
- [x] **Step 4: Commit final polish if needed**
