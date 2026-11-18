// Run: node --test tests/js/*.mjs
// The pieces of a Nara reply the browser renders itself: suggestion chips, email drafts, the comparison table.
import assert from 'node:assert/strict';
import test from 'node:test';
import { decodeComparison, formatNaraText, parseSuggestions, splitDraft } from '../../resources/js/nara-chat.js';

test('suggestion chips: at most three, deduplicated, never from a half-written tag', () => {
    assert.deepEqual(parseSuggestions('Isi\n[suggest:Tulis cover letter|Latih interview|Tulis cover letter|Cari remote|Simpan]'), ['Tulis cover letter', 'Latih interview', 'Cari remote']);
    assert.deepEqual(parseSuggestions('Isi [suggest:Tulis cover le'), []);
    assert.deepEqual(parseSuggestions(null), []);
});

test('a draft splits into subject and body, with or without a subject line', () => {
    assert.deepEqual(splitDraft('Subject: Follow up\n\nHalo Bu Sari,\nTerima kasih.'), { subject: 'Follow up', body: 'Halo Bu Sari,\nTerima kasih.' });
    assert.deepEqual(splitDraft('Subjek: **Lamaran**\nIsi surat'), { subject: 'Lamaran', body: 'Isi surat' });
    assert.deepEqual(splitDraft('Hanya isi'), { subject: '', body: 'Hanya isi' });
});

test('the comparison payload decodes, and garbage does not break the chat', () => {
    const data = { jobs: [{ id: 1, title: 'A' }, { id: 2, title: 'B' }], rows: [{ label: 'Gaji', values: ['1', '2'] }] };
    const encoded = Buffer.from(JSON.stringify(data)).toString('base64url');
    assert.deepEqual(decodeComparison(encoded), data);
    assert.equal(decodeComparison('%%%not-base64%%%'), null);
    assert.equal(decodeComparison(Buffer.from('{"jobs":1}').toString('base64url')), null);
});

test('a draft card and the chips render, and a half-written tag stays hidden while streaming', () => {
    const html = formatNaraText('Ini drafnya:\n[draft:email]\nSubject: Halo\n\nIsi email\n[/draft]\n[suggest:Tulis cover letter|Latih interview]');
    assert.match(html, /nara-draft/);
    assert.doesNotMatch(html, /\[suggest:/);
    const partial = formatNaraText('Ini drafnya:\n[draft:email]\nSubject: Halo\n\nIsi em');
    assert.doesNotMatch(partial, /\[draft:/);
});
