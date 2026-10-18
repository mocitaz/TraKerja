# White page backgrounds

The main canvas of all light-mode page families is now `#FFFFFF`: landing and its footer, auth/OTP/recovery, user workspace, admin workspace, legal/error pages, and the light portfolio themes. Dark-mode choices and component/status/accent colors remain in place.

Changes are made in the existing source tokens and layouts. `--workspace-background`, `--gemini-canvas`, `--auth-page`, and light `--port-bg` use white. The existing light-body safeguard now uses white and clears legacy background images. Admin's sticky header fade starts from white. Explicit outer backgrounds in older AI/import/photo templates and layout bodies are white. The older admin layout has a white light canvas and retains its dark canvas rule. Landing footer and mountain blend bases use white in light mode; the decorative mountain image remains.

Emails, printable CV documents, controls, cards and semantic status colors are not page canvases and were not recolored by a blanket override.

## Validation

- 21 existing tests passed, 971 assertions: auth presentation, admin workspace, legal/error utility pages, portfolio and landing.
- Production build and whitespace checks passed.
- Rendered 32 pages using synthetic accounts and isolated in-memory SQLite with fake mail/outbound HTTP. This includes landing, auth/recovery/OTP, legal/error, nine user views, three light portfolio themes and eleven admin views.
- Measured those rendered HTML fixtures in Chromium at 390px and 1440px: all 64 checks found white body and workspace page/shell backgrounds. The landing footer is also white. Changing the root to dark retained a non-white canvas on pages whose theme follows that setting. Portfolio theme colors are controlled separately by `data-theme`; their three light theme backgrounds were checked as white.
- Fixtures omit application JavaScript to isolate CSS measurements. Existing route/render/navigation behavior is covered by the relevant feature tests. No real user or admin account was accessed.
- Evidence: `output/white-page-backgrounds/checks.json` and selected admin, dashboard, auth and legal screenshots in that directory.

## Delivery gate

Design direction remains the existing TraKerja identity, energy 2 / rhythm 2 / motion 1. The requested white canvas unifies the page families; accents, typography, content and layout remain unchanged.

- Hard gate PASS: computed white canvases at 64 viewport/page checks, retained dark canvases, no new text, imagery, controls or navigation; build and whitespace checks passed.
- Purpose gate PASS: the white canvas implements the user's requested color; component and status colors continue to carry their existing hierarchy and meaning.
- Liveliness PASS: existing 2/2/1 direction and focal points retained; this change adds no animation or decoration.
- Craftsmanship PASS: source tokens and layout fallbacks agree, current admin and user shells were rendered and measured, representative screenshots inspected, and 21 related feature tests passed.
