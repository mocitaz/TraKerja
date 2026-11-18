// Run: node --test tests/js/*.mjs
import assert from 'node:assert/strict';
import test from 'node:test';
import { adminNara, formatAdminText, parseSuggestions } from '../../resources/js/admin-nara.js';

const html = raw => formatAdminText(raw).filter(s => s.type === 'html').map(s => s.html).join('');

test('markdown: tables, lists, bold and admin links render; everything else is escaped', () => {
    const out = html('**Pendapatan** naik.\n\n| Hari | Rp |\n|---|---|\n| Sen | 10 |\n\n- satu\n- dua\n\nBuka [Payments](/admin/payments) atau [x](https://evil.test) <script>alert(1)</script>');
    assert.match(out, /<strong>Pendapatan<\/strong>/);
    assert.match(out, /<table>.*<th>Hari<\/th>.*<td>10<\/td>/s);
    assert.match(out, /<ul><li>satu<\/li><li>dua<\/li><\/ul>/);
    assert.match(out, /<a href="\/admin\/payments"/);
    assert.doesNotMatch(out, /href="https:\/\/evil/, 'only /admin links become links');
    assert.doesNotMatch(out, /<script>/);
    assert.match(out, /&lt;script&gt;/);
});

test('approval cards come only from [adminaction:ID] tags and keep their position; chips and half tags are hidden', () => {
    const segments = formatAdminText('Saya siapkan ini.\n\n[adminaction:7]\n\nCatatan.\n[suggest:Setujui dulu|Lihat tiket]');
    assert.deepEqual(segments.map(s => s.type), ['html', 'action', 'html']);
    assert.equal(segments[1].id, 7);
    assert.doesNotMatch(JSON.stringify(segments), /suggest/);
    assert.deepEqual(formatAdminText('Teks [adminaction:'), [{ type: 'html', html: '<p>Teks</p>' }], 'a half-written tag is not shown while streaming');
    assert.deepEqual(parseSuggestions('x [suggest:Ringkas hari ini|Tiket pending|Ringkas hari ini|Cek sumber loker]'), ['Ringkas hari ini', 'Tiket pending', 'Cek sumber loker']);
});

function environment(events) {
    const enc = new TextEncoder();
    const calls = [];
    return {
        calls,
        document: { querySelector: () => ({ content: 'csrf' }) },
        AbortController,
        fetch: async (url, options) => {
            calls.push({ url, options });
            if (url.includes('/stream')) {
                return { ok: true, status: 200, body: new ReadableStream({ start(c) { events.forEach(e => c.enqueue(enc.encode(e))); c.close(); } }) };
            }
            return { ok: true, status: 200, json: async () => ({ action: { id: 3, status: 'executed', summary: 'x' }, message: 'Done.' }) };
        },
    };
}
const sse = value => 'data: ' + (typeof value === 'string' ? value : JSON.stringify(value)) + '\n\n';
const make = env => Object.assign(adminNara({ streamUrl: '/nara/stream', approveUrl: '/a/__ID__', rejectUrl: '/r/__ID__', historyUrl: '/h', page: 'payments' }, env), { $nextTick: f => f(), $refs: {} });

test('a reply streams in, the final text replaces it and the cards arrive with it', async () => {
    const env = environment([sse({ status: 'Looking at the data…' }), sse({ chunk: 'Halo ' }), sse({ chunk: 'dunia' }), sse({ final: 'Halo dunia\n\n[adminaction:3]', id: 9, actions: { 3: { id: 3, status: 'pending', summary: 'Balas tiket' } } }), sse('[DONE]')]);
    const nara = make(env);
    await nara.send('tes');
    assert.equal(nara.busy, false);
    assert.equal(nara.waiting, false);
    assert.equal(nara.messages.at(-1).content, 'Halo dunia\n\n[adminaction:3]');
    assert.equal(nara.actions[3].status, 'pending');
    assert.equal(JSON.parse(env.calls[0].options.body).page, 'payments');
    assert.equal(env.calls[0].options.headers['X-CSRF-TOKEN'], 'csrf');
});

test('approving a card updates it from the server answer; a stream with no reply removes the empty bubble', async () => {
    const env = environment([sse('[DONE]')]);
    const nara = make(env);
    nara.actions = { 3: { id: 3, status: 'pending', summary: 'x' } };
    await nara.decide(3, true);
    assert.equal(env.calls[0].url, '/a/3');
    assert.equal(nara.actions[3].status, 'executed');
    assert.equal(nara.actions[3].message, 'Done.');
    await nara.send('kosong');
    assert.deepEqual(nara.messages.map(m => m.role), ['user'], 'no empty reply bubble is left behind');
});
