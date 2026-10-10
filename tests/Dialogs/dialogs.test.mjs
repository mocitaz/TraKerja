import { readFileSync } from 'node:fs';
import vm from 'node:vm';
import assert from 'node:assert/strict';
const listeners = {};
let active;
const document = { body: { style: { overflow: 'scroll' } }, get activeElement() { return active; }, addEventListener(name, fn) { listeners[name] = fn; } };
const window = {};
vm.runInNewContext(readFileSync('resources/js/dialogs.js', 'utf8'), { document, window });
const node = (visible = true) => ({ isConnected: true, tabIndex: 0, focus() { active = this; }, getClientRects: () => visible ? [1] : [], closest: () => null, matches: () => false });
const trigger = node(); trigger.focus();
const hidden = node(false), disabled = node(); disabled.matches = () => true;
const first = node(), last = node();
const dialog = (children) => ({ ...node(), querySelectorAll: () => children, contains: el => children.includes(el), setAttribute() {} });
const a = dialog([hidden, disabled, first, last]);
let closed = 0;
window.TraKerjaDialogs.open(a, () => { closed++; window.TraKerjaDialogs.close(a); });
assert.equal(active, first); assert.equal(document.body.style.overflow, 'hidden');
const event = (key, shiftKey = false) => ({ key, shiftKey, preventDefault() { this.prevented = true; }, stopPropagation() {} });
last.focus(); listeners.keydown(event('Tab')); assert.equal(active, first);
listeners.keydown(event('Tab', true)); assert.equal(active, last);
const b = dialog([]); window.TraKerjaDialogs.open(b, () => window.TraKerjaDialogs.close(b));
assert.equal(active, b); listeners.keydown(event('Tab')); assert.equal(active, b);
listeners.keydown(event('Escape')); assert.equal(closed, 0); assert.equal(active, last); assert.equal(document.body.style.overflow, 'hidden');
listeners.keydown(event('Escape')); assert.equal(closed, 1); assert.equal(active, trigger); assert.equal(document.body.style.overflow, 'scroll');
window.TraKerjaDialogs.open(a, () => {}); listeners['livewire:navigating'](); assert.equal(document.body.style.overflow, 'scroll');
listeners.keydown(event('Escape')); assert.equal(closed, 1);
console.log('Dialog focus, empty state, stack, Escape, restore, navigation checks passed');

// Exercise the native class-toggle integration, including an interrupted navigation.
let observeCallback;
const nativeListeners = {};
const classes = new Set(['hidden']);
const native = { ...dialog([first]), dataset: { dialogNative: 'dismiss' }, style: {}, classList: { contains: name => classes.has(name), add: name => classes.add(name) } };
const nativeDocument = { ...document, documentElement: {}, addEventListener(name, fn) { nativeListeners[name] = fn; }, querySelectorAll: () => [native] };
const nativeWindow = { dismiss() { classes.add('hidden'); observeCallback(); } };
vm.runInNewContext(readFileSync('resources/js/dialogs.js', 'utf8'), { document: nativeDocument, window: nativeWindow, MutationObserver: class { constructor(fn) { observeCallback = fn; } observe() {} } });
trigger.focus(); classes.delete('hidden'); observeCallback();
assert.equal(document.body.style.overflow, 'hidden');
nativeListeners.keydown(event('Escape')); assert.equal(document.body.style.overflow, 'scroll');
classes.delete('hidden'); observeCallback();
nativeListeners['livewire:navigating'](); observeCallback(); assert.equal(document.body.style.overflow, 'scroll');
classes.add('hidden'); nativeListeners['livewire:navigated'](); assert.equal(document.body.style.overflow, 'scroll');
console.log('Native class-toggle and navigation suspension checks passed');

// Run the actual Alpine directive callback with its public lifecycle utilities.
const directiveListeners = {};
const nextTicks = [];
let directive;
let registrations = 0;
const directiveDocument = {
    body: { style: { overflow: 'auto' } },
    get activeElement() { return active; },
    addEventListener(name, fn) { directiveListeners[name] = fn; },
};
const directiveWindow = {
    Alpine: {
        directive(name, callback) { assert.equal(name, 'dialog'); directive = callback; registrations++; },
        nextTick(callback) { nextTicks.push(callback); },
    },
};
vm.runInNewContext(readFileSync('resources/js/dialogs.js', 'utf8'), { document: directiveDocument, window: directiveWindow });
directiveListeners['alpine:init']();
assert.equal(registrations, 1, 'existing Alpine and alpine:init must not register twice');
const flushTicks = () => { while (nextTicks.length) nextTicks.shift()(); };
const mount = (expression, scope, children = [], closeExpression) => {
    const attributes = new Map();
    const element = { ...dialog(children), dataset: closeExpression ? { dialogClose: closeExpression } : {}, setAttribute(name, value) { attributes.set(name, value); } };
    const evaluated = [];
    const context = vm.createContext(scope);
    let runEffect;
    let dispose;
    directive(element, { expression }, {
        evaluate(code) { evaluated.push(code); return vm.runInContext(code, context); },
        effect(callback) { runEffect = callback; callback(); },
        cleanup(callback) { dispose = callback; },
    });
    assert.equal(attributes.get('role'), 'dialog');
    assert.equal(attributes.get('aria-modal'), 'true');
    assert.equal(attributes.get('tabindex'), '-1');
    return { element, evaluated, update: () => runEffect(), dispose };
};
trigger.focus();
const local = { show: false };
const emptyMount = mount('show', local);
flushTicks(); assert.equal(active, trigger);
local.show = true; emptyMount.update();
assert.equal(active, trigger, 'opening must wait for Alpine nextTick');
flushTicks(); assert.equal(active, emptyMount.element);
directiveListeners.keydown(event('Tab', true)); assert.equal(active, emptyMount.element);
directiveListeners.keydown(event('Escape'));
assert.equal(local.show, false); assert.equal(emptyMount.evaluated.at(-1), 'show = false');
emptyMount.update(); assert.equal(active, trigger); assert.equal(directiveDocument.body.style.overflow, 'auto');

let customClosed = 0;
const wire = { showGoalModal: true, closeModal() { customClosed++; this.showGoalModal = false; } };
const customMount = mount('$wire.showGoalModal', { $wire: wire }, [first], '$wire.closeModal()');
flushTicks(); assert.equal(active, first);
directiveListeners.keydown(event('Escape'));
assert.equal(customClosed, 1); assert.equal(customMount.evaluated.at(-1), '$wire.closeModal()');
customMount.update(); assert.equal(active, trigger);

const writableWire = { showSettingsModal: true };
const wireMount = mount('$wire.showSettingsModal', { $wire: writableWire });
flushTicks(); directiveListeners.keydown(event('Escape'));
assert.equal(writableWire.showSettingsModal, false, '$wire boolean expression must be writable through the evaluator');
wireMount.update(); assert.equal(active, trigger);

local.show = true; emptyMount.update(); flushTicks();
emptyMount.dispose(); assert.equal(active, trigger); assert.equal(directiveDocument.body.style.overflow, 'auto');
const pendingMount = mount('show', { show: true });
pendingMount.dispose(); flushTicks();
assert.equal(active, trigger, 'cleanup must cancel the queued open');
assert.equal(directiveDocument.body.style.overflow, 'auto');
console.log('Alpine registration, reactive state, evaluator writes, custom close, empty dialog and cleanup checks passed');
