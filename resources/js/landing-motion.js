export function createDemoPlayer({ onChange, schedule = fn => setTimeout(fn, 2200), cancel = clearTimeout }) {
    let step = 0, playing = false, timer;
    const emit = () => onChange({ step, playing });
    const clear = () => { if (timer !== undefined) cancel(timer); timer = undefined; };
    const tick = () => {
        timer = schedule(() => {
            if (!playing) return;
            step = Math.min(3, step + 1);
            if (step === 3) playing = false;
            emit();
            if (playing) tick();
        });
    };
    return {
        play() { clear(); if (step === 3) step = 0; playing = true; emit(); tick(); },
        pause() { clear(); playing = false; emit(); },
        select(index) { clear(); playing = false; step = Math.max(0, Math.min(3, Math.trunc(index))); emit(); },
        destroy() { clear(); playing = false; },
    };
}

export function pointerOffset(event, rect, reduced = false) {
    if (reduced || event.pointerType !== 'mouse' || !rect.width || !rect.height) return { x: 0, y: 0 };
    const clamp = value => Math.max(-1, Math.min(1, value));
    return { x: clamp((event.clientX - rect.left) / rect.width * 2 - 1), y: clamp((event.clientY - rect.top) / rect.height * 2 - 1) };
}

export function reviewPositions(index) {
    const active = ((index % 3) + 3) % 3;
    return Array.from({ length: 3 }, (_, i) => i === active ? 'center' : i === (active + 2) % 3 ? 'before' : 'after');
}

export function productEntranceEffect(kind, index = 0) {
    const easing = 'cubic-bezier(.16,1,.3,1)';
    if (kind === 'headline') return {
        frames: [
            { opacity: 0, translate: '0 28px', clipPath: 'inset(100% 0 -16% 0)' },
            { opacity: 1, translate: '0 0', clipPath: 'inset(-8% 0 -16% 0)' },
        ],
        options: { duration: 900, delay: Math.min(index * 90, 270), easing },
    };
    if (kind === 'window') return {
        frames: [
            { opacity: 0, transform: 'perspective(1200px) translateY(44px) rotateX(7deg) scale(.975)' },
            { opacity: 1, transform: 'perspective(1200px) translateY(0) rotateX(0) scale(1)' },
        ],
        options: { duration: 1050, delay: 60, easing },
    };
    if (kind === 'track') return {
        frames: [{ transform: 'scaleX(0)' }, { transform: 'scaleX(1)' }],
        options: { duration: 650, delay: Math.min(480 + index * 35, 850), easing },
    };
    if (kind === 'panel') return {
        frames: [{ opacity: 0, translate: '0 12px' }, { opacity: 1, translate: '0 0' }],
        options: { duration: 420, delay: 0, easing },
    };

    return {
        frames: [
            { opacity: 0, translate: kind === 'row' ? '18px 0' : '0 14px' },
            { opacity: 1, translate: '0 0' },
        ],
        options: { duration: 650, delay: Math.min(120 + index * 65, 720), easing },
    };
}

export function storyEntranceEffect(kind, index = 0) {
    const easing = 'cubic-bezier(.16,1,.3,1)';
    if (kind === 'headline') return productEntranceEffect('headline', index);
    if (kind === 'arc') return {
        frames: [{ strokeDasharray: '1', strokeDashoffset: '1', opacity: 0 }, { strokeDasharray: '1', strokeDashoffset: '0', opacity: 1 }],
        options: { duration: 1100, delay: 0, easing },
    };
    if (kind === 'node') return {
        frames: [{ opacity: 0, translate: '0 20px', scale: '.88' }, { opacity: 1, translate: '0 0', scale: '1' }],
        options: { duration: 720, delay: Math.min(100 + index * 95, 600), easing },
    };
    if (kind === 'portrait') return {
        frames: [{ opacity: 0, scale: '.84', rotate: '-6deg' }, { opacity: 1, scale: '1', rotate: '0deg' }],
        options: { duration: 800, delay: 180, easing },
    };
    return {
        frames: [{ opacity: 0, translate: '0 16px' }, { opacity: 1, translate: '0 0' }],
        options: { duration: 760, delay: Math.min(index * 100, 380), easing },
    };
}

export function preparationEntranceEffect(kind, index = 0) {
    const easing = 'cubic-bezier(.16,1,.3,1)';
    if (kind === 'headline') return productEntranceEffect('headline', index);
    if (kind === 'person') return {
        frames: [{ opacity: 0, translate: '28px 30px', scale: '1.035' }, { opacity: 1, translate: '0 0', scale: '1' }],
        options: { duration: 1150, delay: 0, easing },
    };
    if (kind === 'review') return {
        frames: [{ opacity: 0, transform: 'perspective(900px) translateY(34px) rotateY(-9deg) rotateZ(-3deg) scale(.96)' }, { opacity: 1, transform: 'perspective(900px) translateY(0) rotateY(0) rotateZ(0) scale(1)' }],
        options: { duration: 950, delay: 230, easing },
    };
    if (kind === 'interview') return {
        frames: [{ opacity: 0, translate: '-22px 0', scale: '.94' }, { opacity: 1, translate: '0 0', scale: '1' }],
        options: { duration: 750, delay: 100, easing },
    };
    if (kind === 'check') return {
        frames: [{ opacity: 0, scale: '.5', rotate: '-25deg' }, { opacity: 1, scale: '1', rotate: '0deg' }],
        options: { duration: 500, delay: 580, easing },
    };
    return {
        frames: [{ opacity: 0, translate: '0 14px' }, { opacity: 1, translate: '0 0' }],
        options: { duration: 700, delay: Math.min(index * 70, 600), easing },
    };
}

