import './i18n';
import { toggleTheme } from './theme';
import { createDemoPlayer, pointerOffset, reviewPositions, createEntranceObserver, productEntranceEffect, storyEntranceEffect, preparationEntranceEffect, plansEntranceEffect, reviewEntranceEffect } from './landing-motion';

document.querySelector('[data-theme-toggle]')?.addEventListener('click', toggleTheme);

document.addEventListener('dragstart', event => {
    if (event.target.closest('img, picture, svg') || event.target.closest('a')?.querySelector('img, svg')) {
        event.preventDefault();
    }
});


const sources = document.querySelector('[data-source-marquee]');
const sourceToggle = sources?.querySelector('[data-source-toggle]');
sourceToggle?.addEventListener('click', () => {
    const paused = sources.dataset.paused !== 'true';
    sources.dataset.paused = String(paused);
    sourceToggle.setAttribute('aria-pressed', String(paused));
    sourceToggle.querySelector('span').textContent = paused ? sourceToggle.dataset.resume : sourceToggle.dataset.pause;
    sourceToggle.querySelector('path').setAttribute('d', paused ? 'm9 5 10 7-10 7V5Z' : 'M8 5v14M16 5v14');
});

const menu = document.querySelector('.lp-mobile-menu');
menu?.addEventListener('click', event => { if (event.target.closest('a')) menu.open = false; });
document.addEventListener('click', event => { if (menu?.open && !menu.contains(event.target)) menu.open = false; });
document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && menu?.open) { menu.open = false; menu.querySelector('summary').focus(); }
});

function selectTab(group, key, focus = false) {
    for (const tab of group.querySelectorAll('[role="tab"]')) {
        const active = tab.dataset.tab === String(key);
        const changed = active && tab.getAttribute('aria-selected') !== 'true';
        tab.setAttribute('aria-selected', String(active));
        tab.tabIndex = active ? 0 : -1;
        const panel = document.getElementById(tab.getAttribute('aria-controls'));
        if (panel) panel.hidden = !active;
        if (changed && panel && group.dataset.tabs === 'product') animateDemoPanel(panel);
        if (active && focus) tab.focus();
    }
}

for (const group of document.querySelectorAll('[data-tabs]')) {
    const tabs = [...group.querySelectorAll('[role="tab"]')];
    group.addEventListener('click', event => {
        const tab = event.target.closest('[role="tab"]');
        if (tab) selectTab(group, tab.dataset.tab);
    });
    group.addEventListener('keydown', event => {
        const index = tabs.indexOf(document.activeElement);
        if (index < 0 || !['ArrowRight', 'ArrowLeft', 'Home', 'End'].includes(event.key)) return;
        event.preventDefault();
        const next = event.key === 'Home' ? 0 : event.key === 'End' ? tabs.length - 1 : (index + (event.key === 'ArrowRight' ? 1 : -1) + tabs.length) % tabs.length;
        selectTab(group, tabs[next].dataset.tab, true);
    });
}

