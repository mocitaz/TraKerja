const test = require('node:test');
const assert = require('node:assert/strict');

test('chart inspection clamps to valid days and keeps hover separate from selection', async () => {
    const { analyticsChart } = await import('../../resources/js/admin-analytics.js');
    const chart = analyticsChart([{label:'Day 1',applications:2,members:1},{label:'Day 2',applications:4,members:0}]);
    assert.equal(chart.current.label,'Day 2');
    chart.select(-5); assert.equal(chart.selected,0);
    chart.hovered=1; assert.equal(chart.current.applications,4); assert.equal(chart.selected,0);
    chart.hovered=null; assert.equal(chart.current.applications,2);
    chart.select(100); assert.equal(chart.selected,1);
});

test('tabs reject unknown panels and arrow navigation wraps and restores tab focus', async () => {
    const { analyticsWorkspace } = await import('../../resources/js/admin-analytics.js');
    const vm=analyticsWorkspace(); let focused;
    global.document={getElementById:id=>({focus(){focused=id;}})};
    vm.$nextTick=fn=>fn();
    vm.selectTab('unknown'); assert.equal(vm.tab,'overview');
    vm.moveTab(-1); assert.equal(vm.tab,'community'); assert.equal(focused,'analytics-tab-community');
    vm.moveTab(1); assert.equal(vm.tab,'overview');
});
