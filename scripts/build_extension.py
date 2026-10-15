#!/usr/bin/env python3
"""Package the Chrome extension without copying local credentials or development files."""

import argparse
import json
from pathlib import Path
import zipfile


ROOT = Path(__file__).resolve().parents[1]
SOURCE = ROOT / 'chrome-extension'
PUBLIC = ROOT / 'public'
PUBLIC_ZIP = PUBLIC / 'downloads/trakerja-extension.zip'
FILES = (
    'manifest.json', 'config.js', 'popup.html', 'popup.js', 'content.js',
    'locations.js', 'locations.json', 'style.css', 'icons/icon.png',
)


def build(environment: str, output: Path) -> None:
    payloads = {name: (SOURCE / name).read_bytes() for name in FILES}
    if environment == 'development':
        if output.resolve().is_relative_to(PUBLIC.resolve()):
            raise ValueError('Development packages must be stored outside public/.')
        manifest = json.loads(payloads['manifest.json'])
        manifest['name'] = 'TraKerja Extension (Development)'
        manifest['host_permissions'] = [
            origin for origin in manifest['host_permissions'] if origin != 'https://trakerja.com/*'
        ] + ['http://localhost:8000/*']
        payloads['manifest.json'] = (json.dumps(manifest, indent=2) + '\n').encode()
        payloads['config.js'] = (
            'const TRAKERJA_EXTENSION_CONFIG = Object.freeze('
            + json.dumps({'environment': 'development', 'apiBase': 'http://localhost:8000/api'})
            + ');\n'
        ).encode()

    output.parent.mkdir(parents=True, exist_ok=True)
    with zipfile.ZipFile(output, 'w', compression=zipfile.ZIP_DEFLATED) as archive:
        for name, data in payloads.items():
            entry = zipfile.ZipInfo('chrome-extension/' + name, date_time=(2026, 1, 1, 0, 0, 0))
            entry.compress_type = zipfile.ZIP_DEFLATED
            entry.external_attr = 0o100644 << 16
            archive.writestr(entry, data)
    print(f'Built {environment} extension: {output}')


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--environment', choices=['production', 'development'], default='production')
    parser.add_argument('--output', type=Path)
    args = parser.parse_args()
    if args.environment == 'development' and args.output is None:
        parser.error('Development packaging requires --output outside public/.')
    try:
        build(args.environment, args.output or PUBLIC_ZIP)
    except ValueError as error:
        parser.error(str(error))


if __name__ == '__main__':
    main()
