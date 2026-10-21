# Optional campaign banners

## Revision 2 design read

User requests a more polished, professional branded series, with less synthetic styling and at least the logo plus TraKerja wordmark. Replace the generic paper shapes, arch and floating plane with restrained editorial portraits and natural studio light. Use a consistent left-aligned black brand mark and the exact text `TraKerja`, ample breathing room and muted photographic backgrounds. Keep the five existing IDs and the optional selector. Generate revised raster artwork with the existing banner as the identity reference and actual icon as the brand reference. No baked-in campaign-specific copy so the series remains reusable. ENERGY 2 / RHYTHM 2 / MOTION 1.

## Initial version

Design read: the user requested 4–5 reusable generated banners featuring existing TraKerja hero, landing or support people, plus an optional banner chooser in the email workspace. Keep the existing admin composer and confirmation flow. ENERGY 2 / RHYTHM 2 / MOTION 1. Use five restrained editorial photographic compositions without baked-in copy so they work in either language and across general campaigns. Preserve reference identity and clothing. Banner thumbnails are genuine assets, not generated UI placeholders. Selection uses a native radio group with an explicit no-banner default; email rendering uses a responsive standard image with inline styles and JPEG compatibility. No automatic dispatch is part of this task.

## Assets and generation

Generated with the built-in imagegen tool, one call per asset. Production copies are JPEG at 1200×400, quality 86, optimized/progressive, about 33–40 KB each. Originals remain under Codex generated_images; all product references point to `public/images/email-banners/`. The support scene was cropped toward the top to preserve hair/head room. No text is baked into the banners; subject and body remain readable HTML even when a mail client blocks images.

| ID / filename | Reference | Scene prompt |
| --- | --- | --- |
| next-step.jpg | landing/hero-portrait-white-v4.webp | A confident friendly welcome. The same smiling man with folded arms on the right, left side simple open off-white space. Soft lilac studio paper backdrop and one gently curved paper plane behind him. |
| fresh-start.jpg | support/trakerja-support-cutout.png | A fresh start. Same man with round glasses on the right, a warm natural smile, black TraKerja shirt. Pale sage studio background, a single architectural soft green arch and light cream paper plane on left. |
| keep-going.jpg | landing/hero-portrait-white-v4.webp | Encouragement. Same smiling curly-haired man on right holding a closed cream notebook at waist. Warm ivory studio background with a muted terracotta semicircle, subtle natural shadows, ample left space. |
| here-to-help.jpg | support/trakerja-support-cutout.png | Friendly support. Same man with glasses sitting on right behind a small clean desk with silver laptop, black TraKerja shirt, calm off-white and muted plum studio, spacious left side. |
| new-perspective.jpg | landing/preparation-portrait.webp | Career preparation. Preserve this woman's identity and outfit, standing on right with a slim notebook, natural approachable expression, cream and dusty rose studio paper background, clean left negative space. |

Shared prompt: “Use case: identity-preserve / ads-marketing. Create one polished photographic email banner for TraKerja career platform, landscape 3:1 composition. Reference image is the identity and clothing reference. [Scene above] Editorial studio photography, realistic human features, accurate face identity, natural hands, soft daylight. Keep face and shoulders fully inside frame. Compose for email width600, not a UI mockup. No headlines, no letters, no floating cards, no dashboards, no decorative icons, no watermark. Only retain the small existing TraKerja shirt mark naturally. Artwork fills the entire rectangular canvas, no outer margin or rounded corners. Make it feel professional and warm, restrained palette grounded in TraKerja reference. Produce a single banner, not a collage.”

## Implementation and verification

PASS. No-banner is the initial selection; native labelled radios show all five thumbnails. The selection travels in the preview payload and the same frozen payload is used at dispatch. All four campaign endpoints validate the banner against the catalogue; external URLs, traversal paths and arrays are rejected. Both bulk and individual delivery apply banner view data to the existing mailables. Verification preview uses a dummy OTP without creating a challenge; actual verification delivery still uses the existing cooldown, locking and OTP service, with an optional banner. Other automatic OTP mail remains banner-free.

