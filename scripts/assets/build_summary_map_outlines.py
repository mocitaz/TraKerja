"""Build lightweight context outlines from the existing geoBoundaries/BPS city data.

Run from the repository root. Simplification only affects the neutral context;
highlighted city areas use the original indonesia-city-boundaries.json paths.
Source/license: https://www.geoboundaries.org/api/current/gbOpen/IDN/ADM2/
CC BY 3.0 IGO. Very small islands remain visible in the Natural Earth base map.
"""
import json,re,math
from pathlib import Path
source=json.loads(Path('resources/data/indonesia-city-boundaries.json').read_text())
def simplify(points,tolerance=.15):
    if len(points)<3:return points
    a,b=points[0],points[-1];dx,dy=b[0]-a[0],b[1]-a[1];den=dx*dx+dy*dy
    def distance(p):
        t=max(0,min(1,((p[0]-a[0])*dx+(p[1]-a[1])*dy)/den)) if den else 0
        return math.hypot(p[0]-a[0]-t*dx,p[1]-a[1]-t*dy)
    index=max(range(1,len(points)-1),key=lambda i:distance(points[i]))
    if distance(points[index])<=tolerance:return [a,b]
    return simplify(points[:index+1])+simplify(points[index:])[1:]
output=[]
for name,region in source.items():
    paths=[]
    for ring in re.findall(r'M([^Z]+)Z',region['path']):
        points=[tuple(map(float,p)) for p in re.findall(r'(-?[\d.]+),(-?[\d.]+)',ring)]
        if len(points)<3 or max(max(p[0] for p in points)-min(p[0] for p in points),max(p[1] for p in points)-min(p[1] for p in points))<.4:continue
        reduced=simplify(points+[points[0]])
        if len(reduced)<4:continue
        paths.append('M'+' L'.join(f'{x:.2f},{y:.2f}' for x,y in reduced[:-1])+'Z')
    output.append({'name':name,'bounds':region['bounds'],'path':' '.join(paths)})
Path('resources/data/indonesia-district-outlines.json').write_text(json.dumps(output,separators=(',',':'))+'\n')
print('Simplified context boundaries:',len(output),'size:',Path('resources/data/indonesia-district-outlines.json').stat().st_size)
