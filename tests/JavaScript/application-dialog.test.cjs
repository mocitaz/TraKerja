const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

function setup(refresh = false, reduced = false) {
    const events = {}, documentEvents = {}, timers = new Map(), calls = [];
    let nextTimer = 1;
    const classes = new Set();
    const modal = {
        dataset: { refreshOnSave: String(refresh) },
        classList: { contains: c => classes.has(c), add: (...c) => c.forEach(x => classes.add(x)), remove: (...c) => c.forEach(x => classes.delete(x)) },
        querySelector: () => ({ scrollTop: 0 }),
    };
    const doc = { getElementById: id => id === 'jobModal' ? modal : {}, addEventListener: (name, handler) => documentEvents[name] = handler };
    const win = {
        location: { href: 'http://localhost/dashboard' }, scrollY: 240,
        matchMedia: () => ({ matches: reduced }),
        addEventListener: (name, handler) => events[name] = handler,
        setTimeout: (fn, delay) => { const id = nextTimer++; timers.set(id, { fn, delay }); return id; },
        clearTimeout: id => timers.delete(id),
        showToastNotification: notice => calls.push(['toast', notice]),
        scrollTo: (...values) => calls.push(['scroll', ...values]),
        Livewire: { dispatch: (...values) => calls.push(['dispatch', ...values]), navigate: url => calls.push(['navigate', url]) },
        TraKerjaDialogs: { close: () => calls.push(['release-focus']) },
    };
    vm.runInNewContext(fs.readFileSync('resources/js/application-dialog.js', 'utf8').replace('export function', 'function') + '\ninstallApplicationDialog(win, doc);', { win, doc, URL });
    const finishClose = () => { const [id, timer] = [...timers].find(([, timer]) => timer.delay < 1000); timers.delete(id); timer.fn(); };
    return { win, events, documentEvents, calls, classes, timers, finishClose };
}

test('successful save closes before resetting or notifying and keeps one handler', () => {
    const app = setup();
    app.events['application-saved']({ detail: { title: 'Application added' } });
    assert.equal(app.classes.has('is-closing'), true);
    assert.equal(app.classes.has('hidden'), false);
    assert.deepEqual(app.calls, []);
    app.finishClose();
    assert.equal(app.classes.has('hidden'), true);
    assert.deepEqual(app.calls.map(c => c[0]), ['release-focus', 'dispatch', 'toast']);
    assert.equal(app.calls.filter(c => c[0] === 'toast').length, 1);
});

test('dashboard refresh preserves scroll and waits for the new page before notification', () => {
    const app = setup(true);
    app.events['application-saved']({ detail: { title: 'Application added' } });
    app.finishClose();
    assert.equal(app.calls.filter(c => c[0] === 'toast').length, 0);
    assert.equal(app.calls.filter(c => c[0] === 'navigate').length, 1);
    app.documentEvents['livewire:navigated']();
    assert.deepEqual(app.calls.find(c => c[0] === 'scroll'), ['scroll', 0, 240]);
    assert.equal(app.calls.filter(c => c[0] === 'toast').length, 1);
    app.documentEvents['livewire:navigated']();
    assert.equal(app.calls.filter(c => c[0] === 'toast').length, 1);
});

test('reduced motion closes without an animation delay', () => {
    const app = setup(false, true);
    app.win.closeJobModal();
    assert.equal([...app.timers.values()][0].delay, 0);
    app.finishClose();
    assert.equal(app.classes.has('hidden'), true);
});

test('save feedback is retained if the user starts closing during the request', () => {
    const app = setup();
    app.win.closeJobModal();
    app.events['application-saved']({ detail: { title: 'Application added' } });
    app.finishClose();
    assert.equal(app.calls.filter(c => c[0] === 'toast').length, 1);
});
