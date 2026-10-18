# Email verification with OTP

Implemented 19 October 2026 following approval of the OTP concept. Existing auth styling, product artwork and unrelated working changes were preserved.

## Behavior

- Registration creates an unverified authenticated account, sends a six-digit code, regenerates the session and opens `/verify-email`.
- The form accepts one pasted six-digit code, supports browser one-time-code autofill and uses numeric input mode. The code is never repopulated after errors.
- Codes expire at ten minutes. Five checks are permitted per code and ten per account in a ten-minute window, including across resends. A code is consumed in the same transaction that marks the account verified.
- Sending has a sixty-second cooldown and a maximum of five sends per account per one-hour window. HTTP sending also has a shared six-per-minute IP limit. HTTP confirmation has thirty-per-minute IP and fifteen-per-minute account limits. Server limits remain effective without JavaScript.
- A resend produces a different code, invalidates the previous code and retains aggregate attempt counters. Failed delivery rolls back the replacement rather than discarding an existing usable code. SMTP timeout is fifteen seconds.
- A successful verification dispatches `Verified` and sends one welcome email. Repeated confirmation of an already verified account does not repeat those effects.
- Email changes invalidate the challenge and legacy code fields, clear verification status, and preserve send/attempt limits. The profile links to the OTP page.
- Legacy verification URLs, including expired URLs, only redirect to login or the OTP page for the current session. They cannot verify an account or switch/login a user. Signature checking is deliberately unnecessary on this redirect-only route: URL identity parameters are unused.
- Password recovery continues using Laravel's password broker and link tokens. The unused password-reset OTP method that shared email-verification storage was removed. Email OTP compatibility callers now use the central verification service.
- New code hashes are stored in `email_verification_codes`, one row per account. Its hash is hidden from serialization; old OTP fields are also hidden and are not accepted for verification. SMTP certificate validation is enabled.

## Architecture and release

`EmailVerificationService` owns issuance, rate counters, expiry, validation and finalization. Issuance and verification serialize on the account row with a database transaction. The user model's standard `sendEmailVerificationNotification()` delegates to this service, so registration and existing admin resend entry points share the same behavior and limits.

The new migration has been applied to the existing local SQLite database only. For another environment, run the migration before serving the new code, rebuild frontend assets, refresh relevant application caches and reload long-running workers with the normal release process. No production deployment was performed.

The mail transport sends synchronously; no new OTP queue worker is required. Real SMTP delivery, provider inbox placement and certificate compatibility have not been exercised. Do not disable certificate validation if a provider fails; correct its certificate/hostname/CA configuration.

## Validation

- 119 tests passed, 2,405 assertions: email OTP, auth presentation, email presentation/copy, password recovery and login/logout. All used isolated in-memory SQLite and fake/in-memory mail transports. The OTP class includes 22 tests covering registration, failure recovery, resend, expiry, limits, cross-account use, changed email, legacy URLs/codes, serialization, profile navigation and one-time effects.
- Two additional profile email-update regression tests passed, 10 assertions.
- Production asset build succeeded. New/rewritten PHP files passed the formatter. `git diff --check` passed.
- Headless Chromium exercised the running application against a separate temporary SQLite database with fake mail and blocked/faked outbound HTTP. No existing user accounts were used.
- Browser layout checks: 320, 390, 820 and 1440px, light/dark, EN/ID; sixteen combinations, no horizontal overflow, input remained inside viewport, input height 50px and a connected label in every combination.
- Browser interactions: empty validation, incorrect code, focused error summary, no echoed code, resend/countdown, pasted full code, successful dashboard redirect, already verified redirect, language/theme switch, and logout. No page JavaScript errors were recorded.
- Existing account deletion test fails with both the baseline User model from HEAD and the updated model. It was left outside this change. This is not a claim that the entire project suite passes.

## Design and delivery gate

Design read: extend the existing TraKerja auth direction documented in `auth-redesign.md`; energy 2, rhythm 2, motion 1. One input is used to support pasting, autofill and keyboard access without six-field focus handling. Existing plum/neutral palette, typography, product image and auth shell provide continuity. The email summary has an explicit gap before the code field; the main action is verification, followed by resend and logout.

- Hard gate PASS: real POST routes and CSRF fields, required six-digit validation, field hints/errors, focused server error, pending states, working countdown, keyboard-compatible native input, measured layout bounds at sixteen combinations. Existing text colors were checked against their surfaces: normal-text ratios range from 4.74:1 to 14.95:1; disabled resend controls are excluded. No new assets, decorative icons, claims or testimonials were created.
- Purpose gate PASS: existing visual identity and product mockup were reused; no new decorative technique was introduced. Spacing separates account context, code entry and recovery actions.
- Liveliness PASS: existing 2/2/1 direction retained, code entry is the focal point, product artwork remains the identity motif on desktop, and verification is the primary action.
- Craftsmanship PASS: real browser clicks/submissions cover verification, resend, theme/language and logout; EN/ID and light/dark remain functional, production build passes, and relevant backend tests pass.

Evidence: `output/otp-verification/browser-checks.json`, `mobile.png` and `desktop-dark.png`. Browser checks used Chromium emulation, not physical iOS/Android devices. Production-database concurrency and actual SMTP delivery remain unverified.
