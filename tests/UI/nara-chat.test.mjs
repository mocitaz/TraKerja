import test from 'node:test';
import assert from 'node:assert/strict';
import { naraChat, formatNaraText } from '../../resources/js/nara-chat.js';

const config = { chatUrl: '/chat', historyUrl: '/history', clearUrl: '/clear', csrfToken: 'fixture', routes: { tracker: { url: '/tracker' } } };
const response = (data, status = 200) => ({ ok: status < 400, status, json: async () => data });
function setup(fetch) {
    const environment = { fetch, setTimeout, clearTimeout, navigator: { clipboard: { writeText: async () => {} } }, document: { body: { style: { overflow: '' } } }, addEventListener() {}, removeEventListener() {} };
    const state = naraChat(config, environment);
    state.$nextTick = callback => callback?.();
    state.$refs = { composer: { style: {}, scrollHeight: 50, focus() {} }, messages: { scrollHeight: 500, clientHeight: 100, scrollTop: 0 } };
    state.historyLoading = false;
    return { state, environment };
}

test('starter prompts are editable and mode selection reaches the provider request', async () => {
    let calls = 0, sent;
    const { state } = setup(async (url, options) => { calls++; sent = options; return response({ success: true, reply: 'One question.' }); });
    state.selectMode('interview');
    state.usePrompt(state.currentMode.starters[0].prompt);
    assert.equal(calls, 0);
    await state.send();
    assert.equal(JSON.parse(sent.body).mode, 'interview');
    assert.equal(sent.headers['X-CSRF-TOKEN'], 'fixture');
    assert.equal(state.messages.length, 2);
});

test('failed responses retain a retryable message without fake assistant output or duplicate user bubbles', async () => {
    const { state, environment } = setup(async () => response({ success: false, reply: 'Unavailable' }));
    state.input = 'Prepare me'; await state.send();
    assert.equal(state.messages.length, 1);
    assert.equal(state.failedText, 'Prepare me');
    assert.match(state.error, /could not finish/);
    state.selectMode('writing');
    environment.fetch = async (url, options) => { assert.equal(JSON.parse(options.body).mode, 'career'); return response({ success: true, reply: 'Try this.' }); };
    await state.retry();
    assert.equal(state.messages.length, 2);
    assert.equal(state.failedText, '');
    assert.equal(state.error, '');
});

test('failed messages can be edited and limits and parallel submits prevent provider calls', async () => {
    let finish, calls = 0;
    const { state } = setup(async () => { calls++; return new Promise(resolve => { finish = resolve; }); });
    state.input = '😀'.repeat(1001); await state.send(); assert.equal(calls, 0);
    state.input = 'Hello'; const pending = state.send(); await state.send(); assert.equal(calls, 1);
    finish(response({}, 429)); await pending;
    assert.match(state.error, /Too many requests/);
    state.editFailed(); assert.equal(state.messages.length, 0); assert.equal(state.input, 'Hello'); assert.equal(state.failedText, '');
});

test('history failures block sending and clear failures retain history until server confirmation', async () => {
    const { state, environment } = setup(async () => response({ history: null }));
    await state.loadHistory(); assert.ok(state.historyError);
    state.input = 'Hello'; await state.send(); assert.equal(state.messages.length, 0);
    environment.fetch = async () => response({ history: [{ role: 'user', content: 'Saved' }] });
    await state.loadHistory(); assert.equal(state.historyError, ''); assert.equal(state.messages.length, 1);
    environment.fetch = async () => response({}, 500);
    await state.clearChat(); assert.equal(state.messages.length, 1); assert.ok(state.error);
    environment.fetch = async () => response({ success: true });
    await state.clearChat(); assert.equal(state.messages.length, 0); assert.equal(state.error, '');
});

test('teardown aborts requests, ignores late replies, and restores page scrolling', async () => {
    let finish, signal;
    const { state, environment } = setup(async (url, options) => { signal = options.signal; return new Promise(resolve => { finish = resolve; }); });
    state.input = 'Hello'; const pending = state.send();
    state.open = true; state.previousOverflow = 'clip'; state.$refs.drawer = { close() {} }; environment.document.body.style.overflow = 'hidden';
    state.destroy(); assert.equal(signal.aborted, true); assert.equal(environment.document.body.style.overflow, 'clip');
    finish(response({ success: true, reply: 'Late' })); await pending;
    assert.equal(state.messages.length, 1);
});

test('scrolling preserves reading position and copying reports clipboard refusal', async () => {
    const { state, environment } = setup(async () => response({}));
    state.$refs.messages.scrollTop = 40; state.trackScroll(); state.scrollToBottom(); assert.equal(state.$refs.messages.scrollTop, 40);
    state.scrollToBottom(true); assert.equal(state.$refs.messages.scrollTop, 500);
    environment.navigator.clipboard.writeText = async () => { throw new Error('denied'); };
    await state.copyMessage('Response', 0); assert.match(state.error, /Select the response/);
});

test('rich responses escape untrusted HTML, restrict actions, and never fabricate STAR scores', () => {
    const html = formatNaraText('### Next steps\n\n- **Prepare**\n- Review\n\n> One question?\n\n<script>alert(1)</script>\n\n[action:tracker|Open "tracker"|<img src=x onerror=alert(1)>]\n\n[action:unknown|Unknown|No route]', config.routes);
    assert.match(html, /<h4>Next steps<\/h4>/); assert.match(html, /<ul><li><strong>Prepare/);
    assert.ok(!html.includes('<script>')); assert.ok(!html.includes('<img'));
    assert.match(html, /href="\/tracker"/); assert.ok(!html.includes('href="#"'));
    const card = formatNaraText('[star_scorecard]\nSituation: 0/10 | Missing context\nTask: bad/10 | No score\nAction: 99/10 | Invalid\n[/star_scorecard]');
    assert.match(card, /0<small>\/10/); assert.ok(!card.includes('99<small>')); assert.ok(!card.includes('7<small>'));
    const code = formatNaraText('```html\n<img src=x>\n```'); assert.match(code, /<pre><code>&lt;img/);
});

test('assistant responses omit emoji while retaining numbers and user message content', () => {
    const html = formatNaraText('🎯 **Prioritas**\n\n✅ Kirim 3 lamaran ke Jakarta 🇮🇩.\n\n1️⃣ Tinjau CV 👩🏽‍💻');
    assert.ok(!/🎯|✅|🇮🇩|👩|🏽|💻|\u200d|\ufe0f|\u20e3/u.test(html));
    assert.match(html, /3 lamaran/);
    assert.match(html, /1 Tinjau CV/);
    const user = formatNaraText('Target saya 🎯', {}, false);
    assert.match(user, /🎯/);
});

test('mixed Markdown keeps paragraphs, lists and multiline quotes together without extra gaps', () => {
    const html = formatNaraText('### Fokus hari ini\nLakukan ini:\n1. Review CV\n2. Kirim lamaran\n   Sesuaikan dengan posisi.\n\n> Ceritakan tantangannya.\n> Jelaskan peranmu.\n\nJawab singkat\ndengan bukti nyata.');
    assert.match(html, /<ol><li>Review CV<\/li><li>Kirim lamaran<br>Sesuaikan dengan posisi\.<\/li><\/ol>/);
    assert.match(html, /<blockquote>Ceritakan tantangannya\.<br>Jelaskan peranmu\.<\/blockquote>/);
    assert.match(html, /<p>Jawab singkat dengan bukti nyata\.<\/p>/);
});
