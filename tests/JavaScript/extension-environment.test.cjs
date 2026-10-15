const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const os = require('node:os');
const path = require('node:path');
const vm = require('node:vm');
const { execFileSync } = require('node:child_process');

function readZip(filename) {
    return JSON.parse(execFileSync('python3', ['-c', `
import json,sys,zipfile
with zipfile.ZipFile(sys.argv[1]) as z:
    names=['manifest.json','popup.html','popup.js','config.js']
    print(json.dumps({n:z.read('chrome-extension/'+n).decode() for n in names if 'chrome-extension/'+n in z.namelist()}))
`, filename], { encoding: 'utf8' }));
}

async function popup(files, logout = false) {
    const nodes = new Map(), requests = [], storage = {};
    let initialize;
    const node = id => {
        if (!nodes.has(id)) nodes.set(id, {
            value: '', style: {}, handlers: {}, classList: { add() {}, remove() {} },
            addEventListener(name, handler) { this.handlers[name] = handler; },
        });
        return nodes.get(id);
    };
    const context = vm.createContext({
        document: { getElementById: node, addEventListener: (name, handler) => { initialize = handler; } },
        window: {}, URL, AbortSignal, setTimeout() {}, alert() {},
        chrome: {
            storage: { local: {
                get: async () => storage,
                set: async data => Object.assign(storage, data),
                remove: async () => {},
            } },
            tabs: { query: (options, callback) => callback([{ id: 1, url: 'https://www.linkedin.com/jobs/view/123' }]) },
            scripting: { executeScript: (options, callback) => callback([]) },
            runtime: {},
        },
        fetch: async (url, options) => {
            requests.push({ url, options });
            return { ok: true, json: async () => ({ token: 'synthetic-token', user: { name: 'Test User' } }) };
        },
    });
    const scripts = [...files['popup.html'].matchAll(/<script src="([^"]+)"/g)].map(match => match[1]);
    for (const name of scripts.filter(name => ['config.js', 'popup.js'].includes(name))) {
        vm.runInContext(files[name], context, { filename: name });
    }
    await initialize();
    node('email').value = 'user@example.test';
    node('password').value = 'synthetic-password';
    await node('login-form').handlers.submit({ preventDefault() {} });
    node('job-province').value = 'DKI Jakarta';
    node('job-city').value = 'Jakarta Selatan';
    node('job-position').value = 'Engineer';
    node('job-company').value = 'Example';
    await node('btn-save').handlers.click();
    if (logout) await node('logout-btn').handlers.click();
    return requests;
}

test('unpacked source sends login and save requests only to production HTTPS API', async () => {
    const files = Object.fromEntries(['manifest.json', 'popup.html', 'popup.js', 'config.js']
        .filter(name => fs.existsSync('chrome-extension/' + name))
        .map(name => [name, fs.readFileSync('chrome-extension/' + name, 'utf8')]));
    const requests = await popup(files);
    assert.deepEqual(requests.map(request => request.url), [
        'https://trakerja.com/api/extension/login', 'https://trakerja.com/api/extension/jobs',
    ]);
    assert.equal(requests[1].options.headers.Authorization, 'Bearer synthetic-token');
    assert.ok(!JSON.parse(files['manifest.json']).host_permissions.some(origin => origin.includes('localhost')));
});

test('public download contains the production API and no localhost host permission', async () => {
    const files = readZip('public/downloads/trakerja-extension.zip');
    const requests = await popup(files);
    assert.deepEqual(requests.map(request => request.url), [
        'https://trakerja.com/api/extension/login', 'https://trakerja.com/api/extension/jobs',
    ]);
    assert.ok(!JSON.parse(files['manifest.json']).host_permissions.some(origin => origin.includes('localhost')));
});

test('development packaging changes API and permissions without changing production source', async () => {
    const temporary = fs.mkdtempSync(path.join(os.tmpdir(), 'trakerja-extension-'));
    const source = fs.readFileSync('chrome-extension/manifest.json', 'utf8');
    try {
        const filename = path.join(temporary, 'development.zip');
        execFileSync('python3', ['scripts/build_extension.py', '--environment', 'development', '--output', filename]);
        const files = readZip(filename);
        assert.deepEqual((await popup(files)).map(request => request.url), [
            'http://localhost:8000/api/extension/login', 'http://localhost:8000/api/extension/jobs',
        ]);
        const manifest = JSON.parse(files['manifest.json']);
        assert.ok(manifest.host_permissions.includes('http://localhost:8000/*'));
        assert.ok(!manifest.host_permissions.includes('https://trakerja.com/*'));
        assert.equal(fs.readFileSync('chrome-extension/manifest.json', 'utf8'), source);
    } finally {
        fs.rmSync(temporary, { recursive: true, force: true });
    }
});

test('production packaging is reproducible and includes only the intended source assets', () => {
    const temporary = fs.mkdtempSync(path.join(os.tmpdir(), 'trakerja-extension-'));
    try {
        const filename = path.join(temporary, 'production.zip');
        execFileSync('python3', ['scripts/build_extension.py', '--output', filename]);
        assert.deepEqual(fs.readFileSync(filename), fs.readFileSync('public/downloads/trakerja-extension.zip'));
        const entries = JSON.parse(execFileSync('python3', ['-c', `
import hashlib,json,sys,zipfile
with zipfile.ZipFile(sys.argv[1]) as z:
    print(json.dumps({n:hashlib.sha256(z.read(n)).hexdigest() for n in z.namelist()}))
`, filename], { encoding: 'utf8' }));
        const expected = ['manifest.json', 'config.js', 'popup.html', 'popup.js', 'content.js',
            'locations.js', 'locations.json', 'style.css', 'icons/icon.png'];
        assert.deepEqual(Object.keys(entries).sort(), expected.map(name => 'chrome-extension/' + name).sort());
        const { createHash } = require('node:crypto');
        for (const name of expected) {
            assert.equal(entries['chrome-extension/' + name], createHash('sha256')
                .update(fs.readFileSync('chrome-extension/' + name)).digest('hex'));
        }
    } finally {
        fs.rmSync(temporary, { recursive: true, force: true });
    }
});

test('development builds cannot overwrite the public download or default to publishing', () => {
    const filename = 'public/downloads/trakerja-extension.zip';
    const before = fs.readFileSync(filename);
    for (const args of [[], ['--output', filename]]) {
        assert.throws(() => execFileSync('python3', ['scripts/build_extension.py', '--environment', 'development', ...args],
            { stdio: 'pipe' }), error => error.status === 2);
        assert.deepEqual(fs.readFileSync(filename), before);
    }
});


test('extension logout revokes the bearer token on the server', async () => {
    const files = Object.fromEntries(['popup.html', 'popup.js', 'config.js'].map(name => [name, fs.readFileSync('chrome-extension/' + name, 'utf8')]));
    const requests = await popup(files, true);
    assert.equal(requests.at(-1).url, 'https://trakerja.com/api/extension/logout');
    assert.equal(requests.at(-1).options.method, 'POST');
    assert.equal(requests.at(-1).options.headers.Authorization, 'Bearer synthetic-token');
});
