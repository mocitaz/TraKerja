import test from 'node:test';
import assert from 'node:assert/strict';
import { configureLocale, currentLocale, t } from '../../resources/js/i18n.js';

test('translation uses explicit keys, preserves unknown user content, and supports fallback', () => {
    configureLocale({ locale: 'id', messages: { 'Save changes': 'Simpan perubahan' } });
    assert.equal(t('Save changes'), 'Simpan perubahan');
    assert.equal(t('PT Actual Company'), 'PT Actual Company');
    assert.equal(t('__proto__'), '__proto__');
    assert.equal(currentLocale(), 'id-ID');
    configureLocale({ locale: 'invalid', messages: {} });
    assert.equal(currentLocale(), 'en-US');
});

test('placeholders preserve user values without recursively translating or replacing them', () => {
    configureLocale({ locale: 'id', messages: { 'Hello, :name. :count requests.': 'Halo, :name. :count permintaan.' } });
    assert.equal(t('Hello, :name. :count requests.', { name: ':count <script>literal</script>', count: 2 }), 'Halo, :count <script>literal</script>. 2 permintaan.');
    configureLocale();
});

test('language switch asks before discarding edits and keeps the current choice on cancellation', async () => {
    const { installLocale } = await import('../../resources/js/i18n.js');
    const handlers = {};
    const error = { hidden: true };
    const form = { action: '/language', dataset: {}, elements: { locale: { value: 'id' }, _token: { value: 'csrf' } }, matches: () => true, querySelector: () => error };
    const doc = { getElementById: () => ({ textContent: JSON.stringify({ locale: 'en', messages: {} }) }), addEventListener: (name, callback) => (handlers[name] ||= []).push(callback) };
    let asked = 0, reloaded = 0;
    const win = { confirm: () => { asked++; return false; }, location: { reload: () => { reloaded++; } } };
    installLocale(win, doc);
    handlers.input[0]({ target: { closest: () => ({}) } });
    let prevented = false;
    await handlers.submit[1]({ target: form, preventDefault: () => { prevented = true; } });
    assert.equal(asked, 1); assert.equal(prevented, true); assert.equal(reloaded, 0); assert.equal(form.elements.locale.value, 'en');
});

test('failed language switch retains the current language and allows retry', async () => {
    const { installLocale } = await import('../../resources/js/i18n.js');
    const handlers = {}, error = { hidden: true };
    const form = { action: '/language', dataset: {}, elements: { locale: { value: 'id' }, _token: { value: 'csrf' } }, matches: () => true, querySelector: () => error };
    const doc = { getElementById: () => ({ textContent: '{"locale":"en","messages":{}}' }), addEventListener: (name, callback) => (handlers[name] ||= []).push(callback) };
    const win = { location: { reload() { throw new Error('must not reload'); } } };
    globalThis.FormData = class {};
    globalThis.fetch = async () => new Response('{"success":false}', { status: 500, headers: { 'content-type': 'application/json' } });
    installLocale(win, doc);
    await handlers.submit[1]({ target: form, preventDefault() {} });
    assert.equal(error.hidden, false); assert.equal(form.dataset.busy, 'false'); assert.equal(form.elements.locale.disabled, false); assert.equal(form.elements.locale.value, 'en');
    configureLocale();
});
