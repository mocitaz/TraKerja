import test from 'node:test';
import assert from 'node:assert/strict';

globalThis.window = {};
globalThis.document = { addEventListener() {} };
const { coverLetterDetail } = await import('../../resources/js/cover-letter-detail.js');

function letter() {
    const state = coverLetterDetail('Cover_Letter_Example.txt');
    state.$refs = { content: { textContent: 'Dear team,\n\nA & B <example>\nThank you.' } };
    return state;
}

test('copy awaits completion and ignores duplicate clicks', async () => {
    let finish, calls = 0, copiedText;
    Object.defineProperty(globalThis, 'navigator', { configurable: true, value: { clipboard: { writeText(text) { calls++; copiedText = text; return new Promise(resolve => { finish = resolve; }); } } } });
    const state = letter();
    const pending = state.copy();
    assert.equal(state.copying, true);
    assert.equal(state.copied, false);
    await state.copy();
    assert.equal(calls, 1);
    finish();
    await pending;
    assert.equal(copiedText, state.$refs.content.textContent);
    assert.equal(state.copied, true);
    assert.equal(state.copying, false);
});

test('clipboard refusal gives manual recovery and permits a retry', async () => {
    Object.defineProperty(globalThis, 'navigator', { configurable: true, value: { clipboard: { async writeText() { throw new Error('denied'); } } } });
    const state = letter();
    await state.copy();
    assert.equal(state.copyFailed, true);
    assert.equal(state.copied, false);
    assert.equal(state.copying, false);
    navigator.clipboard.writeText = async () => {};
    await state.copy();
    assert.equal(state.copied, true);
    assert.equal(state.copyFailed, false);
});

test('TXT export preserves the exact plain text and filename', async () => {
    let blob, clicked = false, revoked;
    const anchor = { click() { clicked = true; } };
    const originalCreate = URL.createObjectURL, originalRevoke = URL.revokeObjectURL;
    URL.createObjectURL = value => { blob = value; return 'blob:letter'; };
    URL.revokeObjectURL = value => { revoked = value; };
    document.createElement = name => { assert.equal(name, 'a'); return anchor; };
    try {
        const state = letter();
        state.download();
        assert.equal(await blob.text(), state.$refs.content.textContent);
        assert.equal(blob.type, 'text/plain;charset=utf-8');
        assert.equal(anchor.download, 'Cover_Letter_Example.txt');
        assert.equal(clicked, true);
        assert.equal(revoked, 'blob:letter');
    } finally { URL.createObjectURL = originalCreate; URL.revokeObjectURL = originalRevoke; }
});
