import test from 'node:test';
import assert from 'node:assert/strict';
globalThis.window = { location: { origin: 'http://localhost', href: 'http://localhost/ai-studio', assign() {} }, addEventListener() {}, removeEventListener() {} };
globalThis.document = { addEventListener() {} };
const { aiStudioPortal } = await import('../../resources/js/ai-studio.js');
const config = { task: 'cover-letter', endpoints: { analyzer: '/analyze', 'cover-letter': '/generate', photo: '/photo', outreach: '/outreach' }, access: { analyzer: true, 'cover-letter': true, photo: true, outreach: true } };
function state() { const value = aiStudioPortal({ ...config, access: { ...config.access } }); value.$nextTick = async fn => fn?.(); value.$refs = { composer: { scrollIntoView() {} } }; return value; }
function event() { return { prevented: false, preventDefault() { this.prevented = true; }, target: { querySelector() { return { value: 'csrf' }; } } }; }
globalThis.FormData = class { constructor(form) { this.form = form; } };

test('request guard prevents double submissions and does not navigate for an unsaved fallback', async () => {
    let resolve, calls = 0, assigned = false;
    window.location.assign = () => { assigned = true; };
    globalThis.fetch = async (url, options) => { calls++; assert.equal(url, '/generate'); assert.equal(options.headers['X-CSRF-TOKEN'], 'csrf'); return new Promise(finish => { resolve = finish; }); };
    const portal = state(), first = event(), duplicate = event();
    const pending = portal.submit(first);
    assert.equal(portal.busy, true);
    await portal.submit(duplicate);
    assert.equal(duplicate.prevented, true);
    assert.equal(calls, 1);
    resolve(new Response(JSON.stringify({ success: true, is_fallback: true, cover_letter: '<script>literal</script>' }), { headers: { 'content-type': 'application/json' } }));
    await pending;
    assert.equal(portal.draftText, '<script>literal</script>');
    assert.equal(portal.busy, false);
    assert.equal(assigned, false);
});

test('validation failures retain composer state and a saved result opens only on the same origin', async () => {
    const portal = state();
    globalThis.fetch = async () => new Response(JSON.stringify({ errors: { company_name: ['Company is required.'] } }), { status: 422, headers: { 'content-type': 'application/json' } });
    await portal.submit(event());
    assert.equal(portal.error, 'Company is required.');
    assert.equal(portal.busy, false);
    let assigned;
    window.location.assign = url => { assigned = url; };
    globalThis.fetch = async () => new Response(JSON.stringify({ success: true, id: 5 }), { headers: { 'content-type': 'application/json' } });
    await portal.submit(event());
    assert.equal(assigned, 'http://localhost/cover-letters/5');
    portal.task = 'outreach';
    globalThis.fetch = async () => new Response(JSON.stringify({ success: true, url: 'https://other.example/private' }), { headers: { 'content-type': 'application/json' } });
    await portal.submit(event());
    assert.match(portal.error, /Check your history/);
    assert.equal(assigned, 'http://localhost/cover-letters/5');
});

test('photo and analyzer preserve native submission while limits and files block invalid requests', async () => {
    const portal = state();
    portal.task = 'photo';
    const native = event();
    await portal.submit(native);
    assert.equal(native.prevented, false);
    assert.equal(portal.busy, true);
    portal.busy = false; portal.access.photo = false;
    const blocked = event(); await portal.submit(blocked);
    assert.equal(blocked.prevented, true);
    const input = { files: [{ name: 'large.pdf', type: 'application/pdf', size: 11 * 1024 * 1024 }], value: 'chosen' };
    portal.fileChanged({ target: input }, 'pdf');
    assert.equal(input.value, ''); assert.match(portal.error, /10 MB/);
});

test('destroyed portal ignores late provider responses and cancels its fetch', async () => {
    let resolve, assigned = false;
    window.location.assign = () => { assigned = true; };
    globalThis.fetch = () => new Promise(finish => { resolve = finish; });
    const portal = state(), pending = portal.submit(event());
    const controller = portal.controller;
    portal.destroy();
    assert.equal(controller.signal.aborted, true);
    resolve(new Response(JSON.stringify({ success: true, id: 1 }), { headers: { 'content-type': 'application/json' } }));
    await pending;
    assert.equal(assigned, false);
});


test('chat collects only missing details and sends the request after the last answer', async () => {
    const portal = state();
    let calls = 0;
    globalThis.fetch = async () => { calls++; return new Response(JSON.stringify({ success: true, is_fallback: true, cover_letter: 'Draft' }), { headers: { 'content-type': 'application/json' } }); };
    portal.answer = 'Example company';
    await portal.sendAnswer(event());
    assert.equal(portal.values.company_name, 'Example company');
    assert.equal(portal.currentStep.key, 'job_title');
    assert.equal(calls, 0);
    portal.answer = 'Software engineer';
    await portal.sendAnswer(event());
    assert.equal(portal.currentStep.key, 'job_description');
    portal.answer = 'Too short';
    await portal.sendAnswer(event());
    assert.match(portal.error, /50–2500/);
    assert.equal(portal.turns.length, 2);
    portal.answer = 'Build reliable applications with the engineering team. Design and maintain APIs.';
    await portal.sendAnswer(event());
    assert.equal(portal.turns.length, 3);
    assert.equal(portal.completedDetails, true);
    assert.equal(calls, 1);
    assert.equal(portal.draftText, 'Draft');
    portal.editTurn(1);
    assert.equal(portal.turns.length, 1);
    assert.equal(portal.answer, 'Software engineer');
    assert.equal(portal.values.job_description, undefined);
    assert.equal(portal.completedDetails, false);
});

test('chat requires attachments and preserves native upload payload after collecting details', async () => {
    const portal = state();
    portal.task = 'analyzer';
    portal.answer = 'A complete job description with enough characters to satisfy the actual provider contract.';
    await portal.sendAnswer(event());
    assert.match(portal.error, /Attach your resume PDF/);
    assert.equal(portal.turns.length, 0);
    portal.fileName = 'resume.pdf';
    portal.$refs.form = {};
    let submitted = 0;
    globalThis.HTMLFormElement = class { submit() { submitted++; } };
    await portal.sendAnswer(event());
    assert.equal(submitted, 1);
    assert.equal(portal.busy, true);
    assert.equal(portal.values.job_description.includes('complete job'), true);
    await portal.sendAnswer(event());
    assert.equal(submitted, 1);
});
