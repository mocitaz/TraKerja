# Summary redesign

## Sources and locations: revised design read

User requests a simpler paired desktop row, drawing on the latest five analytics references. Keep white containers as most recently requested. ENERGY 2 / RHYTHM 3 / MOTION 1 remains. Replace the oversized source donut with a compact proportional ribbon, exact selected-source readout and platform ranking; reuse real platform identities and category colors. A smaller geographic atlas above a compact city ranking balances the adjacent source list. Shared heading, padding and footer baselines make the pair read as one comparison area. Existing source detail tables, exact distribution, map boundaries, outside-map records and data-source notes remain available. No fabricated reference metrics or decorative background effects are transferred.

Implemented as two equal-width panels with aligned headers and bottom disclosures, stacking below 950px. Sources use a proportional ribbon, selectable platform list and compact interview-stage/accepted readout. The map keeps genuine city boundaries, zoom/reset controls, synchronized city selection and outside-map accounting; geographic explanation and attribution live with the full location records. Long rankings scroll within their own region. The distribution regression test now verifies the ribbon's full 100% volume and the unabridged fallback table, preserving coverage after removing the donut.

Follow-up: dark Summary surfaces, selected backgrounds, outlines, primary actions and primary chart marks now use neutral charcoal/gray tokens. Secondary series and inline source/employment colors are muted, and geographic fills are desaturated. Light mode keeps white panels with soft lavender only behind the Current stage readout, as explicitly requested.

Delivery gate PASS: production build, 9 Summary feature tests / 80 assertions and 3 JavaScript tests passed. Browser inspected the paired 1280px desktop layout and stacked 390px mobile layout, source selection (JobStreet 10 / 20.4%, 0 interview stages / 1 accepted), Bandung boundary zoom (6 / 12.2%), Java & Bali mobile control and both full-record disclosures, including geographic attribution. Light and dark screenshots verified; temporary viewport reset. No added claims, imagery or inert controls; existing theme, keyboard, empty-state and reduced-motion handling retained. Screenshots with synthetic data: `paired-sources-locations.jpg`, `paired-sources-locations-dark.jpg` and `stage-lavender-readout.jpg` in `output/summary-redesign/`.

## Design read before implementation

User direction: complete redesign of every Summary section, with the five supplied analytics references; no category tabs. Keep TraKerja's lavender palette and existing workspace typography. References contribute a strong activity plot, clear grouped metrics, map with adjacent ranking, chart-specific visual forms and organized tables. Their sample business figures and decorative blur are not product data.

ENERGY 2 / RHYTHM 3 / MOTION 1. A broad activity chart is the first focal point. Lavender represents application volume, blue scheduled interviews, muted terracotta rejected records and green accepted outcomes. White report panels sit on the existing off-white canvas. Separators, consistent gutters and varied panel proportions establish hierarchy; chart interactions provide exploration without perpetual motion. Existing role avatars and platform marks identify the user's actual categories.

Every analysis remains visible on a single page: activity, recorded outcomes, current stages, sources, locations, roles, employment and companies. One global period selector controls the original backend filtering. Current stage counts are never labeled as historical conversion; scheduled interviews retain their own event dates. All-time totals and the twelve-month activity window remain distinct. Geographic areas without a boundary remain explicitly included outside the map.

## Implementation

The Summary view and all its report sections have been rebuilt. Native SVG activity bars and interview lines, a 100-dot status distribution, current-stage meters, a source donut, an Indonesia boundary map with location markers, role rankings, an employment share strip and searchable company records now coexist without category tabs. The original controller and its date, owner, archive and premium access rules remain in use. Existing legacy Summary styles remain because the admin analytics page still consumes them; the new report uses an isolated `insights-` namespace.

Table search, ordering and pagination operate on the complete supplied dataset. Detail disclosures and no-JavaScript tables retain access to raw values. A stale Explore tracking test fixture was updated to provide the location and applied date required by the existing tracking flow; production Explore behavior was not changed.

