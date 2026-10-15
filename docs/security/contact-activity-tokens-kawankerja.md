# Contact aliases, activity, tokens, and KawanKerja

The legacy `/admin/contact-messages` routes now require authenticated, verified admin access and redirect to the existing SupportTicket feedback workspace. A detail URL resolves a SupportTicket ID, filters the workspace to that ticket, and expands its drawer. It does not mark a legacy ContactMessage row as read. Historical ContactMessage rows are not migrated automatically.

Last-activity middleware assigns the internal timestamp explicitly and saves quietly. It remains excluded from mass assignment, with the existing one-minute write interval.

Password reset and password change atomically save the new password/remember token and revoke all Sanctum tokens. Failed validation never reaches rotation. The profile security panel lists up to 50 API devices (name, usage and expiry only), supports owner-scoped revocation, and can revoke all API devices. Browser login sessions are not included in that list. Chrome extension 1.0.2 invokes the server revoke endpoint before removing local credentials. Failed network/revocation requests keep credentials locally so logout can be retried; an expired/revoked token (401) can be removed locally. The production download ZIP is rebuilt.

Both KawanKerja provider routes share eligibility and admission middleware: verified email required, one active request per account, ten accepted attempts per minute per account, sixty per rolling 24 hours per account, and thirty per minute per IP. Attempts include provider failures. These are request budgets, not monetary/token billing. Use a shared cache backend with atomic locks across all app workers/hosts; local array/file caches cannot enforce a cross-host budget. The account lock expires after 180 seconds and spans the actual SSE callback, with release on completion or exception. Abrupt process termination relies on lock expiry. Rate limits return 429 with Retry-After. The history/clear routes do not invoke a provider.

Streaming may fall back only before any content has been emitted. Failure after emission returns failure, emits an SSE error event, and does not append an alternative answer or save the partial exchange to history. A client should display interrupted content as incomplete and allow retry. The widget currently uses the nonstreaming route.

CV spacing uses the same contract for all four templates: compact 10 mm, standard 15 mm, spacious 20 mm. Margin assertions are separate from content-check network/retry assertions; no template-design change was made.

## Dependency update

Installed Composer updates include Livewire 3.8.10, Dompdf 3.1.6, Laravel 12.70.0, Guzzle 7.15.5, CommonMark 2.10.3, Flysystem 3.36.0, phpseclib 3.0.57, and Symfony HttpFoundation/Yaml 7.4.21. Published Livewire distribution files match the vendor files byte-for-byte. Composer audit reports no advisories for the resulting lockfile. Composer lifecycle scripts were skipped to avoid booting the application's local environment; deployment should run its usual cache/package discovery procedure without migrations from this change.

Npm updates include Vite 7.3.7 and Axios 1.20.0, with compatible transitive updates. Seven npm advisory entries remain in the Tailwind 3 dependency chain (braces/chokidar/micromatch/fast-glob and PostCSS parser/nested); npm recommends a breaking Tailwind 4 upgrade. No major CSS framework migration or advisory suppression was applied. These residual build-tool findings require a separate migration, including visual regression testing.

Reference: [Dompdf upstream advisory](https://github.com/dompdf/dompdf/security/advisories/GHSA-cx96-42px-69fm) identifies 3.1.6 as the patched version for data-URI SVG file reads. Audit status is time-sensitive; rerun `composer audit --locked` and `npm audit` before deployment.

Verification uses disposable SQLite tables, synthetic API tokens, controlled service overrides, cache locks, and fake network/provider calls. No real password, provider, production database, or credential file is used. The full migration-based PHP suite and a real browser/device password-recovery flow were not run.
