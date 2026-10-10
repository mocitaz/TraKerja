import { test } from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import fs from 'node:fs';

function worker() {
  const handlers = new Map(), stores = new Map();
  const key = request => typeof request === 'string' ? new URL(request, 'https://trakerja.test').href : request.url;
  const caches = {
    async open(name) {
      if (!stores.has(name)) stores.set(name, new Map());
      const store = stores.get(name);
      return {
        async put(request, response) { store.set(key(request), response); },
        async match(request) { return store.get(key(request)); },
        async addAll(paths) { for (const path of paths) store.set(key(path), new Response('public offline asset')); }
      };
    },
    async keys() { return [...stores.keys()]; },
    async delete(name) { return stores.delete(name); },
    async match(request) { for (const store of stores.values()) if (store.has(key(request))) return store.get(key(request)); }
  };
  let network = async request => {
    const path = typeof request === 'string' ? new URL(request, 'https://trakerja.test').pathname : new URL(request.url).pathname;
    return new Response(path === '/offline.html' ? 'public offline asset' : 'response', { headers: { 'Content-Type': path === '/images/icon.png' ? 'image/png' : 'text/html' } });
  };
  vm.runInNewContext(fs.readFileSync('public/sw.js', 'utf8'), {
    self: { location: { origin: 'https://trakerja.test' }, clients: { claim() {} }, skipWaiting() {}, addEventListener: (name, fn) => handlers.set(name, fn) },
    caches, fetch: async request => { const response = await network(request); if (response instanceof Response) Object.defineProperty(response, 'type', { value: 'basic' }); return response; }, Response, Headers, URL
  });
  return {
    stores, caches, setNetwork(fn) { network = fn; },
    async event(name, extra = {}) {
      const pending = []; let response;
      handlers.get(name)?.({ ...extra, waitUntil(promise) { pending.push(promise); }, respondWith(promise) { response = promise; } });
      const result = response ? await response : undefined;
      await Promise.all(pending);
      return result;
    },
    async fetch(path, mode = 'navigate') {
      const request = { url: new URL(path, 'https://trakerja.test').href, method: 'GET', mode, headers: new Headers({ accept: mode === 'navigate' ? 'text/html' : '*/*' }) };
      return this.event('fetch', { request });
    }
  };
}

test('private pages, root, API, payments and auth never enter caches', async () => {
  const sw = worker(); await sw.event('install');
  for (const path of ['/', '/tracker', '/dashboard', '/api/user', '/payment/history', '/auth/google/callback']) await sw.fetch(path);
  const keys = [...sw.stores.values()].flatMap(store => [...store.keys()]);
  assert.deepEqual(keys.sort(), ['https://trakerja.test/images/icon.png', 'https://trakerja.test/offline.html'].sort());
});

test('only public asset responses are cached; redirected and HTML assets are refused', async () => {
  const sw = worker(); await sw.event('install');
  sw.setNetwork(async () => new Response('stylesheet', { headers: { 'Content-Type': 'text/css' } }));
  await sw.fetch('/build/assets/app-aB12cd34.css', 'cors');
  assert.equal(await (await sw.caches.match('/build/assets/app-aB12cd34.css')).text(), 'stylesheet');
  sw.setNetwork(async () => new Response('login html', { headers: { 'Content-Type': 'text/html' } }));
  await sw.fetch('/build/assets/auth-aB12cd34.js', 'cors');
  assert.equal(await sw.caches.match('/build/assets/auth-aB12cd34.js'), undefined);
  sw.setNetwork(async () => ({ ok: true, status: 200, redirected: true, type: 'basic', headers: new Headers({ 'Content-Type': 'text/css' }), clone: () => new Response('redirect') }));
  await sw.fetch('/build/assets/redirect-aB12cd34.css', 'cors');
  assert.equal(await sw.caches.match('/build/assets/redirect-aB12cd34.css'), undefined);
});

test('activation and account purge remove only TraKerja caches and rebuild safe offline assets', async () => {
  const sw = worker();
  await (await sw.caches.open('trakerja-cache-v1')).put('/tracker', new Response('previous account secret'));
  await (await sw.caches.open('other-app')).put('/thing', new Response('unrelated'));
  await sw.event('install'); await sw.event('activate');
  assert.equal(sw.stores.has('trakerja-cache-v1'), false);
  assert.equal(sw.stores.has('other-app'), true);
  await (await sw.caches.open('trakerja-old-private')).put('/tracker', new Response('secret'));
  await sw.event('message', { data: { type: 'PURGE_TRAKERJA_CACHES' } });
  assert.equal(sw.stores.has('trakerja-old-private'), false);
  assert.equal(sw.stores.has('other-app'), true);
});

