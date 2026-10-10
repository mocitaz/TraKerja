const stack = [];
let previousOverflow;
let lastTrigger;
let navigating = false;
const focusables = element => [...element.querySelectorAll('a[href], area[href], button, input:not([type="hidden"]), textarea, select, summary, iframe, object, embed, audio[controls], video[controls], [tabindex], [contenteditable="true"]')]
    .filter(el => el.tabIndex >= 0 && !el.matches(':disabled') && !el.closest('[hidden], [inert], [aria-hidden="true"]') && el.getClientRects().length);
const top = () => stack.at(-1);
const focus = entry => (focusables(entry.element)[0] || entry.element).focus();
const dialogs = {
    open(element, close) {
        if (stack.some(entry => entry.element === element) || !element.isConnected) return;
        if (!stack.length) {
            previousOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';
        }
        element.setAttribute('tabindex', '-1');
        const entry = { element, close, trigger: document.activeElement === document.body ? lastTrigger : document.activeElement };
        stack.push(entry);
        focus(entry);
    },
    close(element, restore = true) {
        const index = stack.findIndex(entry => entry.element === element);
        if (index < 0) return;
        const wasTop = index === stack.length - 1;
        const [entry] = stack.splice(index, 1);
        if (!stack.length) document.body.style.overflow = previousOverflow;
        if (restore && wasTop) {
            if (entry.trigger?.isConnected && (!top() || top().element.contains(entry.trigger))) entry.trigger.focus();
            else if (top()) focus(top());
        }
    },
};
window.TraKerjaDialogs = dialogs;
document.addEventListener('click', event => { lastTrigger = event.target.closest('button, a, input, [tabindex]') || document.activeElement; }, true);
document.addEventListener('keydown', event => {
    const entry = top();
    if (!entry) return;
    if (event.key === 'Escape') {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation?.();
        entry.close();
    } else if (event.key === 'Tab') {
        const items = focusables(entry.element);
        const index = items.indexOf(document.activeElement);
        if (!items.length || index < 0 || (event.shiftKey ? index === 0 : index === items.length - 1)) {
            event.preventDefault();
            (items.length ? items[event.shiftKey ? items.length - 1 : 0] : entry.element).focus();
        }
    }
}, true);
document.addEventListener('focusin', event => {
    if (top() && !top().element.contains(event.target) && event.target !== top().element) focus(top());
});
document.addEventListener('livewire:navigating', () => {
    navigating = true;
    while (stack.length) dialogs.close(top().element, false);
    lastTrigger = null;
});
let registered = false;
function register() {
    if (registered) return;
    registered = true;
    window.Alpine.directive('dialog', (element, { expression }, { evaluate, effect, cleanup }) => {
        let active = false;
        element.setAttribute('role', 'dialog');
        element.setAttribute('aria-modal', 'true');
        element.setAttribute('tabindex', '-1');
        effect(() => {
            active = Boolean(evaluate(expression));
            if (active) window.Alpine.nextTick(() => {
                if (active) dialogs.open(element, () => evaluate(element.dataset.dialogClose || `${expression} = false`));
            });
            else dialogs.close(element);
        });
        cleanup(() => { active = false; dialogs.close(element); });
    });
}
document.addEventListener('alpine:init', register);
if (window.Alpine) register();

// Legacy server-rendered dialogs open by toggling their hidden class.
if (typeof MutationObserver !== 'undefined') {
    const syncNative = () => {
        if (navigating) return;
        for (const entry of [...stack]) {
            if (!entry.element.isConnected) dialogs.close(entry.element);
        }
        document.querySelectorAll('[data-dialog-native]').forEach(element => {
            if (element.classList.contains('hidden') || element.style.display === 'none' || !element.getClientRects().length) dialogs.close(element);
            else dialogs.open(element, () => {
                const handler = window[element.dataset.dialogNative];
                if (typeof handler === 'function') handler(element.dataset.dialogArgument || undefined);
                else element.classList.add('hidden');
            });
        });
    };
    const observer = new MutationObserver(syncNative);
    observer.observe(document.documentElement, { subtree: true, childList: true, attributes: true, attributeFilter: ['class', 'style'] });
    document.addEventListener('DOMContentLoaded', syncNative);
    document.addEventListener('livewire:navigated', () => { navigating = false; syncNative(); });
    syncNative();
}
