const test = require('node:test');
const assert = require('node:assert/strict');

global.window = {};
async function composer() {
    await import('../../resources/js/admin-email-workspace.js');
    const vm = window.emailBlastWorkspace({ counts: { all: 4, premium: 0 }, savedTemplates: [] });
    vm.$refs = { composer: { reportValidity: () => true }, stepHeading: { focus() {} }, draftFrame: {} };
    vm.$nextTick = callback => callback();
    return vm;
}

test('the wizard requires an eligible audience, valid copy, language and banner choice', async () => {
    const vm = await composer();
    vm.advance(); assert.equal(vm.step, 1);
    vm.audience = 'premium'; vm.advance(); assert.equal(vm.step, 1);
    vm.audience = 'all'; vm.advance(); assert.equal(vm.step, 2);
    vm.chooseSource('custom'); vm.advance(); assert.equal(vm.step, 2);
    vm.custom.subject = 'Hello'; vm.custom.content = 'A note for our members.';
    vm.custom.buttonText = 'Open'; vm.advance(); assert.equal(vm.step, 2);
    vm.custom.buttonUrl = 'https://example.test'; vm.advance(); assert.equal(vm.step, 3);
    vm.advance(); assert.equal(vm.step, 3);
    vm.language = 'id'; vm.advance(); assert.equal(vm.step, 4);
    vm.advance(); assert.equal(vm.step, 4);
    vm.bannerChoice = 'yes'; vm.advance(); assert.equal(vm.step, 4);
    vm.banner = 'next-step'; vm.advance(); assert.equal(vm.step, 5);
    vm.advance(); assert.equal(vm.step, 5);
    vm.previewReady = true; vm.goStep(2); assert.equal(vm.previewReady, false); assert.equal(vm.completed, 1);
});

test('saved templates populate a campaign copy without changing the library entry', async () => {
    const vm = await composer();
    vm.savedTemplates = [{ id: 7, name: 'Check in', language: 'en', subject: 'Hi', content: 'Original', button_text: '', button_url: '' }];
    vm.savedId = '7'; vm.chooseSaved(); vm.custom.content = 'Edited';
    assert.equal(vm.language, 'en'); assert.equal(vm.savedTemplates[0].content, 'Original');
});

test('a draft changed during preview never receives a review token', async () => {
    const vm = await composer();
    vm.draft = { subject: 'First' };
    vm.request = async () => { vm.draft.subject = 'Changed'; return { html: 'Old render', review_token: 'stale' }; };
    await vm.previewDraft(); assert.equal(vm.reviewToken, ''); assert.equal(vm.studioBusy, false);
});

test('saving requires an explicit review and a preview token', async () => {
    const vm = await composer(); let calls = 0;
    vm.request = async () => { calls++; };
    await vm.saveDraft(); vm.reviewed = true; await vm.saveDraft();
    assert.equal(calls, 0);
});

test('preview and send cannot be invoked before their respective review steps', async () => {
    const vm = await composer(); let calls = 0;
    vm.request = async () => { calls++; };
    await vm.preview(); await vm.send();
    assert.equal(calls, 0);
});

test('stopping a campaign records only attempted outcomes and keeps remaining recipients visible', async () => {
    const vm = await composer();
    vm.modal = 'confirm'; vm.reviewConfirmed = true; vm.previewReady = true;
    vm.users = [{ id: 1, name: 'First', email: 'first@example.test' }, { id: 2, name: 'Second', email: 'second@example.test' }];
    vm.payload = { email_type: 'custom', target_user: 'all', email_language: 'id' };
    let history;
    vm.request = async (url, payload) => {
        if (payload.user_id) { vm.cancelSend(); return { success: true }; }
        history = payload; return { success: true };
    };
    await vm.send();
    assert.equal(vm.processed, 1); assert.equal(vm.successCount, 1); assert.equal(vm.sending, false);
    assert.equal(history.total_target, 2); assert.equal(history.success_count, 1); assert.equal(history.failed_count, 0);
    assert.equal(history.language, 'id'); assert.ok(vm.deliveryStatus.includes('1 not attempted'));
});

test('delivery errors count as unconfirmed and remain visible when history storage fails', async () => {
    const vm = await composer();
    vm.modal = 'confirm'; vm.reviewConfirmed = true; vm.previewReady = true;
    vm.users = [{ id: 1, name: 'Member', email: 'member@example.test' }];
    vm.payload = { email_type: 'welcome', target_user: 'all', email_language: 'en' };
    vm.request = async () => { throw new Error('Unavailable'); };
    await vm.send();
    assert.equal(vm.successCount, 0); assert.equal(vm.failures.length, 1); assert.equal(vm.processed, 1);
    assert.equal(vm.sending, false); assert.ok(vm.logError); assert.equal(vm.failures[0].error, 'Unavailable');
});
