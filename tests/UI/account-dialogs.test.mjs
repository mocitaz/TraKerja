import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import vm from 'node:vm';

function fixture() {
    const template = fs.readFileSync('resources/views/layouts/app.blade.php', 'utf8');
    const start = template.indexOf('            var activeConfirmCallback');
    const end = template.indexOf('            async function confirmLogout(', start);
    const classes = new Set(['hidden']);
    const button = {innerText: 'Confirm', disabled: false};
    const cancel = {disabled: false};
    const close = {disabled: false};
    const notice = {hidden: true, innerText: ''};
    const modal = {dataset: {}, classList: {add: name => classes.add(name), remove: name => classes.delete(name)}, querySelectorAll: () => [button, cancel, close]};
    const elements = {'confirmation-modal': modal, 'confirm-button': button, 'confirm-cancel': cancel, 'confirm-title': {}, 'confirm-message': {}, 'confirm-error': notice};
    const context = vm.createContext({document: {getElementById: id => elements[id]}});
    vm.runInContext(template.slice(start, end), context);
    return {context, classes, button, cancel, notice, modal};
}

test('confirmation awaits its action and prevents repeated submissions or cancellation while busy', async () => {
    const f = fixture(); let finish, calls = 0;
    f.context.openConfirmModal('Sign out?', 'Confirm', 'Sign out', () => {calls++; return new Promise(resolve => {finish = resolve;});}, {variant: 'logout'});
    assert.equal(f.cancel.innerText, 'Stay here'); assert.equal(f.classes.has('hidden'), false);
    const pending = f.button.onclick();
    await f.button.onclick(); f.context.closeConfirmModal();
    assert.equal(calls, 1); assert.equal(f.button.disabled, true); assert.equal(f.button.innerText, 'Signing out…'); assert.equal(f.classes.has('hidden'), false);
    finish(); await pending;
    assert.equal(f.classes.has('hidden'), true); assert.equal(f.button.disabled, false);
});

test('cancel clears the action and a new ordinary confirmation uses the default style', async () => {
    const f = fixture(); let calls = 0;
    f.context.openConfirmModal('Sign out?', 'Confirm', 'Sign out', () => calls++, {variant: 'logout'});
    f.context.closeConfirmModal(); await f.button.onclick(); assert.equal(calls, 0);
    f.context.openConfirmModal('Delete?', 'Confirm deletion', 'Delete', () => calls++);
    assert.equal(f.modal.dataset.variant, 'action'); assert.equal(f.cancel.innerText, 'Cancel');
    await f.button.onclick(); assert.equal(calls, 1); assert.equal(f.classes.has('hidden'), true);
});

test('a rejected action stays open with an error and can be retried', async () => {
    const f = fixture(); let calls = 0;
    f.context.openConfirmModal('Confirm', 'Message', 'Continue', async () => {if (++calls === 1) throw new Error('failure');});
    await f.button.onclick();
    assert.equal(f.notice.hidden, false); assert.equal(f.classes.has('hidden'), false); assert.equal(f.button.disabled, false);
    assert.equal(f.button.innerText, 'Continue');
    await f.button.onclick(); assert.equal(calls, 2); assert.equal(f.classes.has('hidden'), true);
    f.context.openConfirmModal('Next', 'Message', 'Continue', () => {});
    assert.equal(f.notice.hidden, true);
});
