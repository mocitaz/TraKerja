# Safe CV PDF replacement

`POST /cv/import-pdf` parses the PDF and returns a preview without writing CV data. Empty or invalid extraction returns 422. The preview includes every field that would be saved, existing section counts, and an encrypted, authenticated confirmation token scoped to the current user and valid for 15 minutes. No uploaded PDF or server-side draft is retained by this flow.

`POST /cv/import-pdf/confirm` requires the token and explicit `confirm_replace` acceptance. It locks the user row, checks a fingerprint of the saved CV against the preview baseline, and replaces only sections containing parsed entries. Empty sections retain their existing entries. All deletes and inserts share one database transaction; insert failure rolls everything back. A changed baseline, including a replay after successful replacement, returns 409 and requires a new preview. Invalid, expired, tampered, or another user's token returns 422.

Deploy backend routes and rebuilt frontend assets together (`npm run build`). No schema migration is required. Use a transactional database engine for all four CV tables. Production schema/engine settings were not changed or inspected through credentials during this fix.

The parser remains heuristic and recognizes a limited set of headings. Unknown project roles/dates, skill categories/levels/experience, education degrees/majors, and work locations are left blank, without invented qualifications. Projects are not marked ongoing without evidence. Creative-template skills without a category use the neutral heading `Skills`.

Education start dates are required by the existing database schema. When a full month/year cannot be read, the preview leaves dates blank and asks the user to supply the start date before confirmation. Year-only ranges are shown verbatim as a hint rather than converted into invented months. Explicit month/year values use the first day as the storage representation; the CV displays their month/year. Empty degrees/majors are stored as empty strings to satisfy the existing non-null string columns without manufacturing qualifications. The user is prompted to complete remaining details in CV Builder. No schema migration is needed. Parser version checks invalidate previews created before this correction.

The preview explicitly asks the user to review all fields. Only the first 12 detected skills are imported, matching the existing limit. This fix does not add OCR or guarantee extraction accuracy.

Regression tests use real synthetic PDF files and disposable in-memory SQLite tables without running application migrations. They verify empty extraction, preview without writes, partial-section preservation, user isolation, expired/tampered tokens, explicit acceptance, stale/replayed previews, and rollback after an insert failure across multiple sections. Browser checks use the actual dialog/component with synthetic preview data and a stubbed save response, not a signed-in production account.
