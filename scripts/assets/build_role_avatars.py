"""Build the local profession portrait set in the existing Profile SVG style."""

from html import escape
import json
from pathlib import Path
import xml.etree.ElementTree as ET

ROOT = Path(__file__).resolve().parents[2]
INK = '#292b30'
WHITE = '#fffaf5'
LILAC = '#b8a0c8'
GOLD = '#efbd65'
BLUE = '#a9c6dc'
SKINS = ['#f4d2b4', '#dca580', '#b87c59', '#f2c6a5', '#93634d', '#f7dfc2']
HAIRS = ['#292b30', '#634535', '#392f32', '#926343']

# Profession, haircut, face, skin, expression, eyewear, facial hair, head tilt, clothing.
CHARACTERS = """
software curls round 1 grin round stubble -4 #b6a0cf
frontend bob heart 0 smile none none 4 #b6cadb
backend bald square 2 calm square beard -2 #a4a1c2
mobile hijab oval 3 smile none none 3 #d9abc0
data sidepart long 0 focused round none -3 #adc7dd
ai afro wide 4 grin none none 3 #dab57d
cloud waves angular 2 smirk none moustache -4 #9eb2c9
security pixie heart 1 confident none none 4 #a9a3b9
qa bob round 3 calm square none -2 #b2c8ba
network buzz square 4 grin none none 2 #a2bed2
erp braids oval 2 smile none none 3 #c8b5d2
product quiff wide 0 smirk none stubble -3 #c8bd9e
project hijab heart 1 calm none none -4 #b4a9c6
agile curls long 2 grin square moustache 4 #ddbaa4
design twobuns round 3 smile round none -3 #c4b0da
creative long angular 1 smirk none goatee 4 #b1bdcd
marketing ponytail heart 2 grin none none -3 #dcadbb
writing beanie long 0 calm round stubble 4 #b5b6d4
media waves square 3 confident none moustache -5 #b5c7ba
sales sidepart angular 2 grin square none 3 #b1abc5
support hijab round 4 smile none none -3 #cfb6d5
hr bun oval 1 calm round none 4 #d9b69f
finance short square 0 focused square moustache -2 #abbcc9
banking pompadour long 2 smirk none beard 3 #bab0c8
admin bob wide 5 smile none none -3 #cbb2cf
operations quiff round 4 confident none stubble 3 #aab7ca
legal long heart 1 calm square none -4 #b8a8bf
logistics buzz wide 2 grin none beard 3 #d4b39a
mechanical curls square 4 smirk none moustache -3 #a6bac4
electrical pixie oval 1 focused none none 3 #a9bfd4
construction sidepart wide 3 grin none none -3 #e1b16f
science bun long 0 focused round none 3 #c6d4df
health waves square 2 smile round moustache -2 #c2b9d6
education hijab wide 1 grin none none 4 #d8b69b
hospitality quiff heart 3 calm none none -3 #b6aec6
culinary short round 2 grin none moustache 2 #ddd4c8
retail ponytail oval 4 smile none none 4 #d6adc1
transport buzz angular 1 smirk none stubble -3 #b0c4d6
environment braids heart 3 confident round none 3 #aec6b2
service afro round 2 smile none none -4 #d8b7a3
nurse bun heart 0 smile none none 3 #b2c8dd
pharmacy short oval 4 calm square none -3 #d3c4df
dental pixie square 1 grin none none 4 #b6c9cf
midwife hijab round 2 smile none none -3 #c5b1ce
physio quiff angular 0 confident none none 3 #b4cacc
nutrition long oval 3 grin none none -4 #dbbd9f
radiology curls long 4 focused round none 3 #bac3d8
veterinary ponytail wide 1 smile none none -3 #b1cabd
psychology bob square 0 calm round none 4 #d2b6bf
architecture bald oval 3 focused square beard -3 #c1bccd
interior waves heart 2 grin none none 4 #d8bda7
surveying short long 1 confident none moustache -3 #dfb075
safety hijab oval 4 calm none none 3 #dca587
mining buzz square 2 smirk none beard -2 #d7b681
agriculture curls wide 0 grin none none 4 #b3c2a7
fisheries sidepart round 4 calm none moustache -3 #a9c6d6
forestry braids angular 2 smile none none 3 #b0bea8
pilot short square 1 confident none moustache -2 #c3cbd7
cabin bun heart 3 smile none none 4 #b6a7c5
aviation buzz long 4 focused none stubble -3 #a8c0d1
rail bald wide 0 grin round moustache 3 #b1bcd1
pastry bob round 1 smile none none -3 #e0c6d0
barista beanie oval 2 grin round stubble 4 #cbb398
bartender pompadour angular 3 smirk none goatee -4 #b8afc4
waitstaff ponytail square 0 grin none none 3 #c6b3cc
lecturer short long 5 calm round beard -3 #b2b3c7
earlyyears twobuns wide 2 grin none none 4 #dcbba8
translation hijab angular 1 smile round none -3 #c6b4d6
fitness afro square 4 confident none stubble 3 #b6b5d4
beauty mohawk heart 3 smirk none none -4 #d1acc3
guard short wide 2 calm none moustache 2 #aab5cf
cleaning ponytail round 1 grin none none -3 #b0c8d1
general sidepart oval 3 smile round none 3 #bcb0d1
"""
PERSONAS = {row.split()[0]: row.split()[1:] for row in CHARACTERS.strip().splitlines()}

