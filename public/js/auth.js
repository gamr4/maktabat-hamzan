(() => {
    const form = document.querySelector('.auth-form');

    if (!form) {
        return;
    }

    /* Password visibility -------------------------------------------------- */
    const password = form.querySelector('input[name="password"]');
    const toggle = form.querySelector('[data-pw-toggle]');

    if (password && toggle) {
        const icon = toggle.querySelector('i');

        const setRevealed = (revealed) => {
            password.type = revealed ? 'text' : 'password';
            toggle.setAttribute('aria-pressed', revealed ? 'true' : 'false');
            toggle.setAttribute('aria-label', revealed ? 'Masquer le mot de passe' : 'Afficher le mot de passe');

            if (icon) {
                icon.className = revealed ? 'ti ti-eye-off' : 'ti ti-eye';
            }

            toggle.classList.remove('is-pressed');
            // Restart the small press animation without forcing a reflow loop.
            void toggle.offsetWidth;
            toggle.classList.add('is-pressed');
        };

        // Keep the caret and focus in the field while toggling.
        toggle.addEventListener('mousedown', (event) => event.preventDefault());

        toggle.addEventListener('click', () => {
            setRevealed(password.type === 'password');
        });
    }

    /* Caps lock hint ------------------------------------------------------- */
    const caps = form.querySelector('[data-pw-caps]');

    if (password && caps) {
        const updateCaps = (event) => {
            const active = typeof event.getModifierState === 'function'
                && event.getModifierState('CapsLock');

            caps.classList.toggle('is-visible', active);
        };

        password.addEventListener('keydown', updateCaps);
        password.addEventListener('keyup', updateCaps);
        password.addEventListener('blur', () => caps.classList.remove('is-visible'));
    }

    /* Submit feedback ------------------------------------------------------ */
    const button = form.querySelector('[data-submit]');

    if (button) {
        form.addEventListener('submit', () => {
            button.classList.add('is-loading');
            button.setAttribute('aria-busy', 'true');
        });
    }
})();
