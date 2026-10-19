# Tracker interview date audit

## Confirmed defects

The form's `getListeners()` override exposed only the edit events and hid the
listeners already declared on the component. The modal dispatches
`resetFormForNewJob` when closing, but Livewire rejected that event because its
handler was not registered. Unsaved form state could survive cancellation.

`editJob()` also returned early when reopening the same application. This
prevented the form from fetching its current saved schedule and could display a
stale interview date.

## Changes

Removed the redundant listener override so Livewire uses the component's declared
listeners. Removed the same-application early return so each edit event reloads
the application through the existing owner-scoped query.

Added regression coverage for cancelling an unsaved date, reopening the form,
saving a different date, and checking the persisted database value. Another test
changes the saved date while the form exists and verifies that reopening the
same application loads that date.

## Verification

Before the fix, the first regression raised `EventHandlerDoesNotExist` for
`resetFormForNewJob`; the second returned the original date instead of the
updated saved date. Both pass after the fix.

The following PHP suites pass together or in the two recorded test runs:

- SelectionScheduleTest
- TrackerScheduleSafetyTest
- InterviewWorkspaceTest
- InterviewScheduledEmailTest
- ApplicationSaveFeedbackTest
- TrackerWorkspaceTest
- JobDetailWorkspaceTest

Result: 42 tests, 279 assertions. The application-dialog JavaScript suite also
passes all 6 tests. PHP syntax checks and `git diff --check` pass.

An expanded run found an unrelated failure in
`JobApplicationStatusContractTest::test_explore_saved_job_is_visible_in_process_column`.
It also fails when the original `JobApplicationForm` from HEAD is loaded through
a temporary test bootstrap. The test calls Explore's `trackJob` without setting
its required application location. This audit does not change that feature or
its test.

Tests use an isolated in-memory SQLite database and fake/array mail. No
production database or real outgoing email was used. These results confirm the
two reproduced form lifecycle defects; the original production incident still
needs the affected user's exact steps if it persists after deployment.
