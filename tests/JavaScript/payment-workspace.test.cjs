const {test} = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
function fixture() {
    const listeners = new Map();
    const window = {addEventListener: (name, listener) => listeners.set(name, listener), removeEventListener: (name, listener) => {if(listeners.get(name) === listener) listeners.delete(name);}};
    vm.runInNewContext(fs.readFileSync('resources/js/payment-workspace.js', 'utf8'), {window});
    return {state: window.paymentCheckout('qris'), listeners};
}
test('checkout requires valid fields and blocks repeated submissions', () => {
    const {state} = fixture();
    let prevented = 0, reported = 0, valid = false;
    const event = {preventDefault: () => prevented++, target: {checkValidity: () => valid, reportValidity: () => reported++}};
    state.submit(event);
    assert.equal(state.submitting, false); assert.equal(prevented, 1); assert.equal(reported, 1);
    valid = true; state.submit(event); assert.equal(state.submitting, true);
    state.submit(event); assert.equal(prevented, 2);
});
test('returning from provider checkout restores the form and cleans up its listener', () => {
    const {state, listeners} = fixture(); state.init();
    state.submitting = true; listeners.get('pageshow')();
    assert.equal(state.submitting, false); assert.equal(state.method, 'qris');
    state.destroy(); assert.equal(listeners.size, 0);
});
