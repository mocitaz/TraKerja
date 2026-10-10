# Platform logos

The application uses 31 existing platform choices from `App\Support\PlatformCatalog`. Twenty-five branded icons are stored under `public/images/platforms`; no remote image lookup runs when an application page is rendered.

`public/images/platforms/sources.json` records the source website and exact downloaded URL for each image. Icons normally come from the website's icon metadata. Glassdoor, Jora, and Tech in Asia use Google's cached favicon endpoint because direct downloads were blocked. Google Forms uses the product icon linked by Google's Workspace Forms page. Brand imagery remains owned by the respective platform.

Urbanhire could not be downloaded. The identities of the existing NusaCrowd and SkillTrade choices could not be verified as job platforms. These names remain selectable and use text initials rather than an invented brand logo. Email, Website Company, and Other use generic functional icons. Custom names remain supported without requesting images from user-supplied domains.

Refresh assets manually with `python3 scripts/assets/collect_platform_logos.py` (requires Pillow). Inspect images and provenance before accepting refreshed assets; website icons can change. The import validates image formats and rejects SVG scripting, entities, external references, and embedded images. It is not part of application requests or the build.

Platform logos are displayed without a decorative tile, background, or border, as requested. The native platform dropdown remains available alongside the searchable visual picker.
