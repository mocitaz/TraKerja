# Explore and interview calendar — 18 October 2026

## Explore

- Added separate, owner-scoped bookmarks. Saving does not create a job application or award application XP. Closed bookmarked listings remain removable.
- Added All jobs / Saved for later / Already applied views. Applications are matched using the source URL instead of company/title alone. Existing application rows are not rewritten.
- Recording an application asks for the applied date, rejects future dates, and requires a real location if the listing did not provide one. Repeated recording of the same source URL is serialized per account and does not create another application through this path.
- Added up to 20 named searches per account, with apply/remove actions. Keywords, filters, collection and sort are remembered; explicit URL filters override remembered defaults.
- Reduced cards to one main details action; bookmark and application recording are separate secondary actions. Matching CV skills appear first. Source checks/reporting remain available under More.
- Added concise English/Indonesian feedback and corrected the listing-link label.
- Added three tables with owner foreign keys and uniqueness constraints. Before migration, browsing degrades gracefully with storage actions unavailable.

## Interview calendar

- Schedule editing now occurs in the existing calendar modal, preserving the selected month/day and application stage. Date, time, format, location and notes are validated against storage limits.
- Saves re-check ownership and the original schedule fingerprint under a row lock; stale edits are rejected. Changed schedule times follow existing email entitlement/preferences. Notes-only edits do not send another scheduled email.
- Added Today / Next 7 days agenda, paginated, across month boundaries. The upcoming sidebar also crosses months. The monthly calendar and its metrics retain the selected month scope.
- The Today / Next 7 days agenda sits below the calendar and metrics, as requested. Times are larger, metric colors quieter, and weekdays/stages/formats translated.

## Verification

- 20 tests pass across Explore, interview calendar and bilingual catalogue. Coverage includes bookmark/application separation, duplicate source recording, distinct postings with the same title, required location, date validation, owner isolation, named-search limits/default restoration, missing tables, cross-month agenda, inline editing, stale edits and notification preferences.
- All 241 Blade views compile; frontend build and whitespace checks pass.
- Synthetic server-rendered pages inspected in Chromium at 320px, 768px and 1366px, English and Indonesian: no viewport overflow. Additional 390px dark-mode checks passed for both pages. Saved search with a long name and saved-job/skill-match states were inspected.
- Schedule edit modal inspected at 390x600: panel fits the viewport, body scrolls and footer remains accessible. Livewire loading markup in static fixtures is not evidence of runtime duplicate buttons.
- Visual fixtures use an Alpine/Livewire stub; behavior is covered separately by server/Livewire tests. No physical device, external meeting provider, real applicant email, or production deployment was exercised.

## Local activation

The user approved the local migration `2026_10_18_120000_create_explore_workspace_tables.php`. The workspace tables are ready. Explore is currently disabled again at the user’s request: verified `workspace_ready=true` and `explore_maintenance=true`. Production settings were not modified. The scheduling-platform migration is documented separately in selection-schedule.md.
