# Product preview motion

Added staged entrances to the landing product preview: masked headline lines, a subtle perspective entrance for the preview window, staggered application rows, and progress tracks. Switching tabs uses a short panel transition. Entrances run once per page visit and restart on refresh.

The existing TraKerja typography, plum palette, content, spacing, and controls remain in place. Product motion intensity is 2 for this requested treatment. Animations are finite and use opacity, transforms, and headline clipping without animating layout dimensions.

## Gates

- Visual PASS: inspected desktop light and mobile dark screenshots in `output/product-motion`; the final layout is readable and has no residual transforms.
- Responsive PASS: 24 panel, viewport, and theme combinations at widths 320, 390, 820, and 1440 showed the intended panel without horizontal overflow.
- Accessibility PASS: keyboard tab switching works; initial reduced motion skips entrances, changing the preference cancels running animations, and content stays visible with JavaScript disabled. Focus can finish an entrance immediately.
- Interaction PASS: scrolling away and back does not repeat entrances; refreshing restarts them. Walkthrough play, pause, manual steps, and replay work. Browser checks recorded no JavaScript errors or backend writes.

## Validation

- UI tests: 9 passed.
- Landing feature tests: 4 passed, 523 assertions.
- Production Vite build passed.
- Diff whitespace check passed; local Laravel returned HTTP 200.

Browser evidence: `output/product-motion/checks.json`, `1440-light.png`, and `390-dark.png`.
