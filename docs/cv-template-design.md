# CV templates and PDF rendering

The four public template names remain Minimal, Professional, Creative, and Elegant.

- Minimal: classic black-and-white serif resume, centered name/contact block without a repeated job-title tagline, compact single-column entries with company/location/date in reading order, standard section labels, and comma-separated skills. No photo, tables, icons, or skill ratings.
- Professional: formal black-and-white corporate resume with sans-serif type, centered uppercase name/contact details, employer-first entry headings, italic roles, right-aligned dates, and thin section rules. Main content remains one column; dates use inline floats, not tables or separate content columns. No color bands or repeated job-title tagline. It uses a traditional employer/role hierarchy; this is not an official Harvard template or ATS certification. Minimal retains serif type, role-first entries, and inline dates. No photo or tables.
- Creative: terracotta and warm-paper studio editorial, an asymmetric portrait header, large left-aligned name, numbered headings, skills near the profile, and projects before experience. The existing uploaded profile photo is embedded as a 5:6 portrait; missing/unsupported photos use real-name initials. Main content is one column; the bounded header uses a table for stable PDF positioning.
- Elegant: monochrome editorial letterhead with a large left-aligned regular serif name, restrained sans-serif contacts/body, title-case serif section headings, and whitespace-led hierarchy. It uses one main reading column, no photo, no tables, no accent colors, and no background panels.

`cv-templates/partials/document.blade.php` owns the document structure. Each template's style partial owns its visual treatment. This keeps the same profile fields and content across all four templates. Dates use the actual model fields, including `UserAchievement::date`. Education descriptions, project/repository links, and certificate links are included when present. Unknown fields remain empty and empty sections are omitted.

`CvDocument` escapes text, accepts only HTTP(S) links, formats descriptions as paragraphs/lists, and inserts invisible wrap opportunities into long tokens. Descriptions are not placed in table rows or unbreakable entry containers. Headings stay with the following content, while long descriptions can continue across pages. Output remains selectable text, not an image.

## Preview and download

`CvBuilderController::renderDocument` is the single rendering path for PDF preview and download, using A4, embedded DejaVu fonts, and identical validated margin/font settings. No remote font/image requests or PHP execution are enabled. The canvas endpoint now returns an inline PDF, with private/no-store headers. The production CSP allows same-origin objects only on inline CV PDF responses so Chromium can initialize its native viewer; HTML keeps `object-src none` and same-origin frame protection is preserved. See the [Chromium PDF viewer CSP discussion](https://issuetracker.google.com/issues/40213536). Browser PDF-viewer controls vary; the review page includes an open-in-new-tab fallback.

Preview does not consume export allowance. The normal download endpoint retains template authorization and consumes allowance only after successful rendering. An inline PDF is saveable through a browser's PDF viewer, so this allowance measures use of the application's export action, not protection against copying preview content. HTML previews were also printable; do not use preview availability as a DRM boundary.

No guarantee is made that every external ATS parses every PDF identically. Minimal and Professional have a simple reading order, standard headings, no tables, and no decorative imagery. Poppler extraction verifies actual selectable text and its order in synthetic fixture PDFs; external ATS verification remains separate.

## Verification without local credentials or migrations

```sh
APP_ENV=testing CACHE_STORE=array DB_CONNECTION=sqlite DB_DATABASE=:memory: LOG_CHANNEL=null CV_REVIEW_DIR=/tmp/trakerja-cv-review php vendor/bin/phpunit --do-not-cache-result tests/Feature/CvTemplateRenderingTest.php tests/Feature/CvExportQuotaTest.php tests/Feature/ResumeImportSafetyTest.php
python3 tests/Support/check-cv-pdf-layout.py /tmp/trakerja-cv-review
```

The tests deliberately skip `.env`, use synthetic profiles and an in-memory database, and cover all templates with empty, sparse, complete, very long, compact, and spacious content, plus long names/contact fields. Creative also has local portrait fixtures for square and landscape uploads. The long fixture contains an entry exceeding a page. The Poppler check verifies text within page margins, expected sections, reading order, no blank pages, and the long-entry end marker. PNG rendering is used separately for visual inspection. These fixtures do not represent real users or qualifications.

The Minimal revision follows the emphasis on consistent hierarchy and readable PDF output in the [Harvard resume guide](https://careerservices.fas.harvard.edu/resources/create-a-strong-resume/) and simple single-column formatting in the [CareerOneStop formatting guide](https://cloudfront.careeronestop.org/JobSearch/Resumes/ResumeGuide/formatting.aspx). These references are design guidance, not an ATS certification.

Creative portraits are read from the public storage disk used by the profile upload flow. Only local JPEG, PNG, and GIF files within that disk are accepted, up to 2 MB and 12 million pixels. The render normalizes them to 500×600 JPEG using a centered crop, preserving aspect ratio. No remote image requests are made and the original profile upload is not changed. A GD-less environment embeds the original eligible image instead.
