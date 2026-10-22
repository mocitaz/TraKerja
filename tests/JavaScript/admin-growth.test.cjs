const test = require('node:test');
const assert = require('node:assert/strict');

async function chart(counts = Array(60).fill(0)) {
    const { adminGrowth } = await import('../../resources/js/admin-growth.js');
    return adminGrowth({ counts, labels: Array.from({length:60}, (_, i)=>`Day ${i}`), locale:'en' });
}

test('period totals compare equal adjacent windows without counting older registrations', async () => {
    const counts=Array(60).fill(0);counts[0]=90;counts[40]=3;counts[57]=6;
    const graph=await chart(counts);
    assert.equal(graph.total,6);assert.equal(graph.previousTotal,3);assert.equal(graph.change,100);
    graph.setPeriod(7);
    assert.equal(graph.total,6);assert.equal(graph.previousTotal,0);assert.equal(graph.change,null);
    graph.setPeriod(30);
    assert.equal(graph.total,9);assert.equal(graph.previousTotal,90);assert.equal(graph.change,-90);
});
test('zero registrations produce a valid flat chart and no misleading percentage', async () => {
    const graph=await chart();
    assert.equal(graph.total,0);assert.equal(graph.change,null);assert.equal(graph.average,'0');
    assert.equal(graph.max,2);assert.ok(!graph.line.includes('NaN'));assert.ok(!graph.area.includes('Infinity'));
    assert.ok(graph.points.every(point=>point.y===212));
});
test('period changes reset selection and reject unsupported periods', async () => {
    const graph=await chart();graph.tooltipOpen=true;graph.setPeriod(30);
    assert.equal(graph.selected,29);assert.equal(graph.tooltipOpen,false);
    graph.setPeriod(500);assert.equal(graph.period,30);
});
test('pointer and keyboard navigation clamp to actual date bounds', async () => {
    const graph=await chart();
    const target={getBoundingClientRect:()=>({left:100,width:624})};
    graph.inspect({currentTarget:target,clientX:-100});assert.equal(graph.selected,0);
    graph.step(-1);assert.equal(graph.selected,0);
    graph.inspect({currentTarget:target,clientX:1000});assert.equal(graph.selected,13);
    graph.step(1);assert.equal(graph.selected,13);
    graph.step(-1);assert.equal(graph.selected,12);
});
test('curve interpolates through actual daily values and keeps its controls within endpoints', async () => {
    const counts=Array(60).fill(0);counts[56]=5;
    const graph=await chart();graph.counts=counts;
    assert.equal(graph.peak,5);assert.equal(graph.dates[graph.peakIndex],'Day 56');
    assert.equal(graph.points.at(0).x,16);assert.equal(graph.points.at(-1).x,608);
    assert.ok(graph.line.includes(`,${graph.y(5)}`));
    assert.deepEqual(graph.ticks,[6,3,0]);assert.equal(graph.axisIndices.length,5);
});
