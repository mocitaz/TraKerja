import './i18n';
import { toggleTheme } from './theme';

document.querySelector('[data-theme-toggle]')?.addEventListener('click', toggleTheme);
document.querySelector('[data-error-summary]')?.focus({ preventScroll: true });
for (const provider of document.querySelectorAll('[data-auth-provider]')) provider.title = provider.textContent.trim();

for (const button of document.querySelectorAll('[data-password-toggle]')) {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordToggle);
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(visible));
        button.setAttribute('aria-label', visible ? button.dataset.hideLabel : button.dataset.showLabel);
    });
}

for (const details of document.querySelectorAll('.auth-email')) {
    details.addEventListener('toggle', () => {
        if (details.open && !document.querySelector('[data-error-summary]') && !details.contains(document.activeElement)) details.querySelector('input:not([type="hidden"])')?.focus({ preventScroll: true });
    });
}

for (const form of document.querySelectorAll('[data-auth-form]')) {
    form.addEventListener('submit', event => {
        if (form.dataset.submitting === 'true') { event.preventDefault(); return; }
        form.dataset.submitting = 'true';
        const button = event.submitter || form.querySelector('[data-auth-submit]');
        if (!button) return;
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        const label = button.querySelector('[data-submit-label]');
        button.dataset.originalLabel = label?.textContent || '';
        if (label) label.textContent = button.dataset.pendingLabel;
    });
}

window.addEventListener('pageshow', () => {
    for (const form of document.querySelectorAll('[data-auth-form]')) {
        delete form.dataset.submitting;
        for (const button of form.querySelectorAll('[data-auth-submit]')) {
            button.disabled = false;
            button.removeAttribute('aria-busy');
            if (button.dataset.originalLabel) button.querySelector('[data-submit-label]').textContent = button.dataset.originalLabel;
        }
    }
});

for (const form of document.querySelectorAll('[data-otp-resend]')) {
    const availableAt = Date.now() + Number(form.dataset.wait) * 1000;
    const button = form.querySelector('[data-auth-submit]');
    const hint = form.querySelector('[data-otp-countdown]');
    const update = () => {
        const remaining = Math.max(0, Math.ceil((availableAt - Date.now()) / 1000));
        if (form.dataset.submitting !== 'true') button.disabled = remaining > 0;
        hint.textContent = remaining ? hint.dataset.template.replace(':seconds', remaining) : '';
    };
    update();
    const timer = window.setInterval(() => {
        update();
        if (Date.now() >= availableAt) window.clearInterval(timer);
    }, 1000);
    window.addEventListener('pageshow', update);
}

document.addEventListener('dragstart', event => { if (event.target.closest('img, svg')) event.preventDefault(); });