WARDROBE = {
    'software': 'hoodie', 'frontend': 'cardigan', 'backend': 'overshirt',
    'mobile': 'crew', 'ai': 'knit', 'cloud': 'zipper', 'security': 'biker',
    'product': 'polo', 'agile': 'knit', 'design': 'oversized',
    'writing': 'turtleneck', 'media': 'utility', 'operations': 'polo',
    'mechanical': 'coveralls', 'network': 'utility', 'interior': 'cardigan',
}

# Each entry chooses the portrait, uniform, headwear, and foreground work tool.
LOOKS = {
    'software': (2, 'casual', '', 'code'),
    'frontend': (5, 'casual', '', 'browser'),
    'backend': (6, 'casual', '', 'server'),
    'mobile': (8, 'casual', '', 'phone'),
    'data': (7, 'shirt', '', 'chart'),
    'ai': (4, 'casual', '', 'nodes'),
    'cloud': (9, 'casual', '', 'cloud'),
    'security': (0, 'jacket', '', 'shield'),
    'qa': (10, 'shirt', '', 'checklist'),
    'network': (1, 'workwear', '', 'router'),
    'erp': (3, 'shirt', '', 'database'),
    'product': (11, 'jacket', '', 'roadmap'),
    'project': (8, 'jacket', '', 'calendar'),
    'agile': (4, 'shirt', '', 'kanban'),
    'design': (5, 'casual', '', 'tablet'),
    'creative': (0, 'apron', '', 'palette'),
    'marketing': (3, 'jacket', '', 'megaphone'),
    'writing': (6, 'casual', '', 'notebook'),
    'media': (9, 'casual', '', 'camera'),
    'sales': (1, 'suit', '', 'briefcase'),
    'support': (8, 'shirt', 'headset', 'chat'),
    'hr': (5, 'jacket', '', 'people'),
    'finance': (7, 'suit', '', 'calculator'),
    'banking': (6, 'suit', '', 'bank'),
    'admin': (11, 'shirt', '', 'folders'),
    'operations': (2, 'jacket', '', 'gears'),
    'legal': (3, 'suit', '', 'scales'),
    'logistics': (9, 'workwear', '', 'parcel'),
    'mechanical': (4, 'workwear', '', 'wrench'),
    'electrical': (10, 'workwear', 'helmet-white', 'meter'),
    'construction': (1, 'vest', 'helmet', 'blueprint'),
    'science': (7, 'lab', 'goggles', 'flask'),
    'health': (6, 'lab', 'stethoscope', 'medical'),
    'education': (0, 'jacket', '', 'book'),
    'hospitality': (8, 'suit', '', 'bell'),
    'culinary': (9, 'chef', 'chef', 'pan'),
    'retail': (3, 'apron', '', 'bag'),
    'transport': (10, 'workwear', 'cap', 'wheel'),
    'environment': (4, 'workwear', '', 'leaf'),
    'service': (11, 'shirt', '', 'heart'),
    'nurse': (5, 'scrubs', 'nurse', 'medical'),
    'pharmacy': (2, 'lab', '', 'medicine'),
    'dental': (8, 'scrubs', '', 'tooth'),
    'midwife': (3, 'scrubs', 'hijab', 'baby'),
    'physio': (9, 'scrubs', '', 'joint'),
    'nutrition': (0, 'lab', '', 'apple'),
    'radiology': (10, 'scrubs', '', 'xray'),
    'veterinary': (4, 'lab', 'stethoscope', 'paw'),
    'psychology': (11, 'jacket', '', 'conversation'),
    'architecture': (7, 'jacket', 'helmet-white', 'building'),
    'interior': (5, 'casual', '', 'swatches'),
    'surveying': (6, 'vest', 'helmet', 'survey'),
    'safety': (3, 'vest', 'helmet-white', 'safety-check'),
    'mining': (2, 'vest', 'mining', 'rock'),
    'agriculture': (9, 'overalls', 'straw', 'plant'),
    'fisheries': (1, 'overalls', 'cap', 'fish'),
    'forestry': (4, 'ranger', 'ranger', 'tree'),
    'pilot': (6, 'pilot', 'pilot', 'plane'),
    'cabin': (8, 'suit', 'cabin', 'passport'),
    'aviation': (10, 'workwear', 'earmuffs', 'plane-wrench'),
    'rail': (7, 'pilot', 'rail', 'train'),
    'pastry': (0, 'chef', 'chef', 'whisk'),
    'barista': (2, 'apron', '', 'coffee'),
    'bartender': (9, 'waistcoat', '', 'shaker'),
    'waitstaff': (3, 'waistcoat', '', 'tray'),
    'lecturer': (6, 'suit', '', 'books'),
    'earlyyears': (11, 'apron', '', 'blocks'),
    'translation': (5, 'jacket', 'headset', 'languages'),
    'fitness': (4, 'sport', '', 'dumbbell'),
    'beauty': (8, 'apron', '', 'scissors'),
    'guard': (1, 'uniform', 'guard', 'radio'),
    'cleaning': (10, 'workwear', 'cap', 'spray'),
    'general': (7, 'shirt', '', 'notebook'),
}


def path(d, fill='none', **attrs):
    values = ' '.join(f'{key.replace("_", "-")}="{value}"' for key, value in attrs.items())
    return f'<path d="{d}" fill="{fill}" {values}/>'


def rect(x, y, width, height, fill=WHITE, rx=2):
    return f'<rect x="{x}" y="{y}" width="{width}" height="{height}" rx="{rx}" fill="{fill}"/>'


def circle(x, y, radius, fill='none'):
    return f'<circle cx="{x}" cy="{y}" r="{radius}" fill="{fill}"/>'


