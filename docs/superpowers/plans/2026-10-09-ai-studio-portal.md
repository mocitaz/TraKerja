# AI Studio conversation portal

User direction: one portal with a chat-style interface, dashboard colors, compact readable layout, and an easy flow for analyzer, cover letter, photo, and outreach. Existing providers and entitlement rules remain authoritative. A general-purpose conversational model is not configured; the composer starts the existing four tasks and must label template/fallback outputs honestly.

1. Build one owner-scoped portal presenter using paginated merged history (30 rows per page, stable ordering) and selected saved results. Existing result URLs render the same portal; unauthorized access remains blocked. Add saved outreach drafts so refresh does not discard template output.
2. Replace the four-tab form wall with a compact history rail, task suggestions, conversational result area, and one task-aware composer. Native analyzer/photo forms preserve redirects and queue lifecycle; asynchronous letter/outreach requests display actual persisted results or a clearly labeled unsaved fallback.
3. Render structured analyzer data safely, plain-text cover letters with copy/TXT/print, and photo states including provider failures, polling recovery, and idempotent retry. Show real balances and costs before submission.
4. Verify owner isolation, history bounds, persisted result routes, actual payload rendering, safe links, validation, provider responses, clipboard/request failures, navigation cleanup, and mobile reflow. Run PHP regression suite, JavaScript tests, and production build.