const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
const demo = document.querySelector('[data-demo-player]');
const demoAnimations = new Set();
function animateDemoPanel(panel) {
    demoAnimations.forEach(animation => animation.cancel());
    if (motion.matches || typeof panel.animate !== 'function') return;
    const preset = productEntranceEffect('panel');
    const animation = panel.animate(preset.frames, { ...preset.options, fill: 'backwards' });
    demoAnimations.add(animation);
    animation.finished.catch(() => {}).finally(() => demoAnimations.delete(animation));
}
motion.addEventListener('change', () => { if (motion.matches) demoAnimations.forEach(animation => animation.cancel()); });
window.addEventListener('pagehide', () => demoAnimations.forEach(animation => animation.cancel()), { once: true });
if (demo) {
    const play = demo.querySelector('[data-play]');
    const group = demo.querySelector('[data-tabs="product"]');
    let state = { step: 0, playing: false };
    const player = createDemoPlayer({ onChange(next) {
        const changeScene = next.playing || next.step !== state.step;
        state = next;
        demo.querySelector('.lp-demo-window').dataset.demoStep = String(state.step);
        const label = state.playing ? play.dataset.labelPause : state.step === 3 ? play.dataset.labelReplay : play.dataset.labelPlay;
        play.querySelector('span').textContent = label;
        play.querySelector('path').setAttribute('d', state.playing ? 'M8 5v14M16 5v14' : 'm9 5 10 7-10 7V5Z');
        play.setAttribute('aria-label', label);
        play.setAttribute('aria-pressed', String(state.playing));
        for (const step of demo.querySelectorAll('[data-step]')) step.setAttribute('aria-current', Number(step.dataset.step) === state.step ? 'step' : 'false');
        for (const text of demo.querySelectorAll('[data-step-copy]')) text.hidden = Number(text.dataset.stepCopy) !== state.step;
        if (changeScene) selectTab(group, ['tracker', 'calendar', 'ai', 'tracker'][state.step]);
    } });
    play.addEventListener('click', () => state.playing ? player.pause() : player.play());
    for (const step of demo.querySelectorAll('[data-step]')) step.addEventListener('click', () => {
        const index = Number(step.dataset.step);
        player.select(index);
        selectTab(group, ['tracker', 'calendar', 'ai', 'tracker'][index]);
    });
    group.addEventListener('click', () => player.pause());
    group.addEventListener('keydown', event => { if (['ArrowRight', 'ArrowLeft', 'Home', 'End'].includes(event.key)) player.pause(); });
    document.querySelector('[data-open-demo]')?.addEventListener('click', () => {
        if (!motion.matches) player.play();
    });
    document.addEventListener('visibilitychange', () => { if (document.hidden) player.pause(); });
    motion.addEventListener('change', event => { if (event.matches) player.pause(); });
    window.addEventListener('pagehide', () => player.destroy(), { once: true });
}

const art = document.querySelector('[data-perspective]');
const resetArt = () => { art?.style.setProperty('--pointer-x', 0); art?.style.setProperty('--pointer-y', 0); };
art?.addEventListener('pointermove', event => {
    const point = pointerOffset(event, art.getBoundingClientRect(), motion.matches);
    art.style.setProperty('--pointer-x', point.x);
    art.style.setProperty('--pointer-y', point.y);
});
art?.addEventListener('pointerleave', resetArt);
motion.addEventListener('change', resetArt);

const reviews = document.querySelector('[data-review-carousel]');
if (reviews) {
    const cards = [...reviews.querySelectorAll('[data-review-card]')];
    const count = reviews.querySelector('[data-review-count]');
    const transitions = new Set();
    const cancelTransitions = () => transitions.forEach(animation => animation.cancel());
    const animate = (element, frames, options) => {
        if (motion.matches || typeof element.animate !== 'function') return;
        const animation = element.animate(frames, { ...options, fill: 'backwards' });
        transitions.add(animation);
        animation.finished.catch(() => {}).finally(() => transitions.delete(animation));
    };
    let active = 1, touchStart;
    const move = direction => {
        const previous = cards.map(card => card.getBoundingClientRect());
        reviews.getAnimations({ subtree: true }).forEach(animation => animation.cancel());
        active = (active + direction + cards.length) % cards.length;
        const positions = reviewPositions(active);
        cards.forEach((card, index) => {
            card.dataset.position = positions[index];
            card.setAttribute('aria-hidden', String(index !== active));
        });
        count.textContent = count.dataset.countLabel.replace(':current', active + 1).replace(':total', cards.length);
        cards.forEach((card, index) => {
            const before = previous[index], after = card.getBoundingClientRect();
            if (!after.width || !before.width) return;
            animate(card, [
                { transformOrigin: '0 0', transform: `translate(${before.left - after.left}px, ${before.top - after.top}px) scale(${before.width / after.width}, ${before.height / after.height})` },
                { transformOrigin: '0 0', transform: 'translate(0, 0) scale(1, 1)' },
            ], { duration: 780, easing: 'cubic-bezier(.22,1,.36,1)' });
        });
        const selected = cards[active];
        for (const [selector, kind] of [['.lp-review-photo', 'photo'], ['.lp-review-author', 'content'], ['blockquote', 'quote'], ['.lp-review-footer', 'content']]) {
            const element = selected.querySelector(selector);
            const preset = reviewEntranceEffect(kind, 0, direction);
            animate(element, preset.frames, preset.options);
        }
    };
    motion.addEventListener('change', () => { if (motion.matches) cancelTransitions(); });
    window.addEventListener('pagehide', cancelTransitions, { once: true });
    reviews.querySelector('[data-review-previous]').addEventListener('click', () => move(-1));
    reviews.querySelector('[data-review-next]').addEventListener('click', () => move(1));
    reviews.addEventListener('keydown', event => {
        if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') { event.preventDefault(); move(event.key === 'ArrowLeft' ? -1 : 1); }
    });
    const grid = reviews.querySelector('.lp-review-grid');
    grid.addEventListener('pointerdown', event => { if (event.pointerType === 'touch') touchStart = { x: event.clientX, y: event.clientY }; });
    grid.addEventListener('pointerup', event => {
        if (!touchStart) return;
        const x = event.clientX - touchStart.x, y = event.clientY - touchStart.y;
        touchStart = null;
        if (Math.abs(x) > 40 && Math.abs(x) > Math.abs(y)) move(x < 0 ? 1 : -1);
    });
    grid.addEventListener('pointercancel', () => { touchStart = null; });
}

