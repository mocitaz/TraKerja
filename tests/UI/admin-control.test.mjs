import test from 'node:test';
import assert from 'node:assert/strict';
import { formatControlAnswer } from '../../resources/js/admin-control.js';

test('analysis renders Indonesian sections, emphasis and numbered next steps', () => {
    const result = formatControlAnswer('Pengamatan\n\nAda **5 pembayaran** dan *support backlog*.\n\nLangkah berikutnya\n1. Periksa status.\n2. Cocokkan sumber.\n\nKeterbatasan\nData agregat.');
    assert.match(result, /<h3>Pengamatan<\/h3>/);
    assert.match(result, /<strong>5 pembayaran<\/strong>/);
    assert.match(result, /<em>support backlog<\/em>/);
    assert.match(result, /<ol><li>Periksa status.<\/li><li>Cocokkan sumber.<\/li><\/ol>/);
    assert.match(result, /<h3>Keterbatasan<\/h3><p>Data agregat.<\/p>/);
});
test('provider HTML and event handlers remain inert escaped text', () => {
    const result = formatControlAnswer('## <img src=x onerror=alert(1)>\n**<script>alert(1)</script>**\n- [click](javascript:alert(1))');
    assert.doesNotMatch(result, /<(img|script|a)\b/);
    assert.match(result, /&lt;img/);
    assert.match(result, /&lt;script/);
});
test('plain text, CRLF, empty answers and markdown headings render cleanly', () => {
    assert.equal(formatControlAnswer(null), '');
    assert.equal(formatControlAnswer('**Interpretasi**\r\n\r\nBaris satu\r\nbaris dua'), '<h3>Interpretasi</h3><p>Baris satu baris dua</p>');
});
