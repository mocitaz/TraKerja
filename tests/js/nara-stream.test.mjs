// Run: node --test tests/js
// Nara's "thinking" dots must show once (request sent -> first word) and never flicker back on or stay stuck
// when a reply ends, whatever the server does with the connection.
import assert from 'node:assert/strict';
import test from 'node:test';
import { naraChat } from '../../resources/js/nara-chat.js';

const enc = new TextEncoder();
const sse = value => 'data: ' + (typeof value === 'string' ? value : JSON.stringify(value)) + '\n\n';
const chunk = text => sse({ chunk: text });

function stream(events, { closes = true } = {}) {
    let index = 0;
    return new ReadableStream({
        async pull(controller) {
            if (index < events.length) { await new Promise(resolve => setTimeout(resolve, 5)); controller.enqueue(enc.encode(events[index++])); }
            else if (closes) controller.close();
            else await new Promise(() => {}); // the server never closes the connection
        },
    });
}

async function send(events, options) {
    const dots = [];
    const environment = {
        fetch: async () => ({ ok: true, status: 200, body: stream(events, options) }),
        // the 30 s stall watchdog fires after 100 ms here
        setTimeout: (callback, ms) => setTimeout(callback, ms >= 30000 ? 100 : ms),
        clearTimeout, document: { body: { style: {} } }, addEventListener() {}, removeEventListener() {},
    };
    const base = naraChat({ streamUrl: '/stream', chatUrl: '/chat', csrfToken: 't', routes: {} }, environment);
    const chat = new Proxy(base, {
        set(target, key, value) { if (key === 'waiting' && target[key] !== value) dots.push(value); target[key] = value; return true; }, // changes only
        get(target, key) { const value = target[key]; return typeof value === 'function' ? value.bind(chat) : value; },
    });
    Object.assign(chat, { $nextTick: callback => callback(), $refs: {}, $watch() {}, historyLoading: false, input: 'saya butuh cinta' });
    const outcome = await Promise.race([chat.send().then(() => 'done'), new Promise(resolve => setTimeout(() => resolve('hung'), 1500))]);

    return { chat, dots, outcome };
}

test('a normal reply shows the dots once, then hides them for good', async () => {
    const { chat, dots, outcome } = await send([chunk('Fokus '), chunk('TraKerja'), sse({ final: 'Fokus TraKerja', id: 5 }), 'data: [DONE]\n\n']);
    assert.equal(outcome, 'done');
    assert.deepEqual(dots, [true, false]);
    assert.equal(chat.loading, false);
    assert.equal(chat.messages.at(-1).content, 'Fokus TraKerja');
    assert.equal(chat.messages.at(-1).streaming, false);
    assert.equal(chat.messages.at(-1).id, 5);
});

test('a reply that arrives whole (no chunks) works', async () => {
    const { chat, dots, outcome } = await send([sse({ final: 'Hai', id: 8 }), 'data: [DONE]\n\n']);
    assert.equal(outcome, 'done');
    assert.deepEqual(dots, [true, false]);
    assert.equal(chat.messages.at(-1).content, 'Hai');
});

test('the reply is complete at "final" even if the server keeps the connection open', async () => {
    for (const events of [[chunk('Halo'), sse({ final: 'Halo', id: 6 }), 'data: [DONE]\n\n'], [chunk('Halo'), sse({ final: 'Halo', id: 7 })]]) {
        const { chat, dots, outcome } = await send(events, { closes: false });
        assert.equal(outcome, 'done');
        assert.deepEqual(dots, [true, false]);
        assert.equal(chat.loading, false);
        assert.equal(chat.messages.at(-1).streaming, false);
    }
});

test('a stalled stream is cut off, the half reply is removed and the chat is usable again', async () => {
    const { chat, dots, outcome } = await send([chunk('Halo')], { closes: false });
    assert.equal(outcome, 'done');
    assert.deepEqual(dots, [true, false]);
    assert.equal(chat.loading, false);
    assert.equal(chat.messages.length, 1, 'only the question is left');
    assert.ok(chat.error && chat.failedText, 'the user can retry');
});
