# Support redesign

## Stable tab dimensions

New request and Your requests share one CSS Grid cell. Inactive content retains its layout space but is hidden, inert and aria-hidden, so switching tabs preserves the container dimensions without exposing inactive controls to keyboard or assistive technology. Browser measured both states at exactly 660.945px tall. Production build and 3 Support tests/31 assertions pass. Screenshot: `output/support-redesign/stable-history.jpg`.

## Current design: unified container

The user's latest direction supersedes the earlier open layout: one shared rounded container, lavender introduction/photo panel, white form panel. The two columns stretch to the same height through CSS Grid rather than manually matching positions. Typography continues to use the dashboard scale. On tablet/mobile the introduction becomes a compact horizontal header above the form. One outer border groups the entire support workspace; conversation rows remain disclosures rather than additional cards.

The user requested a slightly larger photo and removal of its background. Imagegen extracted `public/images/support/trakerja-support-cutout.png` with real RGBA transparency from the approved portrait. The prior source is preserved. The CSS monochrome/dark filter remains, and the photo spans the full introduction-panel width.

Delivery gate PASS:

- Hard gate: no overflow at 1280px or 390px; photo is explicitly requested, no staff identity or fabricated claims added. Existing form validation, loading and empty/error states retained.
- Purpose: one container establishes a shared boundary and equal column height; lavender identifies the introduction while white distinguishes the working form. Existing dashboard typography, controls and focus treatment retained.
- Liveliness: ENERGY 2 / RHYTHM 2 / MOTION 1, larger transparent portrait is the focal point, short reduced-motion-aware entrance retained.
- Craft: desktop panel top/bottom both measured y=107/829.945. Mobile light/dark inspected; no horizontal overflow. Browser switched request/history tabs and returned to the form; console and previous keyboard/disclosure checks apply. Production build passes; 28 tests/209 assertions passed after container changes, and 3 Support tests/31 assertions passed again after switching the image path.

Current screenshots: `output/support-redesign/container-desktop.jpg`, `output/support-redesign/container-mobile.jpg`.

## Direction and purpose

User direction: landing-page typography, the supplied branded portrait, no card-based layout, darker monochrome photo, and no category-specific instructions above Message.

ENERGY 2 / RHYTHM 2 / MOTION 1. A single editorial composition pairs the portrait and headline with the form. Lavender connects this utility page to TraKerja's landing page; thin separators distinguish form navigation and conversations without adding card containers. The arch frames the supplied portrait without changing the person. Grayscale, contrast 1.06 and brightness .88 match the darker photographic treatment requested. Short entrance motion respects reduced motion.

## Asset

`public/images/support/trakerja-support.png` is the user-approved branded portrait, copied from the prior generated asset. No name, staff role, testimonial or response-time claim is attached to it.

## Verification, 20 October 2026

- Hard gate PASS: category hints removed from the rendered DOM, one H1, no invented metrics or staff identity, existing navigation preserved. No new external destinations.
- Mobile PASS: inspected at 390px; document width matches viewport, form stacks vertically, photo/head remain visible. Desktop inspected at 1280px.
- Contrast PASS: muted text #6b6372 on #f7f7f9 is 5.37:1; field outlines #978a9f on white are 3.25:1. Dark palette inspected with the existing theme toggle.
- Function PASS: browser switched tabs, expanded a conversation/reply, opened deletion confirmation and cancelled without deleting. Category selection works without inserting the removed text. ArrowRight and Home switch tabs and focus. Console error log empty.
- Forms and privacy PASS: 28 tests / 209 assertions cover submission, validation, owner-scoped requests and forbidden foreign deletion, plus utility/admin regressions. Loading and error states remain implemented. Telegram mocked in submission tests; browser preview uses a disposable testing database.
- Purpose gate PASS: typography and lavender follow existing brand direction, arrows indicate send/new-request actions, the portrait is explicitly requested, and no decorative grids, glows or card shadows were added.
- Liveliness PASS: headline and portrait provide the focal point; two-column desktop layout and stacked mobile layout establish hierarchy. Accent is limited to heading, active tab and primary action.
- Craft PASS: `npm run build` succeeds. Light and dark previews inspected; native inputs, native disclosure elements, tab relationships, focus outlines and deletion confirmation retained.

Screenshots: `output/support-redesign/desktop.jpg` and `output/support-redesign/mobile.jpg`.

## Dashboard typography alignment

Follow-up request: match dashboard fonts, form controls and sizing. Support now explicitly inherits the same Plus Jakarta Sans stack. H1 uses the dashboard welcome scale (26px, weight 600, tracking -.9px), section headings use the dashboard panel scale (18px, weight 600), labels use 12px/600 and inputs use 13px. The send button follows the dashboard form's blue action and 12px corner radius. Removed the forced H1 line break; desktop inspection confirms one 33.8px line. Color contrast is retained and the muted ink now matches dashboard #545761.

PASS: Vite production build. Desktop computed styles verify font family, 26px H1, 18px section heading and 13px form text. Browser screenshot: `output/support-redesign/dashboard-font-desktop.jpg`. The previous functional checks remain applicable because this follow-up changes presentation and the H1 separator only.

## Alignment follow-up

PASS: at 1280px both heading boxes start at y=205 and end at y=239. Portrait and Category input start at y=322.195; portrait and Message input end at y=666.195. These deliberate alignment anchors connect the editorial and form columns. At 390px the layout stacks and has no horizontal overflow. Screenshots: `output/support-redesign/aligned-desktop.jpg` and `output/support-redesign/aligned-mobile.jpg`. Existing keyboard, form states, contrast and reduced-motion rules are preserved.