## Verification evidence

- Production build: `npm run build` passed.
- PHP: SummaryWorkspaceTest, SqliteAnalyticsTest, JobApplicationStatusContractTest and WorkspaceNavigationTest passed: **35 tests, 238 assertions**.
- JavaScript: `tests/UI/summary-report.test.mjs` passed **2 tests**, covering search, sorting, pagination, empty results and input immutability.
- Browser: desktop 1440×1100 and mobile 390×844 inspected; document width matched the viewport, with table/chart scrolling contained inside their regions. One H1 and zero tab roles confirmed. Light and dark views inspected; no browser console errors observed.
- Click-through: weekly/monthly/all-time filters; chart period selection and both series toggles; status selection; source selection and detail search; map city zoom, Java & Bali and Indonesia reset; outside-map disclosure; role/employment detail tables; company search, empty results, ordering and next/previous pagination. Keyboard activation is provided for chart periods and map locations, with focus styles and reduced-motion support.
- Accessible semantic text colors and chart colors were checked and adjusted, including dark-mode source rings and map fills. Touch controls have a 44px minimum on coarse pointers. No information relies only on chart color: counts, names, legends and data tables accompany it.
- Owner isolation, period filtering, archive inclusion, premium gating and empty reports are covered by feature tests. Current stages are explicitly not presented as historical conversion. Activity uses actual application and scheduled interview dates.

Preview screenshots use a separate testing SQLite database with synthetic applications, not the user's real application records. The temporary preview server was used only for local verification. Artifacts: `output/summary-redesign/overview.jpg` and `output/summary-redesign/full-report.jpg`.

## Delivery gate — PASS

**Block 1, blockers:** no new fake links, inert controls, invented growth statistics, marketing claims or unavailable features. Every interactive report control has tested behavior; empty states and server-rendered fallbacks exist. Existing loading/error handling remains server-driven. Both themes and responsive layouts were exercised.

**Block 2, purpose:** rounded segments establish a repeated quantitative chart motif; their heights follow the numeric scale. Geographic diamond markers locate small real boundaries that would otherwise disappear at overview scale. Role avatars and platform marks identify actual records. Existing workspace typography and navigation are preserved. Lavender is the application accent; blue interviews, sage accepted and terracotta rejected are semantic series colors. Additional muted hues distinguish categorical source data. No decorative background pattern, blur, glow or looping animation was added.

**Block 3, liveliness:** all answers yes. Dials and Design Read precede implementation. The activity plot is the leading focal point; the map and rankings form subsequent focal points. Wide activity and geography sections alternate with compact status and category analyses. Consistent gutters and section headings organize whitespace. Segmented marks repeat across charts without turning every panel into the same component.

**Block 4, craft:** all failure questions no within the redesigned Summary scope. Sections derive from existing application analytics; none exists solely as filler. Search, sorting, disclosures and filters work with empty and populated data. The page retains TraKerja's profession avatars, platform identities, local geography and application-stage language. No required work was deferred.

Known practical limits: map boundaries are simplified; Jakarta's mainland cities are combined and Bali is represented by Denpasar, as the visible disclaimer explains. Remote and unrecognized locations remain counted outside the map. Waffle percentages are rounded to 100 dots while numeric percentages preserve the actual values. All-time headline totals can exceed the activity chart's explicitly labeled twelve-month window.

## Activity chart refinement

The activity plot now reveals a bounded date/count tooltip and vertical guide on pointer entry or keyboard focus. Selection uses the brand lavender; a thin custom focus stroke replaces the browser's heavy SVG outline. Escape dismisses the tooltip. Series legend controls have restrained hover feedback. Application segments divide the available bar height evenly, removing thin remainder fragments while preserving the exact numeric top. No custom cursor or persistent decorative animation was introduced; the pointer indicates an actionable period.

