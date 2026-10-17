import { t } from './i18n.js';
const tones = {
    success: { icon: 'ph-check', label: t("Success") },
    error: { icon: 'ph-warning-circle', label: t("Error") },
    warning: { icon: 'ph-warning', label: t("Notice") },
    info: { icon: 'ph-info', label: t("Update") },
};

export function installToasts(win, doc) {
    if (win.__trakerjaToasts) return;
    win.__trakerjaToasts = true;
    const active = new Set();

    win.showToast = (type, title, message, duration = 3500, customIcon = null) => {
        const container = doc.getElementById('toast-container');
        if (!container) return;
        if (!message && title) [message, title] = [title, ''];
        if (!message && !title) return;
        const tone = Object.hasOwn(tones, type) ? type : 'info';
        const config = tones[tone];
        const element = (tag, className, text) => {
            const node = doc.createElement(tag);
            node.className = className;
            if (text != null) node.textContent = String(text);
            return node;
        };
        const toast = element('div', `action-toast action-toast--${tone}`);
        toast.setAttribute('role', tone === 'error' ? 'alert' : 'status');
        toast.setAttribute('aria-atomic', 'true');
        const icon = element('span', 'action-toast__icon');
        icon.setAttribute('aria-hidden', 'true');
        const mark = element('i', 'ph-bold');
        const iconClasses = String(customIcon || config.icon).split(/\s+/).filter(name => /^ph-[a-z-]+$/.test(name));
        mark.classList.add(...iconClasses);
        icon.appendChild(mark);
        const content = element('div', 'action-toast__content');
        content.appendChild(element('p', 'action-toast__title', title || config.label));
        if (message) content.appendChild(element('p', 'action-toast__message', message));
        const close = element('button', 'action-toast__close');
        close.type = 'button';
        close.setAttribute('aria-label', t("Dismiss notification"));
        close.appendChild(element('i', 'ph ph-x'));
        close.firstChild.setAttribute('aria-hidden', 'true');
        toast.append(icon, content, close);

        const parsedDuration = Number(duration);
        let remaining = Number.isFinite(parsedDuration) ? Math.max(0, parsedDuration) : 3500;
        const total = remaining;
        let timer = null;
        let started = 0;
        let dismissed = false;
        let hovered = false;
        let focused = false;
        const progress = element('span', 'action-toast__progress');
        progress.setAttribute('aria-hidden', 'true');
        if (total > 0) toast.appendChild(progress);

        const entry = { dismiss };
        function dismiss(immediate = false) {
            if (dismissed) return;
            dismissed = true;
            win.clearTimeout(timer);
            active.delete(entry);
            if (immediate) return toast.remove();
            toast.classList.add('is-leaving');
            win.setTimeout(() => toast.remove(), 180);
        }
        function pause() {
            if (timer === null) return;
            remaining = Math.max(0, remaining - (win.performance.now() - started));
            win.clearTimeout(timer);
            timer = null;
            progress.style.animationPlayState = 'paused';
        }
        function resume() {
            if (!total || dismissed || hovered || focused || doc.hidden || timer !== null) return;
            started = win.performance.now();
            progress.style.animationPlayState = 'running';
            timer = win.setTimeout(() => dismiss(), remaining);
        }
        entry.pause = pause;
        entry.resume = resume;
        close.addEventListener('click', () => dismiss());
        toast.addEventListener('mouseenter', () => { hovered = true; pause(); });
        toast.addEventListener('mouseleave', () => { hovered = false; resume(); });
        toast.addEventListener('focusin', () => { focused = true; pause(); });
        toast.addEventListener('focusout', event => {
            if (toast.contains(event.relatedTarget)) return;
            focused = false;
            resume();
        });
        toast.addEventListener('keydown', event => {
            if (event.key === 'Escape') { event.stopPropagation(); dismiss(); }
        });
        progress.style.animationDuration = `${total}ms`;
        progress.style.animationPlayState = 'paused';
        active.add(entry);
        container.appendChild(toast);
        resume();
    };

    const notify = payload => {
        const data = Array.isArray(payload) ? payload[0] : payload;
        if (typeof data === 'string') return win.showToast('info', '', data);
        if (!data) return;
        win.showToast(data.type || 'info', data.title || '', data.message || data.body || '', data.duration ?? 3500, data.icon || null);
    };
    win.showToastNotification = notify;
    win.addEventListener('showNotification', event => notify(event.detail));
    win.addEventListener('showToast', event => notify(event.detail));
    doc.addEventListener('visibilitychange', () => active.forEach(entry => doc.hidden ? entry.pause() : entry.resume()));
    doc.addEventListener('livewire:navigating', () => active.forEach(entry => entry.dismiss(true)));
}
