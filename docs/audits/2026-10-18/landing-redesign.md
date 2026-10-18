# Landing page redesign — 18 October 2026

## Direction and scope

Approved reference direction: white canvas, strong centered Google Sans Flex typography, restrained blush/lavender/mist-gray hero artwork, a monochrome portrait and real HTML interface cards. Preserve the TraKerja brand, English default, Indonesian switch, account links, legal links and light/dark appearance. The supplied portrait replaces the initial illustrative portrait direction; the person wears a plain black T-shirt, with 42px image headroom on desktop and 48px on mobile.

Design read: public career-workspace introduction for job seekers, editorial product presentation, energy 2 / rhythm 3 / motion 2. The revised palette uses soft logo-related blush and lavender, neutral surfaces, and dark plum for preparation; sage remains in the existing editorial illustrations. Color and depth distinguish the hero; the following sections alternate interactive product views, narrative illustration, preparation examples, document previews and quiet comparison content. Motion explains state changes and supports pointer exploration; there is no autoplay, infinite loop or scroll interception.

All page sections were rebuilt as focused Blade partials under `resources/views/landing`. Dedicated CSS/JS entries isolate the public design from authenticated application screens. Google Sans Flex is self-hosted with its OFL license. Generated artwork and optimized existing portraits are stored under `public/images/landing`.

## Behavior

- Product tabs: Tracker, Calendar and AI Studio, with arrow/Home/End navigation and linked tab panels.
- Walkthrough: play, pause, replay and manual selection. Manual tabs pause playback without reverting the chosen view. Playback finishes, pauses on hidden pages, and does not call an AI provider or save application records.
- Preparation examples: three modes with a working copy action and success/failure feedback. Clearly labeled as example responses.
- Four illustrative CV layout previews link to the real CV builder for signed-in users or registration for guests.
- Workflow and FAQ use native disclosure elements. The mobile menu closes on link selection, outside click or Escape.
- Plans follow the monetization setting, live Premium price, configured access duration, existing feature limits and shared add-on benefits. Free mode retains all three plan prices for comparison, clearly explains that paid purchases are unavailable, and offers no checkout link. Premium pricing comes from settings and credit-pack pricing comes from PaymentPackages::ADDON_PRICE.
- Testimonial names, roles, photographs, quotes and existing five-star ratings are preserved from the prior landing page. The reference-inspired carousel centers a full-height portrait, places previous/next controls on its edges and shows neighboring quote cards on desktop. Touch swipes and keyboard navigation work. The user requested removal of the center action; the final design contains no story/video button or dialog. The selected quote appears over the portrait with a dark backing for readability, and every card includes the actual TraKerja brand icon and five stars.

## Verification

- PHP: four landing tests plus the existing language catalogue test passed (30,608 assertions). Covers both locales, local anchors, linked tab panels, paid/free modes, runtime price, existing testimonials, catalogue placeholders and signed-in action destinations.
- JavaScript: 4 tests passed, covering finite demo playback, pause/cancel, scene bounds, reduced-motion/touch pointer behavior and bidirectional testimonial wrapping.
- 264 Blade files compile; production build and whitespace check pass.
- Chromium: final 28 combinations of English/Indonesian, light/dark, and widths 320/390/600/768/1024/1440/1920px. All have no horizontal document overflow, a loaded local font and visible button/disclosure targets of at least 44px. All three carousel selections have visible center quotes, text/footer content contained within the card, five stars and a loaded brand logo. No story action or dialog remains. Details are in `landing-layout-checks.json`. Earlier checks also confirmed no clipped neighboring quotes.
- Browser interaction checks: manual product tabs and arrow keys, all CV/AI examples, copy feedback, workflow/FAQ disclosures, theme/language switch and carousel navigation. Guest language changes were exercised through the existing endpoint. Public home/login/register/privacy/terms endpoints returned HTTP 200.
- Independent code review confirmed pricing sources and application-style isolation. A focus issue in an intermediate story-dialog version was corrected and browser-verified; that entire dialog/trigger was then removed at the user's request.

