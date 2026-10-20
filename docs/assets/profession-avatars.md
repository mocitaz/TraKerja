# Profession portraits

The user's reference is the illustrated avatar picker on Edit Profile. This set
extends that existing SVG illustration language: dark rounded outlines, clear
faces, pastel clothing, and a pale lavender circular background. Each profession
has workwear, a tool, or both. Faces stay central and tools sit at the shoulder
so the portrait remains legible at the actual table size.

Design scope: replace the profession symbols in Tracker's table and application
cards, including Kanban cards. ENERGY 2 / RHYTHM 1 / MOTION 1: character comes
from the illustration; compact table rows stay uniform and avatars stay still.
The set depicts professions, not real applicants or company employees. Each
profession has an explicit character definition: haircut, facial structure,
expression, skin tone, eyewear, facial hair, head angle, and clothing colour.
The characters retain the flat outlined Profile style. The rejected realistic
image-generation experiment was never installed in the application.

## Assets and matching

- `public/images/role-avatars/`: 72 profession SVGs plus a general portrait.
- `resources/data/job-roles.json`: existing job-title catalogue.
- `resources/data/job-role-specialists.json`: 32 additional specialist categories.
- `app/Support/JobRoleCatalog.php`: merges categories and moves existing titles
  into their specialist category, preserving the longest-phrase-first matcher.
- `scripts/assets/build_role_avatars.py`: reproducible local SVG asset builder.

The catalogue recognizes 812 English and Indonesian titles. Matching uses whole
normalized phrases. For example, Dokter Gigi resolves to dentistry, Dokter Hewan
to veterinary care, and Dokter to clinical medicine. An unknown title gets the
general portrait. Avatar assignment is a visual aid inferred from the position,
not an assertion about the applicant's identity or qualifications.

Run `python3 scripts/assets/build_role_avatars.py` after editing portrait artwork
or category labels. They have no
external images, scripts, fonts, API calls, or image-generation dependency.
The distinct asset directory prevents old occupation symbols cached by browsers
from being reused. No database migration is needed.

## Verification and design gate

- Hard gate PASS: 18 PHP tests and 2,331 assertions cover every catalogue title,
  fallback selection, SVG validity, and actual table/mobile/Kanban rendering.
  The table still names the job and company; the avatar has an accessible label
  and its nested image has an empty alt to avoid duplicate announcements.
- Hard gate PASS: browser checks loaded all 144 catalogue preview images and
  every avatar in the rendered example table. Actual avatars measured 38 × 38 px
  on desktop and 36 × 36 px on mobile. At a 390 px viewport, document width was
  also 390 px. No profile photo is stretched.
- Purpose gate PASS: lavender matches Profile; dark outlines maintain facial
  detail; clothing and tools identify professions; circular crops match the
  requested profile-avatar concept. No glow, gradient, shadow layer, or motion
  was added. Uniform row sizing supports scanning the application list.
- Liveliness PASS: the existing Profile visual direction supplies the motif;
  the face is the focal point and the tool is secondary. The declared dials
  preserve the table's information hierarchy.
- Craftsmanship PASS: the 73 files are distinct, local, valid SVGs. Tests
  distinguish close titles and avoid inferring specializations from partial
  words. Table controls and navigation retain existing behavior, exercised by
  TrackerWorkspaceTest. Build, PHP syntax, and diff checks pass.

The preview captures in `output/role-avatars/` use clearly named example
applications in an in-memory test database. They are static visual captures;
they do not verify interactive browser behavior. No production data was used.

Unchanged page content, text contrast, themes, loading/empty states, and controls
are outside this asset replacement's visual scope. No new UI copy, customer
claims, statistics, testimonials, navigation, forms, or interactive controls
were introduced.
