# Profile and avatar redesign

Profile shares the dashboard background, floating navigation, typography, spacing and restrained glass identity card. Settings use clear tabs: About you, Photo & avatar, Security, Notifications and Activity. Tab navigation supports arrow keys; validation returns to the relevant tab. Decorative icons at the right of panel headings are removed at the user’s request.

## Identity

Twelve original SVG portraits live in public/images/avatars. AvatarCatalog is an allowlist and supplies a deterministic local fallback for accounts with no usable photo. users.avatar_id stores the choice. Selecting an avatar clears the actor’s photo reference; uploading a photo takes precedence without discarding the chosen avatar. Removing a photo restores that avatar. Topbar and dropdown use the same avatar_url accessor.

Avatar selection accepts catalog IDs only and operates on the authenticated user. Photo upload accepts JPEG, PNG and GIF up to 2 MB. Files are saved before replacing the database reference; failed persistence cleans up the new file. Old local photos are deleted only after successful persistence. Existing legacy photo routes remain available; the redesigned Profile uses the new profile/photo endpoints.

## Forms

Keep account/name/email and auto-archive, personal contact/headline/bio/links, password change, email verification, premium-gated notifications, recent activity and password-confirmed deletion. Inputs have labels, errors and focus states. Photo and avatar submission use server redirects with visible status feedback. Password fields have individual show/hide controls. Deletion uses the existing accessible modal, placed above the topbar.

## Local setup and verification

Applied only the avatar_id migration to the local SQLite database and created public/storage with Laravel storage:link. The 150 seeded applications remain present. Deployment elsewhere requires the new migration and a public storage link.

Feature tests cover stable fallback, catalog validation, guest rejection, owner isolation, avatar/photo precedence, file cleanup and valid SVG assets, alongside existing Profile/navigation coverage. Full suite: 367 tests, 1772 assertions passed; nine existing PHPUnit metadata deprecations. Vite build passed. Native browser access showed the new avatar in the topbar, but navigation to Profile was interrupted by the browser’s user-change guard; no full browser interaction verification is claimed.