No dependency installation, schema migration, paid AI request, message sending, or saved application mutation was performed. Local PHP/Vite servers are running for preview. No commit or push has been made for this redesign.

## Delivery gate

- Hard gate PASS: linked actions and section anchors checked; sample data is labeled; no fabricated customer/logo/usage metrics added; keyboard interactions and 28 responsive combinations verified.
- Purpose gate PASS: the hero color field follows the supplied reference; paper ribbons represent a career path; portrait and cards establish hierarchy. Raised treatment is restricted to the hero and active testimonial.
- Liveliness PASS: the typography, portrait collage and paper motif follow the approved references; section compositions vary; interactions expose actual example content.
- Craftsmanship PASS: existing locale/theme/auth/legal behavior is preserved; unsupported legacy pricing/template claims were replaced with current sources; generated imagery complements readable interface content.

## Limits

Authenticated checkout submission, payment-provider behavior and native Safari/Firefox rendering were not exercised. Product and CV previews are illustrative examples, not actual account data. Existing testimonial authenticity is inherited from the prior product content; no new endorsement, rating or date was generated.

## Image assets and final prompts

Built-in imagegen was used. Selected outputs were copied into the project as optimized WebP files; portrait transparency was preserved. Existing testimonial photographs were only resized/compressed into sibling WebP files; their source files are untouched.

### `public/images/landing/hero-portrait.webp`

Input: the portrait supplied by the user in this conversation. Final prompt:

> Edit the supplied portrait for a professional website hero. Preserve this exact person's identity, face, natural warm smile, eyes, teeth, hairstyle, skin texture, proportions and crossed-arm pose. Change ONLY the clothing: replace the entire black suit jacket, white shirt, and tie with one plain well-fitting premium BLACK SHORT-SLEEVE CREW-NECK T-SHIRT. No jacket, no shirt collar, no tie, no logos, no graphics. Keep anatomically natural arms and hands, preserving the crossed-arm pose and face. Remove the white background entirely and output a clean transparent background with genuine alpha, neatly cut out around hair and arms. Preserve natural full-color skin, professional soft studio lighting. Centered waist-up composition including the entire head and crossed arms, no extra person or props, no lettering. The artwork will be styled monochrome in CSS in the website so retain full-color source for flexibility. High fidelity identity preservation is essential.

### `public/images/landing/career-path.webp`

Final prompt:

> Use case: stylized-concept. Create a premium editorial 3D illustration asset for TraKerja, a job application and career preparation web product. Wide landscape composition about 16:9. A sculptural career journey made from a flowing ivory paper ribbon that winds from the bottom left foreground through low rounded sage green architectural steps toward a tall open archway on the far right. At the left edge, a small oversized plum-bound portfolio and a folded ivory resume sheet rest on a soft terracotta platform. Sparse tiny olive trees give scale like a designer travel poster, not a landscape photograph. Refined contemporary 3D product render, tactile matte paper and ceramic, soft afternoon studio shadows, warm off-white background #f6f5f0. Restrained muted sage, aubergine plum, warm peach accents. The center and upper center are mostly clean pale negative space intended for real HTML interface cards to be overlaid, the sculptural elements frame them from the lower edge and sides. Attractive polished design like a high-end creative studio website hero. Crisp detailed surfaces, playful but professional, no harsh neon or rainbow, no sparkles, no generic AI orb. Absolutely no words, letters, logos, watermarks, numbers, UI screenshot, or borders. Do not render an entire website. This is an original illustrative scene, not a copy of a reference.

The asset was moved to the workflow section after the user narrowed the hero direction to a photographic collage.

### `public/images/landing/creative-desk.webp`

Final prompt:

