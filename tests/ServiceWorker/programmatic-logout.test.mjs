import { test } from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import fs from 'node:fs';

function logoutFlow(modal, cleanup) {
  const template = fs.readFileSync('resources/views/layouts/app.blade.php', 'utf8');
  const start = template.search(/            (?:async )?function confirmLogout\(/);
  const end = template.indexOf('            // Listen for Livewire', start);
  const submitted = [];
  let callback;
  const context = vm.createContext({
    window: { prepareTraKerjaLogout: cleanup, ...(modal ? { openConfirmModal(...args) { callback = args[3]; } } : {}) },
    document: { getElementById() { return { submit() { submitted.push('submitted'); } }; } },
    confirm: () => true, console: { error() {} }
  });
  vm.runInContext(template.slice(start, end), context);
  return { submitted, async execute() { const result = context.confirmLogout('logout-sidebar-form'); if (modal) return callback(); return result; } };
}

for (const modal of [true, false]) {
  test(`programmatic logout waits for cache cleanup with ${modal ? 'custom modal' : 'browser confirmation'}`, async () => {
    let complete;
    const cleanup = new Promise(resolve => { complete = resolve; });
    const flow = logoutFlow(modal, () => cleanup);
    const pending = flow.execute();
    assert.deepEqual(flow.submitted, []);
    complete(); await pending;
    assert.deepEqual(flow.submitted, ['submitted']);
  });

  test(`programmatic logout still submits when cleanup fails or is unavailable with ${modal ? 'custom modal' : 'browser confirmation'}`, async () => {
    for (const cleanup of [undefined, async () => { throw new Error('cache unavailable'); }]) {
      const flow = logoutFlow(modal, cleanup);
      await flow.execute();
      assert.deepEqual(flow.submitted, ['submitted']);
    }
  });
}
