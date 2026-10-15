'use strict';

const test = require('node:test');
const assert = require('node:assert/strict');
const http = require('node:http');
const net = require('node:net');
const { validateUrl, createEgressProxy, DEFAULT_DOMAINS } = require('../../scraper-service/egress.cjs');

const publicLookup = async () => [{ address: '93.184.216.34', family: 4 }];

for (const raw of [
    'file:///etc/passwd', 'ftp://linkedin.com/a', 'http://user:pass@linkedin.com',
    'http://linkedin.com:81', 'https://linkedin.com:80', 'https://linkedin.com:0443',
    'http://linkedin.com:', 'http://linkedin.com.', 'http://127.1', 'http://2130706433',
    'http://0x7f000001', 'http://[::1]', 'https://linkedin.com\\@evil.com',
    'https://linkedin.com/\nfoo', 'https://linkedin.com/%0a', 'https://%6cinkedin.com',
    'https://-bad.linkedin.com', 'https://bad_.linkedin.com', 'https:linkedin.com',
]) {
    test(`reject malformed or forbidden URL ${JSON.stringify(raw)}`, async () => {
        await assert.rejects(validateUrl(raw, { lookup: publicLookup, navigation: true }));
    });
}

for (const ip of [
    '0.0.0.1', '10.0.0.1', '100.64.0.1', '127.0.0.1', '169.254.1.2',
    '172.16.0.1', '192.0.0.9', '192.0.2.1', '192.88.99.1', '192.168.1.1',
    '198.18.0.1', '198.51.100.1', '203.0.113.1', '224.0.0.1', '240.0.0.1',
    '::', '::1', '::ffff:93.184.216.34', 'fc00::1', 'fe80::1', 'ff02::1',
    '2001::1', '2001:db8::1', '2002:0808:0808::1', '3fff::1', '4000::1',
]) {
    test(`reject any nonpublic DNS answer ${ip}`, async () => {
        await assert.rejects(validateUrl('https://linkedin.com', {
            lookup: async () => [...await publicLookup(), { address: ip, family: ip.includes(':') ? 6 : 4 }],
        }));
    });
}

test('navigation allowlist uses domain boundaries, resources allow public DNS hosts', async () => {
    assert.equal(DEFAULT_DOMAINS.length, 9);
    const calls = [];
    const lookup = async (...args) => { calls.push(args); return publicLookup(); };
    const result = await validateUrl('https://jobs.linkedin.com/path', { lookup, navigation: true });
    assert.equal(result.url.hostname, 'jobs.linkedin.com');
    assert.deepEqual(result.address, { address: '93.184.216.34', family: 4 });
    assert.deepEqual(calls[0], ['jobs.linkedin.com', { all: true, verbatim: true }]);
    for (const host of ['linkedin.com.evil.com', 'evillinkedin.com', 'example.com']) {
        await assert.rejects(validateUrl(`https://${host}`, { lookup, navigation: true }));
    }
    await validateUrl('https://cdn.example.com/a', { lookup });
    await validateUrl('https://linkedin.com', { lookup: async () => [{ address: '2606:4700:4700::1111', family: 6 }] });
    await assert.rejects(validateUrl('https://linkedin.com', { lookup: async () => [] }));
    await assert.rejects(validateUrl('https://linkedin.com', { lookup: async () => [{ address: '8.8.8.8', family: 6 }] }));
});

function throughProxy(proxy, target, options = {}) {
    return new Promise((resolve, reject) => {
        const req = http.request(proxy.url, { path: target, ...options }, res => {
            const chunks = [];
            res.on('data', chunk => chunks.push(chunk));
            res.on('end', () => resolve({ status: res.statusCode, headers: res.headers, body: Buffer.concat(chunks).toString() }));
        });
        req.on('error', reject);
        req.end(options.body);
    });
}

async function fixture(t, handler) {
    const server = http.createServer(handler);
    await new Promise(resolve => server.listen(0, '127.0.0.1', resolve));
    t.after(() => new Promise(resolve => server.close(resolve)));
    return server.address().port;
}

test('HTTP proxy validates each request, pins lookup, preserves host and does not follow redirects', async t => {
    const seen = [];
    const port = await fixture(t, (req, res) => {
        seen.push({ host: req.headers.host, path: req.url, proxyAuth: req.headers['proxy-authorization'] });
        res.writeHead(302, { location: 'http://127.0.0.1/private' });
        res.end('redirect');
    });
    const pins = [];
    let resolutions = 0;
    const proxy = await createEgressProxy({
        lookup: async () => { resolutions++; return publicLookup(); },
        request: (options, callback) => {
            options.lookup(options.hostname, {}, (error, address, family) => pins.push({ error, address, family }));
            return http.request({ ...options, hostname: '127.0.0.1', port, lookup: undefined }, callback);
        },
    });
    t.after(() => proxy.close());
    assert.match(proxy.url, /^http:\/\/127\.0\.0\.1:\d+$/);
    const result = await throughProxy(proxy, 'http://cdn.example.com/a?q=1', { headers: { host: 'attacker.invalid', 'proxy-authorization': 'secret' } });
    assert.equal(result.status, 302);
    assert.equal(result.body, 'redirect');
    assert.deepEqual(seen, [{ host: 'cdn.example.com', path: '/a?q=1', proxyAuth: undefined }]);
    assert.equal(pins[0].address, '93.184.216.34');
    assert.equal(resolutions, 1);
    assert.equal((await throughProxy(proxy, 'http://127.0.0.1/')).status, 403);
    assert.equal((await throughProxy(proxy, 'http://cdn.example.com/', { method: 'TRACE' })).status, 403);
    assert.equal(seen.length, 1);
});

