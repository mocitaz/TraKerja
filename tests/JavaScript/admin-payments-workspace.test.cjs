const test = require('node:test');
const assert = require('node:assert/strict');

let factory;
async function workspace() {
    global.window = {
        location: { href: 'http://localhost/admin/payments?status=SUCCESS', reload() {} },
        history: { replaceState(a, b, url) { window.location.href = url.toString(); } },
    };
    await import('../../resources/js/admin-payments-workspace.js');
    factory ||= window.adminPaymentsWorkspace;
    return factory();
}

test('tab changes preserve transaction filters and ignore unknown panels', async () => {
    const vm = await workspace();
    vm.selectView('plans');
    assert.equal(new URL(window.location.href).searchParams.get('status'), 'SUCCESS');
    assert.equal(new URL(window.location.href).searchParams.get('view'), 'plans');
    vm.selectView('unknown'); assert.equal(vm.activeView, 'plans');
    vm.selectView('transactions'); assert.equal(new URL(window.location.href).searchParams.has('view'), false);
});

test('a slower detail response cannot replace the most recently requested payment', async () => {
    const vm = await workspace();
    const pending = [];
    global.fetch = () => new Promise(resolve => pending.push(resolve));
    const first = vm.view(1); const second = vm.view(2);
    pending[1]({ ok: true, json: async () => ({ success: true, payment: { id: 2 }, user: null }) });
    await second;
    pending[0]({ ok: true, json: async () => ({ success: true, payment: { id: 1 }, user: null }) });
    await first;
    assert.equal(vm.payment.id, 2); assert.equal(vm.loading, false);
});

test('cancellation requires confirmation and keeps an uncertain outcome visible for review', async () => {
    const vm = await workspace(); let calls = 0;
    global.document = { querySelector: () => ({ content: 'test-token' }) };
    global.fetch = async () => { calls++; throw new TypeError('Network failure'); };
    vm.payment = { id: 2 };
    await vm.cancel(); assert.equal(calls, 0);
    vm.confirming = true; await vm.cancel();
    assert.equal(calls, 1); assert.equal(vm.busy, false);
    assert.match(vm.error, /could not be confirmed/); assert.equal(vm.payment.id, 2);
});

test('legacy records without environment are not labeled as live', async () => {
    const vm = await workspace();
    assert.equal(vm.environment(null), 'Unspecified');
    assert.equal(vm.environment(false), 'Live'); assert.equal(vm.environment(true), 'Sandbox');
});
