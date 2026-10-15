'use strict';

const dns = require('node:dns');
const http = require('node:http');
const net = require('node:net');

const DEFAULT_DOMAINS = Object.freeze([
    'linkedin.com', 'jobstreet.com', 'jobstreet.co.id', 'seek.com', 'glints.com',
    'kalibrr.com', 'dealls.com', 'usedeall.com', 'talentics.id',
]);

function ipv4Number(address) {
    return address.split('.').reduce((number, octet) => (number << 8n) | BigInt(octet), 0n);
}

function ipv6Number(address) {
    const halves = address.split('::');
    const left = halves[0] ? halves[0].split(':') : [];
    const right = halves[1] ? halves[1].split(':') : [];
    const groups = halves.length === 1 ? left : [...left, ...Array(8 - left.length - right.length).fill('0'), ...right];
    return groups.reduce((number, group) => (number << 16n) | BigInt(`0x${group}`), 0n);
}

function inRange(number, base, prefix, bits) {
    const shift = BigInt(bits - prefix);
    return number >> shift === base >> shift;
}

function isPublic(address, family) {
    if (net.isIP(address) !== family) return false;
    if (family === 4) {
        const number = ipv4Number(address);
        return ![
            ['0.0.0.0', 8], ['10.0.0.0', 8], ['100.64.0.0', 10], ['127.0.0.0', 8],
            ['169.254.0.0', 16], ['172.16.0.0', 12], ['192.0.0.0', 24], ['192.0.2.0', 24],
            ['192.88.99.0', 24], ['192.168.0.0', 16], ['198.18.0.0', 15],
            ['198.51.100.0', 24], ['203.0.113.0', 24], ['224.0.0.0', 4], ['240.0.0.0', 4],
        ].some(([base, prefix]) => inRange(number, ipv4Number(base), prefix, 32));
    }
    // Restrict IPv6 to ordinary global unicast; this also excludes mapped IPv4.
    if (family !== 6 || address.includes('.') || address.includes('%')) return false;
    const number = ipv6Number(address);
    return inRange(number, ipv6Number('2000::'), 3, 128) && ![
        ['2001::', 23], ['2001:db8::', 32], ['2002::', 16], ['3fff::', 20],
    ].some(([base, prefix]) => inRange(number, ipv6Number(base), prefix, 128));
}