const heroTitle = document.querySelector('[data-hero-running]');
if (heroTitle && 'IntersectionObserver' in window) {
    const titleAnimations = new Set();
    const titleObserver = new IntersectionObserver(entries => {
        if (!entries.some(entry => entry.isIntersecting)) return;
        titleObserver.disconnect();
        if (motion.matches || typeof heroTitle.animate !== 'function') return;
        const accent = getComputedStyle(document.documentElement).getPropertyValue('--lp-plum').trim();
        const ink = getComputedStyle(heroTitle).color;
        let delay = 0;
        for (const line of heroTitle.querySelectorAll('.lp-hero-line')) {
            for (const letter of line.querySelectorAll('.lp-hero-letter')) {
                const animation = letter.animate([
                    { opacity: 0, transform: 'translateY(.28em)', color: accent },
                    { opacity: 1, transform: 'translateY(-.025em)', color: accent, offset: .65 },
                    { opacity: 1, transform: 'translateY(0)', color: ink },
                ], { duration: 560, delay, easing: 'cubic-bezier(.22,.7,.25,1)', fill: 'backwards' });
                titleAnimations.add(animation);
                animation.finished.catch(() => {}).finally(() => titleAnimations.delete(animation));
                delay += 32;
            }
            delay += 180;
        }
    }, { threshold: .2 });
    titleObserver.observe(heroTitle);
    const cancelTitle = () => titleAnimations.forEach(animation => animation.cancel());
    motion.addEventListener('change', () => { if (motion.matches) cancelTitle(); });
    window.addEventListener('pagehide', () => { titleObserver.disconnect(); cancelTitle(); }, { once: true });
}

