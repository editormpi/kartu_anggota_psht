export function attachNikFormatter(input) {
    if (!input) return;

    input.setAttribute('inputmode', 'numeric');
    input.setAttribute('autocomplete', 'off');
    input.setAttribute('maxlength', '19'); // 16 digits + 3 spaces

    input.addEventListener('input', (e) => {
        const digits = e.target.value.replace(/\D/g, '').slice(0, 16);
        e.target.value = digits.replace(/(\d{4})(?=\d)/g, '$1 ');
    });

    const form = input.form;
    if (form) {
        form.addEventListener('submit', () => {
            input.value = input.value.replace(/\s+/g, '');
        });
    }
}

export function attachPasswordToggle(toggle) {
    if (!toggle) return;

    const targetSelector = toggle.getAttribute('data-toggle-target');
    const target = targetSelector ? document.querySelector(targetSelector) : null;
    if (!target) return;

    toggle.addEventListener('click', (e) => {
        e.preventDefault();
        const isPassword = target.type === 'password';
        target.type = isPassword ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', String(isPassword));
        const showIcon = toggle.querySelector('[data-icon="show"]');
        const hideIcon = toggle.querySelector('[data-icon="hide"]');
        if (showIcon && hideIcon) {
            showIcon.classList.toggle('hidden', isPassword);
            hideIcon.classList.toggle('hidden', !isPassword);
        }
    });
}
