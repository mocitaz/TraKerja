import test from 'node:test';
import assert from 'node:assert/strict';
globalThis.window = { Alpine: { data() {} } };
globalThis.document = { querySelector: () => ({ content: 'csrf-token' }) };
const { cvWorkspace, cvReview } = await import('../../resources/js/cv-workspace.js');

test('PDF validation and repeated submit guards keep invalid files off the network', async () => {
    let calls = 0;
    globalThis.fetch = async () => { calls++; return { ok: true, json: async () => ({ success: true }) }; };
    const state = cvWorkspace('/cv/import-pdf');
    await state.uploadPdf({ name: 'data.exe', type: 'application/pdf', size: 10 });
    assert.equal(state.importError, 'Choose a PDF file.');
    await state.uploadPdf({ name: 'cv.pdf', type: 'application/pdf', size: 11 * 1024 * 1024 });
    assert.match(state.importError, /10 MB/);
    state.importing = true;
    await state.uploadPdf(new File(['pdf'], 'cv.pdf', { type: 'application/pdf' }));
    assert.equal(calls, 0);
});

test('PDF imports send CSRF, preserve provider errors and recover for retry', async () => {
    const state = cvWorkspace('/cv/import-pdf');
    let sent;
    globalThis.fetch = async (url, options) => {
        sent = { url, options };
        return { ok: false, json: async () => ({ message: 'Parser unavailable' }) };
    };
    const pdf = new File(['pdf'], 'cv.pdf', { type: 'application/pdf' });
    await state.uploadPdf(pdf);
    assert.equal(sent.url, '/cv/import-pdf');
    assert.equal(sent.options.headers['X-CSRF-TOKEN'], 'csrf-token');
    assert.equal(sent.options.body.get('resume').name, 'cv.pdf');
    assert.equal(state.importError, 'Parser unavailable');
    assert.equal(state.importing, false);
    globalThis.fetch = async () => ({ ok: true, json: async () => ({ success: true, stage: 'preview', token: 'draft', data: { skills: [{ skill_name: 'PHP' }] } }) });
    await state.uploadPdf(pdf);
    assert.equal(state.importSuccess, false);
    assert.equal(state.importPreview.token, 'draft');
    assert.equal(state.importError, '');
});

test('spacing applies consistently to every template', () => {
    for (const template of ['minimal', 'creative', 'professional', 'elegant']) {
        const state = cvReview(template);
        assert.equal(state.margin, '15mm');
        state.spacing = 'compact'; assert.equal(state.margin, '10mm');
        state.spacing = 'spacious'; assert.equal(state.margin, '20mm');
    }
});

test('content checks retain actual scores and allow retry after connection failure', async () => {
    const state = cvReview('minimal', '/preview?canvas=1', '/check');
    globalThis.fetch = async () => { throw new Error('Offline'); };
    await state.runCheck();
    assert.equal(state.checking, false);
    assert.equal(state.checkError, 'Offline');
    globalThis.fetch = async () => ({ ok: true, json: async () => ({ success: true, score: 18, checks: [], tips: ['Actual suggestion'] }) });
    await state.runCheck();
    assert.equal(state.result.score, 18);
    assert.equal(state.checkError, '');
});
