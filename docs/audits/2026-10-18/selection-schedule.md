# Selection scheduling — 18 October 2026

- The application form and calendar editor use stage-specific headings for interviews, psychometric tests, assessments, group discussions and presentations. Date/time labels specify WIB.
- Delivery choices are Online and Onsite, with Phone call available for interviews. Existing Video, In-person, Phone and Panel records remain editable without silently changing their recorded values.
- Online schedules can store an optional platform; access links, venue/address, call instructions and preparation notes have contextual labels. Switching to Onsite clears the online platform on save.
- The calendar details, application detail, reminder/confirmation emails and CSV round trip use the same delivery labels and platform data.
- The platform input is hidden until its database column exists. Existing schedule saves remain supported before migration.

## Activation and deployment

The user approved and the local environment successfully applied only `2026_10_18_140000_add_interview_platform_to_job_applications.php`. Verified that the platform column is available and Explore remains in maintenance mode. No production migration was executed.

Deployment requires the new migrations and a frontend asset build on the server. Database settings such as the Explore maintenance switch are environment-specific and are not changed by pushing Git commits.

## Verification

- Selection/calendar/CSV, ownership, stale edits, legacy formats, all six schedulable stages, email preferences, and operation before the platform migration are covered by isolated tests using SQLite in memory.
- Rendered synthetic forms were checked in English/Indonesian, on mobile and desktop, and in light/dark mode. The 320px calendar editor scrolls internally and keeps its actions reachable without horizontal page overflow.
- Visual fixtures do not replace an authenticated browser end-to-end test or testing on a physical phone. No real emails, external meetings or production data were exercised.
- Older CV workspace assertions were corrected to inspect actual PDF content and the validated layout values sent to the renderer, instead of searching PDF bytes for HTML/CSS.
