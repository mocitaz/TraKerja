# Payments and plans review

Direction: merge transaction operations and premium configuration into the existing compact TraKerja admin design. Energy 1, rhythm 2, motion 1. Preserve real transaction data and confirmation boundaries.

- Hard gate PASS: one Payments & plans sidebar entry, with Transactions and Plans & access panels. Legacy monetization URL redirects to the plans panel. Statistics, ledger and CSV share filters. No fabricated revenue or gateway-health claims; unspecified environments remain unspecified.
- Purpose gate PASS: receipt, status and payment icons describe their adjacent controls. Status colors distinguish recorded states. Borders group ledger/configuration areas; the tab underline marks location. Existing type and restrained motion keep hierarchy consistent with dashboard/users.
- Liveliness PASS: customer avatars, order references, gateway identifiers, precise environment labels and feature-access copy make the workspace specific to actual TraKerja operations.
- Craftsmanship PASS: desktop 1440 px, tablet 768 px, mobile 390/320 px reviewed. Mobile tables become readable record cards. Dark Indonesian screens and light English screens checked; document width equals viewport width at 320/390/768. Detail dialogs fit the narrow viewport; Escape restores the Details button focus.

Interaction evidence: both tabs update the URL while preserving filters; arrow keys switch/focus tabs. Detail buttons fetch and display isolated QA transactions. Modal Close/Escape restores focus. Premium mode opens a confirmation, then Escape cancels it. Filter, CSV, pagination, price validation, saved price changes, mode confirmation and authorization are covered by backend tests. Cancellation uses a mocked gateway; production payments and prices were not changed. JS tests cover stale detail responses and uncertain cancellation errors. Loading, failure, no records and no matching records have explicit states.

Browser data: separate migrated SQLite database in /tmp with QA-prefixed transactions and QA users. The QA server used a separate session cookie. No public storage cleanup or outbound gateway action was invoked during browser checks.

Validation: 27 Laravel tests passed (33,292 assertions), seven JS tests passed, production Vite build passed, git diff whitespace check passed. Old PHPUnit doc-comment deprecation notices are unrelated to these changes.
