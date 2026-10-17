import { t } from './i18n.js';
export async function autofillJob({ url, read, write, fetcher = fetch, csrf, isCurrent = () => true }) {
    const fields = ['company_name', 'position', 'location', 'career_level', 'platform', 'notes'];
    const before = Object.fromEntries(fields.map(field => [field, read(field) ?? '']));
    const response = await fetcher('/jobs/scrape-url', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ url }), signal: AbortSignal.timeout(60000),
    });
    const data = await response.json().catch(() => ({}));
    if (!isCurrent()) return { stale: true };
    if (!response.ok || !data.success) throw new Error(data.message || t('Could not read this listing. Enter details manually or use the extension.'));
    const details = [data.location ? `Source location: ${data.location}` : '',
        data.work_mode ? `Work mode: ${data.work_mode}` : '',
        data.description ? `Job description (${data.platform || 'URL'}):\n${data.description}` : ''].filter(Boolean);
    const proposed = { company_name: data.company_name, position: data.job_title, location: data.location,
        career_level: data.career_level, platform: data.platform, notes: details.join('\n\n') };
    let filled = 0;
    for (const field of fields) {
        if (!isCurrent()) return { stale: true };
        // Never overwrite pre-existing content or edits made while the request was running.
        if (String(before[field]).trim() || (read(field) ?? '') !== before[field] || !proposed[field]) continue;
        if (await write(field, proposed[field], data) !== false) filled++;
    }
    return { filled, partial: data.partial, missing: data.missing_fields || [], location: data.location || '' };
}

const pending = new WeakSet();
export async function fetchJobDetailsFromUrl(button) {
    const root = button.closest('[data-job-application-form]');
    if (!root || pending.has(root)) return;
    const component = window.Livewire?.find(root.getAttribute('wire:id'));
    const input = root.querySelector('input[type="url"]');
    const url = input?.value.trim();
    const status = (state, message) => window.updateScrapeStatus(state, message, root);
    if (!url || !component) { status('error', 'Enter a job posting URL first.'); return; }
    pending.add(root);
    status('fetching', 'Reading job details…');
    try {
        const result = await autofillJob({
            url, csrf: document.querySelector('meta[name="csrf-token"]').content,
            read: field => component.get(field),
            write: async (field, value) => field === 'location'
                ? await component.call('parseAutofillLocation', value)
                : await component.set(field, value),
            isCurrent: () => root.isConnected && input.value.trim() === url,
        });
        if (result.stale) { status('error', 'URL changed. Run Autofill again.'); return; }
        const labels = { job_title: t("job title"), company_name: 'company', location: 'location', career_level: t("employment type") };
        const missing = result.missing.map(field => labels[field] || field).join(', ');
        status('success', `${result.filled} ${result.filled === 1 ? 'field' : 'fields'} filled. Existing entries kept.`
            + (missing ? ` Check: ${missing}.` : ' Review before saving.'));
    } catch (error) {
        status('error', error.name === 'TimeoutError' ? 'The portal took too long. Try again or enter details manually.' : (error.message === t("Failed to fetch") ? 'Connection interrupted. Please try again.' : error.message));
    } finally { pending.delete(root); }
}
if (typeof window !== 'undefined') window.fetchJobDetailsFromUrl = fetchJobDetailsFromUrl;