def tool(name):
    # Tools occupy the lower-right shoulder, keeping the face unobstructed.
    laptop = rect(0, 1, 33, 23, BLUE) + path('M-3 25H36L32 29H1Z', WHITE)
    clipboard = rect(2, 0, 27, 34) + rect(10, -2, 12, 5, LILAC)
    book = path('M0 4Q8 0 16 4Q24 0 32 4V30Q24 26 16 30Q8 26 0 30Z', WHITE) + path('M16 4V30')
    props = {
        'code': laptop + path('M12 8L7 12L12 16M22 8L27 12L22 16M19 7L15 18'),
        'browser': laptop + path('M1 7H32M6 4H7M11 4H12M5 11H13V19H5ZM17 12H27M17 17H24'),
        'server': rect(3, 0, 27, 31, BLUE) + path('M3 10H30M3 21H30M9 5H10M9 15H10M9 26H10M16 5H24M16 15H24M16 26H24'),
        'phone': rect(7, -1, 21, 35, LILAC, 4) + rect(10, 5, 15, 20) + path('M14 2H20M16 29H18M14 13L17 16L22 10'),
        'chart': clipboard + path('M8 27V20H12V27M16 27V14H20V27M24 27V9M7 30H26'),
        'nodes': laptop + path('M10 12L17 7L25 13L17 19ZM10 12L17 19M17 7V19') + circle(10, 12, 2, LILAC) + circle(17, 7, 2, LILAC) + circle(25, 13, 2, LILAC),
        'cloud': laptop + path('M8 17Q2 10 10 9Q13 1 21 7Q30 5 30 13Q30 17 25 17Z', WHITE),
        'shield': path('M16 0L30 5V17Q29 27 16 33Q3 27 2 17V5Z', LILAC) + path('M9 15L14 20L24 10'),
        'checklist': clipboard + path('M7 10L10 13L14 7M18 10H25M7 21L10 24L14 18M18 21H25'),
        'router': rect(0, 15, 34, 15, BLUE) + path('M5 15V2M29 15V2M7 23H9M14 23H16M22 20H29M22 25H29'),
        'database': path('M2 6C2-2 31-2 31 6V28C31 36 2 36 2 28Z', BLUE) + path('M2 6C2 14 31 14 31 6M2 16C2 24 31 24 31 16'),
        'roadmap': clipboard + path('M9 28V20Q9 16 16 16Q24 16 24 11V7') + circle(9, 27, 3, LILAC) + circle(24, 7, 3, GOLD),
        'calendar': rect(0, 2, 33, 29) + path('M0 11H33M8-1V6M25-1V6M7 17H10M16 17H19M25 17H27M7 25H10M16 25H19'),
        'kanban': rect(0, 0, 34, 31) + path('M11 0V31M23 0V31') + rect(3, 6, 5, 10, GOLD, 1) + rect(15, 6, 5, 16, LILAC, 1) + rect(27, 6, 4, 7, BLUE, 1),
        'tablet': rect(0, 2, 31, 26, LILAC) + rect(4, 6, 23, 18) + path('M9 19L18 10M21-2L33 11M20-1L23-4L36 9L33 12Z', GOLD),
        'palette': path('M17 0C-6 0-5 31 13 32C26 33 15 20 25 20C40 21 36 0 17 0Z', WHITE) + circle(8, 12, 3, GOLD) + circle(16, 7, 3, BLUE) + circle(26, 10, 3, LILAC) + circle(7, 23, 3, LILAC),
        'megaphone': path('M2 12H12L31 2V29L12 19H2ZM9 20L12 32H19L16 22', LILAC) + path('M34 11L39 8M35 20L40 22'),
        'notebook': rect(3, 0, 27, 33, GOLD) + path('M9 0V33M0 7H6M0 15H6M0 24H6M15 10H25M15 17H24M15 24H21'),
        'camera': rect(-1, 8, 35, 23, BLUE) + path('M5 8L8 2H22L25 8Z', BLUE) + circle(17, 19, 8, WHITE) + circle(17, 19, 4, LILAC) + path('M28 12H29'),
        'briefcase': rect(0, 8, 34, 24, GOLD) + path('M10 8V2H24V8M0 17Q17 23 34 17M15 18V24H20V18', WHITE),
        'chat': path('M0 1H33V24H15L5 33V24H0Z', WHITE) + path('M7 9H26M7 16H21'),
        'people': clipboard + circle(11, 12, 4, LILAC) + circle(23, 14, 3, GOLD) + path('M5 25Q5 17 11 17Q17 17 17 25M19 25Q18 19 23 19Q28 19 28 25'),
        'calculator': rect(4, 0, 25, 34, BLUE) + rect(8, 4, 17, 7) + path('M9 17H11M17 17H19M24 17H25M9 24H11M17 24H19M24 24H25M9 30H11M17 30H25'),
        'bank': path('M0 9L17 0L34 9ZM2 13H32M5 13V28M13 13V28M22 13V28M30 13V28M1 30H34', GOLD),
        'folders': path('M0 5H13L17 10H34V33H0Z', BLUE) + path('M2 3V0H18L22 5H32V10M0 16H34'),
        'gears': circle(17, 17, 12, LILAC) + circle(17, 17, 5, WHITE) + path('M17 0V5M17 29V34M0 17H5M29 17H34M5 5L9 9M25 25L29 29M5 29L9 25M25 9L29 5'),
        'scales': path('M17 0V31M8 32H26M0 8H34M6 8L0 22H12ZM28 8L22 22H34Z', GOLD) + path('M0 22Q6 30 12 22M22 22Q28 30 34 22', WHITE),
        'parcel': path('M0 7L17 0L34 7V29L17 36L0 29Z', GOLD) + path('M0 7L17 14L34 7M17 14V36M9 4L26 11V21L22 23V13'),
        'wrench': path('M27 0Q37 9 28 16L14 31Q9 36 4 31Q0 27 4 23L19 10Q15 0 24-3L23 6L29 8Z', BLUE) + circle(9, 27, 2),
        'meter': rect(4, 2, 25, 30, GOLD) + rect(8, 6, 17, 9) + circle(17, 23, 5, WHITE) + path('M17 23L20 20M4 24Q-6 24-3 34M29 24Q38 24 36 34'),
        'blueprint': rect(0, 3, 34, 28, BLUE) + path('M5 26V9H17V15H28V26ZM5 18H17M17 15V26M23 18V23M0 3Q4-3 7 3V31'),
        'flask': path('M11 0H23M13 0V12L1 28Q-1 33 6 33H28Q35 33 32 28L21 12V0', WHITE) + path('M6 23H28L32 29Q33 32 28 32H6Q1 32 3 28Z', LILAC) + circle(14, 25, 2, WHITE) + circle(23, 29, 1, WHITE),
        'medical': clipboard + path('M12 10H20V15H25V23H20V28H12V23H7V15H12Z', LILAC),
        'book': book + path('M4 11H11M4 17H11M21 11H28M21 17H28'),
        'bell': path('M2 25H33M6 23Q6 7 18 7Q30 7 30 23ZM15 3H21M18 3V7M1 30H34', GOLD),
        'pan': path('M0 11H27Q27 28 14 28Q1 28 0 11Z', BLUE) + path('M27 13L40 7M5 4Q1 0 5-4M16 4Q12 0 16-4'),
        'bag': path('M2 8H31L34 34H0ZM10 12V5Q10-5 24 5V12', LILAC),
        'wheel': circle(17, 17, 16, BLUE) + circle(17, 17, 5, WHITE) + path('M1 14L12 17M22 17L33 14M17 22V33'),
        'leaf': path('M1 32Q-3 0 32 0Q37 30 1 32ZM1 32L25 7M12 20L10 10M19 14L28 16', LILAC),
        'heart': path('M17 32L3 18C-8 4 9-5 17 7C26-5 42 4 31 18Z', LILAC),
        'medicine': rect(0, 11, 16, 22) + rect(2, 7, 12, 5, LILAC) + path('M4 21H12M8 17V25M24 12Q18 7 23 2Q28-3 33 2L38 8Q43 13 38 18Q33 23 28 18ZM25 5L36 16', LILAC),
        'tooth': path('M17 4C-1-7-4 12 3 20C6 24 4 35 10 34L17 22L24 34C30 35 28 24 31 20C38 9 34-7 17 4Z', WHITE),
        'baby': path('M17 35Q-3 26 0 12Q3 2 17 2Q32 2 34 12Q37 26 17 35Z', LILAC) + circle(17, 11, 8, '#f4d2b4') + path('M12 10V11M22 10V11M14 15Q17 18 20 15M0 17L28 28M34 17L8 29'),
        'joint': path('M10 0L19 11Q25 10 27 16Q30 23 23 25L14 35L7 28L17 18L3 7Z', WHITE) + circle(21, 17, 7, LILAC),
        'apple': path('M17 8C-8-4-4 27 11 33Q17 29 23 33C39 26 43-5 17 8Z', GOLD) + path('M17 8V0M18 3Q22-5 29-3Q28 6 18 3', LILAC),
        'xray': rect(0, 0, 34, 34, '#576277') + path('M17 5V29M7 8Q17 14 27 8M6 15Q17 21 28 15M7 22Q17 28 27 22', 'none', stroke=WHITE),
        'paw': circle(17, 22, 9, LILAC) + circle(4, 13, 4, LILAC) + circle(12, 5, 4, LILAC) + circle(23, 5, 4, LILAC) + circle(31, 13, 4, LILAC),
        'conversation': path('M0 0H25V19H12L5 26V19H0Z', WHITE) + path('M15 23H26L33 31V23H37V7H29', LILAC) + path('M6 7H19M6 13H16'),
        'building': rect(3, 0, 27, 34, BLUE) + path('M9 7H13V12H9ZM21 7H25V12H21ZM9 19H13V24H9ZM21 19H25V24H21ZM15 34V27H20V34'),
        'swatches': rect(0, 0, 12, 34, GOLD) + '<g transform="rotate(-25 6 29)">' + rect(0, 0, 12, 34, BLUE) + '</g><g transform="rotate(-50 6 29)">' + rect(0, 0, 12, 34, LILAC) + '</g>' + circle(6, 29, 2, WHITE),
        'survey': path('M17 12L5 34M17 12L29 34M17 12V34') + rect(2, 1, 30, 12, GOLD) + circle(26, 7, 4, BLUE),
        'safety-check': clipboard + path('M8 12L16 8L24 12V20Q23 26 16 29Q9 26 8 20Z', GOLD) + path('M12 18L15 21L21 15'),
        'rock': path('M0 25L7 8L22 0L34 13L30 32L11 35Z', '#b3a3a4') + path('M7 8L17 18L34 13M17 18L11 35M17 18L22 0'),
        'plant': path('M17 24V4M17 17Q-2 18 2 4Q17 2 17 17M17 11Q17-4 33 0Q34 13 17 11', LILAC) + path('M4 24H30L26 35H8Z', GOLD),
        'fish': path('M0 16Q15-3 28 12L36 5V28L28 21Q14 36 0 16Z', BLUE) + circle(10, 14, 2, INK) + path('M20 11L16 5M20 22L16 29'),
        'tree': path('M17 0L2 15H9L0 26H34L25 15H32Z', LILAC) + path('M13 26V35H21V26', GOLD),
        'plane': path('M15 0H20L22 12L35 21V26L22 21L21 29L26 32V35L17 33L9 35V32L14 29L13 21L0 26V21L13 12Z', WHITE),
        'passport': rect(4, 0, 26, 34, LILAC) + circle(17, 15, 8, WHITE) + path('M9 15H25M17 7Q9 15 17 23Q25 15 17 7M11 28H23'),
        'plane-wrench': rect(1, 0, 31, 32, BLUE) + path('M8 8L25 25M21 5L6 25M5 25L9 28M19 6L24 4M14 13L25 13M14 13V4', 'none', stroke=WHITE),
        'train': rect(4, 0, 27, 29, BLUE, 6) + rect(8, 5, 19, 10) + circle(11, 22, 2, GOLD) + circle(24, 22, 2, GOLD) + path('M10 29L5 35M25 29L30 35M7 32H28'),
        'whisk': path('M7 34L18 19M18 19C-4 10 10-11 27 0C46 13 25 29 18 19ZM18 19Q5 3 26 0M18 19Q34 9 26 0', 'none'),
        'coffee': path('M3 9H27V26Q15 38 3 26ZM27 12H31Q40 13 34 22H28M0 33H31', WHITE) + path('M9 4Q5 0 9-4M20 4Q16 0 20-4M8 16Q15 9 23 16Q20 24 15 25Q10 24 8 16', LILAC),
        'shaker': path('M7 10H28L24 34H11ZM9 6V2H26V6M6 6H29V10H6Z', BLUE) + path('M13 16L12 25M19 16L18 25'),
        'tray': path('M-1 26H35M4 23Q4 7 17 7Q30 7 30 23ZM14 3H20M17 3V7M7 31H27', WHITE),
        'books': rect(0, 6, 10, 28, GOLD) + rect(11, 0, 11, 34, LILAC) + rect(23, 10, 11, 24, BLUE) + path('M3 12H7M14 7H19M26 16H31M3 28H7M14 28H19M26 28H31'),
        'blocks': rect(0, 17, 17, 17, LILAC) + rect(17, 17, 17, 17, BLUE) + rect(8, 0, 18, 17, GOLD) + path('M5 29L8 22L11 29M22 23H29V29H22Z') + circle(17, 8, 4),
        'languages': book + path('M3 20L8 10L13 20M5 17H11M21 11H29M25 9V13M22 14Q25 20 29 21M28 13Q26 19 21 21'),
        'dumbbell': path('M8 14H26V21H8Z', BLUE) + rect(3, 5, 6, 25, LILAC) + rect(-2, 10, 5, 15, BLUE) + rect(26, 5, 6, 25, LILAC) + rect(32, 10, 5, 15, BLUE),
        'scissors': circle(6, 26, 6, WHITE) + circle(22, 28, 6, WHITE) + path('M10 21L28 0M18 22L6-2M12 17L19 16'),
        'radio': rect(4, 10, 25, 24, BLUE) + path('M9 10V-3M20 10V5H26V10M10 17H23M10 22H23M10 27H18'),
        'spray': path('M9 0H27V6H21V13L29 18V34H5V18L13 12V6H9ZM21 6H29L33 11', BLUE) + rect(9, 21, 16, 8, WHITE),
    }
    return props[name]


