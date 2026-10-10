# Scrape, premium and job status implementation plan

> **For agentic workers:** Use executing-plans and dispatching-parallel-agents for the independent fixes authorized by the user.

**Goal:** Fix audit findings A06–A08 without changing unrelated product flows.

**Architecture:** Fetch job pages through a domain and public-DNS boundary with pinned connections and checked redirects. Centralize active premium checks and preserve remaining renewal duration. Use one status/stage contract, with a migration for historical values.

**Tech stack:** Laravel 12, Livewire 3, PHP, SQLite memory tests.

**Spec:** User audit findings A06, A07, A08 in this conversation.

## Constraints and review focus

Preserve earlier A01–A05 changes. No production database mutations or actual scraping/provider requests. Test DNS rebinding, private IPv4/IPv6, redirects, expired/renewed/legacy premium, and imported or historical application statuses. Network egress enforcement requires deployment controls outside the PHP repository; document that boundary explicitly.

- [x] A06: Write failing request tests in `tests/Feature/JobScrapeUrlSecurityTest.php`; implement `JobPageFetcher` plus `PublicDnsResolver` and `config/job_scraping.php`; replace only the download in `JobScraperController`. Restrict supported domains, HTTP(S) standard ports, all resolved addresses and every redirect; pin public IP and disable proxies/automatic redirects. Verify public-page parsing still succeeds.
- [x] A07: Write failing entitlement/renewal tests; centralize checks in `User::isPremium`, middleware and feature gates. Persist expiration explicitly through trusted writes; carry active remaining duration forward. Preserve explicitly documented legacy null-expiry grants.
- [x] A08: Write failing Explore/board/CSV/status tests; centralize statuses and recruitment stages; migrate aliases without losing stage information; reject unknown mutations. Use the contract in forms, views, exports and summaries.
- [x] Run focused and full PHPUnit suites in memory, PHP lint and diff check; independent review each security/contract boundary. Record known baseline failures separately.

Execution is already authorized by “gas lanjut”; no commit or production deployment is requested.

## Verification outcome

99 focused tests passed, 424 assertions. Full suite: 278 tests, 270 passed, eight unchanged baseline failures, zero errors. Baseline failures remain the four ExploreJobsTest copy expectations and four JobApplicationExportTest legacy route tests documented in the previous fixes. PHP lint passed for 41 changed/new PHP files; git diff --check passed.

Independent A06 review found no blockers. Independent A07 review found that unrelated admin profile edits renewed expired subscriptions; a red regression reproduced that grant and is green after restricting grants to explicit premium-flag transitions. Root reviewed the status integration and completed failing legacy-field/migration tests left by the status implementer; summary outcome tests now keep Offering separate from Accepted.

Code work is complete locally. Production status migration and network-layer egress deployment were not executed. See docs/security/job-scraping-egress.md and docs/security/premium-and-job-status-deployment.md for operational boundaries and the explicit legacy-null premium exception.