> Use case: stylized-concept. Create a premium editorial 3D illustration for the CV and portfolio section of TraKerja, a career preparation website. Landscape 4:3 composition. An art-directed modern creative desk still life: one tall gently curved ivory paper portfolio standing on a low matte muted sage pedestal, with a dark aubergine closed laptop at a relaxed angle, an elegant plum pencil leaning on the document, and a small terracotta sculptural paperclip. Two understated cream document sheets fan behind the main sheet, one sheet has an abstract dark circular portrait placeholder and a few short delicate embossed lines suggesting a resume but absolutely no readable text. Sculptural warm white architectural wall behind, soft afternoon sunlight through a window creates subtle long shadows. Sophisticated tactile ceramic and paper materials, generous negative space, restrained palette warm off-white #f6f5f0, muted sage green, dark plum, a touch of warm peach. A beautiful high-end creative studio campaign, coherent with a career paper ribbon journey hero. Close to real product photography but deliberately sculptural 3D. Crop cleanly with no frame. No words, letters, logos, numbers, watermark, stars, neon, rainbow, UI screenshot or web page. This image will support real HTML resume previews, not replace them.

## Palette and pricing follow-up

The user requested less sage and visible plan prices. The hero now uses blush/lavender/mist-gray, the preparation section uses dark plum, Premium uses pale lavender, and the credit pack uses pale blush. Free, Premium and AI credits are always visible; monetization still controls paid checkout availability. Runtime screenshots and 10 light/dark viewport checks at 320/390/768/1024/1440px confirmed all three prices, no document/card overflow, and aligned desktop actions. PHP tests cover visible prices with monetization disabled and the absence of checkout links. Both catalogue translations render.

## Footer reference follow-up

The footer now uses a full-width branded composition: contact and working section navigation at the top, a large existing TraKerja icon and typographic wordmark, and legal/social links below. The light treatment is pale lavender/blush; dark uses an off-black surface with restrained plum/blush illumination and a white mark. No new external assets or dependencies were added. Ten Chromium checks at 320/390/768/1440/1920px used the actual theme toggle and confirmed the rendered theme, contained wordmark, no horizontal overflow, working section anchors and link heights of at least 44px. Desktop and mobile screenshots were visually checked in both themes. PHP: five tests, 30,630 assertions; production build and whitespace check passed.

## Indonesian portrait collage


Added a decorative collage immediately after testimonials, before the community invitation. Eight fictional Indonesian adults are presented as monochrome portraits within pastel circles of varied sizes. There is no claim button, name, rating or endorsement attached to this artwork. Desktop uses the reference's staggered composition; phones show two rows of four. All images share one cached atlas, loaded lazily, with CSS cropping each tile. The collage is hidden from assistive technology because it adds no content. Browser checks at 320/390/768/1440px confirmed placement, all eight loaded portraits, contained circles and no overflow or actions.

Final asset: `public/images/landing/indonesian-portraits.webp` (1774 × 887, approximately 143 KiB). Built-in imagegen generated the original PNG; cwebp converted it without changing the composition. Source: `/Users/Luthfi/.codex/generated_images/01a13ece-6fbf-7f83-95c0-738b06ae41db/exec-505a40a8-cb3a-4726-bfa3-32492e5cadf1.png`.

Final built-in prompt:

> Use case: photorealistic-natural. Asset type: one production portrait atlas for a career website community collage. Generate a precisely aligned FOUR COLUMN by TWO ROW contact sheet, eight equal square tiles, absolutely no spacing, no borders, no captions, no typography. Each tile is a separate photorealistic black-and-white head-and-shoulders studio portrait of a DIFFERENT fictional Indonesian adult age 23–35, natural real skin texture, contemporary casual/professional clothes, authentic individual faces and hairstyles, friendly confident expressions, a mixture of smiling and relaxed thoughtful poses. Men and women equally represented; include one woman wearing a neatly draped hijab, one man with naturally curly hair, one woman with short hair, others with varied contemporary Indonesian hairstyles. No stereotypical costume or props. All subjects fully inside their individual tile, whole heads visible, shoulders reach the bottom of that tile, centered portrait composition and close editorial crop. The PHOTOGRAPHS and people are monochrome grayscale, but each tile has a different flat very soft pastel background: top row warm peach, pale lavender, blush pink, pale powder blue; bottom row pale lilac, light rose, soft cream, mist gray-blue. Soft even editorial studio lighting, premium authentic recruitment campaign photography. Keep tile boundaries EXACTLY at 25%, 50%, 75% horizontally and 50% vertically; no overlap between tiles. All eight portraits same scale. This atlas will be cropped by code into individual round portraits. No circles rendered, no UI, no button, no logo, no stars, no text or watermark.

