# Admin email composer and campaign history

Scope: /admin/email-blast, /admin/email-blast/history, their dialogs, and a reviewed Gemini template library.

Design read: an operational email composer for TraKerja administrators, following the established compact neutral admin language. ENERGY 1 / RHYTHM 2 / MOTION 1.

Reasons for the major choices:
- Color and type reuse the dashboard's neutral surfaces, blue focus accent and Plus Jakarta Sans so this remains part of the same admin console.
- Five sequential steps follow the requested audience → message → language → optional banner → review workflow. Future steps stay locked; changing earlier choices invalidates campaign preview.
- The side summary provides context without repeating editable controls. It moves below the form on smaller screens.
- Audience choices show actual eligible account counts, while the final preview fetches the exact recipient list, including the verification-only restriction.
- Ready-made, saved and custom sources serve distinct composition needs; Gemini lives within message preparation rather than being another mandatory step.
- Generated copy stays inside the established email shell: logo, personalized greeting, optional CTA/banner, signoff and footer. No model-generated HTML is executed.
- Shared compact statistics, filters and tables align history with the member directory. Phone rows become readable record cards, with exact WIB timestamps and outcome labels.
- Dialogs share boundaries, typography, controls and focus handling with other admin dialogs. Disclosure keeps technical failures and recipient lists accessible without dominating the first view.

Implementation and data boundaries:
- Existing mailable types are centralized in EmailTemplateCatalog; the preview and sending endpoints now use the same construction path.
- Explicit campaign language overrides recipient preferences for that campaign, including verification mail. Other verification flows retain their existing locale defaults.
- Gemini receives the administrator's brief and editorial references from existing TraKerja copy, without account lists. It returns validated structured plain text. Missing configuration, timeouts, provider errors and invalid output have visible failure paths.
- Saving a template requires an explicit administrator review and an HMAC token for a preview of the exact normalized draft, bound to that administrator. Editing invalidates the client review; a changed server payload fails token validation.
- New campaign logs retain template name, subject, language and banner. Old records honestly show Not recorded where metadata was never stored. Counts distinguish successful, failed/unconfirmed and missing outcomes; SMTP acceptance is not presented as inbox delivery or an open.
- Two email migrations were applied locally. The initial migration command also applied an already-pending payments identity migration that adds two nullable columns; existing rows were retained. No payment source files were changed in this task.

Delivery gate:
- R-01/R-04/R-06/R-07/R-08/R-09/R-10/R-11/R-12/R-13/R-14/R-19/R-22/R-29/R-30/R-31 PASS: major choices are explained above. No new decorative media, background patterns, gradients, glow, generic AI icon or entrance animations. Semantic account, email, edit and delivery icons use the existing icon family. Elevation is reserved for dialogs.
- R-02/R-15/R-16/R-20/R-21/R-37 PASS: direct task-specific labels, established TraKerja direction, working light/dark modes and declared dials. No marketing filler added.
- R-03/R-05/C-1/C-3/C-4 PASS: inspected the composer and history at 320, 390, 768 and 1440px. All five composer steps report page scrollWidth equal to viewport width at 320px. Tablet history scrolls its table within the wrapper. Indonesian and English labels were inspected; no page overflow observed.
- R-17/R-18/R-23/R-36/R-38/C-5 PASS: counts, identities, historical errors and banner assets come from existing records/assets. No invented testimonials, members, campaign outcomes or inbox/open claims. Progress visual states were exercised only in temporary client memory; delivery behavior was tested using fakes.
- R-24/R-26/R-27/C-2 PASS: audience validation, message-source selection, required custom copy/CTA pairing, language, banner/no-banner branches, final preview, desktop/mobile preview controls, recipient confirmation, history filtering, empty history and record details have concrete behavior. Tests cover pause/stop-related outcomes, delivery errors, storage failure, generation failure and invalid draft rejection.
- R-25/R-32 PASS: established readable foreground/surface tokens retained, visible keyboard focus, associated form labels, keyboard-accessible radio choices/disclosures, labeled sandboxed preview frames, and the existing dialog focus manager. History detail receives focus; Escape closes it and restores the original Details button.
- R-33/R-34 PASS: changes live in source files and migrations. Light/dark screenshots and Indonesian rendering inspected for the new flows and history; review preferences restored afterward.
- R-35 PASS: production build, 42 relevant Laravel tests and 7 JavaScript tests pass; git diff --check passes. All predefined campaign mailables render in both languages. Template generation was also exercised against the configured Gemini provider and the resulting draft previewed successfully. No email was sent to users during validation; sending and library persistence tests use Mail/HTTP fakes and the isolated testing database.
- Delivery gate PASS: hard gate, purpose gate, liveliness and craftsmanship checks above are satisfied for this scope.

Provider reference used during implementation: https://ai.google.dev/gemini-api/docs/generate-content/structured-output
