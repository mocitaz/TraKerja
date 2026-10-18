# Email presentation — 18 October 2026

## Design

- White canvas and 600px content frame, charcoal text and one muted plum accent. System fonts, fluid tables and inline base styles keep the content usable without a webfont or external stylesheet.
- All 37 application email views share `emails.layouts.base`, with a compact brand header, clearer heading, neutral detail panels and a shorter footer with dashboard, email preferences and help links.
- Verification and password reset actions appear before supporting information. On narrow screens, actions stack and receipt metadata uses vertical label/value pairs with separators between complete facts.
- Laravel Markdown notifications use the same shell, palette and footer. Removed the duplicated subcopy wrapper and the logo's JavaScript error handler; the brand name remains visible when images are blocked.
- Email copy now uses dedicated English/Indonesian catalogues. Indonesian text is concise and conversational, using “kamu”; security, payment and holiday messages keep an appropriate tone. Dynamic application data and authentication action URLs are preserved; custom campaign subjects and bodies remain literal and escaped.
- Receipts use the actual package, amount and payment details, while Premium messages show the account's actual expiry. Removed fixed discounts and unsupported promises of unlimited or lifetime access. Missing dates use a clear localized fallback.

## Verification

- 103 tests passed: all application email views in English/Indonesian, actual Mailable render paths and subjects, authentication notifications, custom-content escaping, action URL preservation, concise copy, accurate receipt/expiry information, missing dates, admin campaign previews, selection schedule emails, notification eligibility, and the bilingual catalogue.
- 252 Blade views compile. Frontend production build and whitespace checks pass.
- Chromium preview checks cover eight representative templates, two languages and widths of 320, 600 and 1050px: 48 combinations with no horizontal page overflow, loaded local logo fixtures and primary action heights of at least 44px.
- Additional visual inspection covered the 390px welcome message, 320px receipt metadata, desktop verification and Markdown notification. Preview data is synthetic; no email was sent to a user and no database migration or application setting was changed.

## Limits

Browser previews cannot establish pixel parity with every Gmail, Outlook or Apple Mail version. Actual inbox rendering and clients that force dark-mode color conversion have not been exercised. Email clients may ignore rounded corners or media queries; the base content remains table-based with inline styles.
