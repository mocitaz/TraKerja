import test from 'node:test';
import assert from 'node:assert/strict';
globalThis.window = { Alpine: { data() {} } };
globalThis.document = { querySelector: () => ({ content: 'csrf' }) };
const { cvWorkspace } = await import('../../resources/js/cv-workspace.js');
const file = () => new File(['pdf'], 'cv.pdf', { type: 'application/pdf' });
const preview = { success: true, stage: 'preview', token: 'server-draft',
    existing_counts: { experiences: 2, educations: 1, projects: 1, skills: 3 },
    data: { experiences: [], educations: [], projects: [], skills: [{ skill_name: 'PHP' }] } };

test('upload only previews; confirmation sends server token and explicit acceptance', async () => {
    const calls = [];
    globalThis.fetch = async (url, options) => {
        calls.push({ url, options });
        return { ok: true, json: async () => calls.length === 1 ? preview : { success: true, stage: 'saved' } };
    };
    const state = cvWorkspace('/cv/import-pdf', '/cv/import-pdf/confirm');
    await state.uploadPdf(file());
    assert.equal(state.importSuccess, false);
    assert.equal(state.importPreview.token, 'server-draft');
    assert.equal(calls.length, 1);
    assert.equal(state.importSections.find(section => section.key === 'skills').existing, 3);
    await state.confirmImport();
    assert.equal(calls[1].url, '/cv/import-pdf/confirm');
    assert.equal(calls[1].options.headers['X-CSRF-TOKEN'], 'csrf');
    assert.deepEqual(JSON.parse(calls[1].options.body), { token: 'server-draft', confirm_replace: true });
    assert.equal(state.importSuccess, true);
    assert.equal(state.importPreview, null);
});

test('confirmation is unavailable without preview or while another request runs', async () => {
    globalThis.fetch = async () => { throw new Error('must not send'); };
    const state = cvWorkspace('/cv/import-pdf');
    await state.confirmImport();
    state.importPreview = preview;
    state.importing = true;
    await state.confirmImport();
    assert.equal(state.importSuccess, false);
});

test('stale preview conflict clears confirmation and displays server feedback', async () => {
    const state = cvWorkspace('/cv/import-pdf');
    state.importPreview = preview;
    globalThis.fetch = async () => ({ ok: false, status: 409, json: async () => ({ message: 'CV changed; upload again' }) });
    await state.confirmImport();
    assert.equal(state.importError, 'CV changed; upload again');
    assert.equal(state.importPreview, null);
    assert.equal(state.importing, false);
    assert.equal(state.importSuccess, false);
});

test('failed replacement keeps preview for retry and does not report success', async () => {
    const state = cvWorkspace('/cv/import-pdf');
    state.importPreview = preview;
    globalThis.fetch = async () => ({ ok: false, status: 500, json: async () => ({ message: 'All changes rolled back' }) });
    await state.confirmImport();
    assert.equal(state.importError, 'All changes rolled back');
    assert.equal(state.importPreview.token, 'server-draft');
    assert.equal(state.importSuccess, false);
});

test('selecting another PDF discards an earlier preview when parsing fails', async () => {
    const state = cvWorkspace('/cv/import-pdf');
    state.importPreview = preview;
    globalThis.fetch = async () => ({ ok: false, status: 422, json: async () => ({ message: 'No readable sections' }) });
    await state.uploadPdf(file());
    assert.equal(state.importPreview, null);
    assert.equal(state.importError, 'No readable sections');
});

test('missing education date must be supplied by the user before confirming', async () => {
    const state = cvWorkspace('/cv/import-pdf');
    state.importPreview = { ...preview, data: { ...preview.data, educations: [{ institution_name: 'Example University', start_date: null }] } };
    let sent;
    globalThis.fetch = async (_url, options) => { sent = JSON.parse(options.body); return {ok:true,json:async()=>({success:true,stage:'saved'})}; };
    await state.confirmImport();
    assert.equal(sent, undefined);
    assert.match(state.importError, /education start date/i);
    state.educationStartDates[0] = '2019-08-01';
    await state.confirmImport();
    assert.deepEqual(sent.education_start_dates, {0: '2019-08-01'});
    assert.equal(state.importSuccess, true);
});

test('completion feedback preserves the preview instead of forcing a new upload', async () => {
    const state = cvWorkspace('/cv/import-pdf');
    state.importPreview = preview;
    globalThis.fetch = async () => ({ok:false,status:422,json:async()=>({message:'Complete the missing date',requires_completion:true})});
    await state.confirmImport();
    assert.equal(state.importPreview.token, 'server-draft');
    assert.equal(state.importError, 'Complete the missing date');
});
