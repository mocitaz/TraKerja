# Feedback and Survey redesign

Direction: follow the supplied compact dashboard/member concept and existing TraKerja admin shell. Neutral panels, blue selection cues, Plus Jakarta Sans, existing profile avatars. Energy 1, rhythm 2, motion 1. No new visual assets.

## Delivery review

- Hard gate PASS: real ticket counts and filtered survey ratings; no fabricated trends. New search supports member/guest identity. Existing admin policies protect reads and writes. Blade escapes messages/comments. Empty states distinguish no records from no filter matches. Delete and survey availability changes require a separate review step.
- Purpose gate PASS: status colors identify actual ticket states; selected status cards link to a real filter. Topic bars compare actual ratings on a stated 1–5 scale. Avatars identify respondents. Message surfaces distinguish original text from the latest reply. Borders and spacing group operations without decorative effects.
- Liveliness PASS: TraKerja identities, support categories, real response questions and member navigation carry the product context. Compact status counts serve the ticket review task; survey responses precede topic analysis to make individual feedback accessible.
- Craftsmanship PASS: browser review at 1440, 768, 390 and 320 px, English/light and Indonesian/dark. Record cards replace tables through 900 px. Document width equals viewport at 320, 390 and 768 px. Dialogs scroll within the viewport. Escape restores focus to View response and the invitation trigger. No console errors/warnings in the reviewed survey screen.

## Interaction evidence

- Feedback status cards navigate to filtered lists; category/status/search submit real GET queries. Pagination retains filters.
- Review opens a focused dialog with the sender, original message, current admin reply, response form, independent status update and member link.
- Save reply was submitted through the browser using a QA ticket in an isolated SQLite database; the resulting success notice and saved reply were verified. The form disables repeat submission while navigating.
- Delete ticket opens an explicit confirmation with ticket context; Keep ticket returns to the review. Actual deletion is covered by test database assertions.
- Invalid reply drafts keep their ticket id and submitted message, so the correct modal reopens. Backend validation leaves the record unchanged.
- Survey search, overall rating and comment filters update both response lists and summary scores. Tests verify exact response ids and averages.
- View response opens all 12 questions, grouped using the same question source as the member survey; full written comments are escaped and preserved.
- Enable/pause invitations opens a confirmation. Escape cancels and restores focus. Test database submissions verify toggles preserve existing responses.
- View member links use the established admin detail route. Close buttons and Escape use the shared dialog focus trap.

## Validation

25 Laravel tests passed (33,660 assertions): member voice, admin operations/navigation, member survey, invitations and language catalogue. Eight JS tests passed including feedback dialog state, settings navigation and billing regressions. Production Vite build and git diff whitespace check passed. Existing unrelated PHPUnit doc-comment deprecation warnings remain.

Browser fixtures used localhost:8001 and /tmp/trakerja-billing-ui.sqlite with QA-prefixed content. No real feedback was replied to/deleted, and the primary survey setting was not changed.