def attire(style, color, build='oval'):
    color = {'lab': WHITE, 'scrubs': BLUE, 'chef': WHITE, 'vest': GOLD,
             'suit': '#69617a', 'pilot': WHITE, 'uniform': '#727b96',
             'ranger': '#9b9981', 'sport': LILAC}.get(style, color)
    shoulder = {'wide': 15, 'square': 18, 'round': 20, 'oval': 23, 'long': 25, 'heart': 24, 'angular': 21}[build]
    shape = path(f'M{shoulder} 120V108Q{shoulder} 91 48 85H72Q{120-shoulder} 91 {120-shoulder} 108V120', color)
    if style in ['lab', 'jacket', 'suit']:
        shape += path('M48 86L42 98L54 105L60 93L66 105L78 98L72 86', WHITE)
        shape += path('M60 94V120')
    elif style in ['shirt', 'pilot', 'uniform', 'workwear', 'ranger']:
        shape += path('M48 86L48 98L60 92L72 98V86M60 94V120')
        shape += rect(29, 103, 15, 10, color)
    else:
        shape += path('M46 88Q60 105 74 88')
    if style == 'scrubs':
        shape += path('M47 86L60 101L73 86M33 106H44V114H33Z')
    if style in ['apron', 'overalls', 'waistcoat']:
        shape += path('M40 87L43 101H77L80 87M43 101L36 120H84L77 101Z', LILAC if style == 'apron' else BLUE)
    if style == 'vest':
        shape += path('M42 88L38 120M78 88L82 120M24 108H96', 'none', stroke=WHITE, stroke_width=5)
    if style in ['pilot', 'uniform']:
        shape += path('M28 95H43M77 95H92', 'none', stroke=GOLD, stroke_width=5)
        shape += path('M55 94L60 99L65 94L62 118H58Z', '#69617a')
    if style == 'sport':
        shape += path('M38 91V120M82 91V120', 'none', stroke=WHITE, stroke_width=4)
    if style in ['hoodie', 'oversized']:
        shape += path('M44 86Q34 88 38 100L51 110H69L82 99Q85 89 76 86L70 96H50Z', color)
        shape += path('M50 99V113M70 99V111M43 119L47 113H69L74 119')
    if style == 'cardigan':
        shape += path('M47 87L58 108L72 87L77 120H42Z', WHITE) + path('M58 108V120M64 112H65M64 118H65')
    if style == 'overshirt':
        shape += path('M47 86L44 98L56 104L60 92L66 104L77 97L72 86M60 94V120')
        shape += path('M27 107H42V116H27ZM78 107H93V116H78ZM28 109H41M79 109H92')
    if style == 'knit':
        shape += path('M43 91Q60 107 77 91M44 94L42 98M49 97L48 102M55 99V104M61 100V105M67 99L68 104M73 96L75 101M24 116H97', 'none', stroke_width=1.6)
    if style in ['zipper', 'utility', 'coveralls']:
        shape += path('M49 86L44 96L56 100L60 92L65 100L77 96L72 86M60 94V120M56 103V118')
        shape += rect(29, 101, 18, 14, color) + path('M30 106H46M33 102V106M38 102V106')
        if style == 'coveralls':
            shape += path('M25 92L36 96M84 96L95 92M25 115H97', 'none', stroke=WHITE, stroke_width=3)
    if style == 'turtleneck':
        shape += path('M46 84H74V97Q60 104 46 97Z', color) + path('M49 87V96M55 87V98M62 87V98M69 87V96', 'none', stroke_width=1.5)
    if style == 'biker':
        shape += path('M44 87L39 100L53 107L60 93L76 87L70 105L62 112V120M32 109L45 105', WHITE)
    if style == 'polo':
        shape += path('M47 86L45 96L56 101L60 92L65 101L76 96L72 86M60 94V113M64 104H65M64 110H65')
    if style == 'apron':
        shape += path('M45 107H70V118H45ZM47 108V111M68 108V111')
    if style == 'chef':
        shape += path('M48 86L45 97L54 100L60 91L69 99L76 91M54 100V120M62 107H63M72 107H73M62 115H63M72 115H73')
    return shape