export function plansEntranceEffect(kind, index = 0, column = 1) {
    const easing = 'cubic-bezier(.16,1,.3,1)';
    if (kind === 'headline') return {
        frames: [{ opacity: 0, clipPath: 'inset(-12% 50% -12% 50%)', scale: '.97' }, { opacity: 1, clipPath: 'inset(-12% -2% -12% -2%)', scale: '1' }],
        options: { duration: 950, delay: Math.min(index * 110, 220), easing },
    };
    if (kind === 'card') {
        const direction = 1 - column;
        return {
            frames: [{ opacity: 0, transform: `perspective(1100px) translate(${direction * 12}px, 32px) rotateY(${direction * 6}deg) scale(.965)` }, { opacity: 1, transform: 'perspective(1100px) translate(0, 0) rotateY(0) scale(1)' }],
            options: { duration: 1000, delay: column * 100, easing },
        };
    }
    if (kind === 'price') return {
        frames: [{ opacity: 0, translate: '0 12px', scale: '.92' }, { opacity: 1, translate: '0 0', scale: '1' }],
        options: { duration: 800, delay: 220 + column * 100, easing },
    };
    return {
        frames: [{ opacity: 0, translate: kind === 'feature' ? '-10px 0' : '0 10px' }, { opacity: 1, translate: '0 0' }],
        options: { duration: 600, delay: Math.min(100 + index * 55 + column * 80, 800), easing },
    };
}

export function reviewEntranceEffect(kind, index = 0, direction = 1) {
    const easing = 'cubic-bezier(.16,1,.3,1)';
    if (kind === 'headline') return {
        frames: [{ opacity: 0, translate: `${direction * 24}px 0`, clipPath: 'inset(-12% 0 -12% 100%)' }, { opacity: 1, translate: '0 0', clipPath: 'inset(-12% -2% -12% -2%)' }],
        options: { duration: 950, delay: Math.min(index * 100, 220), easing },
    };
    if (kind === 'card') return {
        frames: [{ opacity: 0, translate: `${direction * 18}px 36px`, rotate: `${direction * 4}deg`, scale: '.94' }, { opacity: 1, translate: '0 0', rotate: '0deg', scale: '1' }],
        options: { duration: 1000, delay: Math.min(index * 100, 200), easing },
    };
    if (kind === 'photo') return {
        frames: [{ clipPath: direction > 0 ? 'polygon(0 0, 0 0, 0 100%, 0 100%)' : 'polygon(100% 0, 100% 0, 100% 100%, 100% 100%)', scale: '1.08' }, { clipPath: 'polygon(0 0, 100% 0, 100% 100%, 0 100%)', scale: '1' }],
        options: { duration: 1000, delay: 80, easing },
    };
    if (kind === 'quote') return {
        frames: [{ opacity: 0, translate: '0 22px', transform: 'skewY(2deg)' }, { opacity: 1, translate: '0 0', transform: 'skewY(0)' }],
        options: { duration: 800, delay: 260, easing },
    };
    return {
        frames: [{ opacity: 0, translate: `${direction * 12}px 0` }, { opacity: 1, translate: '0 0' }],
        options: { duration: 650, delay: Math.min(180 + index * 60, 450), easing },
    };
}

export function createEntranceObserver({ elements, motion, Observer, targets = element => [element], effect }) {
    if (!Observer) return () => {};
    const revealed = new Set(), animations = new Set();
    const stop = () => { if (motion.matches) animations.forEach(animation => animation.cancel()); };
    const observer = new Observer(entries => {
        let sequence = 0;
        for (const { target, isIntersecting } of entries) {
            if (!isIntersecting || revealed.has(target)) continue;
            revealed.add(target);
            observer.unobserve(target);
            target.dataset.revealed = 'true';
            if (motion.matches) continue;
            targets(target).forEach((element, index) => {
                if (typeof element.animate !== 'function') return;
                const preset = effect?.(element, index);
                const animation = element.animate(preset?.frames ?? [
                    { opacity: 0, translate: '0 16px' },
                    { opacity: 1, translate: '0 0' },
                ], { duration: 620, delay: Math.min(sequence++ * 45 + index * 65, 180), easing: 'cubic-bezier(.22,.7,.25,1)', ...preset?.options, fill: 'backwards' });
                animations.add(animation);
                animation.finished.catch(() => {}).finally(() => animations.delete(animation));
            });
        }
    }, { threshold: 0, rootMargin: '0px 0px -32px 0px' });
    elements.forEach(element => observer.observe(element));
    motion.addEventListener('change', stop);
    return () => {
        observer.disconnect();
        animations.forEach(animation => animation.cancel());
        motion.removeEventListener('change', stop);
    };
}
