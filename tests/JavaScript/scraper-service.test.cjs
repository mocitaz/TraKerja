const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const { EventEmitter } = require('node:events');

const token = 'synthetic-service-token-'.repeat(2);

function fixture(options = {}) {
    let registered;
    const calls = { launches: [], browserClosed: 0, proxyClosed: 0, validated: [] };
    const handlers = {};
    const page = {
        setUserAgent: async () => {}, setViewport: async () => {}, setExtraHTTPHeaders: async () => {},
        setBypassServiceWorker: async () => {}, setCacheEnabled: async () => {}, setRequestInterception: async () => {},
        on: (name, fn) => { handlers[name] = fn; },
        goto: options.goto || (async () => ({ status: () => 200 })),
        content: async () => '<html>Public job</html>', title: async () => 'Public job',
    };
    const browser = { newPage: async () => page, close: async () => { calls.browserClosed++; }, on() {} };
    const launch = async config => { calls.launches.push(config); return browser; };
    const validate = async (url, settings) => {
        calls.validated.push({ url, settings });
        if (!url.startsWith('https://linkedin.com/')) throw new Error('Forbidden destination');
    };
    const proxyFactory = async () => ({ url: 'http://127.0.0.1:43210', close: async () => { calls.proxyClosed++; } });
    const express = () => ({ disable() {}, use() {}, post: (path, fn) => { registered = fn; },
        listen: (port, host) => { calls.binding = { port, host }; } });
    express.json = () => () => {};
    const module = { exports: {} };
    const requireStub = name => {
        if (name === 'express') return express;
        if (name === 'puppeteer') return { launch };
        if (name === './egress.cjs') return { validateUrl: validate, createEgressProxy: proxyFactory };
        return require(name);
    };
    vm.runInNewContext(fs.readFileSync('scraper-service/server.js', 'utf8'), {
        module, require: requireStub, process: { env: { SCRAPER_SERVICE_TOKEN: token } },
        console: { log() {}, error() {} }, URL, Buffer, setTimeout, clearTimeout,
    });
    const handler = module.exports.createScrapeHandler
        ? module.exports.createScrapeHandler({ token, launch, validate, proxyFactory, ...options })
        : registered;
    function request(body = { url: 'https://linkedin.com/jobs/view/1' }, authorization = 'Bearer ' + token) {
        const req = Object.assign(new EventEmitter(), { body, get: () => authorization });
        const res = Object.assign(new EventEmitter(), {
            statusCode: 200, headers: {}, headersSent: false,
            status(code) { this.statusCode = code; return this; },
            set(name, value) { this.headers[name] = value; return this; },
            json(data) { this.data = data; this.headersSent = true; return this; },
        });
        return { req, res, run: () => handler(req, res) };
    }
    return { calls, handlers, page, request, startServer: module.exports.startServer };
}

test('service listener binds only to loopback and missing authentication configuration fails closed', () => {
    const app = fixture();
    assert.equal(typeof app.startServer, 'function');
    app.startServer();
    assert.equal(app.calls.binding.host, '127.0.0.1');
    assert.throws(() => fixture({ token: '' }), /SCRAPER_SERVICE_TOKEN/);
});

test('missing or incorrect service authentication never starts a browser or resolves a target', async () => {
    for (const authorization of ['', 'Bearer wrong']) {
        const app = fixture();
        const call = app.request(undefined, authorization);
        await call.run();
        assert.equal(call.res.statusCode, 401);
        assert.equal(app.calls.launches.length, 0);
        assert.equal(app.calls.validated.length, 0);
    }
});

test('client supplied proxy and forbidden URLs are rejected before browser launch', async () => {
    const app = fixture();
    for (const body of [{ url: 'https://linkedin.com/jobs', proxy: 'http://127.0.0.1' },
        { url: 'http://127.0.0.1/admin' }, { url: ['https://linkedin.com/jobs'] }]) {
        const call = app.request(body);
        await call.run();
        assert.ok([400, 422].includes(call.res.statusCode));
    }
    assert.equal(app.calls.launches.length, 0);
});

test('successful render uses guarded browser egress and cleans up browser and proxy', async () => {
    const app = fixture();
    const call = app.request();
    await call.run();
    assert.equal(call.res.statusCode, 200);
    assert.equal(call.res.data.html, '<html>Public job</html>');
    assert.ok(app.calls.launches[0].args.includes('--proxy-server=http://127.0.0.1:43210'));
    assert.ok(app.calls.launches[0].args.includes('--proxy-bypass-list=<-loopback>'));
    assert.ok(!app.calls.launches[0].args.includes('--no-sandbox'));
    assert.equal(app.calls.browserClosed, 1);
    assert.equal(app.calls.proxyClosed, 1);
});

test('busy service rejects extra work and timeout releases resources and the slot', async () => {
    let started;
    const ready = new Promise(resolve => { started = resolve; });
    const app = fixture({ maxConcurrency: 1, timeoutMs: 30, goto: () => { started(); return new Promise(() => {}); } });
    const first = app.request();
    const running = first.run();
    await ready;
    const second = app.request();
    await second.run();
    assert.equal(second.res.statusCode, 429);
    assert.equal(app.calls.launches.length, 1);
    await running;
    assert.equal(first.res.statusCode, 504);
    assert.equal(app.calls.browserClosed, 1);
    assert.equal(app.calls.proxyClosed, 1);
    app.page.goto = async () => ({ status: () => 200 });
    const third = app.request();
    await third.run();
    assert.equal(third.res.statusCode, 200);
});

test('interception rejects file navigation and private redirect requests', async () => {
    const app = fixture();
    await app.request().run();
    assert.equal(typeof app.handlers.request, 'function');
    for (const url of ['file:///etc/passwd', 'http://127.0.0.1/internal']) {
        let aborted = false;
        await app.handlers.request({ url: () => url, isNavigationRequest: () => true, redirectChain: () => [],
            continue: async () => { assert.fail('unsafe request continued'); }, abort: async () => { aborted = true; } });
        assert.equal(aborted, true);
    }
    let aborted = false;
    await app.handlers.request({ url: () => 'https://linkedin.com/jobs', isNavigationRequest: () => true,
        redirectChain: () => [1, 2, 3, 4], continue: async () => { assert.fail('redirect limit bypassed'); },
        abort: async () => { aborted = true; } });
    assert.equal(aborted, true);
});

test('launch failure closes the proxy and does not retain a concurrency slot', async () => {
    const app = fixture({ launch: async () => { throw new Error('Synthetic launch failure'); }, maxConcurrency: 1 });
    for (let i = 0; i < 2; i++) {
        const call = app.request();
        await call.run();
        assert.equal(call.res.statusCode, 502);
    }
    assert.equal(app.calls.proxyClosed, 2);
});

test('client disconnect closes browser and proxy without replying to the closed connection', async () => {
    let started;
    const ready = new Promise(resolve => { started = resolve; });
    let finish;
    const app = fixture({ goto: () => { started(); return new Promise(resolve => { finish = resolve; }); } });
    const call = app.request();
    const running = call.run();
    await ready;
    call.res.emit('close');
    finish({ status: () => 200 });
    await running;
    assert.equal(call.res.headersSent, false);
    assert.equal(app.calls.browserClosed, 1);
    assert.equal(app.calls.proxyClosed, 1);
});