def headwear(style):
    if style in ['helmet', 'helmet-white', 'mining']:
        color = WHITE if style == 'helmet-white' else GOLD
        hat = path('M30 34Q31 12 60 12Q89 12 90 34Z', color) + rect(27, 32, 66, 7, color, 3) + path('M52 14V30M68 14V30')
        return hat + (circle(60, 26, 7, BLUE) + circle(60, 26, 3, WHITE) if style == 'mining' else '')
    if style == 'chef':
        return path('M35 33V23Q22 15 34 7Q43 0 51 8Q61-6 71 8Q83 1 90 13Q95 23 84 26V34Z', WHITE) + path('M35 26H84M45 19V25M74 19V25')
    if style == 'nurse':
        return path('M34 19L44 9H77L87 19L80 33H41Z', WHITE) + path('M56 16H65V21H70V27H65V31H56V27H51V21H56Z', LILAC, stroke='none')
    if style == 'pilot':
        return path('M29 22L60 12L91 22L84 32H36Z', '#727b96') + path('M35 30H85V35H35Z', GOLD) + path('M36 35H85Q78 46 48 39Z', INK) + path('M47 24L56 25L60 21L64 25L74 24L68 29H53Z', GOLD)
    if style == 'cabin':
        return path('M39 24Q43 12 72 16L84 25L77 35L39 30Z', LILAC) + path('M39 30L77 35L74 40L38 35Z', '#c8b5d6') + circle(61, 27, 3, GOLD)
    if style == 'guard':
        return path('M33 31V22Q60 8 87 22V31Z', '#727b96') + path('M31 31H88Q75 43 50 35Z', '#727b96') + path('M55 18H66V26L60 30L55 26Z', GOLD)
    if style == 'rail':
        return path('M32 30V19Q60 10 88 19V30Z', '#727b96') + path('M31 30H89V34H31Z', LILAC) + path('M37 34H87Q75 43 46 38Z', INK) + circle(60, 24, 4, GOLD)
    if style == 'cap':
        return path('M33 31V22Q60 8 87 22V31Z', BLUE) + path('M31 31H88L98 37Q79 44 61 35H31Z', BLUE) + path('M61 17L63 30', 'none', stroke_width=1.5)
    if style in ['straw', 'ranger']:
        return path('M38 31L43 11H76L83 31Z', GOLD if style == 'straw' else '#9b9981') + path('M25 31Q60 24 96 31L91 38H30Z', GOLD if style == 'straw' else '#9b9981')
    if style == 'headset':
        return path('M31 49V35Q33 8 60 8Q89 8 89 35V49', 'none', stroke_width=4) + rect(28, 43, 9, 19, LILAC, 4) + rect(84, 43, 9, 19, LILAC, 4) + path('M89 62Q90 72 70 72') + rect(65, 69, 9, 6, LILAC, 3)
    if style == 'earmuffs':
        return path('M30 50V35Q30 5 60 5Q90 5 90 35V50', 'none', stroke_width=4) + rect(26, 43, 11, 22, GOLD, 5) + rect(84, 43, 11, 22, GOLD, 5)
    if style == 'goggles':
        return rect(39, 44, 43, 16, 'none', 5) + path('M35 49H39M82 49H87M60 44V60')
    if style == 'stethoscope':
        return path('M46 85V98Q46 111 57 111Q68 111 68 98V85M57 111V116H79V105', 'none', stroke_width=3) + circle(79, 103, 5, BLUE)
    return ''


