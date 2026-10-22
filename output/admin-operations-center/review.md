# Operations Center

Direction: the established compact TraKerja admin workspace. Energy 1, rhythm 2, motion 1. Neutral panels, existing Plus Jakarta Sans/Phosphor assets, small statistics and blue focus/selection cues. One page uses task navigation for incidents, account controls and service checks; a right-hand drawer keeps context on desktop and fills the viewport on phones.

## Implemented

- `/admin/operations-center`, linked in the existing Operations sidebar group; English and Indonesian labels.
- Incidents: search, status/urgency filters, critical-first unresolved ordering, pagination, creation, admin assignment, affected-member lookup, service association, status changes, resolution/reopening and required handling notes. Events record actor ID, time and before/after values.
- Account controls: member directory/search, restricted-only filter, entire-account or API-only restrictions, optional expiry, linked incident, reason and explicit confirmation. Admin accounts cannot be targeted. Restriction and restoration are transactional, owner-scoped and logged. Releasing one restriction leaves all others in force. The last 30 restriction records and latest 50 handling actions are displayed.
- Restrictions are enforced on web requests and authenticated extension/API requests; extension login checks restrictions again under the same user row lock used by admin actions. Existing API tokens are removed when a restriction is applied. Explicit all-token revocation is also available, with a reason and confirmation. Browser sessions are not revoked by the API-token action. Full-account restrictions deny application requests while retaining sign-out. Expired restrictions stop blocking without a scheduler.
- Service checks: explicit, per-service checks with bounded connections, stored duration, actor and time; 30-second cooldown; latest 20 check records and stale indicators after 15 minutes. No automatic network requests on page load. No uptime claims.
- Gemini retrieves configured model metadata without generating content. SMTP opens/authenticates/closes a transport without sending a message; non-SMTP transports are not claimed to have passed. Pakasir performs a read-only status request for an existing V2 transaction in the current environment, without reconciliation or payment changes. Without such a transaction the result is Not checked. Storage writes/reads/deletes only a uniquely named probe on the default disk.
- Failures store a bounded generic description and HTTP status, not remote response bodies, exception details or credentials. Missing configuration and unsupported checks have distinct statuses.

## Corrections found during verification

The drawer initially reused teleported forms across modes. A keyed wrapper now owns the template lifecycle; service history to incident creation creates one correctly prefilled drawer, and closing it leaves zero stale drawers. Browser incident updates were then verified to persist with their note and resolution time.

Event/check models now let Eloquent write created_at in the application timezone instead of relying on SQLite's UTC CURRENT_TIMESTAMP default. This prevents seven-hour-old timestamps and false stale indicators for new checks. Tests assert freshly saved records match the current minute. Early QA-only fixture events from before this fix retain their original timestamps; no production events were created during QA.

## Verification

90 focused Laravel tests passed, 35,329 assertions: OperationsCenterTest (19 tests), AdminWorkspaceTest, AdminOperationsWorkspaceTest, ProfileSettingsWorkspaceTest, LanguageCatalogueTest, ExtensionSecurityTest, AuthenticationTest and AdminSupportAuthorizationTest. Coverage includes unauthorized and stale component access, locked targets, ownership, independent restoration, expiry validation, web/API enforcement, token issuance/revocation, assignment validation, audit history, read-only provider checks, sensitive-error redaction and storage probe cleanup. External HTTP and SMTP checks are mocked; storage tests use fake storage.

Production Vite build, scoped Pint formatting and git diff whitespace checks passed. Existing PHPUnit doc-comment deprecation notices remain outside this change.

Browser QA used the separate localhost:8001 database and cookie. A QA incident was created and resolved, a QA member's API access restricted and restored with notes, related-incident navigation and service-history prefill exercised, and SMTP's log-only configuration correctly reported as unconfigured without sending mail. Main user accounts, payments, credentials, files and provider state were not changed. The new schema migration was applied to the application database so the feature is usable.

## Delivery gate

- Hard gate PASS: 1440 px light desktop, 768 px dark Indonesian tablet and 320 px phone layouts inspected without document overflow. Compact phone statistics measure 82 px; phone drawer fits the viewport and form fields use 16 px text. Existing neutral contrast/focus styling and real avatars are retained. Empty/loading/error states and actual source-backed counts are present. No fabricated service success, assets or claims.
- Purpose gate PASS: four comparable summary buttons lead to their matching filters/tabs. Tables carry operational detail; a single drawer groups the selected record and its required actions. Semantic state colors identify urgency/health only. Existing icons describe incidents, account access and service connection state; no decorative gradients, new illustrations or unnecessary animation.
- Liveliness PASS: energy 1, rhythm 2, motion 1. The selected task and record are the focus. Summary cards, queue rows, service results and the history timeline vary density according to their content, with deliberate section spacing and the established blue accent.
- Craftsmanship PASS: native buttons/forms preserve keyboard operation; Escape closes the drawer and removes its teleported DOM. Creation, update, restriction/restoration, service history, cross-panel navigation and prefilled related incident were exercised in the isolated browser; the remaining provider operations are covered by mocked integration tests. No console errors on the inspected final flows. Authorization and enforcement extend to the backend, rather than relying on hidden UI controls.
