const {test} = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

function fixture(fetcher) {
    const timers = new Map(), listeners = new Map(), redirects = [];
    let id = 0;
    const window = {location: {assign: url => redirects.push(url)}, addEventListener: (name, cb) => listeners.set(name, cb), removeEventListener: (name, cb) => { if (listeners.get(name) === cb) listeners.delete(name); }};
    const context = {window, document: window, fetch: fetcher, AbortController, navigator: {clipboard: {writeText: async () => {}}}, setTimeout: (cb, delay) => {timers.set(++id, {cb, delay}); return id;}, clearTimeout: id => timers.delete(id)};
    const source = fs.existsSync('resources/js/payment-waiting.js') ? fs.readFileSync('resources/js/payment-waiting.js', 'utf8') : '';
    vm.runInNewContext(source, context);
    assert.equal(typeof window.paymentWaitingStatus, 'function', 'compiled helper must expose an Alpine component');
    const state = window.paymentWaitingStatus({statusUrl: '/status', successUrl: '/success', failedUrl: '/failed', initialStatus: 'WAITING'});
    return {state, timers, listeners, redirects};
}
const response = (status = 'WAITING') => ({ok: true, status: 200, redirected: false, json: async () => ({success: true, status})});

test('initial schedule and repeated manual checks allow only one fetch', async () => {
    let resolve, calls = 0;
    const f = fixture(() => {calls++; return new Promise(r => resolve = r);});
    f.state.init();
    assert.equal([...f.timers.values()][0].delay, 2000);
    const running = f.state.poll();
    await f.state.poll();
    assert.equal(calls, 1);
    resolve(response()); await running;
    assert.equal(f.state.busy, false);
    assert.deepEqual([...f.timers.values()].map(t => t.delay), [10000]);
});
test('network failures back off and successful verification resets delay', async () => {
    let fail = true;
    const f = fixture(async () => { if(fail) throw new Error('offline'); return response('PENDING'); });
    for (const delay of [10000, 20000, 40000, 60000, 60000]) {
        await f.state.poll(); assert.ok(f.state.error); assert.equal(f.state.status, 'WAITING');
        assert.equal([...f.timers.values()][0].delay, delay);
    }
    fail = false; await f.state.poll(); assert.equal(f.state.error, '');
    assert.equal([...f.timers.values()][0].delay, 10000);
});
test('timeout aborts stalled verification and releases retry', async () => {
    const f = fixture((url, options) => new Promise((resolve, reject) => options.signal.addEventListener('abort', () => reject(new Error('aborted')))));
    const running = f.state.poll();
    [...f.timers.values()].find(t => t.delay === 12000).cb();
    await running;
    assert.ok(f.state.error.includes('timed out')); assert.equal(f.state.busy, false);
    assert.equal([...f.timers.values()][0].delay, 10000);
});
for (const status of ['SUCCESS', 'FAILED', 'CANCELED', 'EXPIRED']) test(`${status} stops polling and redirects to the existing result page`, async () => {
    const f = fixture(async () => response(status)); await f.state.poll();
    assert.deepEqual(f.redirects, [status === 'SUCCESS' ? '/success' : '/failed']);
    assert.equal(f.timers.size, 0); await f.state.poll(); assert.equal(f.redirects.length, 1);
});
for (const status of [401,403,404,419,500,503]) test(`HTTP ${status} reports verification error without payment failure redirect`, async () => {
    const f = fixture(async () => ({ok:false,status})); await f.state.poll();
    assert.ok(f.state.error); assert.deepEqual(f.redirects, []); assert.equal(f.state.status, 'WAITING');
    assert.equal(f.timers.size, [401,403,404,419].includes(status) ? 0 : 1);
});
for (const payload of [{success:false}, {success:true,status:'MYSTERY'}, {success:true}, null]) test(`invalid verification payload ${JSON.stringify(payload)} remains retryable`, async () => {
    const f = fixture(async () => ({ok:true, json:async () => payload})); await f.state.poll();
    assert.ok(f.state.error); assert.equal(f.state.status, 'WAITING'); assert.deepEqual(f.redirects, []);
});
test('redirected login HTML is an authentication failure', async () => {
    const f = fixture(async () => ({ok:true,redirected:true,json:async () => {throw new Error('HTML');}})); await f.state.poll();
    assert.ok(f.state.error.includes('Sign in')); assert.equal(f.timers.size, 0);
});
test('navigation cleanup aborts in-flight work, removes listeners and ignores late success', async () => {
    let resolve, signal;
    const f = fixture((url, options) => {signal=options.signal; return new Promise(r => resolve=r);});
    f.state.init(); const running = f.state.poll();
    f.listeners.get('livewire:navigating')();
    assert.equal(signal.aborted, true); assert.equal(f.timers.size, 0); assert.equal(f.listeners.size, 0);
    resolve(response('SUCCESS')); await running;
    assert.deepEqual(f.redirects, []); assert.equal(f.timers.size, 0);
});

test('bfcache restoration resumes polling once and leaves manual checking available', async () => {
    let calls = 0;
    const f = fixture(async () => { calls++; return response(); });
    f.state.init();
    f.listeners.get('pagehide')({persisted: true});
    assert.equal(f.timers.size, 0);
    assert.equal(typeof f.listeners.get('pageshow'), 'function');
    await f.listeners.get('pageshow')({persisted: true});
    assert.equal(calls, 1);
    await f.listeners.get('pageshow')({persisted: true});
    assert.equal(calls, 1);
    await f.state.poll();
    assert.equal(calls, 2);
    assert.equal(f.timers.size, 1);
    f.state.destroy();
    assert.equal(f.listeners.size, 0);
    assert.equal(f.timers.size, 0);
    await f.state.poll();
    assert.equal(calls, 2);
});

test('restoration ignores aborted pre-hide result without disturbing resumed request', async () => {
    const requests = [];
    const f = fixture((url, options) => new Promise(resolve => requests.push({resolve, signal: options.signal})));
    f.state.init();
    const old = f.state.poll();
    f.listeners.get('pagehide')({persisted: true});
    assert.equal(requests[0].signal.aborted, true);
    const resumed = f.listeners.get('pageshow')({persisted: true});
    assert.equal(requests.length, 2);
    requests[0].resolve(response('SUCCESS')); await old;
    assert.equal(f.state.busy, true);
    assert.deepEqual(f.redirects, []);
    requests[1].resolve(response()); await resumed;
    assert.equal(f.state.busy, false);
    assert.equal(f.timers.size, 1);
});

test('terminal redirect removes restoration hooks permanently', async () => {
    const f = fixture(async () => response('SUCCESS'));
    f.state.init();
    await f.state.poll();
    assert.equal(f.listeners.size, 0);
    assert.equal(f.state.stopped, true);
    await f.state.poll();
    assert.deepEqual(f.redirects, ['/success']);
});

test('pagehide without bfcache and Livewire navigation permanently destroy polling', async () => {
    for (const event of ['pagehide', 'livewire:navigating']) {
        const f = fixture(async () => response());
        f.state.init();
        f.listeners.get(event)({persisted: false});
        assert.equal(f.state.stopped, true);
        assert.equal(f.listeners.size, 0);
        assert.equal(f.timers.size, 0);
    }
});