## Pricing card reference follow-up

## Preparation portrait banner

The former AI example-chat section was replaced with a reference-inspired compact banner: short copy and the real AI Studio/registration action on the left, the supplied woman's edited portrait on the right, and concise CV/interview cards above. The portrait is cropped at the waist using the banner's clipping boundary; typography and card heights were reduced after user feedback. A subtle diagonal plum grid replaces the first circular backdrop. The final CSS filter exactly matches the male hero: `grayscale(1) contrast(1.06)`. Original colors are retained in the file. Removed the obsolete conversation tabs/copy handler and styles. Both languages render; PHP checks passed (5 tests / 30,604 assertions).

Asset: `public/images/landing/preparation-portrait.webp` (1024 × 1536, approximately 125 KiB, alpha preserved). Built-in imagegen edited the user-supplied portrait; cwebp optimized it. Final prompt:

> Use case: identity-preserve. Edit the supplied photo into a premium website portrait cutout. Preserve the exact woman in the input: same facial identity, warm slight smile, eyes, nose, long wavy dark brown hair, age, skin tone and natural proportions. Preserve her pale blush-pink blazer over black top and black trousers, one hand in the trouser pocket and the other holding the existing plain takeaway coffee cup. Remove ALL background, the white strip, cropped promotional text, number badge and surrounding office. Output genuine transparent alpha with clean detailed hair edges. Natural soft studio light, photographic skin texture, tasteful professional career campaign quality. Reconstruct only missing clothing edges or hair detail if necessary, preserving the original pose. Composition: entire head with generous clear space around hair, down through upper thighs; the woman fills the vertical frame but no head clipping. No added text, props, jewelry, logo, UI, watermark or decorative backdrop. Do not replace this person with another person. Clean high-resolution realistic portrait for compositing into a web banner.

Pricing cards now separate a price/action header from a feature/terms body, following the supplied reference while retaining Google Sans Flex, the site's container widths and restrained brand colors. Premium uses a pale lavender/blush header (muted plum in dark mode); the other plans stay neutral. All actions occupy the same header position and unavailable purchases remain non-interactive labels. Runtime prices, benefits and payment eligibility are unchanged. Sixteen browser checks across EN/ID, light/dark and 320/390/1024/1440px confirmed three visible prices, no document/card overflow, actions above features and aligned desktop headers. Five PHP tests (30,636 assertions), production build and whitespace check passed.

## Interactive workflow showcase

Replaced the career-path illustration and disclosure list with a left-side introduction and four selectable layered cards: save an opportunity, plan the next step, prepare, and review progress. Each selection updates a labeled example panel using the existing accessible tab controller. Arrow/Home/End keyboard behavior is shared with other landing previews; reduced motion disables entrance movement. Examples are labeled, all data is illustrative, and no record or provider request is created. The original image file is retained but is no longer used in this section. EN/ID catalogue parity and tab-panel associations passed PHP checks (five tests, 30,638 assertions); four JavaScript checks passed. Browser checks exercised all four selections, keyboard focus, light/dark at 320/390/768/1440px. The rotated layer bounds were tightened after detecting a 2px mobile viewport expansion; final 320px inner/client/scroll widths all equal 320. Production build and whitespace checks passed.

## Pricing spacing and composition revision

The final pricing design follows the newer reference: centered heading and introduction, neutral equal-height cards, aligned prices, feature separators and actions at the bottom. Premium uses only a restrained plum border and faint lavender header. Added a measured gap of 96px after the preparation banner on desktop and 60px on phones; the section background fades into the page. Runtime prices, lifetime wording, benefits and disabled-purchase behavior are preserved. Eight browser combinations (320/390/1024/1440px, actual light/dark toggle) confirmed no card/document overflow, aligned desktop actions and the intended gap. Five PHP tests (30,514 assertions), production build and whitespace check passed.