Verification: production build passed; Summary feature suite passed **9 tests / 80 assertions**. Browser keyboard selection showed January 2026 with 4 applications and 2 interviews; Escape hid the tooltip. Saved example-data screenshot: `output/summary-redesign/activity-interaction.jpg`. Delivery gate remains PASS: the additions serve quantitative inspection, retain keyboard focus and reduced-motion support, and use existing theme variables.

## Outcome panel refinement

The headline now pairs its percentage with the actual status and numerator/denominator, replacing the ambiguous “Accepted share” label. Pointer entry on a dot or legend row highlights that status; dot inspection adds a small tooltip and a restrained halo. Clicking a status locks the selection; repeating the action or pressing Escape clears it. Keyboard users operate the three legend buttons instead of navigating 100 decorative dots. Hover and focus are temporary and do not change application data. The existing all-records accessibility label and exact numeric legend remain available.

Verification: **9 Summary tests / 80 assertions** and production build passed. Browser selection of Accepted highlighted only its dots; pointer inspection of In progress displayed 35 applications / 71.4% in both the tooltip and headline; Escape restored the default accepted summary. Desktop and 390px mobile layouts were inspected. Mobile caption aligns left and the tooltip is bounded to the chart width. Example screenshot: `output/summary-redesign/outcomes-interaction.jpg`. Delivery gate PASS: semantic colors and theme tokens retained; hover serves identification, no constant animation, reduced-motion rules cover transitions; counts still derive from the original owner-scoped report.

## Current-stage refinement

Replaced ten narrow vertical tiles with an aligned 5×2 comparison grid, reflowing to two columns on mobile. A prominent lavender readout starts with the largest current stage and follows pointer/focus inspection. Each stage has a relevant process icon, exact count/share and a comparison meter normalized to the largest stage count. The proportional ribbon uses raw counts, including an unrecognized-stage remainder when present. This is a distribution of current records, never a funnel or a claim of completed milestones.

Clicking a stage preserves its selection; Escape returns to the largest stage. A restrained radial hover wash follows the pointer inside the hovered button, with a 2px lift; it provides local selection feedback. All ten controls are native buttons with pressed state and visible keyboard focus, including zero-count stages. Reduced-motion rules suppress the transitions. Repeated card geometry supports direct stage comparison; the larger readout supplies the visual hierarchy.

Verification: production build, diff whitespace check and **9 Summary tests / 80 assertions** passed. Browser selected Assessment Test (5 / 14.3%), inspected Applied (0 / 0%), and reset with Escape. Desktop, 390px mobile and dark mode were visually checked. Saved example-data screenshot: `output/summary-redesign/stages-interaction.jpg`. Delivery gate PASS: real counts, purposeful process icons and cursor feedback, operable controls, no historical conversion claims, readable theme tokens and responsive alignment.

## Source panel refinement

Balanced the donut, platform comparison rows and a contained outcome readout. The readout identifies the selected source by name and separates current interview-stage counts from accepted counts. Its caution about volume versus source quality remains visible below the comparison. Platform rows retain real brand marks, exact counts and percentages, with a colored selection edge. A local radial hover wash follows the cursor within each row. Donut segments now support pointer selection and a bounded tooltip; temporary inspection synchronizes the chart center, row highlight and outcome readout. Native platform buttons provide the equivalent keyboard controls, and Escape restores the leading source.

Verification: production build and **9 Summary tests / 80 assertions** passed. Pointer selection of JobStreet displayed 10 applications / 20.4% with 0 current interview stages and 1 accepted; direct LinkedIn arc selection displayed 11 / 22.4%, 6 interview stages and 2 accepted, with a tooltip. Desktop and 390px layouts were visually inspected. A keyboard verification attempt was interrupted by a browser automation timeout; row click/key handlers explicitly synchronize the selected source and readout. Example screenshot: `output/summary-redesign/sources-interaction.jpg`. Delivery gate PASS for implemented scope: real proportions and original aggregate fallback retained, keyboard-equivalent native buttons, readable theme tokens, bounded tooltip, reduced-motion support and purposeful cursor feedback.