Build passed (82 modules). New banner tests plus existing admin and email presentation checks completed with 765 assertions, no failures (77 existing `/dev/null/.env` setup warnings). Existing admin/OTP tests separately passed 25 tests, 232 assertions. Tests use Mail::fake; no real campaign was dispatched. Browser QA in a separate synthetic SQLite environment confirmed no-banner initial state, selected thumbnail styling, banner in the preview, responsive 390px layout and dark-mode selection. Native focus states and large label tap areas remain; no new motion. Browser proof: `output/email-banners/chooser.png`, `email-preview-mobile.png`. Actual Gmail/Outlook delivery was not exercised.

## Revision 2 assets and prompts

Generated with the built-in imagegen tool, one edit per banner. Each edit supplied the initial banner and `public/images/icon.png` as references. Current production files are `public/images/email-banners/{ID}-v2.jpg`, 1200×400, optimized quality-86 JPEG; 41,725 / 43,921 / 43,173 / 43,705 / 42,765 bytes respectively. Versioned paths refresh old thumbnails without changing the five selector IDs. Source originals remain in Codex generated_images; current paths are recorded locally in `output/email-banners/sources-v2.json`.

Shared revision prompt: “Use case: identity-preserve / ads-marketing. Edit this existing email banner into a refined TraKerja branded photographic campaign header. Image 1 is the edit target and exact identity/pose reference. Image 2 is the actual brand icon reference. [Scene below] LANDSCAPE 3:1, full bleed, photographic not 3D. A single coherent visual identity across this set: person occupies the right 40 percent, full head with breathing room inside frame, shoulders and upper torso remain visible; left 55 percent is calm negative space containing one compact brand lockup. Place the exact triple-chevron icon from image 2 in flat charcoal black to the LEFT of the text TraKerja in a clean, carefully kerned semibold sans-serif, horizontal baseline alignment. Brand lockup on left centered vertically, inset 8 percent from left edge; text should be large enough to read at a 600px-wide email (approx 40px high at that display size). Exact text: TraKerja. No other text, no tagline, no gradients in the mark, no repeated wordmark or oversized watermark. Logo must have exactly THREE right-pointing chevrons matching the supplied mark. Keep human identity, skin texture and natural clothing, no airbrushed plastic skin. Preserve existing small shirt logo. Light is believable, colors muted and balanced, no artificial shiny CGI, no floating objects, no abstract 3D shapes, no glass UI cards, no invented product screenshots, no outer frame, no rounded corners. Create one banner only.”

Scene edits:

- next-step: A soft warm gray seamless studio background, subtle real side light. Keep the smiling man in black shirt with arms folded. Restrained charcoal and warm off-white palette, no purple.

- fresh-start: A desaturated sage-gray seamless studio background with natural window light. Keep the same man with glasses in black shirt. Remove the arch and paper airplane entirely. Sophisticated muted green and charcoal palette.

- keep-going: A warm stone-gray studio background. Keep the same smiling man holding the cream notebook. Remove the orange semicircle, use a very subtle warm rust accent only as the edge of a real wall at far right. Natural editorial portrait.

- here-to-help: An understated real work setting with an off-white wall, desk and silver laptop on the right with the same man wearing glasses. Remove the purple semicircle and plant, keep clean photographic natural side light.

- new-perspective: A quiet pale dusty rose-gray seamless photographic backdrop. Preserve the same woman, pink blazer and notebook. Remove synthetic folds or angular background panels. Natural studio portrait with soft daylight.

Revision 2 delivery gate: PASS. Reviewed the five compressed outputs together for spelling, three-chevron mark, portrait framing, color consistency and text legibility. Real catalogue assets appear in the dark-mode desktop picker; existing optional selection and campaign flow are unchanged. Preview QA uses only the synthetic local environment, with no email dispatch. The existing seven banner/admin tests passed (95 assertions). Original version remains locally for comparison; v2 is the active catalogue.

