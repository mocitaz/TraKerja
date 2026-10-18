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

export function createEntranceObserver({ elements, motion, Observer, targets = element => [element] }) {
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
                const animation = element.animate([
                    { opacity: 0, translate: '0 16px' },
                    { opacity: 1, translate: '0 0' },
                ], { duration: 620, delay: Math.min(sequence++ * 45 + index * 65, 180), easing: 'cubic-bezier(.22,.7,.25,1)', fill: 'backwards' });
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
