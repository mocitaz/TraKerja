# Tracker UI audit — 10 October 2026

Scope: signed-in `/tracker`, List and Board. Based on the supplied screenshot, Blade structure, and CSS cascade. Exact browser computed styles and mobile device measurements were not collected.

| Finding | Evidence | Change |
| --- | --- | --- |
| Tracker typography is overridden by generic page CSS | `tracker.css` is imported before `workspace-pages.css`; `.tracker-page h1` and `.workspace-page h1` have equal specificity, as do several paragraph selectors. The later generic rules win. | A scoped `tracker-density.css` import after shared page styles explicitly controls tracker text and spacing. |
| Status and recruitment stage are hard to distinguish | The List Progress cell combines status, stage and follow-up action. | Separate Status and Stage columns; the existing eligible follow-up action stays with Stage. |
| Dates mix two events | Applied contains both application date and interview schedule. | Separate Applied and Interview columns with semantic dates and WIB time. |
| The table lacks column boundaries | Only horizontal row separators exist; the Opportunity cell consumes 36% width. | Seven explicit column widths, subtle vertical separators, alternating row backgrounds and hover/focus highlighting. |
| Header context disappears in long lists | The table header has no sticky behavior. | Sticky column headings inside a bounded scroll region, accessible region label and visible keyboard focus. |
| Padding dominates the page | Large header, result block, 20px table cell padding and spaced board cards. | Compact header/results, 12px cells and 14px board cards; coarse-pointer controls keep 44px targets. |
| Kanban repeats status and uses an initial badge | Each column names its status; the card repeats it and displays a decorative company initial. | Board cards lead with the actual recruitment stage, then role/company; remove the initial badge and keep a subtle drag grip. |
| Sort direction is unclear | Sort buttons use a generic bidirectional icon. | Actual directional icon and `aria-sort` tied to Livewire sort state. |

Existing functionality retained: owner scoping, filtering, pagination, pin/edit/delete, follow-up eligibility/drafting, auto-archive preference, drag/drop and select-based status changes. No application records are changed by these layout edits.

Validation: integration coverage verifies distinct cell data and sort changes; existing tracker tests cover owner boundaries, moves, follow-up and archival. Build validation checks compiled CSS.
