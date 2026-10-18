# Billing UI redesign — 18 October 2026

## Scope

Premium checkout, add-on checkout, payment waiting/success/failed, history, and coming soon. Canceled and expired orders reuse the shared status view.

- Replaced the layered payment stylesheet with a single theme based on neutral surfaces, sage benefits and plum actions.
- Simplified checkout copy, removed duplicate credit bundles, retained configured prices, duration and actual entitlements. Mobile/tablet show the price before payment-method selection; the summary still confirms the total.
- Kept payment method fields, native required validation, CSRF, submit protection and provider redirect unchanged.
- Moved payment actions ahead of the timeline. Unsuccessful orders no longer show a future benefits step. Status follows the stored order, regardless of URL.
- Preserved polling, retry, error announcements and virtual-account copying. Added busy states for assistive technology.
- Retained owner-scoped history and its mobile card layout. Improved empty state and dark-theme contrast.
- Removed unsupported development percentage, launch quarter, security and waitlist claims from coming soon.
- Localized status headings/descriptions, package labels and recorded benefits. Added English/Indonesian copy.

## Verification

- 18 tests: payment workspace, waiting UI, membership portal and language catalogue; all pass (29,403 assertions).
- 25 JavaScript checkout/polling tests pass. Updated the existing VM harness to support the previously introduced translation import.
- 241 Blade views compile; PHP syntax and whitespace checks pass; production asset build passes.
- Rendered isolated Laravel responses with synthetic users/orders and SQLite in memory, for both English and Indonesian. Provider, mail and queue operations were faked.
- Inspected browser layouts for 9 page/state variants at desktop 1366px, tablet 768px, mobile 320px, and mobile dark 390px. No horizontal viewport overflow in these measurements. See billing-layout-checks.json.
- Verified native checkout validation and QRIS selection/summary update in the browser. Inspected pending actions, long order references, receipt, history cards and coming-soon design.
- Final 320px checks after adding early mobile pricing: Premium, add-on and empty history have no horizontal overflow.

## Limits

Browser checks used rendered fixtures with the built application CSS/JavaScript and an Alpine/Livewire stub. Payment polling used synthetic responses. No real payment, external checkout completion, production deployment, or physical iOS/Android browser test was performed. No real database migration or data change was needed.
