import test from 'node:test';
import assert from 'node:assert/strict';
globalThis.window = { location: { origin: 'http://localhost', href: 'http://localhost/ai-studio', assign() {} }, history: { replaceState() {} }, addEventListener() {}, removeEventListener() {} };
globalThis.document = { addEventListener() {} };
const { aiStudioPortal, studioRunStatus } = await import('../../resources/js/ai-studio.js');
const config = { task: 'cover-letter', endpoints: { analyzer: '/analyze', 'cover-letter': '/generate', photo: '/photo', outreach: '/outreach' }, access: { analyzer: true, 'cover-letter': true, photo: true, outreach: true } };
function state() {
    const value = aiStudioPortal({ ...config, access: { ...config.access } });
    value.values = { company_name: 'Actual company', job_title: 'Engineer', job_description: 'Build reliable applications with the engineering team. Design and maintain APIs.' };
    value.$refs = { file: { value: '', focus() {} }, description: { focus() {} } };
    return value;
}
function event() { return { prevented: false, preventDefault() { this.prevented = true; }, target: { querySelector() { return { value: 'csrf' }; } } }; }
globalThis.FormData = class { constructor(form) { this.form = form; } };

test('one submit saves the request and duplicate clicks do not create another request', async () => {
    let resolve, calls = 0, assigned;
    window.location.assign = url => { assigned = url; };
    globalThis.fetch = async (url, options) => { calls++; assert.equal(url, '/generate'); assert.equal(options.headers['X-CSRF-TOKEN'], 'csrf'); return new Promise(finish => { resolve = finish; }); };
    const portal = state(), first = event(), duplicate = event();
    const pending = portal.submit(first);
    assert.equal(portal.busy, true);
    await portal.submit(duplicate);
    assert.equal(duplicate.prevented, true);
    assert.equal(calls, 1);
    resolve(new Response(JSON.stringify({ success: true, status: 'queued', url: '/ai-studio?run=8' }), { headers: { 'content-type': 'application/json' } }));
    await pending;
    assert.equal(portal.busy, false);
    assert.equal(assigned, 'http://localhost/ai-studio?run=8');
});

test('validation errors retain all form values and reject external result links', async () => {
    const portal = state(), before = { ...portal.values };
    globalThis.fetch = async () => new Response(JSON.stringify({ errors: { company_name: ['Company is required.'] } }), { status: 422, headers: { 'content-type': 'application/json' } });
    await portal.submit(event());
    assert.equal(portal.error, 'Company is required.');
    assert.equal(portal.busy, false);
    assert.deepEqual(portal.values, before);
    globalThis.fetch = async () => new Response(JSON.stringify({ success: true, url: 'https://other.example/private' }), { headers: { 'content-type': 'application/json' } });
    await portal.submit(event());
    assert.match(portal.error, /Check your history/);
});

test('missing CV and insufficient requirements stop before any network request', async () => {
    const portal = state(); portal.task = 'analyzer';
    let calls = 0; globalThis.fetch = async () => { calls++; };
    await portal.submit(event()); assert.match(portal.error, /Upload a PDF/);
    portal.resumeId = '5'; portal.values.job_description = 'Short';
    await portal.submit(event()); assert.match(portal.error, /50 characters/);
    assert.equal(calls, 0);
    const input = { files: [{ name: 'large.pdf', type: 'application/pdf', size: 11 * 1024 * 1024 }], value: 'chosen' };
    portal.fileChanged({ target: input }, 'pdf');
    assert.equal(input.value, ''); assert.match(portal.error, /10 MB/);
});

test('photo uses the same asynchronous submission and requires an attachment', async () => {
    const portal = state(); portal.task = 'photo';
    await portal.submit(event()); assert.match(portal.error, /Choose a photo/);
    portal.fileName = 'portrait.jpg';
    let endpoint; globalThis.fetch = async url => { endpoint = url; return new Response(JSON.stringify({ success: true, url: '/ai-photo/3' }), { headers: { 'content-type': 'application/json' } }); };
    await portal.submit(event()); assert.equal(endpoint, '/photo');
    portal.access.photo = false; endpoint = null;
    await portal.submit(event()); assert.equal(endpoint, null);
});

test('changing task keeps typed context, clears incompatible attachments and ignores selecting current task', () => {
    const portal = state(), before = { ...portal.values };
    portal.fileName = 'resume.pdf'; portal.$refs.file.value = 'selected';
    portal.choose('cover-letter'); assert.equal(portal.fileName, 'resume.pdf');
    portal.choose('outreach');
    assert.deepEqual(portal.values, before); assert.equal(portal.fileName, '');
    assert.equal(portal.showRequirements, true);
    portal.busy = true; portal.choose('photo'); assert.equal(portal.task, 'outreach');
});

test('application selection supplies company and role and discards requirements for a different role', () => {
    const portal = state();
    portal.applications = [{ id: 7, company_name: 'New company', position: 'Designer' }];
    portal.applicationId = '7'; portal.applyApplication();
    assert.equal(portal.values.company_name, 'New company'); assert.equal(portal.values.job_title, 'Designer');
    assert.equal(portal.values.job_description, '');
    portal.values.job_description = 'Requirements for Designer'; portal.applyApplication();
    assert.equal(portal.values.job_description, 'Requirements for Designer');
});

test('destroyed portal cancels its request and ignores late responses', async () => {
    let resolve, assigned = false;
    window.location.assign = () => { assigned = true; };
    globalThis.fetch = () => new Promise(finish => { resolve = finish; });
    const portal = state(), pending = portal.submit(event());
    const controller = portal.controller; portal.destroy();
    assert.equal(controller.signal.aborted, true);
    resolve(new Response(JSON.stringify({ success: true, url: '/ai-studio?run=1' }), { headers: { 'content-type': 'application/json' } }));
    await pending; assert.equal(assigned, false);
});

test('completed requests open verified local results and failed requests stop polling', async () => {
    let assigned;
    window.location.assign = url => { assigned = url; };
    globalThis.fetch = async () => new Response(JSON.stringify({ status: 'completed', credit_state: 'consumed', url: '/ai-studio?task=analyzer&id=4' }), { headers: { 'content-type': 'application/json' } });
    const run = studioRunStatus('/status', 'processing', 'reserved'); await run.poll();
    assert.equal(run.credit, 'consumed'); assert.equal(assigned, 'http://localhost/ai-studio?task=analyzer&id=4');
    globalThis.fetch = async () => new Response(JSON.stringify({ status: 'failed', credit_state: 'refunded', message: 'Try again later.' }), { headers: { 'content-type': 'application/json' } });
    await run.poll(); assert.equal(run.credit, 'refunded'); assert.equal(run.message, 'Try again later.'); assert.equal(run.timer, null);
    globalThis.fetch = async () => new Response(JSON.stringify({ status: 'completed', url: 'https://other.example/' }), { headers: { 'content-type': 'application/json' } });
    await run.poll(); assert.match(run.error, /could not be verified/);
});