def haircut(style, color, scarf):
    back = ''
    if style in ['long', 'bob', 'braids']:
        outline = 'M29 91V37Q28 9 59 9Q92 9 92 39L94 91L81 94H36Z' if style != 'bob' else 'M29 73V36Q29 8 60 8Q91 8 91 37V73Q80 87 60 80Q39 88 29 73Z'
        back = path(outline, color)
    if style in ['bun', 'ponytail']:
        back = circle(85, 30, 13, color)
        if style == 'ponytail':
            back += path('M86 34Q108 49 92 89L82 76L88 53Z', color)
    if style == 'twobuns':
        back = circle(30, 28, 13, color) + circle(90, 28, 13, color)
    if style == 'hijab':
        return path('M28 95L31 39Q31 10 60 10Q90 10 90 39L94 96L63 105Z', scarf), path('M32 55V35Q33 13 60 13Q87 13 88 35V64L80 47V33Q60 25 41 41V62Z', scarf)
    fronts = {
        'short': 'M35 43V29Q36 14 60 14Q85 14 85 31V45L78 39V29Q57 34 42 29V45Z',
        'sidepart': 'M34 46V31Q34 14 53 14L80 16Q90 22 84 43L78 45V28Q60 40 41 30V45Z',
        'quiff': 'M35 45V29L31 21L45 23Q52 4 74 13L88 9L85 35L78 43V28Q63 39 42 30V45Z',
        'pompadour': 'M34 47V32Q27 22 36 17Q31 4 47 12Q58 1 70 11Q90 4 89 23L83 44L77 44V29Q58 38 42 28V45Z',
        'curls': 'M34 45Q25 39 32 31Q24 20 36 20Q31 7 45 13Q51 2 61 13Q73 3 79 17Q92 10 91 24Q102 29 87 44L78 45V31Q69 38 60 29Q50 40 42 31L40 47Z',
        'afro': 'M32 49Q20 47 25 35Q17 26 29 20Q25 8 39 11Q45 0 56 9Q67-1 76 10Q91 4 93 19Q106 23 97 35Q103 47 87 51L80 42V32Q68 39 59 29Q49 39 40 33L39 49Z',
        'bob': 'M34 49V32Q34 11 60 12Q87 11 87 36V60L78 46V29L66 40H59V29L41 39V52Z',
        'long': 'M33 56V33Q34 10 60 11Q87 10 87 35V59L78 46V28Q61 45 43 32L40 59Z',
        'pixie': 'M34 46L31 29L39 28L36 13L48 18L59 7L64 15L83 11L89 30L81 44L77 30Q65 38 44 33L40 46Z',
        'bun': 'M34 47V32Q34 13 60 14Q87 13 87 36L80 45L77 29Q61 38 42 30V46Z',
        'twobuns': 'M34 45V31Q35 12 60 13Q85 12 86 34L80 45V30Q70 38 60 29Q51 39 41 30V46Z',
        'braids': 'M34 49V31Q33 10 60 12Q87 10 87 34L81 48L76 29Q59 39 42 29L40 49Z',
        'buzz': 'M35 40V31Q35 15 60 15Q85 15 85 31V41L79 37V27Q60 23 41 29V41Z',
        'waves': 'M34 46Q27 36 34 29Q29 17 42 18Q48 7 59 14Q73 3 79 16Q95 14 88 32L82 45L76 30Q65 41 52 31L41 39V48Z',
        'ponytail': 'M34 46V30Q34 12 60 13Q86 11 87 35L80 44L77 29Q58 41 41 31V47Z',
        'mohawk': 'M35 46V32Q35 23 45 24L46 11L54 14L58 4L65 13L73 8L76 26Q86 25 85 35V45L78 41V32L41 33V46Z',
        'beanie': 'M34 44V31Q34 14 59 14Q86 14 86 34V46L78 42V34H42V46Z',
        'bald': 'M34 53V44L39 37V54ZM81 37L86 45V54H81Z',
    }
    front = path(fronts[style], color)
    if style == 'braids':
        for x in [30, 35, 40, 81, 86, 91]:
            back += path(f'M{x} 40Q{x-3} 62 {x} 83', 'none', stroke=color, stroke_width=6)
            back += path(f'M{x-1} 48L{x+2} 51M{x-1} 58L{x+2} 61M{x-1} 68L{x+2} 71', 'none', stroke=INK, stroke_width=1.2)
    if style == 'beanie':
        front += path('M31 30Q30 5 60 5Q90 5 90 30Z', scarf) + rect(29, 27, 62, 11, scarf, 4) + path('M42 13L40 25M55 10L54 25M68 10L69 25M80 15L82 25', 'none', stroke_width=1.5)
    return back, front


