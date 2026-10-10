# Premium and job status deployment notes

## Premium

`User::isPremium()` requires the premium flag, paid payment status and a future `premium_until`. Expiry at the current instant is inactive. Existing null-expiry memberships remain indefinite for backward compatibility; this change does not infer an expiration date for historical/manual grants. New verified purchases and new admin grants store an explicit expiration using `pakasir.premium_duration_days`. This is a deliberate legacy exception, not a claim that all historical subscriptions now have a duration.

Renewal adds duration to the current future expiry of an active membership; otherwise it starts at the verified settlement time. The existing unique payment grant ledger prevents replay from extending membership again. `premium_until` remains outside mass assignment; trusted writers assign it explicitly. An unrelated admin profile edit leaves payment status and expiry untouched. There are no banned/inactive account status fields in the current user schema; revoked/unpaid premium flags are covered by the entitlement check.

## Application status

Canonical outcomes are `On Process`, `Accepted`, and `Rejected`. Recruitment stages remain separate; Explore saves `On Process` + `Applied`. Form, table, Kanban and CSV use `App\Support\JobApplicationStatus`. Summary outcome counts use the canonical status rather than treating an offer as an acceptance.

Migration `2026_10_08_000002_normalize_job_application_statuses.php` handles historical aliases:

| Historical status | Status | Stage when no more specific stage exists |
| --- | --- | --- |
| Applied / Pending | On Process | Applied |
| Interview | On Process | HR - Interview |
| Offering / Offered | On Process | Offering |
| Declined | Rejected | Existing stage retained |
| Withdrawn | Rejected | Not Processed |

Empty current status falls back to the legacy `status` field. Canonical status wins when the two fields disagree. The migration synchronizes the legacy field, preserves archive flags and timestamps, and is idempotent. Original aliases cannot be reconstructed by `down()`; retain a database snapshot if rollback must restore the exact historical values. `Declined` and `Rejected` become the same canonical outcome under this contract.

Before deployment, review distinct production values in both status fields for values outside the documented aliases. Take a database backup and run the migration before reopening tracker writes. No production or workspace database migration was run during implementation; tests migrated SQLite in memory only.
