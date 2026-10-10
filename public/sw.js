const CACHE_NAME = 'trakerja-public-v2';
const PUBLIC_ASSETS = ['/offline.html', '/images/icon.png'];
let cacheGeneration = 0;

async function purgeCaches(keepCurrent = false) {
  const names = await caches.keys();
  await Promise.all(names.filter(name => name.startsWith('trakerja-') && (!keepCurrent || name !== CACHE_NAME)).map(name => caches.delete(name)));
}

async function cachePublicAssets(generation = cacheGeneration) {
  const cache = await caches.open(CACHE_NAME);
  await Promise.all(PUBLIC_ASSETS.map(async path => {
    const url = new URL(path, self.location.origin);
    try {
      const response = await fetch(url.href, { credentials: 'omit', cache: 'reload' });
      if (generation === cacheGeneration && safeToCache(response, url)) {
        await cache.put(path, response.clone());
      }
    } catch {
        // A missing public fallback must never prevent the private cache purge.
    }
  }));
}

self.addEventListener('install', event => {
  event.waitUntil(cachePublicAssets());
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(purgeCaches(true).then(() => self.clients.claim()));
});

self.addEventListener('message', event => {
  if (event.data?.type === 'PURGE_TRAKERJA_CACHES') {
    const generation = ++cacheGeneration;
    event.waitUntil(purgeCaches().then(() => cachePublicAssets(generation)));
  }
});

function isPublicAsset(url) {
  return PUBLIC_ASSETS.includes(url.pathname)
    || /^\/build\/assets\/[\w.-]+[.-][A-Za-z0-9_-]{8,}\.(css|js|woff2?|png|jpe?g|svg|webp|ico)$/.test(url.pathname);
}

function safeToCache(response, url) {
  const type = (response.headers.get('Content-Type') || '').split(';')[0].trim().toLowerCase();
  const extension = url.pathname.split('.').pop();
  const allowedTypes = {
    html: ['text/html'], css: ['text/css'], js: ['text/javascript', 'application/javascript'],
    png: ['image/png'], jpg: ['image/jpeg'], jpeg: ['image/jpeg'], svg: ['image/svg+xml'],
    webp: ['image/webp'], ico: ['image/x-icon', 'image/vnd.microsoft.icon'],
    woff: ['font/woff', 'application/font-woff'], woff2: ['font/woff2']
  };
  return response.ok && response.type === 'basic' && !response.redirected
    && !/(?:private|no-store)/i.test(response.headers.get('Cache-Control') || '')
    && (allowedTypes[extension] || []).includes(type);
}

function offlineError() {
  return new Response('Network connection lost.', { status: 503, headers: { 'Content-Type': 'text/plain' } });
}

self.addEventListener('fetch', event => {
  const request = event.request;
  const url = new URL(request.url);
  if (request.method !== 'GET' || url.origin !== self.location.origin) return;

  if (isPublicAsset(url)) {
    const generation = cacheGeneration;
    event.respondWith((async () => {
      const cache = await caches.open(CACHE_NAME);
      try {
        const response = await fetch(request);
        if (generation === cacheGeneration && safeToCache(response, url)) await cache.put(request, response.clone());
        return response;
      } catch {
        return (await cache.match(request)) || offlineError();
      }
    })());
    return;
  }

  if (request.mode === 'navigate') {
    event.respondWith(fetch(request).catch(async () => {
      const cache = await caches.open(CACHE_NAME);
      return (await cache.match('/offline.html')) || offlineError();
    }));
  }
});
