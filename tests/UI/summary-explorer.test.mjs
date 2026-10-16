import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';

const source = readFileSync(new URL('../../resources/views/summary/partials/explorer.blade.php', import.meta.url), 'utf8');
const binding = source.match(/class="explorer-ring"\s+:class="([^"]+)"/)?.[1];

test('donut class binding is valid and follows hover, selection, and reset', () => {
    assert.ok(binding, 'The production donut must have its interactive class binding');
    for (const index of [0, 1, 7]) {
        const expression = binding.replaceAll("{{ $item['index'] }}", String(index));
        const evaluate = new Function('hovered', 'selected', `return (${expression});`);
        assert.deepEqual(evaluate(null, null), { 'is-selected': false, 'is-muted': false });
        assert.deepEqual(evaluate(null, index), { 'is-selected': true, 'is-muted': false });
        assert.deepEqual(evaluate(index, index + 1), { 'is-selected': true, 'is-muted': false });
        assert.deepEqual(evaluate(index + 1, index), { 'is-selected': false, 'is-muted': true });
    }
});