def expression(kind, glasses, beard, hair, face):
    wide = face in ['wide', 'round', 'square']
    left, right = (47, 73) if wide else (49, 71)
    features = path(f'M{left-4} 44Q{left} 42 {left+4} 44M{right-4} 44Q{right} 42 {right+4} 44')
    if kind == 'grin':
        features += path(f'M{left-3} 52Q{left} 48 {left+3} 52M{right-3} 52Q{right} 48 {right+3} 52')
    else:
        features += path(f'M{left} 51V54M{right} 51V54', 'none', stroke_width=3)
    features += path('M60 53L57 61L62 62' if face in ['long', 'angular'] else 'M59 56Q55 64 61 63')
    if beard in ['beard', 'goatee']:
        features += path('M39 63L47 68L51 65Q60 62 69 65L74 69L81 62Q80 82 60 86Q41 82 39 63Z' if beard == 'beard' else 'M52 76H67L63 85H57Z', hair)
    if beard == 'stubble':
        features += path('M43 67L44 69M46 73L47 75M50 77L51 79M69 77L70 75M74 73L75 71M78 68L79 66', 'none', stroke_width=1.4)
    if beard == 'moustache':
        features += path('M60 65Q54 60 49 68L45 69Q54 73 60 68Q67 73 75 69L70 68Q65 60 60 65Z', hair)
    mouths = {
        'grin': path('M50 68Q60 72 71 67Q69 79 60 78Q52 77 50 68Z', WHITE) + path('M54 75H67', 'none', stroke_width=1.3),
        'smile': path('M52 69Q61 78 70 68'),
        'calm': path('M54 71Q61 74 67 70'),
        'smirk': path('M52 72Q61 74 70 67M69 66L72 67'),
        'confident': path('M51 70Q60 77 70 69') + path('M43 43L52 42M69 41L77 44'),
        'focused': path('M55 72H65M44 43L52 45M68 45L76 43'),
    }
    features += mouths[kind]
    if beard == 'beard':
        features += path('M53 71Q60 75 67 70', 'none', stroke=WHITE)
    if glasses == 'round':
        features += circle(left, 52, 10) + circle(right, 52, 10) + path(f'M{left+10} 51H{right-10}M35 50H{left-10}M{right+10} 50H86')
    if glasses == 'square':
        features += rect(left-10, 46, 19, 14, 'none', 4) + rect(right-9, 46, 19, 14, 'none', 4) + path(f'M{left+9} 51H{right-9}M35 50H{left-10}M{right+10} 50H86')
    return features


