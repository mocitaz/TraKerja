# Reports and CSV round-trip implementation plan

User authorized A09/A10 inline execution. Preserve earlier fixes and use SQLite memory, fake HTTP and fake queues.

- [x] A09: Reproduce repeated-report abuse. Add unique `(job_posting_id,user_id)` ledger, locked transactional recording, and background verification through the safe URL fetcher. Reports alone never change status. Test replay, distinct users, DB uniqueness, guest, closed listings and verification outcomes.
- [x] A10: Reproduce export/import mismatch. Export a data-only schema and preserve text with reversible spreadsheet-safe encoding. Share file parsing/validation between preview and commit; expose row totals/errors and actual preview rows. Replace the fake CSV UI scan and examples while preserving layout.
- [x] Run focused/full tests, lint and build. Update prior reporting test for the intentionally changed behavior. Document migration and worker requirements, plus baseline failures.

No production migrations, queues or network requests are authorized or needed for validation.

Validation and remaining browser limitation: see `docs/security/reports-and-csv-deployment.md`.
