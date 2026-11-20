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

    /* Shell dynamic micro-tilt & sheen ------------------------------------- */
    const shell = document.querySelector('[data-tilt]');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const finePointer = window.matchMedia('(pointer: fine)');

    if (shell && !reduceMotion.matches && finePointer.matches) {
        let targetRx = 0;
        let targetRy = 0;
        let currentRx = 0;
        let currentRy = 0;
        let targetSheen = 0;
        let currentSheen = 0;
        let targetPx = 50;
        let targetPy = 50;
        let currentPx = 50;
        let currentPy = 50;
        let rafId = 0;
        let isHovered = false;

        const updateTilt = () => {
            const ease = 0.1;
            currentRx += (targetRx - currentRx) * ease;
            currentRy += (targetRy - currentRy) * ease;
            currentSheen += (targetSheen - currentSheen) * ease;
            currentPx += (targetPx - currentPx) * ease;
            currentPy += (targetPy - currentPy) * ease;

            shell.style.setProperty('--tilt-x', `${currentRx.toFixed(2)}deg`);
            shell.style.setProperty('--tilt-y', `${currentRy.toFixed(2)}deg`);
            shell.style.setProperty('--pointer-x', `${currentPx.toFixed(1)}%`);
            shell.style.setProperty('--pointer-y', `${currentPy.toFixed(1)}%`);
            shell.style.setProperty('--sheen-opacity', currentSheen.toFixed(3));

            const isSettled = Math.abs(targetRx - currentRx) < 0.01
                && Math.abs(targetRy - currentRy) < 0.01
                && Math.abs(targetSheen - currentSheen) < 0.01;

            if (!isSettled || isHovered) {
                rafId = window.requestAnimationFrame(updateTilt);
            } else {
                rafId = 0;
            }
        };

        const scheduleUpdate = () => {
            if (!rafId) {
                rafId = window.requestAnimationFrame(updateTilt);
            }
        };

        const onPointerMove = (event) => {
            if (reduceMotion.matches) return;

            const rect = shell.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            // Micro-tilt range clamped to [-1.6deg, 1.6deg]
            const maxTilt = 1.6;
            const normX = (event.clientX - centerX) / (window.innerWidth / 2);
            const normY = (event.clientY - centerY) / (window.innerHeight / 2);

            targetRy = Math.max(-maxTilt, Math.min(maxTilt, normX * maxTilt));
            targetRx = Math.max(-maxTilt, Math.min(maxTilt, -normY * maxTilt));

            // Inside or near shell bounds?
            const margin = 80;
            const inside = event.clientX >= (rect.left - margin)
                && event.clientX <= (rect.right + margin)
                && event.clientY >= (rect.top - margin)
                && event.clientY <= (rect.bottom + margin);

            isHovered = inside;
            targetSheen = inside ? 1 : 0;

            if (inside) {
                const px = ((event.clientX - rect.left) / rect.width) * 100;
                const py = ((event.clientY - rect.top) / rect.height) * 100;
                targetPx = Math.max(0, Math.min(100, px));
                targetPy = Math.max(0, Math.min(100, py));
            }

            scheduleUpdate();
        };

        const onPointerLeave = () => {
            isHovered = false;
            targetRx = 0;
            targetRy = 0;
            targetSheen = 0;
            scheduleUpdate();
        };

        window.addEventListener('pointermove', onPointerMove, { passive: true });
        document.documentElement.addEventListener('pointerleave', onPointerLeave);

        reduceMotion.addEventListener('change', () => {
            if (reduceMotion.matches) {
                if (rafId) {
                    window.cancelAnimationFrame(rafId);
                    rafId = 0;
                }
                shell.style.removeProperty('--tilt-x');
                shell.style.removeProperty('--tilt-y');
                shell.style.removeProperty('--pointer-x');
                shell.style.removeProperty('--pointer-y');
                shell.style.removeProperty('--sheen-opacity');
            }
        });
    }
})();
