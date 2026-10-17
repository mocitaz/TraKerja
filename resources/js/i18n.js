let messages = {}, locale = 'en';
export function configureLocale(config = {}) {
    locale = ['en', 'id'].includes(config.locale) ? config.locale : 'en';
    messages = config.messages && typeof config.messages === 'object' ? config.messages : {};
}
export function t(key, replacements = {}) {
    let value = Object.hasOwn(messages, key) && typeof messages[key] === 'string' ? messages[key] : key;
    value = value.replace(/:([A-Za-z0-9_]+)/g, (match, name) => Object.hasOwn(replacements, name) ? String(replacements[name]) : match);
    return value;
}
export function currentLocale() { return locale === 'id' ? 'id-ID' : 'en-US'; }
export function installLocale(win, doc) {
    const load = () => {
        try { configureLocale(JSON.parse(doc.getElementById('app-translations')?.textContent || '{}')); }
        catch { configureLocale(); }
        win.t = t;
    };
    load();
    if (win.__localeInstalled) return;
    win.__localeInstalled = true;
    let dirty = false;
    doc.addEventListener('input', event => { if (event.target.closest('form:not([data-language-form])')) dirty = true; });
    doc.addEventListener('submit', event => { if (!event.target.matches('[data-language-form]')) dirty = false; });
    doc.addEventListener('livewire:navigated', () => { load(); dirty = false; });
    doc.addEventListener('change', event => {
        const form = event.target.closest('[data-language-form]');
        if (form && event.target.name === 'locale') form.requestSubmit();
    });
    doc.addEventListener('submit', async event => {
        const form = event.target;
        if (!form.matches('[data-language-form]')) return;
        event.preventDefault();
        if (form.dataset.busy === 'true') return;
        if (dirty && !win.confirm(t('Changing language will reload this page. Unsaved changes may be lost. Continue?'))) {
            if (form.elements.locale.type !== 'hidden') form.elements.locale.value = locale; return;
        }
        const data = new FormData(form);
        form.dataset.busy = 'true';
        const select = form.elements.locale; select.disabled = true;
        const options = form.querySelector('fieldset'); if (options) options.disabled = true;
        const error = form.querySelector('[data-language-error]');
        error.hidden = true;
        const controller = new AbortController(); const timeout = setTimeout(() => controller.abort(), 12000);
        try {
            const response = await fetch(form.action, { method: 'POST', body: data, credentials: 'same-origin', signal: controller.signal, headers: { Accept: 'application/json', 'X-CSRF-TOKEN': form.elements._token.value } });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error();
            dirty = false; win.location.reload();
        } catch {
            error.textContent = t('Language could not be changed. Please try again.'); error.hidden = false;
            if (select.type !== 'hidden') select.value = locale;
        } finally { clearTimeout(timeout); select.disabled = false; if (options) options.disabled = false; form.dataset.busy = 'false'; }
    });
}
if (typeof window !== 'undefined' && typeof document !== 'undefined' && typeof document.addEventListener === 'function') installLocale(window, document);
