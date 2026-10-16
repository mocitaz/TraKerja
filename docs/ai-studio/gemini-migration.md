# AI Studio: direct Gemini processing

CV review, cover letters, and recruiter outreach use `GeminiStudioService` directly. The old Vercel analyzer/writing endpoints and local-template fallback are removed. Photo Studio retains its separate provider and queue.

## Deployment

1. Back up the database and deploy the application code with the additive `ai_studio_runs` migration.
2. Run `php artisan migrate --force` on the deployment host before opening AI Studio.
3. Configure `GEMINI_API_KEY` securely on the host; do not put it in source control or the browser. Existing Gemini configuration is shared with Nara.
4. Optionally set `GEMINI_STUDIO_ANALYZER_MODEL` and `GEMINI_STUDIO_WRITING_MODEL`. They default to `GEMINI_MODEL` (otherwise `gemini-flash-lite-latest`). Verify the chosen model is available to the deployment account and supports PDF input and structured JSON output before rollout. No model fallback silently calls the old API.
5. Start a supervised worker: `php artisan queue:work ai_studio --queue=ai-studio --timeout=160 --tries=1`. Restart this worker when deploying code changes. The dedicated database connection has a 240-second retry interval, longer than the worker timeout. Photo workers remain separate.
6. Keep the Laravel scheduler running. `ai-studio:recover` runs every minute, redispatches queued jobs, refunds processing leases that expired, and removes failed source files and encrypted context after seven days.
7. Clear/rebuild application configuration and frontend assets as in the normal deployment process. Exercise each text task with a small real sample, and verify final state and balances, before enabling production traffic.

These commands are deployment instructions; authoring this change does not execute migrations or start workers against an existing database.

## Request contract

The existing POST routes remain, but now require a UUID `request_key` and return JSON HTTP 202 with `success`, `request_id`, `status`, and a same-origin `url` to the persisted request. Native forms redirect there. Repeating the same UUID for the same user/task returns the same request and never charges again. Use a new UUID for a deliberate new attempt. Retry is a POST to `/ai-studio/requests/{run}/retry`; its reservation is separate after the prior reservation was refunded.

Status is fetched from authenticated `/ai-studio/requests/{run}`. Both reads and retries enforce ownership; admin users cannot submit customer Studio work. Each account can have two active text requests and 60 text requests per rolling day; submit/retry routes additionally allow 10 requests per minute. Outreach does not consume credits but shares these limits. Free access mode suspends credit deductions, not provider usage limits.

Lifecycle: queued → processing → completed / failed. A metered Analyzer/Cover Letter credit is reserved atomically with request creation, consumed atomically with result persistence, or refunded once on failure. Settings changes after submission do not alter the reservation. Processing leases prevent a delayed provider response from publishing after a timeout refund. Recovery and the worker failure hook cover stopped workers. Recovery is required for refunds after hard process termination.

User-selected PDFs are copied into private local storage. Original saved CV files can be removed independently without breaking submitted work. PDFs are deleted after success; failed PDFs remain available for retry for seven days. Input context is encrypted at rest using the application key, erased after success, and removed after seven days on failed requests. Keep the application key stable. Account deletion removes the user's Studio source directory as well as cascading request rows. Provider payloads, response bodies, and keys are not logged.

## Context and results

Only owned tracker applications and CVs are selectable. Tracker supplies company/role/stage/status, never notes as job requirements; users confirm the actual description. Analyzer requires an uploaded or saved PDF under 10 MB. Writing can use an optional PDF plus explicit saved profile facts. Word files remain supported by the resume vault but must be converted to PDF for Studio attachments.

Gemini receives untrusted document/context data under fixed system instructions. The service checks HTTP status, complete finish reason, JSON shape, nonempty content, and task-specific bounds. An unreadable CV is a failure with a refund, not an empty successful review. Structured output helps predictable rendering but does not guarantee factual correctness; generated claims still require review.

New CV reviews have an evidence comparison, editorial match estimate, actions and limitations. Previous results still render through the legacy generic view. New outreach is provider-generated; legacy template drafts remain labelled as templates. Writing is editable locally before copy/TXT export; edits are not autosaved to the database.

## Verification

Tests use isolated in-memory databases, fake storage/queues, and simulated Gemini responses. They cover duplicate submission, reservations, refunds, invalid/truncated results, source privacy, ownership, saved context, retry, expired leases, and failure to save a result. This does not establish account/model availability or real generation latency.

API references: [PDF input](https://ai.google.dev/gemini-api/docs/generate-content/document-processing), [structured output](https://ai.google.dev/gemini-api/docs/structured-output).
