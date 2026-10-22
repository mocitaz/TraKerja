const test = require('node:test');
const assert = require('node:assert/strict');

let factory;
async function workspace({ hash = '', initial = 'general', reduced = false } = {}) {
    const panels = Object.fromEntries(['general', 'integrations', 'maintenance'].map((id, index) => [id, {
        top: index * 600 + 60,
        getBoundingClientRect() { return { top: this.top }; },
        querySelector() { return { focus: () => { this.focused = true; } }; },
        scrollIntoView(options) { this.scrolled = options; },
    }]));
    const handlers = {}; let disconnected = false;
    global.document = { getElementById: id => panels[id], documentElement: { scrollHeight: 2400 } };
    global.window = {
        location: { href: 'http://localhost/admin/settings?tab=database' + hash, hash },
        scrollY: 0, innerHeight: 800,
        matchMedia: () => ({ matches: reduced }),
        history: { replaceState(a, b, url) { window.location.href = url.toString(); window.location.hash = url.hash; } },
        addEventListener(type, callback) { handlers[type] = callback; }, removeEventListener(type) { if (type === 'scroll') disconnected = true; },
    };
    await import('../../resources/js/admin-settings-workspace.js');
    factory ||= window.adminSettingsWorkspace;
    const vm = factory(initial);
    vm.$nextTick = cb => cb(); vm.init();
    return { vm, panels, observe: () => handlers.scroll(), disconnected: () => disconnected };
}

test('legacy section links resolve within the canonical page and remove the old query parameter', async () => {
    const { vm, panels } = await workspace({ initial: 'maintenance' });
    assert.equal(vm.section, 'maintenance');
    assert.equal(new URL(window.location.href).searchParams.has('tab'), false);
    assert.equal(window.location.hash, '#maintenance');
    assert.equal(panels.maintenance.focused, true);
    assert.equal(panels.maintenance.scrolled.behavior, 'instant');
});

test('navigation respects reduced motion and rejects unknown sections', async () => {
    const { vm, panels } = await workspace({ reduced: true });
    vm.navigate('integrations');
    assert.equal(vm.section, 'integrations'); assert.equal(panels.integrations.scrolled.behavior, 'instant');
    vm.navigate('unknown'); assert.equal(vm.section, 'integrations');
});

test('the last section stays selected when the document ends before its heading reaches the top', async () => {
    const { vm, panels, observe, disconnected } = await workspace();
    panels.general.top = -900; panels.integrations.top = -300; panels.maintenance.top = 300;
    window.scrollY = 1600; observe(); assert.equal(vm.section, 'maintenance');
    window.scrollY = 800; observe(); assert.equal(vm.section, 'integrations');
    vm.destroy(); assert.equal(disconnected(), true);
});
