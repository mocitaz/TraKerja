# Workspace footer

Refit: rejected the narrow 900px variant. Footer frame is now 1240px, with a spacious brand area and three evenly sized navigation groups (32px group gaps), while link rows are a tighter 30px. Copyright remains without a separator. Desktop browser verified no overflow; `output/support-redesign/footer-refit.jpg` shows the widened layout. Frame padding prevents the fixed assistant from covering the company text. Existing mobile stacking is retained.

Latest revision: Instagram, email and Telegram are matching icon-only links directly under the TraKerja logo, with accessible names, tooltips and 44px hit targets. The tagline and separate Stay connected column were removed at the user's request. Three navigation groups remain; Support is plain text in the TraKerja group. Navigation tests pass (6 tests/56 assertions). `output/support-redesign/footer-social.jpg` shows the preceding arrangement.

Direction: reference-inspired columns, professional and informative, existing dashboard typography, monochrome branding. ENERGY 1 / RHYTHM 2 / MOTION 1. A brand column and four navigation groups provide hierarchy. Rules, shared spacing and a separate copyright row provide structure without cards or decoration.

The shared workspace/admin footer now contains real product routes, existing landing anchors, official community/contact links and legal documents. Support moved out of the account menu and is a plain text link in Stay connected, as requested. Admin Support opens the existing feedback inbox. Landing's separate custom footer remains as previously designed. Pages explicitly configured without a footer retain that behavior.

The existing logo image is rendered black with CSS brightness(0) in light mode, white with brightness(0) invert(1) in dark mode; wordmark colors are #111 and #fff. No new logo artwork was invented.

Delivery gate:

- Hard gate PASS: all internal route names and landing anchors exist; real Instagram, Telegram and email addresses reused. No fabricated features, metrics, newsletters or app-store links. Desktop 1280px and mobile 390px inspected without overflow. Focus outline and 44px mobile link targets retained.
- Purpose gate PASS: groups distinguish product, public information, contact and legal destinations. Monochrome branding follows the user's explicit direction. One separator divides navigation from ownership information; no decorative animation added.
- Liveliness PASS: brand identity anchors the footer; four desktop columns reflow to two on phones, providing clear hierarchy and rhythm.
- Craft PASS: browser verified black light-mode and white dark-mode logo/wordmark computed styles, inspected mobile two-column layout, and clicked Support successfully. Keyboard tabbing revealed visible focus on legal links. Tests verify Support appears once in the footer and no longer in navigation, while legal and product destinations remain available. 14 related tests/142 assertions passed. Build succeeds.

Screenshots under `output/support-redesign/`: `footer-light.jpg`, `footer-dark.jpg`, `footer-mobile.jpg` (initial button placement); `footer-final.jpg` is the final text-link arrangement.
