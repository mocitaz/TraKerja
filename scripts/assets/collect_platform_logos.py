"""Refresh local platform icons from their public website metadata."""
import io
import re
import json
from concurrent.futures import ThreadPoolExecutor
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urljoin
from urllib.request import Request, urlopen
import xml.etree.ElementTree as ET
from PIL import Image

ROOT = Path(__file__).resolve().parents[2]
DEST = ROOT / 'public/images/platforms'
SITES = {
    '9cv9': ('9cv9', 'https://9cv9.com'),
    'Cake: Cari Lowongan': ('cake', 'https://www.cake.me'),
    'Dealls': ('dealls', 'https://dealls.com'),
    'Disnakerja.com': ('disnakerja', 'https://disnakerja.com'),
    'Fiverr': ('fiverr', 'https://www.fiverr.com'),
    'Freelancer': ('freelancer', 'https://www.freelancer.com'),
    'Glassdoor': ('glassdoor', 'https://www.glassdoor.com'),
    'Glints': ('glints', 'https://glints.com'),
    'Google Forms': ('google-forms', 'https://docs.google.com/forms'),
    'Indeed': ('indeed', 'https://www.indeed.com'),
    'JobStreet': ('jobstreet', 'https://id.jobstreet.com'),
    'Jobseeker App': ('jobseeker', 'https://www.jobseeker.app'),
    'JobsDB': ('jobsdb', 'https://hk.jobsdb.com'),
    'Jora': ('jora', 'https://id.jora.com'),
    'Kalibrr': ('kalibrr', 'https://www.kalibrr.com'),
    'Karir.com': ('karir', 'https://www.karir.com'),
    'Karirhub (SIAPkerja)': ('karirhub', 'https://karirhub.kemnaker.go.id'),
    'KitaLulus': ('kitalulus', 'https://www.kitalulus.com'),
    'LinkedIn': ('linkedin', 'https://www.linkedin.com'),
    'Loker.id': ('loker', 'https://www.loker.id'),
    'Microsoft Forms': ('microsoft-forms', 'https://forms.office.com'),
    'Pintarnya.com': ('pintarnya', 'https://pintarnya.com'),
    'SkillAcademy': ('skillacademy', 'https://skillacademy.com'),
    'Talentics': ('talentics', 'https://www.talentics.id'),
    'Tech in Asia': ('tech-in-asia', 'https://www.techinasia.com'),
    'Urbanhire': ('urbanhire', 'https://www.urbanhire.com'),
}

OVERRIDES = {
    '9cv9': ['https://9cv9.com/favicon.svg'],
    'Google Forms': ['https://www.gstatic.com/images/branding/productlogos/forms_2026q3/v1/web/192px.svg'],
    'Glassdoor': ['https://www.google.com/s2/favicons?domain=glassdoor.com&sz=128'],
    'Jora': ['https://www.google.com/s2/favicons?domain=jora.com&sz=128'],
    'Tech in Asia': ['https://www.google.com/s2/favicons?domain=techinasia.com&sz=128'],
    'Urbanhire': ['https://www.google.com/s2/favicons?domain=urbanhire.com&sz=128'],
}

class Icons(HTMLParser):
    def __init__(self):
        super().__init__()
        self.links = []
    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag == 'link' and 'icon' in attrs.get('rel', '').lower() and attrs.get('href'):
            sizes = [int(size) for size in re.findall(r'(\d+)x\d+', attrs.get('sizes', '') + ' ' + attrs['href'])]
            size = max(sizes, default=180 if 'apple-touch' in attrs.get('rel', '') else (128 if '.svg' in attrs['href'] else 32))
            self.links.append((-size, attrs['href']))

def get(url):
    with urlopen(Request(url, headers={'User-Agent': 'Mozilla/5.0'}), timeout=8) as response:
        return response.read(2_000_000), response.url

def collect(item):
    name, (slug, site) = item
    candidates = OVERRIDES.get(name, []).copy()
    try:
        body, final = get(site)
        parser = Icons()
        parser.feed(body.decode('utf-8', errors='ignore'))
        candidates += [urljoin(final, path) for _, path in sorted(parser.links)]
    except Exception:
        pass
    candidates += [site + '/apple-touch-icon.png', site + '/favicon.ico']
    for source in dict.fromkeys(candidates):
        try:
            body, final = get(source)
            if b'<svg' in body[:1000]:
                if b'<!ENTITY' in body or b'<!DOCTYPE' in body:
                    continue
                root = ET.fromstring(body)
                for element in root.iter():
                    if element.tag.split('}')[-1] in {'script', 'foreignObject', 'image'}:
                        raise ValueError('Unsupported SVG content')
                    if any(key.lower().startswith('on') or ('href' in key.lower() and not value.startswith('#')) for key, value in element.attrib.items()):
                        raise ValueError('Unsupported SVG attribute')
                filename = slug + '.svg'
                (DEST / filename).write_bytes(body)
                return name, {'site': site, 'source': final, 'file': filename}
            icon = Image.open(io.BytesIO(body))
            if icon.format == 'ICO':
                icon = icon.ico.getimage(max(icon.ico.sizes(), key=lambda size: size[0]))
            icon = icon.convert('RGBA')
            if min(icon.size) < 16:
                continue
            icon.thumbnail((128, 128))
            filename = slug + '.png'
            icon.save(DEST / filename, optimize=True)
            return name, {'site': site, 'source': final, 'file': filename, 'size': list(icon.size)}
        except Exception:
            continue
    return name, {'site': site, 'file': None, 'status': 'needs_verified_asset'}

if __name__ == '__main__':
    DEST.mkdir(parents=True, exist_ok=True)
    with ThreadPoolExecutor(max_workers=6) as pool:
        records = dict(pool.map(collect, SITES.items()))
    (DEST / 'sources.json').write_text(json.dumps(records, indent=2) + '\n')
    for name, record in records.items():
        print(name, record.get('file') or 'MISSING')
