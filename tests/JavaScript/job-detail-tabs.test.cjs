const test = require('node:test');
const assert = require('node:assert/strict');

async function setup(reduced = false) {
    const { jobDetailTabs } = await import('../../resources/js/job-detail-tabs.js');
    const calls = [];
    let finishExit;
    const animation = (name) => ({
        finished: name === 'exit' ? new Promise(resolve => { finishExit = resolve; }) : Promise.resolve(),
        cancel() { calls.push(['cancel', name]); },
    });
    const component = jobDetailTabs({ matchMedia: () => ({ matches: reduced }) });
    component.$nextTick = async () => { calls.push(['visible', component.tab]); };
    component.$refs = { panels: { style: {}, animate: () => { calls.push(['resize']); return animation('resize'); } } };
    for (const [key, height] of Object.entries({ overview: 560, notes: 180, record: 360 })) {
        component.$refs[`panel_${key}`] = {
            getBoundingClientRect: () => ({ height }),
            focus: () => calls.push(['focus', key]),
            animate: (_frames, options) => { const kind = options.duration === 80 ? 'exit' : 'enter'; calls.push([kind, key]); return animation(kind); },
        };
        component.$refs[`tab_${key}`] = { focus: () => calls.push(['tab-focus', key]) };
    }
    return { component, calls, finishExit: () => finishExit() };
}

test('keeps old panel until fade ends and animates container height after switching', async () => {
    const { component, calls, finishExit } = await setup();
    const switching = component.selectTab('notes', true);
    assert.equal(component.tab, 'overview');
    assert.equal(component.$refs.panels.style.height, '560px');
    finishExit();
    await switching;
    assert.equal(component.tab, 'notes');
    assert.ok(calls.findIndex(c => c[0] === 'exit') < calls.findIndex(c => c[0] === 'visible'));
    assert.ok(calls.some(c => c[0] === 'resize'));
    assert.deepEqual(calls.at(-1), ['focus', 'notes']);
    assert.equal(component.$refs.panels.style.height, '');
    assert.equal(component.switching, false);
});

test('rapid tab requests select the latest panel without overlapping switches', async () => {
    const { component, calls, finishExit } = await setup();
    const switching = component.selectTab('notes');
    await component.selectTab('record');
    finishExit();
    await switching;
    assert.equal(component.tab, 'record');
    assert.deepEqual(calls.filter(c => c[0] === 'visible'), [['visible', 'record']]);
});

test('reduced motion switches without animations and supports keyboard navigation', async () => {
    const { component, calls } = await setup(true);
    await component.selectTab('notes');
    assert.equal(component.tab, 'notes');
    assert.equal(calls.some(c => ['exit', 'enter', 'resize'].includes(c[0])), false);
    component.keyTab({ key: 'End', preventDefault() {} });
    await new Promise(resolve => setImmediate(resolve));
    assert.equal(component.tab, 'record');
    assert.ok(calls.some(c => c[0] === 'tab-focus' && c[1] === 'record'));
});