test('offline navigation ignores even old cached private responses', async () => {
  const sw = worker(); await sw.event('install');
  await (await sw.caches.open('trakerja-cache-v1')).put('/tracker', new Response('account secret'));
  sw.setNetwork(async () => { throw new Error('offline'); });
  assert.equal(await (await sw.fetch('/tracker')).text(), 'public offline asset');
  assert.equal(await sw.fetch('/api/user', 'cors'), undefined);
});

test('non-GET, cross-origin, and non-navigation API requests stay on the network', async () => {
  const sw = worker();
  for (const request of [
    { url: 'https://trakerja.test/payment/webhook', method: 'POST', mode: 'cors' },
    { url: 'https://other.test/asset.css', method: 'GET', mode: 'cors' },
    { url: 'https://trakerja.test/api/user', method: 'GET', mode: 'cors' }
  ]) assert.equal(await sw.event('fetch', { request }), undefined);
  assert.equal(sw.stores.size, 0);
});

test('assets marked private or no-store are never saved', async () => {
  const sw = worker();
  for (const directive of ['private, max-age=60', 'no-store']) {
    sw.setNetwork(async () => new Response('private script', { headers: { 'Content-Type': 'text/javascript', 'Cache-Control': directive } }));
    await sw.fetch('/build/assets/private-aB12cd34.js', 'cors');
    assert.equal(await sw.caches.match('/build/assets/private-aB12cd34.js'), undefined);
  }
});


test('current dot-separated build outputs are cached', async () => {
  const sw = worker();
  for (const [path, type] of [['/build/assets/styles.DdGJi_8A.css', 'text/css'], ['/build/assets/entry.Bj43h_rG.js', 'text/javascript']]) {
    sw.setNetwork(async () => new Response('build content', { headers: { 'Content-Type': type } }));
    await sw.fetch(path, 'cors');
    assert.equal(await (await sw.caches.match(path))?.text(), 'build content');
  }
});

test('install refuses private, no-store, redirected and incorrect MIME preseed responses', async () => {
  for (const kind of ['private', 'no-store', 'redirected', 'mime']) {
    const sw = worker();
    sw.setNetwork(async request => {
      const response = new Response('login secret', { headers: {
        'Content-Type': kind === 'mime' ? 'application/json' : (String(request).includes('icon') ? 'image/png' : 'text/html'),
        'Cache-Control': ['private', 'no-store'].includes(kind) ? kind : 'public'
      } });
      if (kind === 'redirected') Object.defineProperty(response, 'redirected', { value: true });
      return response;
    });
    await sw.event('install');
    assert.equal(await sw.caches.match('/offline.html'), undefined);
    assert.equal(await sw.caches.match('/images/icon.png'), undefined);
  }
});

test('auth purge invalidates pending asset fetch writes', async () => {
  const sw = worker();
  let finish;
  sw.setNetwork(() => new Promise(resolve => { finish = resolve; }));
  const pending = sw.fetch('/build/assets/entry-Bj43h_rG.js', 'cors');
  await new Promise(resolve => setTimeout(resolve, 0));
  assert.equal(typeof finish, 'function');
  sw.setNetwork(async () => new Response('safe public asset', { headers: { 'Content-Type': 'image/png' } }));
  await sw.event('message', { data: { type: 'PURGE_TRAKERJA_CACHES' } });
  finish(new Response('previous account script', { headers: { 'Content-Type': 'text/javascript' } }));
  await pending;
  assert.equal(await sw.caches.match('/build/assets/entry-Bj43h_rG.js'), undefined);
});


test('failed public preseed fetch cannot retain private caches during auth purge', async () => {
  const sw = worker();
  await (await sw.caches.open('trakerja-cache-v1')).put('/tracker', new Response('secret'));
  sw.setNetwork(async () => { throw new Error('offline'); });
  await sw.event('message', { data: { type: 'PURGE_TRAKERJA_CACHES' } });
  assert.equal(sw.stores.has('trakerja-cache-v1'), false);
  assert.equal(await sw.caches.match('/tracker'), undefined);
  assert.equal((await sw.fetch('/tracker')).status, 503);
});
