import test from 'node:test';
import assert from 'node:assert/strict';
import { dashboardWelcome } from '../../resources/js/dashboard-welcome.js';

test('pointer movement is bounded and disabled for touch or paused motion', () => {
    const state = dashboardWelcome();
    const values = {};
    state.$el = {getBoundingClientRect: () => ({left: 0, top: 0, width: 400, height: 200}), style: {setProperty: (key, value) => values[key] = value}};
    state.move({pointerType: 'mouse', clientX: 1000, clientY: -100});
    assert.equal(values['--welcome-x'], '1');
    assert.equal(values['--welcome-y'], '-1');
    state.paused = true;
    state.move({pointerType: 'mouse', clientX: 0, clientY: 200});
    assert.equal(values['--welcome-x'], '1');
    state.reset();
    assert.equal(values['--welcome-x'], '0');
    state.paused = false;
    state.move({pointerType: 'touch', clientX: 0, clientY: 200});
    assert.equal(values['--welcome-x'], '0');
});

test('reduced-motion updates and navigation teardown clean up the media listener', () => {
    let listener, removed;
    const media = {matches: true, addEventListener: (_, fn) => listener = fn, removeEventListener: (_, fn) => removed = fn};
    const state = dashboardWelcome({matchMedia: () => media});
    state.$el = {style: {setProperty() {}}};
    state.init();
    assert.equal(state.reduced, true);
    listener({matches: false});
    assert.equal(state.reduced, false);
    state.destroy();
    assert.equal(removed, listener);
});
