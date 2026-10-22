# Admin analytics review

Direction: the existing compact admin dashboard/member workspace, informed by the member Summary page's interactive activity chart, outcome breakdown and inspectable records. Plus Jakarta Sans, neutral panels, blue data/selection cues, restrained warm line for member registrations. Energy 1, rhythm 2, motion 1. No visual assets created.

## Delivery gate

- Hard gate PASS: statistics come from stored records. Member activity uses event dates and distinct member ids; profile completeness is no longer presented as activity. Admin-owned records are excluded. Date windows exclude future records and include the first day's midnight. Blank reply averages and peak hours display honest empty states. Public component actions and render enforce admin access; drilldown types/columns are allowlisted and server-derived filters are locked.
- Purpose gate PASS: blue bars show application creation, the warm line shows new member registration, and the slider provides precise inspection on touch/keyboard. Status colors identify disjoint current outcomes with exact counts alongside the graphic. Rankings open their own filtered records. Three tabs organize growth, usage and community rather than showing every chart simultaneously. Shadows are confined to dialogs/loading notices.
- Liveliness PASS: actual member avatars, application stages, sources, target companies, product records, account status and signup cohorts make the report specific to TraKerja. The same admin typography, compact metrics and sidebar remain consistent across the workspace.
- Craftsmanship PASS: browser checks at 1440, 768, 390 and 320 px; Indonesian/light and dark, plus English/light. Document width equals viewport width at narrow/tablet sizes. Modal records reflow, charts fit their panels, and short period labels prevent narrow-screen control crowding. Escape closes the dialog and restores its trigger focus. No console errors/warnings in the reviewed analytics page.

## Interaction evidence

- Period buttons update the URL and recompute totals/timeline; browser 7-day check returned exactly 7 chart days, 14 QA applications and five successful export events.
- Refresh reads fresh data and rebuilds the chart using a revision key, including repeated refreshes within the same second.
- Tabs switch panels; arrow keys wrap and focus the selected tab. Tests reject unknown tabs.
- Timeline hover/click and native range input inspect exact day values. Previous/next controls clamp at valid dates; series toggles hide/show the matching chart layer. Inspect day opens application records for that date.
- Summary metrics open members, activity-based members, applications and successful exports. Outcome/stage/source/company/role controls open matching application buckets.
- Modal search was exercised in the browser: QA Role 23 returned exactly one matching record. Pagination moved from the first ten records to the second ten without changing the report period. Pagination copy is localized explicitly. No browser URL pagination is used for the dialog.
- Product cards open CV analyses, photo records, cover letters and saved CVs. Browser photo modal showed the actual QA filename, completed state and member link. Automated tests verify all four types support title search without rendering prompt, analysis or letter payloads.
- Community links use existing member/feedback routes. Membership snapshot and lifetime XP are explicitly independent of the period filter. Return-activity cohorts distinguish observation windows from completed cohorts. Support time measures the average time to the latest saved reply, using fractional hours.

## Validation and limits

23 Laravel tests passed, plus five existing locale tests completed with warnings caused by their /dev/null/.env fixture path; no failed assertions (34,128 assertions in the combined run). Two JavaScript behavior tests passed. Production Vite build and git diff whitespace checks passed. Existing unrelated PHPUnit doc-comment deprecation notices remain.

Analytics now loads fresh aggregates on entry, period change and explicit refresh; tab switching and modal searching do not recalculate the entire report. All-time summaries are accompanied by an explicitly labeled last-30-days daily chart. Saved product records are not presented as successful generations or downloads. Goal outcomes refer to the current saved state of goals created in the selected period.

Browser data came from the isolated QA SQLite database on localhost:8001. No main database records or user-facing Summary code were changed.
