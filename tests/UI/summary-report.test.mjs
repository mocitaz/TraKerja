import test from 'node:test';
import assert from 'node:assert/strict';
import { summaryRecordTable } from '../../resources/js/summary-report.js';

const rows = Array.from({ length: 15 }, (_, index) => ({ name: index === 0 ? 'Élan & Research' : `Company ${index}`, total: index + 1, accepted: index % 4, interviews: index % 3 }));

test('ranked data preserves all records across pages and does not mutate the dataset', () => {
    const state = summaryRecordTable(rows);
    assert.equal(state.pageCount, 3);
    assert.equal(state.visibleRows[0].total, 15);
    assert.equal(state.firstRow, 1);
    state.page = 3;
    assert.equal(state.visibleRows.length, 3);
    assert.equal(state.firstRow, 13);
    assert.equal(state.lastRow, 15);
    assert.equal(rows[0].name, 'Élan & Research');
});

test('search and sorting reset pagination; unmatched searches have honest empty results', () => {
    const state = summaryRecordTable(rows);
    const watchers = {};
    state.$watch = (key, callback) => { watchers[key] = callback; };
    state.init();
    state.page = 3;
    state.query = 'ÉLAN';
    watchers.query();
    assert.equal(state.page, 1);
    assert.equal(state.visibleRows[0].name, 'Élan & Research');
    state.query = 'No matching company';
    assert.deepEqual(state.visibleRows, []);
    assert.equal(state.firstRow, 0);
    assert.equal(state.lastRow, 0);
    assert.equal(state.pageCount, 1);
    state.query = '';
    state.sort = 'accepted';
    state.page = 2;
    watchers.sort();
    assert.equal(state.page, 1);
    assert.equal(state.visibleRows[0].accepted, 3);
});

test('company initials distinguish names that share legal prefixes and retain Unicode letters', () => {
    const state = summaryRecordTable([]);
    assert.equal(state.initials('PT Bumi Solusi'), 'BS');
    assert.equal(state.initials('PT. Karya Digital'), 'KD');
    assert.equal(state.initials('CV Élan Riset'), 'ÉR');
    assert.equal(state.initials('BCA'), 'BC');
});
