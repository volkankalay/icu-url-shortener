document.querySelectorAll('.auth-password-toggle').forEach((button) => {
    const input = document.getElementById(button.getAttribute('aria-controls'));
    button.hidden = false;

    button.addEventListener('click', () => {
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(visible));
    });
});
