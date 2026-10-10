import test from 'node:test';
import assert from 'node:assert/strict';

globalThis.window = {addEventListener() {}, removeEventListener() {}};
globalThis.FormData = class { constructor(form) { this.values = form.values; } get(name) {return this.values[name] ?? null;} };
globalThis.HTMLFormElement = class { submit() { this.submissions++; } };
const {surveyWorkspace} = await import('../../resources/js/survey-workspace.js');

const sections = [['first', 'second'], ['third', 'fourth'], ['fifth', 'sixth']];
function fixture(values = {}) {
    const state = surveyWorkspace(sections);
    const focused = [], reported = [], listeners = new Map();
    window.addEventListener = (name, callback) => listeners.set(name, callback);
    window.removeEventListener = (name, callback) => { if(listeners.get(name) === callback) listeners.delete(name); };
    const form = {values: {...values}, submissions: 0, valid: true, checkValidity() {return this.valid;}, reportValidity() {reported.push('form');}, querySelector(selector) {return {focus() {focused.push(selector);}, reportValidity() {reported.push(selector);}};}};
    state.$refs = {form, feedback: {value: 'Saved draft'}};
    state.$nextTick = callback => Promise.resolve().then(() => callback?.());
    state.init();
    return {state, form, focused, reported, listeners};
}
const complete = Object.fromEntries(sections.flat().map(field => [field, '4']));

test('progress counts valid ratings and navigation keeps all answers and feedback', async () => {
    const {state, form} = fixture({first: '4', second: '6', third: '2'});
    assert.equal(state.answered, 2); assert.equal(state.feedbackLength, 11);
    state.go(2); await Promise.resolve(); state.go(0);
    assert.equal(form.values.first, '4'); assert.equal(state.$refs.feedback.value, 'Saved draft');
});
test('continue validates the current section and focuses its first missing rating', async () => {
    const {state, focused} = fixture({first: '3'});
    await state.next(); assert.equal(state.step, 0); assert.ok(state.error); assert.ok(focused.includes('input[name="second"]'));
    state.$refs.form.values.second = '5'; await state.next(); assert.equal(state.step, 1); assert.equal(state.error, '');
});
test('final submission opens the section with missing answers instead of submitting a hidden invalid form', async () => {
    const {state, form, focused} = fixture({first: '2', second: '3', fifth: '4', sixth: '4'});
    state.step = 2; let prevented = false;
    await state.submit({preventDefault() {prevented = true;}});
    assert.equal(prevented, true); assert.equal(state.step, 1); assert.equal(form.submissions, 0); assert.equal(state.submitting, false);
    assert.ok(focused.includes('input[name="third"]'));
});
test('complete native POST is submitted once even with two rapid clicks', async () => {
    const {state, form} = fixture(complete);
    const event = {preventDefault() {}};
    const pending = state.submit(event); await state.submit(event); await pending;
    assert.equal(form.submissions, 1); assert.equal(state.submitting, true);
});
test('feedback constraints and browser history return release the submit guard', async () => {
    const {state, form, listeners, reported} = fixture(complete);
    form.valid = false; await state.submit({preventDefault() {}});
    assert.equal(form.submissions, 0); assert.equal(state.submitting, false); assert.ok(reported.includes('form'));
    form.valid = true; await state.submit({preventDefault() {}}); listeners.get('pageshow')();
    assert.equal(state.submitting, false); state.destroy(); assert.equal(listeners.size, 0);
});