async function validateUrl(raw, { lookup = dns.promises.lookup, navigation = false, allowedDomains = DEFAULT_DOMAINS } = {}) {
    const reject = () => { throw new Error('Destination is not allowed.'); };
    if (typeof raw !== 'string' || raw.length > 2048 || /[\x00-\x20\x7f\\]/.test(raw) || /%(?:0[0-9a-f]|1[0-9a-f]|7f)/i.test(raw)) reject();
    const match = /^(https?):\/\/([^/?#]+)(?:[/?#]|$)/i.exec(raw);
    if (!match) reject();
    const scheme = match[1].toLowerCase();
    const authority = match[2];
    const hostMatch = /^([a-z0-9.-]+)(?::(80|443))?$/i.exec(authority);
    if (!hostMatch) reject();
    const host = hostMatch[1].toLowerCase();
    if (host.length > 253 || host.split('.').some(label => label.length > 63 || !/^[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/.test(label))) reject();
    if (hostMatch[2] && hostMatch[2] !== (scheme === 'https' ? '443' : '80')) reject();
    const url = new URL(raw);
    if (url.hostname !== host || net.isIP(host) || url.username || url.password) reject();
    if (navigation && !allowedDomains.some(domain => host === domain || host.endsWith(`.${domain}`))) reject();
    const addresses = await lookup(host, { all: true, verbatim: true });
    if (!Array.isArray(addresses) || addresses.length === 0 || addresses.some(record => !record || typeof record.address !== 'string' || !isPublic(record.address, record.family))) reject();
    return { url, address: { address: addresses[0].address, family: addresses[0].family } };
}

function forwardingHeaders(headers) {
    const excluded = new Set([
        'connection', 'proxy-connection', 'proxy-authorization', 'proxy-authenticate',
        'keep-alive', 'te', 'trailer', 'transfer-encoding', 'upgrade',
        ...(headers.connection || '').toLowerCase().split(',').map(value => value.trim()),
    ]);
    return Object.fromEntries(Object.entries(headers).filter(([key]) => !excluded.has(key.toLowerCase())));
}

async function createEgressProxy({
    lookup = dns.promises.lookup, connect = net.connect, request = http.request,
    maxSockets = 32, maxBytes = 20 * 1024 * 1024,
    connectTimeoutMs = 5000, socketTimeoutMs = 15000,
} = {}) {
    for (const value of [maxSockets, maxBytes, connectTimeoutMs, socketTimeoutMs]) {
        if (!Number.isSafeInteger(value) || value <= 0) throw new Error('Invalid proxy limit.');
    }
    const sockets = new Set();
    let bytes = 0;
    let closed = false;
    let exhausted = false;
    let closing;

    function destroyAll() {
        for (const socket of sockets) socket.destroy();
    }

    function track(socket) {
        if (closed || exhausted || sockets.size >= maxSockets) {
            socket.destroy();
            return false;
        }
        sockets.add(socket);
        socket.on('error', () => {});
        socket.once('close', () => sockets.delete(socket));
        socket.setTimeout(socketTimeoutMs, () => socket.destroy());
        // Count both sides conservatively, including HTTP framing and tunnel bytes.
        socket.on('data', chunk => {
            bytes += chunk.length;
            if (bytes > maxBytes) {
                exhausted = true;
                destroyAll();
            }
        });
        return true;
    }

    const server = http.createServer(async (req, res) => {
        let upstream;
        const fail = () => {
            if (res.headersSent) res.destroy();
            else res.writeHead(403, { connection: 'close' }).end('Destination is not allowed.');
        };
        if (!['GET', 'HEAD', 'POST', 'OPTIONS'].includes(req.method)) return fail();
        try {
            const { url, address } = await validateUrl(req.url, { lookup });
            if (url.protocol !== 'http:' || closed || exhausted || req.destroyed) return fail();
            const headers = forwardingHeaders(req.headers);
            headers.host = url.host;
            headers.connection = 'close';
            upstream = request({
                protocol: 'http:', hostname: url.hostname, port: 80,
                method: req.method, path: `${url.pathname}${url.search}`, headers,
                agent: false,
                lookup: (_host, options, callback) => {
                    if (typeof options === 'function') { callback = options; options = {}; }
                    if (options && options.all) callback(null, [address]);
                    else callback(null, address.address, address.family);
                },
            }, response => {
                res.writeHead(response.statusCode, forwardingHeaders(response.headers));
                response.on('error', () => res.destroy());
                response.pipe(res);
            });
            const timer = setTimeout(() => upstream.destroy(new Error('Connection timed out.')), connectTimeoutMs);
            timer.unref();
            upstream.once('socket', socket => {
                if (!track(socket)) return;
                socket.once('connect', () => clearTimeout(timer));
            });
            upstream.once('close', () => clearTimeout(timer));
            upstream.on('error', fail);
            req.on('aborted', () => upstream.destroy());
            res.once('close', () => upstream.destroy());
            req.pipe(upstream);
        } catch {
            upstream?.destroy();
            fail();
        }
    });
    server.on('connection', track);
    server.on('upgrade', (_req, socket) => socket.end('HTTP/1.1 403 Forbidden\r\nConnection: close\r\n\r\n'));
    server.on('clientError', (_error, socket) => socket.destroy());
    server.on('connect', async (req, client, head) => {
        let upstream;
        const fail = () => {
            upstream?.destroy();
            if (!client.destroyed) client.end('HTTP/1.1 403 Forbidden\r\nConnection: close\r\n\r\n');
        };
        try {
            if (!/^[a-z0-9.-]+:443$/i.test(req.url)) return fail();
            const { address } = await validateUrl(`https://${req.url}/`, { lookup });
            if (closed || exhausted || client.destroyed) return fail();
            upstream = connect({ host: address.address, port: 443, family: address.family });
            if (!track(upstream)) return fail();
            const timer = setTimeout(fail, connectTimeoutMs);
            timer.unref();
            upstream.once('close', () => { clearTimeout(timer); client.destroy(); });
            upstream.once('error', fail);
            client.once('close', () => upstream.destroy());
            upstream.once('connect', () => {
                clearTimeout(timer);
                if (client.destroyed || closed || exhausted) return upstream.destroy();
                client.write('HTTP/1.1 200 Connection Established\r\n\r\n');
                if (head.length) upstream.write(head);
                client.pipe(upstream);
                upstream.pipe(client);
            });
        } catch {
            fail();
        }
    });
    await new Promise((resolve, reject) => {
        server.once('error', reject);
        server.listen(0, '127.0.0.1', resolve);
    });
    return {
        url: `http://127.0.0.1:${server.address().port}`,
        close() {
            if (!closing) {
                closed = true;
                destroyAll();
                closing = new Promise((resolve, reject) => server.close(error => error ? reject(error) : resolve()));
            }
            return closing;
        },
    };
}

module.exports = { DEFAULT_DOMAINS, validateUrl, createEgressProxy };
