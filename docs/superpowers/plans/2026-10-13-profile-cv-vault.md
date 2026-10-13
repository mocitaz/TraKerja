# User Resume Vault in Profile Implementation Plan

> **Goal:** Add a dedicated "My Resumes" section to the user profile page (`/profile`) allowing users to upload, manage, archive, preview, download, and set primary versions of multiple CVs (PDF/DOC/DOCX).

---

## 1. Architecture & Deliverables

### File Deliverables:
- **Database**:
  - `database/migrations/2026_10_13_140000_create_user_resumes_table.php`
- **Model**:
  - `app/Models/UserResume.php`
  - Relationship added to `app/Models/User.php`: `public function resumes(): HasMany`
- **Controller**:
  - `app/Http/Controllers/UserResumeController.php` (store, download, preview, update, setPrimary, destroy)
- **Routes**:
  - Registered in `routes/web.php` under `auth` middleware group
- **Views & Styles**:
  - `resources/views/profile/edit.blade.php`: Added `'resumes' => 'My Resumes'` tab, identity facts counter, and `#profile-panel-resumes` panel with upload form and card list.
  - `resources/css/profile.css`: Matching styling for `.profile-resume-card`, `.profile-resume-grid`, `.profile-resume-badge`, etc.
- **Automated Tests**:
  - `tests/Feature/UserResumeTest.php`

---

## 2. Tasks

### Task 1: Migration & Model
- Create `user_resumes` migration with `user_id`, `title`, `file_path`, `file_name`, `file_size`, `file_type`, `is_primary`.
- Create `UserResume` model with relationship, casts, and formatted size accessor.
- Add `resumes()` relationship to `User` model.

### Task 2: Controller & Endpoints
- Create `UserResumeController` with:
  - `store`: validate file (pdf, doc, docx, max 10MB) + title, store in `resumes/{user_id}/`, handle primary flag.
  - `download`: authorize user, download file with original filename.
  - `preview`: authorize user, stream response for inline PDF viewing.
  - `update`: rename title.
  - `setPrimary`: toggle primary status among user's resumes.
  - `destroy`: delete physical file and database record.
- Register routes in `routes/web.php`.

### Task 3: Profile View & CSS Integration
- Update `ProfileController::edit` to pass `$resumes`.
- Update `resources/views/profile/edit.blade.php` to include tab, upload dropzone/form, resume cards, actions (preview, download, rename, primary, delete).
- Harmonize styling in `resources/css/profile.css` matching `.profile-panel`.

### Task 4: Testing & Verification
- Write and run `tests/Feature/UserResumeTest.php`.
- Run `npm run build` and full test suite.
