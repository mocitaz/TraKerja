"""Validate synthetic CV rendering fixtures using Poppler; no third-party Python packages."""
import pathlib
import subprocess
import sys
import xml.etree.ElementTree as ET

folder = pathlib.Path(sys.argv[1])
namespace = {'x': 'http://www.w3.org/1999/xhtml'}
files = sorted(folder.glob('*.pdf'))
assert files, 'No PDF fixtures found'
for pdf in files:
    raw = subprocess.check_output(['pdftotext', '-bbox', str(pdf), '-'], stderr=subprocess.DEVNULL)
    document = ET.fromstring(raw)
    pages = document.findall('.//x:page', namespace)
    words = document.findall('.//x:word', namespace)
    margin = 28.34 if '-compact' in pdf.stem else (56.69 if '-spacious' in pdf.stem else 42.51)
    for word in words:
        assert float(word.attrib['xMin']) >= margin - 1, f'{pdf.name}: left overflow'
        assert float(word.attrib['xMax']) <= 595.28 - margin + 1, f'{pdf.name}: right overflow'
        assert float(word.attrib['yMin']) >= margin - 1, f'{pdf.name}: top overflow'
        assert float(word.attrib['yMax']) <= 841.89 - margin + 1, f'{pdf.name}: bottom overflow'
    for page in pages:
        page_words = page.findall('.//x:word', namespace)
        for i, first in enumerate(page_words):
            for second in page_words[i + 1:]:
                overlap_x = min(float(first.attrib['xMax']), float(second.attrib['xMax'])) - max(float(first.attrib['xMin']), float(second.attrib['xMin']))
                overlap_y = min(float(first.attrib['yMax']), float(second.attrib['yMax'])) - max(float(first.attrib['yMin']), float(second.attrib['yMin']))
                assert not (overlap_x > 0.75 and overlap_y > 2), f'{pdf.name}: overlapping text bounds'
    text = ' '.join(word.text or '' for word in words).casefold()
    if pdf.stem.endswith('-long'):
        assert 'long_entry_end' in text, f'{pdf.name}: long entry truncated'
    if '-empty' not in pdf.stem:
        assert 'work experience' in text and 'skills' in text, f'{pdf.name}: missing headings'
        start = text.index('work experience')
        role = text.index('senior product manager', start)
        employer = text.index('example technology group 1', start)
        date = text.index('jan 2020', start)
        assert (employer < role < date if pdf.stem.startswith('professional-') else role < employer < date), f'{pdf.name}: invalid reading order'
        if '-sparse' not in pdf.stem:
            for marker in ['education', 'projects', 'organizations', 'certifications', 'awards', 'jun', '2024', 'repository:']:
                assert marker in text, f'{pdf.name}: missing {marker}'
    assert all(page.findall('.//x:word', namespace) for page in pages), f'{pdf.name}: blank page'
    print(f'{pdf.name}: {len(pages)} page(s), text and margins OK')
print(f'Validated {len(files)} fixture PDFs. This does not certify compatibility with an external ATS.')