## Location panel refinement

Three aligned metrics now precede a contained map canvas and numbered city ranking. Contextual lavender separates the atlas from its surrounding panel; segmented controls distinguish overview and Java/Bali views. City names use the canonical short label, with original aliases retained in each row's title and the full-record table. The hover highlight connects ranking rows, city boundaries, marker halos and the live caption. A bounded tooltip follows pointer inspection. Clicking a city retains the existing geographic zoom; Escape resets the overview.

Diamond markers retain a consistent visible screen size as the viewport and map extent change; larger transparent hit areas ease pointer targeting without expanding the actual boundary. Hover washes are local to ranking rows, and map text selection is suppressed during pointer gestures. The existing mapped/unmapped accounting, raw records, boundary sources and geographic limitations remain visible. Comparison bars remain normalized to the largest city count; percentages still use all applications.

Verification: production build and **9 Summary tests / 80 assertions** passed. Desktop 1440×1100 and mobile 390×844 layouts were inspected. Jakarta selection zoomed its real boundary and reported 7 applications / 14.3%; Java/Bali and overview controls worked. Pointer inspection of Bandung showed its tooltip, marker halo, ranking highlight and caption with 6 applications / 12.2%. Screenshot: `output/summary-redesign/locations-interaction.jpg` (example data). Delivery gate PASS: truthful mapped coverage, meaningful map/stack/globe icons, purposeful tooltip and hover feedback, native ranking/zoom controls with SVG keyboard equivalents, bounded layout and theme variables; no perpetual animation or geographic conversion claims.

## Roles, employment and companies refinement

Role rankings are now native selectable buttons. Pointer/focus inspection highlights the role and updates a compact current-interview-stage/accepted readout; selection persists after leaving. Existing profession avatars have a small hover scale, and a local lavender wash follows the pointer within each row. Comparison bar lengths remain relative to the largest role count, while displayed percentages use all applications.

Employment segments and numerical cards share selection state. A clear name/percentage readout identifies the inspected category. Segment widths retain actual count proportions; inset emphasis marks inspection without dimming category colors. All employment categories are exposed as native buttons instead of limiting the numerical cards to six. Both panels align their detail disclosures at the bottom and stack on mobile.

Tables retain the full datasets, sorting, pagination and no-JavaScript fallback. Name, application and accepted column headers now also trigger sorting and expose `aria-sort`. Numeric columns align right; muted zero counts, a lavender header band and restrained row cursor wash aid comparison. Search has a working clear control. Company initials exclude PT/CV prefixes, so real names such as PT Bumi Solusi and PT Karya Digital display BS and KD instead of identical PT marks. Initials do not claim to be company logos.

Verification: production build, **9 Summary feature tests / 80 assertions** and **3 JavaScript tests** passed. The added test checks distinct legal-prefix initials and Unicode handling. Browser selected Product Designer (1 current interview stage / 0 accepted), selected Contract (12 / 24.5%), sorted companies via the Accepted header (dropdown synchronized), and exercised unmatched search plus Clear search. Desktop 1440×1100 and mobile 390×844 inspected. Screenshots: `output/summary-redesign/roles-employment-interaction.jpg` and `output/summary-redesign/companies-interaction.jpg`, both using example data. Delivery gate PASS: purposeful hover feedback, native keyboard-operable controls, truthful statistics and preserved fallback records, theme variables and reduced-motion coverage; no invented company imagery or inert row actions.

## Period control alignment

Replaced the dropdown with three native GET submit buttons using the Tracker List/Board switcher appearance. The group sits below the heading; its accessible label remains Report period. Pressed styling identifies the selected period, and the duplicate all-time caption is removed while finite date ranges remain visible. Native forms preserve filtering without JavaScript. Browser clicking Last 30 days navigated to `?timeFilter=monthly`, selected the correct button and displayed 6 example applications. Build and 9 feature tests passed. Screenshot: `output/summary-redesign/period-switcher.jpg`. Delivery gate PASS: established workspace control, real filtering, keyboard-native buttons, responsive and dark styles aligned with Tracker.

