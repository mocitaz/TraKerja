'use strict';

const { timingSafeEqual } = require('node:crypto');
const { validateUrl, createEgressProxy } = require('./egress.cjs');

function deadline(promise, milliseconds) {
    let timer;
    return Promise.race([
        promise,
        new Promise((_, reject) => {
            timer = setTimeout(() => {
                const error = new Error('Render deadline exceeded.');
                error.code = 'RENDER_TIMEOUT';
                reject(error);
            }, milliseconds);
        }),
    ]).finally(() => clearTimeout(timer));
}

function createScrapeHandler({
    token,
    launch = options => require('puppeteer').launch(options),
    validate = validateUrl,
    proxyFactory = createEgressProxy,
    maxConcurrency = 2,
    timeoutMs = 35000,
} = {}) {
    if (typeof token !== 'string' || token.length < 32) throw new Error('SCRAPER_SERVICE_TOKEN must contain at least 32 characters.');
    if (!Number.isSafeInteger(maxConcurrency) || maxConcurrency < 1 || maxConcurrency > 8) throw new Error('Invalid scraper concurrency limit.');
    if (!Number.isSafeInteger(timeoutMs) || timeoutMs < 1) throw new Error('Invalid scraper deadline.');
    const expected = Buffer.from('Bearer ' + token);
    let active = 0;

    return async (req, res) => {
        const authorization = req.get('authorization');
        if (typeof authorization !== 'string') return res.status(401).json({ success: false, error: 'Unauthorized.' });
        const provided = Buffer.from(authorization);
        if (provided.length !== expected.length || !timingSafeEqual(expected, provided)) {
            return res.status(401).json({ success: false, error: 'Unauthorized.' });
        }
        if (!req.body || typeof req.body.url !== 'string' || Object.hasOwn(req.body, 'proxy')) {
            return res.status(400).json({ success: false, error: 'A job URL is required; client proxies are not supported.' });
        }
        if (active >= maxConcurrency) {
            return res.status(429).set('Retry-After', '2').json({ success: false, error: 'Renderer is busy.' });
        }

        active++;
        let browser;
        let proxy;
        let disconnected = false;
        let cleaning;
        const cleanup = () => {
            if (!cleaning) cleaning = Promise.allSettled([
                browser ? deadline(Promise.resolve().then(() => browser.close()), 3000).catch(() => browser.process?.()?.kill('SIGKILL')) : Promise.resolve(),
                proxy ? Promise.resolve().then(() => proxy.close()) : Promise.resolve(),
            ]);
            return cleaning;
        };
        const onClose = () => {
            disconnected = true;
            if (browser && proxy) void cleanup();
        };
        res.once('close', onClose);

        try {
            try {
                await deadline(validate(req.body.url, { navigation: true }), 5000);
            } catch {
                return res.status(422).json({ success: false, error: 'Destination is not allowed or could not be validated.' });
            }
            if (disconnected) return;
            proxy = await proxyFactory();
            if (disconnected) return;
            browser = await launch({
                headless: 'new',
                timeout: 10000,
                args: [
                    '--proxy-server=' + proxy.url,
                    '--proxy-bypass-list=<-loopback>',
                    '--host-resolver-rules=MAP * ~NOTFOUND',
                    '--disable-quic',
                    '--force-webrtc-ip-handling-policy=disable_non_proxied_udp',
                    '--disable-background-networking',
                    '--disable-extensions',
                    '--disable-sync',
                    '--no-first-run',
                ],
            });
            if (disconnected) return;
            const render = async () => {
                const page = await browser.newPage();
                browser.on('targetcreated', async target => {
                    try {
                        if (target.type() === 'page') {
                            const otherPage = await target.page();
                            if (otherPage && otherPage !== page) await otherPage.close();
                        }
                    } catch { /* A closing browser may already have removed the target. */ }
                });
                await page.setBypassServiceWorker(true);
                await page.setCacheEnabled(false);
                await page.setRequestInterception(true);
                page.on('request', async request => {
                    try {
                        if (request.redirectChain().length > 3) throw new Error('Redirect limit exceeded.');
                        await deadline(validate(request.url(), { navigation: request.isNavigationRequest() }), 5000);
                        if (!request.isInterceptResolutionHandled?.()) await request.continue();
                    } catch {
                        try {
                            if (!request.isInterceptResolutionHandled?.()) await request.abort('blockedbyclient');
                        } catch { /* Request may have ended during cleanup. */ }
                    }
                });
                await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
                await page.setViewport({ width: 1280, height: 800 });
                await page.setExtraHTTPHeaders({ 'Accept-Language': 'en-US,en;q=0.9,id;q=0.8' });
                const response = await page.goto(req.body.url, { waitUntil: 'networkidle2', timeout: 30000 });
                if (!response || response.status() >= 400) throw new Error('Source page unavailable.');
                const html = await page.content();
                if (Buffer.byteLength(html) > 2 * 1024 * 1024) throw new Error('Rendered page exceeds limit.');
                return { success: true, title: await page.title(), html };
            };
            const result = await deadline(render(), timeoutMs);
            if (!disconnected) res.json(result);
        } catch (error) {
            if (!disconnected && !res.headersSent) {
                res.status(error.code === 'RENDER_TIMEOUT' ? 504 : 502)
                    .json({ success: false, error: 'Could not safely render the source page.' });
            }
        } finally {
            res.removeListener('close', onClose);
            await cleanup();
            active--;
        }
    };
}

function startServer() {
    const express = require('express');
    const handler = createScrapeHandler({
        token: process.env.SCRAPER_SERVICE_TOKEN,
        maxConcurrency: Number(process.env.SCRAPER_MAX_CONCURRENCY || 2),
    });
    const app = express();
    app.disable('x-powered-by');
    app.use(express.json({ limit: '8kb', strict: true }));
    app.post('/scrape', handler);
    app.use((error, req, res, next) => {
        if (res.headersSent) return next(error);
        res.status(error.type === 'entity.too.large' ? 413 : 400)
            .json({ success: false, error: 'Invalid request body.' });
    });
    return app.listen(Number(process.env.PORT || 3000), '127.0.0.1', () => {
        console.log('Authenticated scraper listening on loopback.');
    });
}

if (require.main === module) startServer();
module.exports = { createScrapeHandler, startServer };
