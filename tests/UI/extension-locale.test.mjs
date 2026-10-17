import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const source = readFileSync(new URL('../../chrome-extension/translations.js', import.meta.url), 'utf8');
async function load(language, stored) {
    const context = vm.createContext({
        navigator: { language },
        chrome: { storage: { local: { get: async () => ({ language: stored }) } } },
        document: { addEventListener() {} },
    });
    vm.runInContext(`${source}\nglobalThis.localeApi = ExtensionLocale;`, context);
    await context.localeApi.ready;
    return context.localeApi;
}

test('extension chooses stored language and preserves captured values during interpolation', async () => {
    const api = await load('en-US', 'id');
    assert.equal(api.locale, 'id');
    assert.equal(api.t('Simpan ke Kanban'), 'Simpan lamaran');
    assert.equal(api.t('Detected: :location', { location: ':name <Jakarta>' }), 'Terdeteksi: :name <Jakarta>');
    assert.equal(api.t('__proto__'), '__proto__');
});

test('extension defaults to English and does not translate unknown job content', async () => {
    const api = await load('en-US', 'invalid');
    assert.equal(api.locale, 'en');
    assert.equal(api.t('Pilih Provinsi'), 'Choose a province');
    assert.equal(api.t('PT Actual Company'), 'PT Actual Company');
    assert.equal((await load('id-ID')).locale, 'en');
});