User refinement: the period group now sits on the right of the header, following the supplied white outlined List/Board reference with a light-gray active segment and rounded rectangular corners. Mobile retains right alignment on its own row. Native submit behavior is unchanged; build and 9 feature tests passed. The preceding period screenshot records the earlier below-title treatment.

User refinement: removed the top scope line, four headline metric cards and their overlap note. The header/period control now leads directly into Activity & progress. Detailed analyses retain their original data and explanatory notes. The Summary feature suite still passes (9 tests / 80 assertions).

## Three-column categories and company directory

Roles, employment and companies now share one equal-width desktop row with aligned panel headings and bottom disclosures. They stack below 950px. Company presentation is rebuilt as a ranked directory of the five highest application totals: meaningful initials, full company names, exact count/share and an accessible expandable outcome panel. Selecting a company closes the previous detail; Escape collapses the selection. Pointer tracking stays local to the row. The default presentation no longer shows a large table. All companies, search, sorting, pagination, original data notes and no-JavaScript records remain accessible through the full-details disclosure.

Verification: production build and **9 Summary feature tests / 80 assertions** passed. Desktop 1440×1100 inspected with all three panels on one row; 390×844 inspected with a readable company list. Browser expanded Company B, confirmed Company A collapsed, opened All companies and observed its search/sort/paging controls, then closed the full disclosure. Screenshot: `output/summary-redesign/three-column-categories.jpg`, example data. Delivery gate PASS: user-requested composition, content-driven ranking, no fake logos or added data claims, native expanded/controls semantics, responsive stack, existing theme/reduced-motion support and full-record access.

User refinement: company previews are now plain list rows with initials, name and application count. Removed per-company expansion, percentage sublabels, carets, selected styling and interview/accepted detail panels. The three-column desktop arrangement and full-record disclosure remain. Build and 9 Summary tests passed; preceding screenshots show the superseded expandable treatment.

## Category panel alignment

Reduced the common heading space from 78px to 60px. Role and company rows share a 64px minimum height, 40px identity image, matching text/count alignment and consistent padding. The employment preview uses the same 320px content baseline as five list rows; its explanatory note aligns with the role readout. Bottom disclosures still align through the shared flex layout. Removed the employment heading's briefcase icon as requested. Minimum heights allow longer names to wrap without clipping, and the existing single-column breakpoint remains.

Delivery gate PASS: existing TraKerja direction and theme tokens retained; no new imagery, statistics, decorative effects or controls. Production build and 9 Summary feature tests / 80 assertions passed. Browser inspection at 1280px confirms aligned headings, role/company rows and bottom disclosure rules; clicking Contract updates both pressed controls and the 24.5% readout. Screenshot `output/summary-redesign/aligned-categories.jpg` uses synthetic preview data. Changes are limited to layout and removing the requested icon; existing keyboard, empty states and full-record controls remain.

## Compact stage overview

Reduced stage tiles from 157px to 104px minimum height, with smaller inline icons and no redundant ordinal numbers. The selected-stage readout is a compact horizontal row. After reviewing Dashboard colors, the user's final direction restores a white container with neutral gray selection, no colored background or gradient. Five columns on desktop and two on mobile preserve readable names; hover, focus, selection, Escape reset and truthful count/share calculations remain.

Delivery gate PASS: final palette follows the user's explicit white preference; no new decorative assets, claims or controls. Build and 9 Summary tests / 80 assertions passed. Browser selection of Assessment Test updates the readout to 5 applications / 14.3% with sample data. Existing reduced-motion and keyboard styles remain. Screenshot `output/summary-redesign/compact-white-stages.jpg` records the final treatment, superseding intermediate color experiments.
