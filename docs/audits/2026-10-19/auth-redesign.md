# Auth and onboarding redesign — 19 October 2026

## Final design

All six auth screens now share an isolated Blade layout, local Google Sans Flex font, dedicated stylesheet and small JavaScript entry. The user requested a complete reference-led redesign: white rounded two-column shell, functional form at left, a pair of TraKerja iPhones at right. Pale pink/blue background fields are CSS, not a baked studio photograph. Dark mode uses the same composition with charcoal/plum tones. The supplied reference's usage counts and promotional claims were not copied. The user explicitly removed the right-side headline, description and feature list.

Final login/register choice order: full-width **Continue with email** at top; Google and LinkedIn as two equal icon buttons below. The email disclosure expands a real HTML form. Provider names remain available to assistive technology and as hover titles. No GitHub, Facebook or Apple provider was introduced. Forms and their existing actions, field names, CSRF protection, reset token, remember-browser checkbox, verification/resend/logout and OAuth routes remain intact. Server authentication, rate limiting, password rules, OAuth configuration and verification logic were not changed.

## Design reasons

Design read: auth and account recovery for job seekers, using the user's supplied reference and existing TraKerja identity; energy 2, rhythm 2, motion 1.

- Split composition gives the form a quiet functional column while the actual product mockup establishes continuity with the landing.
- Google Sans Flex and plum/blush/mist tones continue the established landing typography and palette.
- The soft CSS background follows the explicit reference; transparent devices avoid a second rectangular image backdrop or podium.
- Form width is capped at 380px; structural space centers short choices while long forms expand naturally without a fixed-height crop.
- Device presentation stays sticky and bounded on desktop and is hidden below 800px, prioritizing the form on phones.
- Controls are at least 44px; text inputs use 16px to avoid mobile focus zoom. Inputs, buttons and shell use different radii.
- Subtle finite entry motion is disabled by reduced-motion preference. No ongoing decorative motion was added.

## Behavior and checks

- Native email disclosure works without JavaScript, reopens on errors/old input, and keeps the form beneath the email option.
- Password toggles have accessible names, aria-controls and aria-pressed; confirmation fields have their own control.
- Validation messages are attached to inputs; an error summary receives focus. Passwords are never redisplayed from old input.
- Form submission shows a pending state and blocks repeated submission; browser history restoration clears the pending state.
- Production Turnstile remains enabled under the existing server conditions. Compact widget layout fits narrow form columns. Local/testing skips match the existing server validation exception. A live challenge was not submitted.
- Both locales retain the existing language switch and unsaved-input confirmation. Theme preference persists using the existing theme module.

Fresh verification: 19 PHP tests passed with 30,896 assertions (auth presentation, landing, language catalogue, and mocked LinkedIn OAuth state behavior). Production build succeeded; all 270 Blade views compiled; whitespace check passed. Browser evidence includes 48 width/theme/page checks, 24 Indonesian checks, and eight final email-first ordering checks at 320/390/820/1440px. No document overflow, checked form bounds issue, lost heading, or undersized primary control was found. Shared interactions were checked: disclosure, password visibility, empty-field validation, pending/reset state, locale/theme, and focused server-error summary. Final icon buttons are equally sized and both clickable. See auth-layout-checks.json.

## Delivery gate

- Hard gate PASS: real form/provider destinations and CSRF retained; no invented provider/statistic; responsive and validation checks above pass.
- Purpose gate PASS: layout, palette, typography, photograph and finite motion each have the explicit reference/product purpose documented above.
- Liveliness PASS: declared 2/2/1 direction, a clear form focal point, real product imagery and restrained brand color follow the user's reference.
- Craftsmanship PASS: all six screens share components; EN/ID and light/dark checked; dedicated assets avoid changing dashboard/legal layouts.

## Asset

Built-in imagegen edited the previously generated user-approved two-iPhone mockup, removing the studio backdrop and podium. Final project asset: public/images/auth/iphone-landing-cutout.webp. Optimized with WebP alpha preserved. Source input remains in output/design-mockups/2026-10-19/01-iphone-17-landing-duo.png. Exact edit prompt:

> Use case: background-extraction. Edit this exact supplied product mockup. Remove absolutely EVERYTHING outside the two iPhone devices: remove the entire pastel lavender/white studio background, floor, rounded white display podium, cast shadows outside the hardware, and all surrounding scenery. Output ONLY the exact two iPhones as one clean photographic cutout on a genuinely transparent alpha background, not a checkerboard painted into the image. Preserve the complete silhouettes of BOTH phones with clean precise silver/black edges, correct rounded corners, side buttons, Dynamic Islands. Keep every pixel of the white and pastel screen UI opaque: preserve exact TraKerja branding, both screen page layouts, readable existing words, photos of the man and woman, browser bars, white UI cards, phone reflections, device positions, relative scale, and poses. Do not change the screen content, do not redesign, do not add text, do not add a shadow ellipse, no new props, no hands, no fake reflection plane. Keep the two phones fully within the frame and crop the transparent canvas fairly tightly around both devices with 5% transparent breathing room on all sides. High-resolution faithful product cutout with real alpha, suitable for light and dark website backgrounds.

## Limits

Browser checks used Chromium emulation, not physical iOS Safari or Android devices. Protected pages were visually reviewed using isolated HTML fixtures with a synthetic unsaved account; middleware protection was tested separately. Real Google/LinkedIn authorization, sign-up, password-reset email delivery and verification email delivery were not performed. No production data, external account state, notification, payment or schema was changed. No commit or push was made.

