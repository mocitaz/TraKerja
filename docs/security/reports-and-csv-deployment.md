# A09/A10 deployment notes

## Listing reports

Run the normal deployment migration to create `job_posting_reports`. Its unique `(job_posting_id, user_id)` constraint and the locked transaction prevent repeated reports from increasing the count. Historical aggregate counts cannot identify past reporters; this migration does not reconstruct them or reopen previously closed listings.

Run a worker consuming the `discovery` queue with an asynchronous queue connection. Each new report schedules verification through `JobPageFetcher`; only HTTP 404/410 closes a listing automatically. A successful page records validation time, while blocked destinations, fetch failures and other HTTP errors leave the listing active. Text indicating expiration requires moderation because hidden templates can contain that text on an active listing. Apply the egress controls in `job-scraping-egress.md` too.

## CSV

Export contains a header and data rows only. The extra `CSV Encoding` column identifies `trakerja-v1` reversible spreadsheet escaping; keep it when reimporting exports. Manual CSV files do not need this column. Dates use `YYYY-MM-DD`; interview timestamps preserve seconds. Human-readable summaries remain in the application's summary views.

Preview and import share parsing and validation, including authorization. Preview writes nothing and returns up to ten valid rows plus total counts and row errors. Import revalidates the submitted file, inserts valid rows transactionally and reports skipped rows. Row numbers refer to logical CSV records, including a single record with multiline text.

## Validation

- Focused report, CSV and status tests: 27 tests, 120 assertions passing.
- Full suite: 290 tests, 1,191 assertions, six existing failures (two Explore success-copy expectations and four requests to inactive legacy export/stat routes), no errors.
- Production data was not migrated. Validation used isolated SQLite databases and fake queues/HTTP.
- Vite build passed. Native-browser validation remains incomplete: the test window became unavailable (`cgWindowNotFound`), so no end-to-end UI success is claimed.
