function copyWithSelection(value, button) {
    const field = document.createElement('textarea');
    field.value = value;
    field.readOnly = true;
    field.style.cssText = 'position: fixed; inset: 0 auto auto 0; opacity: 0; pointer-events: none';
    document.body.append(field);
    field.focus({ preventScroll: true });
    field.select();

    try {
        return document.execCommand('copy');
    } catch {
        return false;
    } finally {
        field.remove();
        button.focus({ preventScroll: true });
    }
}

document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-copy-value]');
    if (!button) return;

    const status = document.getElementById(button.dataset.copyStatus);
    const value = button.dataset.copyValue;
    status.textContent = '';
    status.hidden = true;

    try {
        await navigator.clipboard.writeText(value);
    } catch {
        if (!copyWithSelection(value, button)) {
            window.prompt('Kopyalamak için kısa bağlantı', value);
            return;
        }
    }

    status.hidden = false;
    status.textContent = 'Bağlantı kopyalandı.';
});
