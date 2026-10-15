# Chrome extension environments

The checked-in `chrome-extension/` directory is production-ready source. `popup.html` loads `config.js` before `popup.js`; login and job-save requests use `https://trakerja.com/api`. Its manifest grants the production API origin and supported job-site access, without localhost access.

## Production download

Run from the project root:

```sh
python3 scripts/build_extension.py
```

This rebuilds `public/downloads/trakerja-extension.zip`, the existing download used by Automation. The archive contains a `chrome-extension/` folder with the manifest at its root. Packaging includes only the listed extension assets, never local environment files or credentials. ZIP output is deterministic for the same source files.

## Local development

Build a separate archive explicitly:

```sh
python3 scripts/build_extension.py --environment development --output /tmp/trakerja-extension-development.zip
```

The development archive targets `http://localhost:8000/api`, grants localhost instead of the production API origin, and is named `TraKerja Extension (Development)`. It does not modify the checked-in production source. Development builds require an explicit output path and cannot be written inside `public/`.

Extract development and production archives into separate folders and load the intended folder through Chrome's Load unpacked action. For an existing manual installation, replace its files with the new archive contents and reload the extension in `chrome://extensions/`; previously downloaded copies are not updated by changing the website ZIP.

## Verification

```sh
node --test tests/JavaScript/extension-environment.test.cjs
```

Tests simulate Chrome and HTTP without real credentials or requests. They exercise login and job-save requests from source and the public ZIP, verify environment-specific host permissions, and build development packages in a temporary directory.

After deployment, verify real login and saving a job in Chrome using an approved account. Local tests do not establish production API availability or actual job-site compatibility.