const entranceTargets = '.lp-hero-copy > *, .lp-hero-art, .lp-sources-heading, .lp-sources-rows, .lp-section-top, .lp-product-toolbar, .lp-demo-window, .lp-demo-description, .lp-story-heading, .lp-story-arc, .lp-story-message, .lp-prep-copy, .lp-prep-visual, .lp-plan-notice, .lp-plan, .lp-plans-login, .lp-reviews-heading, .lp-review-stage, .lp-person, .lp-community > *, .lp-faq > div:first-child, .lp-faq-list > details, .lp-footer-top, .lp-footer-bottom';
const stopEntrances = createEntranceObserver({
    elements: [...document.querySelectorAll('[data-reveal]')].flatMap(section => [...section.querySelectorAll(entranceTargets)].filter(element => element.closest('[data-reveal]') === section && !element.matches('[data-hero-running]') && !element.closest('[data-demo-player], .lp-story, .lp-prep-section, .lp-plans-section, .lp-reviews'))),
    motion,
    Observer: window.IntersectionObserver,
});
const stopProductEntrances = createEntranceObserver({
    elements: demo ? [...demo.querySelectorAll('.lp-section-top, .lp-product-toolbar, .lp-demo-window, .lp-demo-description')] : [],
    motion,
    Observer: window.IntersectionObserver,
    targets(container) {
        if (container.matches('.lp-section-top')) return [...container.querySelectorAll('.lp-eyebrow, .lp-heading > span, .lp-section-intro')];
        if (container.matches('.lp-product-toolbar')) return [...container.querySelectorAll('[role="tab"], [data-play]')];
        if (container.matches('.lp-demo-window')) return [
            container,
            container.querySelector('.lp-demo-topbar'),
            ...container.querySelectorAll('.lp-demo-panel:not([hidden]) .lp-preview-title, .lp-demo-panel:not([hidden]) .lp-preview-row, .lp-demo-panel:not([hidden]) .lp-agenda-preview, .lp-demo-panel:not([hidden]) .lp-demo-conversation'),
            ...container.querySelectorAll('.lp-demo-progress button, .lp-progress-track'),
        ].filter(Boolean);
        return [container];
    },
    effect(element, index) {
        const kind = element.matches('.lp-heading > span') ? 'headline'
            : element.matches('.lp-demo-window') ? 'window'
            : element.matches('.lp-progress-track') ? 'track'
            : element.matches('[data-example-row]') ? 'row' : 'content';
        return productEntranceEffect(kind, index);
    },
});
const stopStoryEntrances = createEntranceObserver({
    elements: [...document.querySelectorAll('.lp-story-heading, .lp-story-arc, .lp-story-message')],
    motion,
    Observer: window.IntersectionObserver,
    targets(container) {
        if (container.matches('.lp-story-heading')) return [...container.querySelectorAll('.lp-heading > span, .lp-section-intro')];
        if (container.matches('.lp-story-arc')) return [...container.querySelectorAll('.lp-story-line > path, .lp-story-node')];
        return [...container.querySelectorAll(':scope > p, .lp-story-portrait, .lp-story-founder strong, .lp-story-founder div > span')];
    },
    effect(element, index) {
        const kind = element.matches('.lp-heading > span') ? 'headline'
            : element.matches('.lp-story-line > path') ? 'arc'
            : element.matches('.lp-story-node') ? 'node'
            : element.matches('.lp-story-portrait') ? 'portrait' : 'content';
        return storyEntranceEffect(kind, index);
    },
});
const stopPreparationEntrances = createEntranceObserver({
    elements: [...document.querySelectorAll('.lp-prep-copy, .lp-prep-visual')],
    motion,
    Observer: window.IntersectionObserver,
    targets(container) {
        if (container.matches('.lp-prep-copy')) return [...container.querySelectorAll('.lp-eyebrow, .lp-heading > span, .lp-section-intro, .lp-button, .lp-prep-tools > span, .lp-prep-note')];
        return [...container.querySelectorAll('.lp-prep-person, .lp-prep-interview, .lp-prep-review, .lp-prep-interview > .lp-icon:last-child, .lp-prep-review-top, .lp-prep-review > strong, .lp-prep-nara')];
    },
    effect(element, index) {
        const kind = element.matches('.lp-heading > span') ? 'headline'
            : element.matches('.lp-prep-person') ? 'person'
            : element.matches('.lp-prep-review') ? 'review'
            : element.matches('.lp-prep-interview') ? 'interview'
            : element.matches('.lp-prep-interview > .lp-icon:last-child') ? 'check' : 'content';
        return preparationEntranceEffect(kind, index);
    },
});
const stopPlansEntrances = createEntranceObserver({
    elements: [...document.querySelectorAll('.lp-plans-section .lp-section-top, .lp-plan-notice, .lp-plan, .lp-plans-login')],
    motion,
    Observer: window.IntersectionObserver,
    targets(container) {
        if (container.matches('.lp-section-top')) return [...container.querySelectorAll('.lp-eyebrow, .lp-heading > span, .lp-section-intro')];
        if (container.matches('.lp-plan')) return [container, ...container.querySelectorAll('h3, .lp-plan-price, .lp-plan-rate > span, .lp-plan-intro, li, .lp-button, .lp-plan-availability, .lp-plan-terms')];
        return [container];
    },
    effect(element, index) {
        const card = element.closest('.lp-plan');
        const column = card ? [...card.parentElement.children].indexOf(card) : 0;
        const kind = element.matches('.lp-heading > span') ? 'headline'
            : element.matches('.lp-plan') ? 'card'
            : element.matches('.lp-plan-price') ? 'price'
            : element.matches('li') ? 'feature' : 'content';
        return plansEntranceEffect(kind, index, column);
    },
});
const stopReviewEntrances = createEntranceObserver({
    elements: [...document.querySelectorAll('.lp-reviews-heading, .lp-review-stage')],
    motion,
    Observer: window.IntersectionObserver,
    targets(container) {
        if (container.matches('.lp-reviews-heading')) return [...container.querySelectorAll('.lp-eyebrow, .lp-heading > span')];
        return [...container.querySelectorAll('.lp-review-card, .lp-review-card[data-position="center"] .lp-review-photo, .lp-review-card[data-position="center"] .lp-review-author, .lp-review-card[data-position="center"] blockquote, .lp-review-card[data-position="center"] .lp-review-footer, .lp-review-nav')];
    },
    effect(element, index) {
        const kind = element.matches('.lp-heading > span') ? 'headline'
            : element.matches('.lp-review-card') ? 'card'
            : element.matches('.lp-review-photo') ? 'photo'
            : element.matches('blockquote') ? 'quote' : 'content';
        const direction = element.matches('[data-position="before"]') ? -1 : 1;
        return reviewEntranceEffect(kind, index, direction);
    },
});
document.addEventListener('focusin', event => {
    for (let element = event.target; element && element !== document.body; element = element.parentElement) {
        element.getAnimations().forEach(animation => animation.finish());
    }
});
window.addEventListener('pagehide', stopEntrances, { once: true });
window.addEventListener('pagehide', stopProductEntrances, { once: true });
window.addEventListener('pagehide', stopStoryEntrances, { once: true });
window.addEventListener('pagehide', stopPreparationEntrances, { once: true });
window.addEventListener('pagehide', stopPlansEntrances, { once: true });
window.addEventListener('pagehide', stopReviewEntrances, { once: true });

