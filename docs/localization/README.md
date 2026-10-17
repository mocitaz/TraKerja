# English and Bahasa Indonesia

TraKerja supports `en` and `id`. Display language is chosen from the account menu or Profile. Public/auth pages have the same control. A language change reloads the current page; the browser asks for confirmation if another form has been edited. It does not silently save or translate that form.

## Storage and activation

The additive `2026_10_17_160000_add_locale_to_users_table.php` migration adds a nullable two-letter preference. Apply it before enabling account-wide persistence:

```bash
php artisan migrate --path=database/migrations/2026_10_17_160000_add_locale_to_users_table.php --force
```

The switch still works with session/cookie storage before that migration; the page does not fail when the column is absent. Account persistence across devices starts once the column exists. Selection is validated against `AppLocale::OPTIONS` and saved through a CSRF-protected POST, never a query-string side effect. Redirects are limited to local paths. Locale cookies are encrypted, HttpOnly and SameSite=Lax (Secure on HTTPS).

Authenticated account preference takes priority, then session/cookie, then English. Authenticated API routes also apply recipient preference; unauthenticated API requests can negotiate `Accept-Language`. User locale is restored after requests to avoid leaking one user's language to the next worker request.

Run the normal frontend build after deployment. Restart queue workers after deploying language-aware code. Emails addressed to User models use Laravel's `HasLocalePreference`; custom email bodies authored by admins remain unchanged.

## Authoring copy

- `lang/en.json` and `lang/id.json` contain presentation copy. Blade uses `__()`; application JavaScript imports `t` from `i18n.js`.
- Add frontend translation keys to `lang/frontend.json`. Only this subset is sent to the browser; English identity translations are omitted. Do not send the entire UI catalogue with every page.
- Laravel validation, auth, pagination and password reset messages have matching PHP catalogues under `lang/en` and `lang/id`.
- Translate literal presentation text, never raw user data or every DOM text node. Company names, roles, notes, descriptions, stored documents, identifiers, URLs, CSV data, status values and API enum values retain their originals.
- `UiLabel::value` translates only an allowlist of canonical status/stage/employment labels. Values submitted by forms remain unchanged.
- Use complete phrases with named placeholders for variable content. Render them through escaped Blade output or DOM `textContent`. Never interpolate untrusted content into HTML using a translated string.
- Dates use per-instance locale and translated/ISO formatting. Keep ISO dates and numeric form values stable.

## Voice

Professional, warm, direct, and respectful. Indonesian uses **Anda** consistently. Prefer short verbs for actions and clear nouns for navigation. Avoid slang, unnecessary apologies, exaggerated promises, literal English sentence order and decorative emoji. Keep TraKerja, Nara, platform names and legal entity names recognizable.

Preferred glossary: application → lamaran; recruitment stage → tahap rekrutmen; credits → kredit; support → bantuan; cover letter → surat lamaran; resume → CV. A recruitment “User Interview” is “Wawancara user”, not an interview about software users.

CV document language is independent from display language and defaults to English. The preview/export control supports English and Indonesian headings; it does not translate the user's experience descriptions. AI Studio defaults its requested output language to display language and supports explicit writing-language overrides. Nara uses display language unless the user requests another language. Existing generated documents and conversation history are not rewritten.

## Verification

`LanguagePreferenceTest` covers guest/account persistence, fallback during deployment, redirects, ownership of preference writes, unchanged user data, email locale and AI output overrides. `LanguageCatalogueTest` checks matching catalogue keys and placeholder preservation. Frontend tests cover literal-key lookup, nonrecursive interpolation, unsaved-edit confirmation, failure and retry. Broad page/Livewire regression tests and Blade compilation remain required; catalogue checks alone do not prove editorial quality or responsive layout quality.

When adding a feature, add both translations and check English/Indonesian in light/dark themes, narrow mobile layouts, loading/error/empty states, and after a Livewire update. Product-copy review remains necessary for new or revised copy.

## Chrome extension

The popup has its own English/Indonesian selector, persisted in extension local storage. It defaults to English and sends `Accept-Language` with API requests; authenticated account preference takes precedence on the server. Only explicitly marked UI labels are translated. Captured job details and submitted enum values stay unchanged. Build the downloadable ZIP with `scripts/build_extension.py` so `translations.js` is included.
