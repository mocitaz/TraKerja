# Portfolio redesign — 18 October 2026

## Changes

- Public /@slug pages and private previews share an editorial layout with a contained desktop frame, consistent section spacing and quieter theme accents.
- Replaced the tilted portrait and alternating colorful project cards with a framed profile image and neutral project cards.
- Added a work/contact hero action and a Skills navigation anchor. Existing links, content ordering, escaping and unpublished-site protection are retained.
- Mobile identity layout reserves space for the photo; long names use a full-width arrangement. Navigation and links remain reachable by touch and keyboard.
- Portfolio theme is explicit in the rendered document. Removed visitor app-theme detection from this standalone page and excluded it from the app's forced light-background rule.
- Updated portfolio-specific English/Indonesian labels and document language, without translating the user's content.
- Separated public-site CSS from publishing-dialog styles; adjusted theme swatches to the new palette.

## Verification

- Portfolio feature suite and language catalogue: 6 tests passed, 29,411 assertions.
- Production asset build and compilation of all 241 Blade views passed; diff whitespace check clean.
- Viewed the actual local /@user-lokal page on desktop and mobile, including the lower sections/contact area.
- Rendered all four themes with synthetic long content in isolated SQLite memory, in English and Indonesian. Browser checks covered 320px, 768px and 1366px: 24 combinations, zero viewport overflow, no photo/action overlap, no broken section anchors. See portfolio-layout-checks.json.
- Empty private preview rendered correctly at 390px with no invented projects and no horizontal overflow.
- No real account/content edits, publishing changes, migrations, or production deployment were performed. Physical device/Safari and external contact/project destinations were not exercised.
