# Admin density audit

Direction: continue the existing TraKerja admin design with smaller, less dominant typography and tighter operational panels. Keep Plus Jakarta Sans, neutral surfaces, existing assets and blue selection/data cues. Energy 1, rhythm 2, motion 1. Shared statistics remain equal in size so adjacent measures are easy to compare. Tables and task panels supply the visual rhythm; no new decoration or feature was added.

## Changes

| Element | Before | After |
| --- | --- | --- |
| Main headings | 30–31 px maximum, weight 700 | 24 px maximum, weight 600; 22 px on phones |
| Desktop stat cards | 82–84 px minimum, 26 px values | 74 px minimum, 22 px values |
| Section headings | Commonly 16 px, weight 700 | 14–16 px, weight 600 |
| Email stage heading | 20–21 px, weight 700 | 17 px, weight 600 |
| Dashboard growth total | 38 px desktop, 34 px phone | 30 px desktop, 28 px phone |
| Analytics chart | 230 px maximum | 200 px maximum |
| Shared dialog title/body | 18 px bold title, 22 px padding | 16 px semibold title, 18 px padding |
| Main frame | 1600 px maximum; 38 px top padding | 1480 px maximum; 28 px top padding |

Reduced directory row and panel spacing, settings section gaps, composer audience tile height, and billing summary sizes. Admin profile adjustments are role-scoped, including its teleported dialog, so the member profile keeps its existing typography. Mobile fields retain 16 px type and existing touch targets. Existing small table text was preserved. Dashboard growth chart height remains 150 px desktop and 130 px phone to preserve inspection accuracy.

The tablet Users document overflow came from an absolutely positioned screen-reader label in the horizontally scrollable table, rather than its toolbar. Making the table wrapper a positioning context contains the label without clipping menus on the mobile card layout.

## Browser audit

Visited dashboard, users, member detail, activity log, analytics, email composer, campaign history, settings, transactions, plans, feedback, survey, and admin profile at 1440, 768, and 320 px. All document widths match their viewport after the table fix. Light desktop/phone and dark tablet checked. Additional 390 px screenshots inspected for dashboard, analytics product usage and avatar dialog. Desktop users and dark dashboard inspected visually.

Verified menu open/Escape, growth period selection, analytics tab switching, analytics member-detail dialog, profile avatar dialog and Escape dismissal. Analytics dialog measured 358 px inside a 390 px viewport, with a 16 px title; no console errors on the inspected flows. Existing behavior coverage remains in the page suites and JavaScript tests.

## Validation

- Production Vite build passed; git diff whitespace check passed.
- 39 Laravel tests passed, 272 assertions: AdminWorkspaceTest, AdminOperationsWorkspaceTest, AdminAnalyticsWorkspaceTest, AdminEmailWorkspaceTest, AdminMemberVoiceTest, ProfileSettingsWorkspaceTest.
- 25 JavaScript tests passed across analytics, email composer, feedback review, growth, payments, settings and profile.
- An additional broad JavaScript glob included three older tests in admin-workspaces.test.cjs that fail before assertions because their vm harness cannot parse the existing ESM imports. This CSS audit does not change those scripts or that older harness. The current per-workspace suites above pass. Existing PHPUnit doc-comment deprecation notices also remain.
- Browser QA used localhost:8001, a separate SQLite database and a distinct session cookie. No production data, files, email deliveries, prices or maintenance actions were changed.

## Delivery gate

- Hard gate PASS: all 13 inspected page variants fit at 320/768/1440 px after the table containment fix. Mobile form text remains 16 px. Existing focus, loading/error/empty states and real data sources remain. Neutral secondary text has contrast 5.52:1 on white and 9.03:1 in dark mode. No new assets, claims, navigation or nonfunctional controls were introduced.
- Purpose gate PASS: smaller titles and semibold weights reduce visual dominance; tighter panel spacing groups related operational data. Existing blue continues to identify active states and data. Identical statistics enable comparison. Existing logo, typeface, icon system, palette and elevation remain; no gradients, decorative arrows or animation were added.
- Liveliness PASS: energy 1, rhythm 2, motion 1. Page title and task content remain the focus, with blue selection cues and consistent neutral panels. Whitespace separates sections while compact rows and summaries vary density according to content.
- Craftsmanship PASS: source styles were edited in their existing workspace files, shared title/stat tokens keep pages aligned, and profile changes are admin-scoped. Production build, 39 Laravel tests, 25 current JavaScript tests and browser interactions above verify this change. The unrelated older harness limitation is explicitly recorded rather than represented as a passing full-suite run.

## Sidebar simplification follow-up

Removed language selection, theme switching and the User workspace link from the admin sidebar, including their unused styles. Profile navigation and sign-out remain. The existing profile Appearance & language panel still contains both language and theme controls. Collapsed sidebar text hiding and mobile close-button styles remain independent of the removed controls.

- Hard gate PASS: source inspection confirms the removed controls are absent from the sidebar and both preferences remain in profile; no new controls or data were added.
- Purpose gate PASS: removing duplicate preferences and the requested workspace link reduces sidebar clutter; existing branding and navigation styling remain.
- Liveliness PASS: energy 1, rhythm 2, motion 1 retained; active navigation and profile identity remain the sidebar's focal points.
- Craftsmanship PASS: 9 relevant Laravel tests passed with 66 assertions; production build and whitespace checks passed. The previous browser tab was no longer available for a fresh visual check; no new visual browser verification is claimed for this small removal.

## Pre-push verification update

The older admin-workspaces JavaScript harness mentioned above has now been updated to import ESM and set the current wizard/review prerequisites. All 28 admin/profile JavaScript tests pass. The admin language-control regression now verifies controls on Profile and their absence from the sidebar. The combined pre-push PHP run passed 243 tests (1,863 assertions), with three existing PHPUnit doc-comment deprecations. It used an explicit 512 MB test-process limit after the default 128 MB run exhausted memory. No application memory setting was changed.
