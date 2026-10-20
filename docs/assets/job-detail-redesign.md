# Application detail redesign

## Direction

The user's job-portal reference establishes a prominent profession identity,
compact metadata, a four-column fact strip, and a secondary right-hand column.
Keep TraKerja's white surfaces, lavender accent, typography, and illustrated
profession portraits. ENERGY 2 / RHYTHM 2 / MOTION 2: the job title and avatar
lead; a brief 6 px entrance supports hierarchy and respects reduced motion.

The page manages an existing application. Header facts come from that record:
status, stage/outcome, location, employment, application date, source and last
edit date. No match scores, employer verification badges, applicant counts or
unrecorded job requirements were added. The AI card opens the existing AI
Studio. Schedule, research, salary-reference disclosure, notes, ownership and
application actions retain their existing functionality.

## Team decision after the initial review

Removed Company research, its LinkedIn/Glassdoor links, and Salary reference.
Removed the bottom application reminder, Back to tracker link and the workspace
copyright/company footer from the application detail page. The top All
applications navigation remains available. Other pages keep their default
workspace footer through AppLayout's optional showFooter property.

The lower resource column now contains only the selection schedule and uses its
natural height. The grid measurements and screenshots below record the earlier
review state before this removal. Latest validation: 22 PHP tests, 200 assertions
across JobDetailWorkspaceTest, TrackerWorkspaceTest and ExploreWorkspaceTest;
the detail test asserts the removed content is absent.

## Precision

Use one CSS grid with explicit identity/preparation and content/resources rows.
Header and AI card stretch within the same row. The main content and resource
column share the second row. Remove sticky positioning that shifted one column
relative to the other. Use consistent 24 px desktop gaps and 18 px outer radii.

The profession portrait has one circular crop: transparent container, no outer
rounded-square backing, 78 × 78 px on desktop and 62 × 62 px on phones.

## Verification and design gate

- Hard gate PASS: 26 PHP tests / 2,377 assertions cover detail rendering,
  owner isolation, escaped notes and unsafe URLs, actions, schedule persistence,
  tab/panel relationships and all profession-title mappings. Nine JavaScript
  tests cover tab transitions, keyboard navigation, reduced motion and modal
  save/cancel sequencing. Build and diff checks pass.
- Hard gate PASS: actual browser testing uses a separate local application on
  port 8018, isolated SQLite database, synthetic account and clearly labelled
  example application. Tabs, keyboard Home navigation, loading the owned edit
  form, closing the form, and opening/cancelling archive confirmation were
  exercised. No original user record or outgoing mail was changed.
- Responsive PASS: at 1280 px, both top cards start at 156 px and end at 516 px;
  the second-row containers start at 540 px and end at 1377 px. At 820 px the
  equivalent top bounds are 156/508 px and second-row bounds 526/1347 px.
  At 390 px all four containers are 356 px wide, and the document is 390 px
  wide. The portrait is square with a 50% radius and no backing layer.
- Contrast PASS: measured muted text on white 5.44:1, muted text on lavender
  4.56:1, and white primary-button text on purple 7.18:1. Focus indicators remain
  visible; controls use 44 px targets. Dark theme was checked and light restored.
- Purpose gate PASS: the existing profession avatar identifies the position;
  briefcase and location icons label real metadata; source/research brands name
  their destinations. Lavender identifies the AI preparation card and primary
  actions. No decorative patterns, glass, fabricated statistics or new assets
  were introduced for the page.
- Liveliness PASS: the title/portrait is the focal point, the four facts form a
  scanning strip, and the smaller resource cards form a secondary hierarchy.
  Space separates identity, actions, facts and tab content. Motion is brief and
  does not loop.
- Craftsmanship PASS: original edit/archive/delete/source routes and the
  modal remain connected. The header and panels use real record values and
  explicit missing-data states. Screenshots are saved in
  `output/job-detail-redesign/desktop.jpg` and `mobile.jpg`.

The live user's `/jobs/28` required their authenticated session in the agent's
browser, so visual testing used the isolated fixture with the same record ID.
The reference salary component was retained; its dataset and estimates were
not re-audited as part of the layout change.
