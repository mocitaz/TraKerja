# Halftone mountain footer and readable FAQ

The final reference is the user's monochromatic mountain landscape printed with dense dots and crosshatching. The landing now uses an original plum/lavender mountain image in that style, replacing the earlier vector illustration. There is no green palette, Gaussian blur, new text, or new interactive control.

The decoration is absolutely positioned and clipped inside the lower footer, behind the large wordmark. It never overlaps the FAQ. Hiding it does not change document height. Light mode multiplies the white-backed illustration at 25% opacity; dark mode inverts its luminance, restores the plum hue and uses screen blending at 24%. Footer content is stacked above the artwork, and an edge mask blends it into the lavender background. Image detail is not blurred. It is lazy loaded with explicit dimensions; the WebP is approximately 810 KiB.

Design direction: existing TraKerja identity; energy 2 / rhythm 2 / motion 1. The reference supplies the terrain silhouette and print texture, while plum/lavender match the product. No continuous decorative animation is added.

## Asset provenance

Generated with the built-in imagegen tool using the user's second mountain reference as composition/style guidance. The unused prior generation is not referenced by the website.

Selected original: `/Users/Luthfi/.codex/generated_images/01a15031-d049-7040-aba1-554f2096d964/exec-7f533e65-3801-4845-85d4-5c4440eeea47.png`.

Project asset: `public/images/landing/footer-mountains-plum-v1.webp`, encoded at WebP quality 92 without resizing. The original remains intact.

Exact generation prompt:

> Use case: stylized-concept. Create a panoramic website footer background illustration with the EXACT visual language of this reference: a real layered mountain landscape transformed into crisp halftone newspaper / risograph / crosshatch dot printing. Reference image is only composition/style guidance. Wide 3:1 image. Multiple irregular natural mountainous ridges receding into distance; deep high foreground slopes on bottom left and right, a central valley, lower mountain in center middle, faraway misty jagged peaks along upper third. Very crisp small regularly spaced dark dots and x-shaped halftone marks, varying density to describe mountain shadows and terrain volumes. Monochromatic muted PLUM / AUBERGINE ink on a clean WHITE paper backdrop. Absolutely NO GREEN, no teal, no blue-green. Dominant dark ink color #70567e with darker valleys #493552, pale lavender distant peaks. Upper 15% fades gently to pure white. Bottom edge stays richly textured. Reference should remain recognizable through mountain composition, dense dotted print texture and atmospheric layers. No smooth sine curves, no ribbons, no flat vector waves, no 3D satin, NO blur or out-of-focus dots. No website UI, no words, no copyright, no branding, no lines of text, no watermark. Full bleed side edges. This is an actual image asset that will sit behind existing FAQ/footer text with CSS opacity control.

## Verification and delivery gate

Current evidence is recorded in `output/footer-landscape/faq-legibility-checks.json` and `faq-clean-1440.png` / `faq-clean-390.png`. Eight checks at 320/390/820/1440px in both themes verify the image is contained by the footer and does not overlap FAQ, no document height is added, no horizontal overflow occurs, the community section is absent and all six questions remain. Native accordion toggling is exercised using the keyboard. Earlier screenshots document the previous placements.

- Hard gate PASS: build, whitespace check and four landing tests (523 assertions) passed; eight layout/theme checks cover artwork containment, zero added space, viewport bounds, keyboard operation and no JavaScript errors. The decorative layer has `pointer-events:none` and is behind footer content.
- Purpose gate PASS: halftone texture and mountain layers follow the user's latest reference; plum/lavender follow the existing brand. The fade joins the page and footer without a new section.
- Liveliness PASS: existing 2/2/1 direction retained; the FAQ has a quiet, readable page background, and the mountains add depth behind the footer wordmark.
- Craftsmanship PASS: desktop/mobile and light/dark screenshots inspected; reduced-motion remains static; no new action or form was introduced. Browser checks use Chromium emulation rather than physical devices.

## FAQ position adjustment

FAQ content is raised 24px on narrow screens and up to 48px on desktop by redistributing its existing top/bottom padding. The section's total height and the mountain/footer position are preserved. Browser checks at 320/390/820/1440px measured rises of 24/24/27.39/48px, zero document-height change, no horizontal overflow and at most 0.016px rounding in the mountain position. Build and whitespace checks passed. Gate PASS: the existing direction and controls are preserved, the adjusted content remains responsive, and no space or motion was added. Evidence: `output/footer-landscape/faq-rise-checks.json` and `faq-raised.png`.

## Community section

The community callout was removed at the user's request. Its unused glass and responsive styling has also been removed. The footer's Telegram destination remains available.

## Lavender footer restored

At the user's request, the landing footer is again the exception to the white page canvas: its light base is `#F7F4F9` with the original lavender/blush/mist radial tints. The landscape fade ends on the same base, preserving the seamless boundary. Other light page canvases remain white. Browser computed-color checks confirm the light footer and blend base match, the page body remains white, and the dark footer remains `#131017`. Build and whitespace checks passed. Gate PASS: the original footer direction is restored without new content, controls, layout or motion; source colors and rendered theme colors agree.

