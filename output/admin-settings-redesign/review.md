# Settings workspace review

Direction: follow the existing TraKerja dashboard and member pages, with compact neutral surfaces, blue navigation cues, and Plus Jakarta Sans. Energy 1, rhythm 2, motion 1. No new visual assets.

- Hard gate PASS: one Settings sidebar link; legacy integrations and maintenance URLs redirect to the matching section. Forms retain authorization and validation. Provider labels describe configuration presence, not service uptime. Actual database and file scan values replace placeholders.
- Purpose gate PASS: section icons identify general settings, connections, and maintenance; the sticky navigation supports a long page. Dividers separate independently saved forms. Blue indicates the current location. Neutral panels group operational controls without decorative effects.
- Liveliness PASS: the workspace follows the supplied compact admin reference while retaining TraKerja typography, sidebar, member links, and specific provider/maintenance information.
- Craftsmanship PASS: browser review at 320, 390, 768, and 1440 px; light/dark and English/Indonesian. Final Indonesian dark check at 320 px returned document width 320 px. Anchor navigation placed Integrations below the sticky navigation at 155 px. Reduced motion and section tracking have JS tests.

Interaction evidence: General forms validate and save independently in automated tests; integration links select and focus their heading; key replacement opens a review dialog; webhook validation rejects unsupported/missing URLs; maintenance dialogs open and close by Cancel/Escape with focus restoration. File scanning, refresh, cleanup scope and failure handling run against a fake disk. Maintenance access changes run only against the test database. No live cleanup or key rotation was performed.

Validation: combined final run with billing, admin operations/navigation, and language catalogue: 27 Laravel tests passed (33,292 assertions). Seven settings/billing JS tests passed. Production Vite build and git diff whitespace check passed. Existing unrelated PHPUnit doc-comment deprecation notices remain.
