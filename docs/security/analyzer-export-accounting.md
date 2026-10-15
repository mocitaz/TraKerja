# Analyzer results and CV export accounting

## Analyzer contract

HTTP 2xx is necessary but insufficient. `AnalyzerResultContract` requires a JSON object with usable text in at least one recognized field: `ringkasan`, a string list `rencana_aksi`, or `teks_revisi` / `alasan_perubahan` inside a recognized CV section. Section names follow the existing result views: `pendidikan`, `pengalaman_kerja`, `pengalaman_organisasi`, `projek`, `keterampilan`, `prestasi_dan_publikasi`, `pengalaman`, and `keahlian`.

Recognized fields are type checked even if another field contains usable content. Scores, when provided as `skor_kecocokan`, must be numeric JSON values between 0 and 100; a score alone is not an analysis. Actions must be a list of nonblank strings (at most 100). Text fields are limited to 20,000 characters. Empty responses, HTML, scalar/list roots, metadata-only objects, explicit errors, `success` values other than `true`, and failure statuses are rejected. Extra provider fields are retained with a valid result. This is structural and content-presence validation, not a guarantee of advice quality.

The existing atomic credit reservation occurs before the provider request. Only a validated result saved successfully is finalized. Result creation and trial tracking share one transaction; failures roll back and the reservation is refunded. The previous unsaved-result fallback no longer finalizes a charge. Provider or database details are not exposed in the new storage-failure message. A killed PHP process still requires persistent reservation reconciliation; this change does not add a reservation ledger or schema migration.

Provider verification uses controlled responses, not real resumes or live provider calls. Coordinate provider schema changes with this contract and regression fixtures.

## CV exports

Template validation and template access checks precede rendering. DomPDF `download()` renders eagerly, so its returned PDF response is created and checked before recording quota consumption. Rendering/setup failures do not consume quota. A short transaction locks the current user row, reads current membership, resets the monthly counter against the stored reset date, and consumes a slot only when available. PDF generation takes place outside that transaction. If another request consumes the final slot during rendering, this request receives the limit message instead of a PDF.

Monthly reset uses a database predicate so an old model cannot reset a counter already reset and incremented by another request. Free mode and active Premium retain unlimited exports. The relevant counter remains `cv_generated_this_month` with the existing three-per-month free-tier limit; the separate legacy `cv_exports` counter is not used by this route.

Quota records a successfully prepared server response; the server cannot establish that a browser finished receiving or saving a download. Tests cover stale models, interleaved consumption, reset races, rendering failures, quota-write failure, and real DomPDF output from local HTML without external assets. Tests use disposable in-memory SQLite tables without application migrations; production database concurrency has not been exercised. Transactional storage is required in production.
