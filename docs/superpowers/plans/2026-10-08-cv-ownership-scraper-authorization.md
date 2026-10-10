# A04 CV ownership and A05 scraper authorization

The user authorized these fixes from the audit. Execute inline with test-driven-development, then independent code review.

- [x] Reproduce foreign edit/save/delete/reorder across skills, experience, education, projects, organizations and achievements; check legitimate owner CRUD.
- [x] Replace global CV lookups with authenticated-user relations, create through those relations and omit user_id from edited fields.
- [x] Reject non-admin scraper mount, reads and actions before validation or side effects; preserve admin route and persistent Livewire guards already added for A03.
- [x] Remove automatic source seeding from scraper visits and keep existing explicit ScraperSourceSeeder.
- [x] Verify focused/full tests against the eight known baseline failures; review diff and ownership/action boundaries.

No production data, provider calls, queue workers, or real scraping during verification. Use SQLite memory and fake HTTP/queue. CV preview/export and portfolio were inspected for user scoping; avoid unrelated UI or scraping behavior changes.

Verification: 122 focused tests passed with 491 assertions. Full suite: 210 tests, 202 passed, the same eight baseline failures (ExploreJobs copy assertions and legacy export routes), zero errors. PHP lint and git diff --check passed. Independent review found a certificate URL validation-key regression; corrected and covered by a failing-then-passing regression test. No remaining review blockers.

Scraper defaults are now created only through the existing explicit ScraperSourceSeeder; visiting /admin/scraper leaves an empty source table untouched. No new database migration is required for A04/A05.
