# Profile settings redesign

Direction: the existing TraKerja dashboard and compact admin/member pages. A short account overview replaces the large identity column; section navigation sits alongside independent panels. Avatar/photo selection and CV management use a shared dialog. Plus Jakarta Sans, neutral surfaces, blue selection cues. Energy 1, rhythm 2, motion 1. Existing avatar assets only.

## Delivery gate

- Hard gate PASS: account identity, membership, credit balances, CV metadata, API devices and recent activity use existing owner-scoped sources. Admin and member navigation retain their appropriate sections. Forms use their established routes, CSRF and validation. No fictional statistics or generated assets. Deletion requires a password; file deletion and API revocation have context-specific confirmations.
- Purpose gate PASS: the compact identity strip identifies the current account; the section navigation separates independent settings. Blue marks the current section and selected avatar. File-type labels and actual names make the CV library scannable. Red is reserved for destructive controls. Dialog elevation separates an active task from the underlying page.
- Liveliness PASS: actual profile images, CV versions, account preferences, device names and existing TraKerja typography carry the product identity. Member-only CV, membership and notification sections remain available without adding career preferences to administrator profiles.
- Craftsmanship PASS: reviewed administrator and member screens at 1440 px, member tablet at 768 px, and narrow screens at 320 px. Light English and dark Indonesian checked. Document width matches viewport at 320/768. Section navigation scrolls within its own container; the initially selected mobile section is brought into view. Modal contents fit and scroll within the viewport. Escape restores focus to the invoking image button. Mobile file actions and close controls use 44 px targets. Notification switches were verified at 34x20 px inside their larger clickable labels, with no native checkbox background image.

## Interaction evidence

- Account, Security, Activity and member-specific section links update the tab query parameter. Direct security links and API-revocation status reopen Security correctly. Arrow/Home/End navigation uses the tab pattern.
- Change image opens the catalog/photo dialog with the existing avatar selected. Saving an avatar/photo keeps existing backend validation and cleanup rules. Temporary photo preview URLs are revoked on replacement/close; automated tests use fake public storage for uploads and removal.
- Wrong-password account deletion was submitted in the isolated browser QA account. Validation reopened the deletion dialog in Security, displayed the error and left the password field blank. No browser account deletion was performed.
- The deletion dialog now renders for admins directly in the profile root; it no longer depends on a modal stack absent from the admin layout.
- CV rename was submitted through the browser using a QA title containing an apostrophe. The saved label and success notice were verified on the resumes tab. Upload, private download/preview authorization, primary selection and deletion are covered by existing tests using fake storage. No browser file was uploaded or deleted.
- Revoke device access opens a named confirmation. A QA-only API device was revoked through the browser, returning to Security. Individual/all-device revocation retains owner-scoped server actions and explicitly distinguishes API tokens from browser sessions.
- Language change and theme switch were exercised through the profile controls. Notifications, membership/credits/payment history and recent activity retain their existing data and routes. Membership period and order-count copy is localized.

## Correctness fix

The account deletion regression test exposed an existing remember-token issue: logging out after deleting the model could rotate the token and save the deleted user again. Clearing the in-memory remember token before logout prevents that write. Tests verify the deleted account stays absent and another account remains intact; private file cleanup behavior remains covered.

## Validation

Final focused run: 38 Laravel tests passed (34,464 assertions), covering profile settings, roles, avatar/photo handling, CV operations, membership, password changes and translation catalogue. Three JavaScript behavior tests passed. Broader account-cleanup/token safety run had no failures, with 20 existing warnings from its /dev/null/.env fixture path. Production Vite build and git diff whitespace checks passed. Existing unrelated PHPUnit doc-comment deprecation notices remain.

Browser work used the separate QA SQLite database on localhost:8001 with a distinct session cookie. Only QA labels, preferences/access and fixture credentials were used; main account records, passwords, devices and files were not modified. The seeded QA PDF metadata has no physical file, so browser download/preview was intentionally not invoked; those paths were exercised by automated storage tests.