## Application-source marquee

Replaced the hero feature-name strip with all 31 source choices from PlatformCatalog::OPTIONS, using existing local logo assets where available and source names throughout. The final user-requested layout is one aligned row with grayscale, reduced-opacity logos, not two rows. Copy explicitly frames the list as places from which applications can be recorded. It makes no partnership or automated-import claim. The row loops slowly with a working pause/resume control; reduced-motion mode shows one static horizontally scrollable list and hides the repeated decorative copy. Screen readers encounter each source once. Existing JobStreet logo cropping is preserved. Browser checks confirmed 31 sources, loaded images, pause/resume, no overflow and the reduced-motion scroll alternative. PHP catalogue/source-presence tests passed (5 tests / 30,576 assertions); build and whitespace checks passed.

## Sticky centered navigation

Renamed About TraKerja to Our story / Cerita kami. Desktop uses equal-width outer grid columns so the five center links align with the viewport center independently of brand/account controls. The header sticks at top with a restrained light/dark glass surface. Guest topbar signup CTA remains removed. Anchor scroll padding keeps headings below the sticky header. Browser checks at 320/390/900/1024/1440/1920px confirmed no overflow, top=0 after scrolling, no desktop overlaps and menu center within 0.004px of viewport center. The mobile menu opens and closes on selection; the product anchor is below the 77px header. Five PHP tests (30,576 assertions), build and whitespace checks passed.

## Footer wordmark motion

The large TraKerja wordmark now reveals its eight letters in a staggered rise on first viewport entry. Mouse entry and keyboard focus trigger a finite sequential lift/color wave; pointer movement adds a very small text/logo offset. Animations have a cooldown, no perpetual loop, preserve the back-to-top link and do not enable image dragging. Reduced-motion preference suppresses and cancels animation. Browser checks confirmed eight entrance animations, eight hover-wave animations, bounded pointer offsets, no overflow on desktop/mobile, zero animations under reduced motion, and unchanged text/link semantics. Five PHP tests (30,576 assertions), production build and whitespace checks passed.

## Full-page mobile and motion audit

Audited the current hero, source marquee, demo, founder story, preparation, pricing, testimonials, portrait collage, community, FAQ and footer. The established photography, copy, plans, section order and palette remain intact.

- Standardized section spacing with an 88 / 72 / 56px desktop / tablet / phone scale. Removed doubled spacing before the story and excessive space after pricing; preserved separation before the pricing background.
- Increased small mobile labels and supporting copy, retained touch targets of at least 44px, and gave footer legal/social links a minimum width.
- Tablet testimonial layout now shows one readable full-width card with the same previous/next controls, instead of squeezing three cards. Pricing cards are capped at 560px on tablet. The preparation portrait moves right on tablet; its interview pill and review card no longer cross the face. Phone portrait remains cropped at the waist.
- Replaced CSS-hidden whole-section reveals with progressive Web Animations entrances on individual visible content groups. Each plays once per page load, including after refresh, as it enters the viewport. Short staggered batches preserve reading order. No page section depends on JavaScript to become visible. Existing footer letter motion and source pause/resume remain. Reduced motion skips/cancels entrances, and keyboard focus finishes any animation on the focused content.
- Browser evidence: 12 Indonesian width/theme combinations at 320/390/600/768/1024/1440px have no horizontal page overflow, no checked content outside the viewport and no visible link/button/disclosure shorter than 44px. English checks at 320/390/768/1440px also have no overflow; all three selected testimonial quotes fit their cards. See landing-mobile-motion-checks.json.
- Inspected screenshots of phone product/preparation/footer, tablet preparation/pricing, and desktop testimonials. Scrolled the entire page: 43 groups revealed, no broken loaded images. Verified refresh entrance activity, zero animations under reduced motion, sticky header, menu open/close on selection, three demo panels, FAQ and carousel. Browser console has no warnings/errors.

Verification is limited to Chromium emulation; physical iOS Safari and Android devices have not been tested. No account data, payment flows or application backend behavior changed.
