# Payment idempotency and admin support implementation plan

> Execute inline using superpowers:executing-plans and test-driven-development. The user selected A02 and A03 from the audit and authorized implementation.

Goal: grant payment benefits once, preserve settled SUCCESS, and restrict admin support to admins.

Architecture: a shared PaymentSettlementService locks payment and user, writes a ledger unique on payment/benefit, and commits benefits and SUCCESS atomically. Historical successful payments receive a legacy marker. A SupportTicketPolicy guards controller actions in addition to the admin route middleware; Livewire retains that middleware on subsequent requests.

Stack: existing Laravel, Livewire, PHPUnit; no new dependencies. Spec: audit A02/A03 in docs/audits/2026-10-08/audit-trakerja.md.

- [x] A02 red: model terminal transitions, ledger replay/uniqueness, rollback, historical backfill, and sync/webhook cross-path tests.
- [x] A02 green: ledger migration/model, shared settlement service, guarded model transitions, controller and command integration.
- [x] A03 red: guest/user/premium/admin HTTP matrix and controller policy boundary tests.
- [x] A03 green: admin middleware, support policy, controller authorizations, persistent Livewire middleware.
- [x] Verify focused tests, full suite against known eight baseline failures, syntax/diff checks, and independent review.

No live payment calls, production migrations, real support-ticket mutations, refund processing, or unrelated audit fixes. Test database is SQLite memory. Concurrency claims are limited to locking design and DB uniqueness tests until checked on the deployment database.

Rulings: historical markers also cover paid_at non-null rows because the old handler could downgrade settled status; ledger deletes follow existing payment/account hard-delete cascade to preserve account deletion behavior. New status writes through Eloquent lock and reject SUCCESS downgrades; raw external writes bypass model guards but cannot regrant benefits through settlement.

Verification: initial A02/A03 tests demonstrated 18 failures in 26 tests. Added stale model, rollback, unique constraint, historic downgraded payment, polling/sync cross-path and role-revocation HTTP/Livewire coverage. Final full suite: 116 tests, 108 pass, same eight baseline failures, no errors. Independent review: no remaining blockers after historical backfill fix.

Deployment: migration 2026_10_08_000001_create_payment_grants_table must run before enabling the changed payment handlers/workers; pause payment writes and sync workers while applying schema/backfill. Run php artisan migrate as part of the deployment, then resume workers and smoke-test with provider sandbox. It has only been executed on the disposable test DB in this session. Concurrent processes on the production SQL engine remain a staging validation item. No refund workflow was introduced: refunds must not downgrade settlement status.