test('CONNECT rejects forbidden targets before transport and pins public targets', async t => {
    const local = net.createServer(socket => socket.pipe(socket));
    await new Promise(resolve => local.listen(0, '127.0.0.1', resolve));
    t.after(() => new Promise(resolve => local.close(resolve)));
    const destinations = [];
    const proxy = await createEgressProxy({ lookup: publicLookup, connect: options => {
        destinations.push(options);
        return net.connect({ host: '127.0.0.1', port: local.address().port });
    } });
    t.after(() => proxy.close());
    async function connect(target) {
        const socket = net.connect(new URL(proxy.url).port, '127.0.0.1');
        await new Promise(resolve => socket.once('connect', resolve));
        socket.write(`CONNECT ${target} HTTP/1.1\r\nHost: ${target}\r\n\r\n`);
        const response = await new Promise(resolve => socket.once('data', resolve));
        return { socket, response: response.toString() };
    }
    for (const target of ['127.0.0.1:443', 'example.com:80', 'user@example.com:443', 'example.com:0443']) {
        const result = await connect(target);
        assert.match(result.response, /^HTTP\/1\.1 403/);
        result.socket.destroy();
    }
    assert.equal(destinations.length, 0);
    const result = await connect('cdn.example.com:443');
    assert.match(result.response, /^HTTP\/1\.1 200/);
    assert.equal(destinations[0].host, '93.184.216.34');
    assert.equal(destinations[0].port, 443);
    result.socket.write('tunnel-payload');
    assert.equal((await new Promise(resolve => result.socket.once('data', resolve))).toString(), 'tunnel-payload');
    await proxy.close();
    assert.equal(result.socket.destroyed || await new Promise(resolve => result.socket.once('close', () => resolve(true))), true);
});

test('DNS is revalidated on every forward request, including a rebinding answer', async t => {
    const port = await fixture(t, (_req, res) => res.end('public'));
    let resolutions = 0;
    let transports = 0;
    const proxy = await createEgressProxy({
        lookup: async () => ++resolutions === 1 ? publicLookup() : [{ address: '127.0.0.1', family: 4 }],
        request: (options, callback) => {
            transports++;
            return http.request({ ...options, hostname: '127.0.0.1', port, lookup: undefined }, callback);
        },
    });
    t.after(() => proxy.close());
    assert.equal((await throughProxy(proxy, 'http://cdn.example.com/')).status, 200);
    assert.equal((await throughProxy(proxy, 'http://cdn.example.com/')).status, 403);
    assert.equal(resolutions, 2);
    assert.equal(transports, 1);
});

test('HTTP websocket upgrades cannot reach an upstream transport', async t => {
    let transports = 0;
    const proxy = await createEgressProxy({ lookup: publicLookup, request: () => { transports++; throw new Error('Unexpected transport'); } });
    t.after(() => proxy.close());
    const response = await throughProxy(proxy, 'http://cdn.example.com/', { headers: { connection: 'upgrade', upgrade: 'websocket' } });
    assert.equal(response.status, 403);
    assert.equal(transports, 0);
});

test('concurrent accepted sockets and idle lifetime are bounded', async t => {
    const proxy = await createEgressProxy({ maxSockets: 1, socketTimeoutMs: 80 });
    t.after(() => proxy.close());
    const first = net.connect(new URL(proxy.url).port, '127.0.0.1');
    first.on('error', () => {});
    await new Promise(resolve => first.once('connect', resolve));
    const firstClosed = new Promise(resolve => first.once('close', resolve));
    const second = net.connect(new URL(proxy.url).port, '127.0.0.1');
    second.on('error', () => {});
    await new Promise(resolve => second.once('close', resolve));
    assert.equal(second.destroyed, true);
    await firstClosed;
    assert.equal(first.destroyed, true);
});

test('byte exhaustion destroys connections and disables later transports', async t => {
    let transports = 0;
    const proxy = await createEgressProxy({
        lookup: publicLookup, maxBytes: 64,
        request: () => { transports++; throw new Error('Unexpected transport'); },
    });
    t.after(() => proxy.close());
    const socket = net.connect(new URL(proxy.url).port, '127.0.0.1');
    socket.on('error', () => {});
    await new Promise(resolve => socket.once('connect', resolve));
    const closed = new Promise(resolve => socket.once('close', resolve));
    socket.write('GET http://cdn.example.com/ HTTP/1.1\r\nHost: cdn.example.com\r\nX-Fill: ' + 'x'.repeat(100) + '\r\n\r\n');
    await closed;
    const later = net.connect(new URL(proxy.url).port, '127.0.0.1');
    later.on('error', () => {});
    await new Promise(resolve => later.once('close', resolve));
    assert.equal(transports, 0);
});

test('CONNECT establishment has a deadline and close is idempotent', async () => {
    const proxy = await createEgressProxy({ lookup: publicLookup, connectTimeoutMs: 25, connect: () => new net.Socket() });
    const socket = net.connect(new URL(proxy.url).port, '127.0.0.1');
    socket.on('error', () => {});
    socket.resume();
    await new Promise(resolve => socket.once('connect', resolve));
    const closed = new Promise(resolve => socket.once('close', resolve));
    socket.write('CONNECT cdn.example.com:443 HTTP/1.1\r\nHost: cdn.example.com:443\r\n\r\n');
    await closed;
    await Promise.all([proxy.close(), proxy.close()]);
});
