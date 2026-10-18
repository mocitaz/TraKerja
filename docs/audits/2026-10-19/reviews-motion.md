# Testimonial motion

Direction: retain the existing TraKerja plum typography, testimonial content, portrait imagery, and center-card hierarchy. ENERGY 2 / RHYTHM 2 / MOTION 2. The testimonial treatment uses an editorial page-reveal motif rather than repeating the other sections' vertical entrances.

On entrance, the headline reveals horizontally and the cards settle from a slight fan arrangement. The selected portrait wipes into view, followed by the author, quotation and signature. On navigation, measured card positions and sizes animate into their new grid positions; the selected story gets a directional portrait reveal and a subtly skewed quotation entrance. Rapid changes cancel outstanding motion before starting the next transition. No automatic carousel rotation was added. The active author's name now explicitly uses white to remain readable over the dark photo treatment in light mode.

- Hard Gate PASS: 24 card/viewport/theme checks at widths 320, 390, 820, and 1440 found no final horizontal overflow, invisible quotation, residual card transform, or multiple active cards. Existing content, images, brand palette, navigation labels and live status remain intact; the active author's name uses white for contrast.
- Purpose Gate PASS: measured position transitions explain which story moves into focus; the portrait wipe and delayed quotation establish the photo, person and statement in sequence. Motion is finite and adds no layout spacing or new visual assets.
- Liveliness PASS: the active portrait remains the focal point and the page-reveal gesture provides a distinct motif within the existing brand composition. The stated dials match the requested motion.
- Craftsmanship PASS: browser checks covered scroll, refresh, no repeated entrance on rescroll, next/previous and wraparound, keyboard arrows, ten rapid clicks, touch swipe, reduced motion at load, switching preferences during a transition, and static no-JavaScript content. No page errors were recorded. Final desktop light and mobile dark screenshots were inspected. Nine UI tests, production build and whitespace checks passed.

Evidence is saved in `output/reviews-motion/`.