def portrait(role, label):
    _, clothing, hat, prop = LOOKS[role]
    cut, face_kind, skin_id, mood, glasses, beard, tilt, color = PERSONAS[role]
    skin = SKINS[int(skin_id)]
    hair = '#b6aca3' if role == 'lecturer' else HAIRS[list(PERSONAS).index(role) % len(HAIRS)]
    scarf = color if cut == 'hijab' else '#b89acb'
    back, fringe = haircut(cut, hair, scarf)
    body = attire(WARDROBE.get(role, clothing), color, face_kind)
    neck = path('M52 74V87Q60 96 68 87V74', skin)
    ears = path('M36 48Q28 43 30 54Q31 63 38 60M84 48Q92 43 90 54Q89 63 82 60', skin) + path('M33 51L35 56M87 51L85 56', 'none', stroke_width=1.4)
    faces = {
        'round': 'M35 37Q36 17 60 17Q84 17 85 37V56Q86 82 60 84Q34 82 35 56Z',
        'oval': 'M38 35Q39 18 60 18Q81 18 82 35V56Q80 78 60 85Q40 78 38 56Z',
        'square': 'M34 37Q36 18 60 18Q84 18 86 37V62Q85 77 74 81H46Q35 77 34 62Z',
        'heart': 'M36 36Q36 17 60 17Q84 17 84 36V52Q83 70 60 85Q37 70 36 52Z',
        'long': 'M39 33Q39 16 60 16Q81 16 81 33V60Q80 82 60 88Q40 82 39 60Z',
        'wide': 'M32 38Q33 18 60 18Q87 18 88 38V57Q87 77 60 81Q33 77 32 57Z',
        'angular': 'M36 36L43 22L60 17L77 22L84 36L82 63L72 78L60 85L47 78L37 63Z',
    }
    face = path(faces[face_kind], skin)
    face_detail = expression(mood, glasses if hat != 'goggles' else 'none', beard, hair, face_kind)
    if cut in ['bun', 'bob', 'ponytail', 'twobuns'] and hat not in ['headset', 'earmuffs']:
        face_detail += circle(35, 60, 2, GOLD) + circle(85, 60, 2, GOLD)
    if cut == 'hijab':
        neck = ''
        face_detail += path('M34 63Q40 82 62 90L86 69L91 92L63 107L31 91Z', scarf) + path('M41 85L61 98L78 87')
    headgear = headwear(hat) if hat != 'stethoscope' else ''
    collar_tool = headwear(hat) if hat == 'stethoscope' else ''
    head = f'<g transform="rotate({tilt} 60 78)">{ears}{face}{fringe}{face_detail}{headgear}</g>'
    if prop in ['code', 'browser', 'cloud', 'nodes']:
        tool_transform = 'translate(48 92) scale(1.3)'
        hands = path('M45 115Q38 108 44 106L52 113M86 114L91 106Q97 105 97 110L91 119', skin)
    else:
        tool_transform = 'translate(68 83)'
        hands = path('M73 108Q66 103 69 99Q72 97 77 103L82 110', skin)
    background = ['#ece5f1', '#e8edf3', '#f3e7dc', '#eee3ec'][list(PERSONAS).index(role) % 4]
    return f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" role="img" aria-labelledby="title">
<title id="title">{escape(label)} · illustrated profession avatar</title>
<defs><clipPath id="portrait"><circle cx="60" cy="60" r="60"/></clipPath></defs>
<g clip-path="url(#portrait)">
<rect width="120" height="120" fill="{background}"/>
<g stroke="{INK}" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
<g data-part="portrait">{back}{body}{neck}{head}{collar_tool}</g>
<g data-part="work-tool" transform="{tool_transform}">{tool(prop)}</g>
{hands}
</g></g></svg>
'''


def main():
    base = json.loads((ROOT / 'resources/data/job-roles.json').read_text())
    specialists = json.loads((ROOT / 'resources/data/job-role-specialists.json').read_text())
    fields = base | specialists | {'general': {'label': 'General role'}}
    if set(fields) != set(LOOKS) or set(fields) != set(PERSONAS):
        raise ValueError('Every profession needs a portrait and a character definition.')
    destination = ROOT / 'public/images/role-avatars'
    destination.mkdir(parents=True, exist_ok=True)
    for role, field in fields.items():
        svg = portrait(role, field['label'])
        ET.fromstring(svg)
        (destination / f'{role}.svg').write_text(svg)
    print(f'Built {len(fields) - 1} profession avatars and one general portrait.')


if __name__ == '__main__':
    main()
