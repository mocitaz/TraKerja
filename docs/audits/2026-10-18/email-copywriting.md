# Email copy — 18 October 2026

## Scope and voice

All 37 custom email templates now use scoped `lang/en/emails.php` and `lang/id/emails.php` catalogues, including subjects, headings, introductions, actions, shared greetings and footer copy. English remains available; Indonesian copy uses “kamu”, short sentences and direct actions. Admin-authored campaign subjects and bodies, support messages, selection notes and other user content are preserved verbatim and escaped.

The tone is warm and polite without forced slang or emoji. Marketing is encouraging without pressuring people to keep applying. Security copy stays explicit about expiry, secrecy and unexpected requests. Holiday messages are respectful and avoid static years.

Examples:

- Welcome: “Hai, selamat datang di TraKerja” / “Catat lamaran pertama”.
- Verification: “Satu langkah lagi sebelum mulai.” / “Verifikasi email”.
- Payment: “Pembayaranmu sudah masuk” / “Lihat paket dan kredit”.
- Follow-up: “Tanya kabar lamaran, lebih terarah” / “Pilih lamaran”.
- Goal: “Target kamu tercapai” / “Boleh bangga sama langkah yang satu ini.”

## Accuracy

- Removed fixed promo discounts, blanket free/unlimited/lifetime claims, guaranteed screening outcomes and unsupported operational promises.
- Receipts show the actual purchased package, recorded amount, payment method and payment date; credit purchases do not imply Premium activation.
- Premium access displays the account's expiry or explicitly states that no end date is recorded.
- Reset links retain configured expiry; OTP instructions retain the ten-minute, single-use and secrecy rules.
- Selection messages retain stage-specific titles, WIB dates, method, online platform, access/location and preparation notes.
- Missing dates show a localized “Belum dicatat” / “Not recorded” fallback.
- Follow-up copy explicitly says that generating a draft does not send it automatically.
- Campaign actions use existing routes; removed obsolete `/goals` and `/jobs` destinations.

## Verification

103 tests passed, including six copy-specific tests, all custom templates in both languages, actual Mailable rendering and subjects, authentication action URLs, escaping, catalogue parity, admin previews and selection mail regressions. All 252 Blade files compile; production build and whitespace checks pass.

48 Chromium layouts across eight representative templates, two languages and widths of 320/600/1050px have no page overflow, loaded local logo fixtures and primary action heights of at least 44px. Synthetic fixtures only; no real email was sent. Actual Gmail/Outlook/Apple Mail inbox rendering remains unverified.
