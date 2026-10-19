# Auth Turnstile layout

Retain the existing TraKerja auth direction, colors, typography and forms. ENERGY 2 / RHYTHM 2 / MOTION 1. The shared login/register partial now uses Cloudflare's supported flexible widget size instead of compact. The wrapper reserves 65px instead of 140px; its child maintains the documented 300px minimum width. Narrow screens have enough outer space to display that width without clipping.

- Hard Gate PASS: 16 login/register width/theme checks at 320, 390, 820 and 1440 pixels found no horizontal overflow or widget clipping, using an iframe sizing fixture. Production-only rendering and the API script remain covered by six auth presentation tests, 262 assertions.
- Purpose Gate PASS: the shorter horizontal layout aligns the challenge with the form. No scaling, hidden branding, token bypass, clipping, or internal iframe modifications are used.
- Liveliness PASS: the existing auth heading, accent and composition remain unchanged; no motion or decorative assets were added.
- Craftsmanship PASS: production build and whitespace checks passed. Security validation and form submission code were not changed. Live production Cloudflare challenges were not exercised locally; browser checks validate the wrapper against documented geometry.

Reference: https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/widget-configurations/
