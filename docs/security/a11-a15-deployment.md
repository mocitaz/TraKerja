# A11–A15 deployment and verification

## Extension login

Login requires verified email and normalizes email case/whitespace. Limits are five attempts per account/minute and twenty per IP/minute; keys are hashed. Use a shared production cache and the correct trusted-proxy configuration so limits remain effective across application instances.

New tokens last thirty days and grant only `jobs:create`, `account:read`, and `token:revoke`. Protected endpoints require the corresponding ability and verified email. Logout revokes only the current bearer token. Existing wildcard tokens are not rewritten or mass-revoked; they can only reach the guarded API endpoints and must still satisfy verification and token-age limits.

## AI photo requests

Apply `2026_10_09_000001_add_processing_lifecycle_to_ai_photos.php` before enabling the new UI. Existing photos default to completed. New requests reserve one credit and save a unique user/request UUID in one transaction. Unmetered requests reserve nothing, even if monetization is enabled while they are running. Completion consumes the reservation; failure refunds it once. Replaying a submit or job never charges again.

Run the dedicated database queue worker:

```sh
php artisan queue:work ai_photos --queue=ai-photo --timeout=330 --tries=1
```

The connection uses retry-after 360 seconds; provider timeout is 300 seconds. Workers need the application's private `local` storage and database. Multiple worker hosts need shared private storage and a shared cache supporting atomic locks for job uniqueness. Keep the configured `ai_photos` connection asynchronous.

Enable the Laravel scheduler. `ai-photos:recover` runs every minute, redispatches missing queued work with unique-job protection, refunds expired processing, and fails/refunds requests still queued after thirty minutes. Leases are rechecked under lock before refunding. `ai-photos:recover --cleanup` runs hourly to remove terminal source files and orphan uploads older than seven days, including uploads left after account deletion. Completed source files are removed immediately. Failed-source retries create a new request UUID and reserve a new credit; the failed request remains terminal. A timeout can mean the provider processed the image, so the worker does not automatically make another paid provider call.

The result/status/retry routes scope requests to their owner. The UI displays queued/processing/completed/failed states and a real status-check error with retry. Users can leave and return through history.

## Survey and PWA

Survey invitations are optional. Dismissal lasts seven days for that account in the current session; clearing the session can show the invitation again. Login/OAuth/payment and core tasks are never redirected to survey.

The new worker caches only offline/icon assets and hashed public build assets with safe MIME/cache directives. It never caches root/private/auth/payment/API HTML. Activation removes old TraKerja caches; account changes and both normal/programmatic logout purge them too. Existing installations receive the change when the worker update activates; validate that transition on staging, including a previously installed old worker.

## Verification results and limits

Focused PHP: 50 tests/327 assertions passing. Node: 20 tests passing. Full suite: 334/340 passing, 1,518 assertions, six existing failures and no errors. Vite build, PHP lint and diff checks pass.

Validation uses SQLite memory, fake HTTP/queues, private temporary storage, rendered DOM checks and executable Node VM tests. Provider calls and production migrations were not performed. Browser rendering, keyboard/screen-reader operation, real offline installation transitions and concurrent MySQL execution remain staging checks. The six existing failing tests are:

- `ExploreJobsTest::test_user_can_save_scraped_job_to_job_tracker`
- `ExploreJobsTest::test_user_can_save_scraped_job_via_confirmation_modal`
- `JobApplicationExportTest::test_export_csv_requires_authentication`
- `JobApplicationExportTest::test_export_csv_returns_csv_file`
- `JobApplicationExportTest::test_export_csv_only_includes_user_data`
- `JobApplicationExportTest::test_export_stats_returns_json`

The first two expect old success copy; the other four call inactive legacy routes. Active CSV routes have separate passing coverage.
