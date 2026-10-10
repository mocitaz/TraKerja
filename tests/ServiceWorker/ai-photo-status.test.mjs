import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import vm from 'node:vm';

function component(fetch) {
    let reloads = 0;
    const timers = new Map();
    let number = 0;
    const context = vm.createContext({
        window: {location: {reload: () => reloads++}}, fetch, AbortController,
        setTimeout: (fn, delay) => { timers.set(++number, {fn, delay}); return number; },
        clearTimeout: id => timers.delete(id),
    });
    vm.runInContext(fs.readFileSync('resources/js/ai-photo-status.js', 'utf8'), context);
    return {state: context.window.photoRequestStatus('/ai-photo/1/status', 'queued'), timers, reloads: () => reloads};
}

test('status refresh transitions to processing then reloads once on terminal state', async () => {
    let status = 'processing';
    const {state, timers, reloads} = component(async () => ({ok: true, json: async () => ({status})}));
    await state.poll();
    assert.equal(state.status, 'processing');
    assert.equal(reloads(), 0);
    assert.equal([...timers.values()][0].delay, 4000);
    status = 'completed';
    await state.poll();
    await state.poll();
    assert.equal(state.status, 'completed');
    assert.equal(reloads(), 1);
    assert.equal(timers.size, 0);
});

test('network failure displays recoverable error and delays next check', async () => {
    const {state, timers} = component(async () => {throw new Error('offline');});
    await state.poll();
    assert.equal(state.status, 'queued');
    assert.match(state.error, /couldn’t check the photo status/);
    assert.equal([...timers.values()][0].delay, 10000);
});

test('overlapping checks and destroyed components do not make extra requests', async () => {
    let resolve;
    let calls = 0;
    let signal;
    const {state, timers, reloads} = component((url, options) => {
        calls++;
        signal = options.signal;
        return new Promise(done => resolve = done);
    });
    const pending = state.poll();
    await state.poll();
    assert.equal(calls, 1);
    state.destroy();
    assert.equal(signal.aborted, true);
    resolve({ok: true, json: async () => ({status: 'completed'})});
    await pending;
    assert.equal(reloads(), 0);
    assert.equal(timers.size, 0);
});