const footerBrand = document.querySelector('[data-footer-wordmark]');
if (footerBrand && typeof footerBrand.animate === 'function') {
    const letters = [...footerBrand.querySelectorAll('.lp-footer-letter')];
    const animations = new Set();
    let lastWave = -Infinity;
    const animate = (element, frames, options) => {
        const animation = element.animate(frames, options);
        animations.add(animation);
        animation.finished.catch(() => {}).finally(() => animations.delete(animation));
    };
    const wave = (entrance = false) => {
        if (motion.matches || performance.now() - lastWave < 1000) return;
        lastWave = performance.now();
        const lift = Math.min(20, footerBrand.querySelector('.lp-footer-name').getBoundingClientRect().height * .11);
        const color = getComputedStyle(footerBrand).color;
        const accent = getComputedStyle(document.documentElement).getPropertyValue('--lp-plum').trim();
        letters.forEach((letter, index) => {
            const frames = entrance ? [
                { transform: 'translateY(32px) rotateX(35deg)', opacity: 0, color },
                { transform: 'translateY(-5px) rotateX(0deg)', opacity: 1, color: accent, offset: .65 },
                { transform: 'translateY(0) rotateX(0deg)', opacity: 1, color },
            ] : [
                { transform: 'translateY(0)', color },
                { transform: `translateY(${-lift}px) rotate(-2deg)`, color: accent, offset: .42 },
                { transform: 'translateY(0) rotate(0deg)', color },
            ];
            animate(letter, frames, { duration: entrance ? 850 : 650, delay: index * 45, easing: 'cubic-bezier(.22,.7,.25,1)' });
        });
    };
    const reset = () => {
        footerBrand.style.setProperty('--brand-x', 0);
        footerBrand.style.setProperty('--brand-y', 0);
    };
    footerBrand.addEventListener('pointerenter', event => { if (event.pointerType === 'mouse') wave(); });
    footerBrand.addEventListener('focus', () => wave());
    footerBrand.addEventListener('pointermove', event => {
        const point = pointerOffset(event, footerBrand.getBoundingClientRect(), motion.matches);
        footerBrand.style.setProperty('--brand-x', point.x);
        footerBrand.style.setProperty('--brand-y', point.y);
    });
    footerBrand.addEventListener('pointerleave', reset);
    motion.addEventListener('change', () => {
        if (motion.matches) animations.forEach(animation => animation.cancel());
        reset();
    });
    if ('IntersectionObserver' in window) {
        const brandObserver = new IntersectionObserver(entries => {
            if (entries.some(entry => entry.isIntersecting)) {
                wave(true);
                brandObserver.disconnect();
            }
        }, { threshold: .5 });
        brandObserver.observe(footerBrand);
    }
}
