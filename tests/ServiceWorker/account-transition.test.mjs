import { test } from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import fs from 'node:fs';

async function runRegistration(account, previous) {
  const listeners = new Map(), messages = [], deleted = [], storage = new Map();
  if (previous) storage.set('trakerja-sw-account', previous);
  let finish;
  const registered = new Promise(resolve => { finish = resolve; });
  const source = fs.readFileSync('resources/views/components/service-worker-registration.blade.php', 'utf8').replace(/@json\([^\n]+\)/, JSON.stringify(account));
  class HTMLFormElement { constructor(action) { this.action = action; } }
  const browserWindow = { caches: {}, addEventListener() {} };
  vm.runInNewContext(source, {
    navigator: { serviceWorker: { controller: { postMessage(message) { messages.push(message.type); } }, async register() { return { async update() { finish(); } }; } } },
    window: browserWindow,
    caches: { async keys() { return ['trakerja-cache-v1', 'trakerja-public-v2', 'unrelated']; }, async delete(name) { deleted.push(name); } },
    document: { readyState: 'complete', addEventListener(name, handler) { listeners.set(name, handler); } },
    localStorage: { getItem: name => storage.get(name), setItem: (name, value) => storage.set(name, value), removeItem: name => storage.delete(name) },
    console, URL, location: { href: 'https://trakerja.test/tracker' }, HTMLFormElement
  });
  await registered;
  return { deleted, messages, storage, prepareLogout: () => browserWindow.prepareTraKerjaLogout(), async logout() { listeners.get('submit')({ target: new HTMLFormElement('https://trakerja.test/logout') }); await new Promise(resolve => setTimeout(resolve, 0)); } };
}

test('account change and guest transition purge only own caches', async () => {
  for (const account of ['user:2', 'guest']) {
    const result = await runRegistration(account, 'user:1');
    assert.deepEqual(result.deleted, ['trakerja-cache-v1', 'trakerja-public-v2']);
    assert.deepEqual(result.messages, ['PURGE_TRAKERJA_CACHES']);
    assert.equal(result.storage.get('trakerja-sw-account'), account);
  }
});

test('same account preserves static cache until logout purges it', async () => {
  const result = await runRegistration('user:1', 'user:1');
  assert.deepEqual(result.deleted, []);
  await result.logout();
  assert.deepEqual(result.deleted, ['trakerja-cache-v1', 'trakerja-public-v2']);
  assert.equal(result.storage.has('trakerja-sw-account'), false);
});


test('programmatic logout cleanup exports the actual cache purge and clears account marker', async () => {
  const result = await runRegistration('user:1', 'user:1');
  await result.prepareLogout();
  assert.deepEqual(result.deleted, ['trakerja-cache-v1', 'trakerja-public-v2']);
  assert.deepEqual(result.messages, ['PURGE_TRAKERJA_CACHES']);
  assert.equal(result.storage.has('trakerja-sw-account'), false);
});
