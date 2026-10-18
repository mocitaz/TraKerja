import test from 'node:test';
import assert from 'node:assert/strict';
import { createDemoPlayer, pointerOffset, reviewPositions } from '../../resources/js/landing-motion.js';

test('demo playback finishes, can replay, and never makes a provider request', () => {
    let callback;
    const frames = [];
    const player = createDemoPlayer({
        onChange: state => frames.push({ ...state }),
        schedule: fn => { callback = fn; return 1; },
        cancel() {},
    });
    player.play();
    assert.equal(frames.at(-1).playing, true);
    for (let i = 0; i < 3; i++) callback();
    assert.equal(frames.at(-1).step, 3);
    assert.equal(frames.at(-1).playing, false);
    player.play();
    assert.equal(frames.at(-1).step, 0);
    player.select(2);
    assert.equal(frames.at(-1).playing, false);
    assert.equal(frames.at(-1).step, 2);
    player.destroy();
});

test('pausing cancels a pending scene and manual selection stays in bounds', () => {
    let canceled, last;
    const player = createDemoPlayer({ onChange: state => last = state, schedule: () => 42, cancel: id => canceled = id });
    player.play();
    player.pause();
    assert.equal(canceled, 42);
    assert.equal(last.playing, false);
    player.select(99);
    assert.equal(last.step, 3);
    player.select(-3);
    assert.equal(last.step, 0);
});

test('pointer perspective is small and disabled for touch and reduced motion', () => {
    const rect = { left: 0, top: 0, width: 1000, height: 500 };
    assert.deepEqual(pointerOffset({ pointerType: 'touch', clientX: 300, clientY: 200 }, rect), { x: 0, y: 0 });
    assert.deepEqual(pointerOffset({ pointerType: 'mouse', clientX: 2000, clientY: -200 }, rect, true), { x: 0, y: 0 });
    assert.deepEqual(pointerOffset({ pointerType: 'mouse', clientX: 2000, clientY: -200 }, rect), { x: 1, y: -1 });
});

test('testimonial navigation keeps one centered card and wraps in both directions', () => {
    assert.deepEqual(reviewPositions(1), ['before', 'center', 'after']);
    assert.deepEqual(reviewPositions(3), ['center', 'after', 'before']);
    assert.deepEqual(reviewPositions(-1), ['after', 'before', 'center']);
});

const { createEntranceObserver } = await import('../../resources/js/landing-motion.js');

function entranceFixture(reduced = false) {
    let notify, preference;
    const observed = new Set(), calls = [], animations = [];
    const target = {
        dataset: {},
        animate(frames, options) {
            calls.push({ frames, options });
            const animation = { finished: new Promise(() => {}), cancel() { this.canceled = true; } };
            animations.push(animation);
            return animation;
        },
    };
    const motion = { matches: reduced, addEventListener(name, fn) { preference = fn; }, removeEventListener() {} };
    class Observer {
        constructor(callback) { notify = callback; }
        observe(element) { observed.add(element); }
        unobserve(element) { observed.delete(element); }
        disconnect() { observed.clear(); }
    }
    const cleanup = createEntranceObserver({ elements: [target], motion, Observer });
    return { target, calls, animations, observed, cleanup,
        intersect(visible = true) { notify([{ target, isIntersecting: visible }]); },
        reduce() { motion.matches = true; preference(); },
    };
}

test('section entrances wait for visibility and run once without hiding content in CSS', () => {
    const view = entranceFixture();
    view.intersect(false);
    assert.equal(view.calls.length, 0);
    view.intersect();
    view.intersect();
    assert.equal(view.calls.length, 1);
    assert.equal(view.observed.size, 0);
    assert.equal(view.calls[0].options.fill, 'backwards');
    assert.equal(view.calls[0].frames.at(-1).opacity, 1);
});

test('reduced motion skips entrances and changing the preference cancels active motion', () => {
    const reduced = entranceFixture(true);
    reduced.intersect();
    assert.equal(reduced.calls.length, 0);
    const view = entranceFixture();
    view.intersect();
    view.reduce();
    assert.equal(view.animations[0].canceled, true);
    view.cleanup();
    assert.equal(view.observed.size, 0);
});

test('unsupported observers leave section content untouched', () => {
    const element = { animate() { assert.fail('unsupported browsers must keep static content'); } };
    assert.doesNotThrow(() => createEntranceObserver({ elements: [element], motion: { matches: false }, Observer: null })());
});
