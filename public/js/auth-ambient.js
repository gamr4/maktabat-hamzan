(() => {
    /*
     * Papeterie — login ambient field.
     * Recreated from two references, without React or any library:
     *   - React Bits "Particles": drifting motes, twinkle, soft halos,
     *     constellation links and cursor parallax.
     *   - Aceternity "Dotted Glow": a schematic dot lattice lit by a
     *     slow-moving light that also leans toward the pointer.
     * Everything is drawn on one 2D canvas in a single animation loop.
     */
    const canvas = document.querySelector('[data-auth-field]');

    if (!canvas) {
        return;
    }

    const ctx = canvas.getContext('2d', { alpha: true });

    if (!ctx) {
        return;
    }

    const TAU = Math.PI * 2;
    const MAX_DIM = 1920;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const darkMode = window.matchMedia('(prefers-color-scheme: dark)');
    const finePointer = window.matchMedia('(pointer: fine)');

    let width = 0;
    let height = 0;
    let dpr = 1;
    let dots = [];
    let motes = [];
    let dotLayer = null;
    let palette = null;
    let sprites = null;
    let raf = 0;
    let last = 0;
    let running = false;
    let resizeTimer = 0;

    const pointer = { x: 0, y: 0, tx: 0, ty: 0, active: 0, target: 0 };
    const light = { x: 0, y: 0, ax: 0, ay: 0 };

    const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

    /* Small deterministic RNG so the field is stable between reloads. */
    const seeded = (seed) => {
        let state = seed >>> 0;

        return () => {
            state = (state + 0x6d2b79f5) >>> 0;
            let t = state;
            t = Math.imul(t ^ (t >>> 15), t | 1);
            t ^= t + Math.imul(t ^ (t >>> 7), t | 61);

            return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
        };
    };

    const parseHex = (value) => {
        const clean = String(value).trim().replace('#', '');
        const full = clean.length === 3
            ? clean.split('').map((char) => char + char).join('')
            : clean;
        const number = Number.parseInt(full, 16);

        if (Number.isNaN(number)) {
            return [44, 44, 44];
        }

        return [(number >> 16) & 255, (number >> 8) & 255, number & 255];
    };

    const rgba = (rgb, alpha) => `rgba(${rgb[0]}, ${rgb[1]}, ${rgb[2]}, ${alpha})`;

    const readPalette = () => {
        const styles = getComputedStyle(document.documentElement);
        const dark = darkMode.matches;
        const accent = parseHex(styles.getPropertyValue('--color-accent') || '#eb5757');

        return {
            dark,
            accent,
            dot: dark ? [226, 232, 240] : [84, 96, 112],
            line: dark ? [226, 232, 240] : [96, 108, 124],
            dotBase: dark ? 0.1 : 0.14,
            dotMax: dark ? 0.42 : 0.46,
            moteBase: dark ? 0.55 : 0.62,
            linkBase: dark ? 0.13 : 0.14,
        };
    };

    const makeGlow = (rgb) => {
        const size = 64;
        const layer = document.createElement('canvas');
        layer.width = size;
        layer.height = size;
        const glow = layer.getContext('2d');

        if (!glow) {
            return layer;
        }

        const gradient = glow.createRadialGradient(size / 2, size / 2, 0, size / 2, size / 2, size / 2);
        gradient.addColorStop(0, rgba(rgb, 0.85));
        gradient.addColorStop(0.4, rgba(rgb, 0.26));
        gradient.addColorStop(1, rgba(rgb, 0));

        glow.fillStyle = gradient;
        glow.fillRect(0, 0, size, size);

        return layer;
    };

    const spacing = () => (width < 560 ? 26 : width < 960 ? 30 : 34);

    const buildDotLayer = () => {
        const gap = spacing();
        const start = gap / 2;
        const random = seeded(90210);

        dots = [];

        for (let y = start; y < height + gap; y += gap) {
            for (let x = start; x < width + gap; x += gap) {
                dots.push({
                    x,
                    y,
                    r: 0.6 + random() * 0.35,
                    phase: random() * TAU,
                    speed: 0.12 + random() * 0.2,
                    accent: random() < 0.05,
                });
            }
        }

        const layer = document.createElement('canvas');
        layer.width = Math.max(1, Math.round(width * dpr));
        layer.height = Math.max(1, Math.round(height * dpr));
        const base = layer.getContext('2d');

        if (base) {
            base.setTransform(dpr, 0, 0, dpr, 0, 0);
            base.clearRect(0, 0, width, height);
            base.fillStyle = rgba(palette.dot, palette.dotBase);

            for (const dot of dots) {
                base.beginPath();
                base.arc(dot.x, dot.y, dot.r, 0, TAU);
                base.fill();
            }
        }

        dotLayer = layer;
    };

    const buildMotes = () => {
        const random = seeded(77123);
        const count = clamp(Math.round((width * height) / 42000), 14, 52);

        motes = [];

        for (let index = 0; index < count; index += 1) {
            motes.push({
                x: random() * width,
                y: random() * height,
                vx: (random() - 0.5) * 3.2,
                vy: (random() - 0.5) * 3.2,
                r: 0.75 + random() * 1.7,
                depth: 0.2 + random() * 0.8,
                phase: random() * TAU,
                speed: 0.25 + random() * 0.6,
                alpha: 0.45 + random() * 0.55,
                accent: random() < 0.16,
                drawX: 0,
                drawY: 0,
            });
        }
    };

    const rebuildPalette = () => {
        palette = readPalette();
        sprites = {
            light: makeGlow(palette.dark ? [236, 239, 243] : [44, 44, 44]),
            accent: makeGlow(palette.accent),
        };
        buildDotLayer();
    };

    const smooth = (x, y, centerX, centerY, radius) => {
        const dx = x - centerX;
        const dy = y - centerY;
        const limit = radius * radius;
        const distanceSq = (dx * dx) + (dy * dy);

        if (distanceSq >= limit) {
            return 0;
        }

        const t = 1 - Math.sqrt(distanceSq) / radius;

        return t * t * (3 - 2 * t);
    };

    const drawLight = (x, y, radius, rgb, alpha) => {
        if (alpha <= 0) {
            return;
        }

        const gradient = ctx.createRadialGradient(x, y, 0, x, y, radius);
        gradient.addColorStop(0, rgba(rgb, alpha));
        gradient.addColorStop(0.55, rgba(rgb, alpha * 0.35));
        gradient.addColorStop(1, rgba(rgb, 0));
        ctx.fillStyle = gradient;
        ctx.fillRect(x - radius, y - radius, radius * 2, radius * 2);
    };

    const updateMotes = (delta, now) => {
        const parallaxX = pointer.x - width / 2;
        const parallaxY = pointer.y - height / 2;

        for (const mote of motes) {
            mote.x += mote.vx * delta;
            mote.y += mote.vy * delta;

            if (mote.x < -24) {
                mote.x = width + 24;
            } else if (mote.x > width + 24) {
                mote.x = -24;
            }

            if (mote.y < -24) {
                mote.y = height + 24;
            } else if (mote.y > height + 24) {
                mote.y = -24;
            }

            mote.drawX = mote.x + parallaxX * 0.008 * mote.depth;
            mote.drawY = mote.y
                + parallaxY * 0.008 * mote.depth
                + Math.sin(mote.phase + now * 0.0002) * 2;
        }
    };

    const render = (now) => {
        const reduced = reduceMotion.matches;
        const time = reduced ? 0 : now;
        const seconds = time * 0.001;

        ctx.clearRect(0, 0, width, height);

        if (dotLayer) {
            ctx.drawImage(dotLayer, 0, 0, width, height);
        }

        light.x = width * (0.5 + 0.33 * Math.sin(seconds * 0.07));
        light.y = height * (0.5 + 0.3 * Math.cos(seconds * 0.055));
        light.ax = width * (0.5 + 0.36 * Math.cos(seconds * 0.045 + 1.7));
        light.ay = height * (0.5 + 0.32 * Math.sin(seconds * 0.062 + 0.8));

        const active = pointer.active;
        const lightRadius = Math.min(width, height) * 0.38;
        const pointerRadius = Math.min(width, height) * 0.24;

        drawLight(light.x, light.y, lightRadius * 1.5, palette.accent, palette.dark ? 0.04 : 0.026);
        drawLight(light.ax, light.ay, lightRadius, palette.dot, palette.dark ? 0.03 : 0.03);

        if (active > 0.01) {
            drawLight(pointer.x, pointer.y, pointerRadius * 1.7, palette.accent, (palette.dark ? 0.05 : 0.035) * active);
        }

        for (const dot of dots) {
            const glow = Math.max(
                smooth(dot.x, dot.y, light.x, light.y, lightRadius),
                smooth(dot.x, dot.y, light.ax, light.ay, lightRadius * 0.85) * 0.7,
                smooth(dot.x, dot.y, pointer.x, pointer.y, pointerRadius) * active
            );

            if (glow < 0.015) {
                continue;
            }

            const twinkle = 0.72 + 0.28 * Math.sin(seconds * dot.speed * 6 + dot.phase);

            ctx.globalAlpha = glow * palette.dotMax * twinkle;
            ctx.fillStyle = dot.accent ? rgba(palette.accent, 1) : rgba(palette.dot, 1);
            ctx.beginPath();
            ctx.arc(dot.x, dot.y, dot.r + glow * 0.9, 0, TAU);
            ctx.fill();
        }

        ctx.globalAlpha = 1;

        const linkDistance = width < 600 ? 96 : 132;
        const linkDistanceSq = linkDistance * linkDistance;
        const activeMotes = [];

        for (const mote of motes) {
            const glow = Math.max(
                smooth(mote.drawX, mote.drawY, light.x, light.y, lightRadius),
                smooth(mote.drawX, mote.drawY, light.ax, light.ay, lightRadius * 0.85) * 0.7,
                smooth(mote.drawX, mote.drawY, pointer.x, pointer.y, pointerRadius) * active
            );

            activeMotes.push({ mote, glow });
        }

        ctx.lineWidth = 1;

        let links = 0;

        for (let i = 0; i < activeMotes.length && links < 48; i += 1) {
            const a = activeMotes[i];

            for (let j = i + 1; j < activeMotes.length && links < 48; j += 1) {
                const b = activeMotes[j];
                const dx = a.mote.drawX - b.mote.drawX;
                const dy = a.mote.drawY - b.mote.drawY;
                const distanceSq = dx * dx + dy * dy;

                if (distanceSq > linkDistanceSq) {
                    continue;
                }

                const falloff = 1 - Math.sqrt(distanceSq) / linkDistance;
                const glow = Math.max(a.glow, b.glow);

                ctx.globalAlpha = falloff
                    * (palette.linkBase + glow * 0.18)
                    * (0.5 + 0.5 * Math.min(a.mote.alpha, b.mote.alpha));
                ctx.strokeStyle = rgba(palette.line, 1);
                ctx.beginPath();
                ctx.moveTo(a.mote.drawX, a.mote.drawY);
                ctx.lineTo(b.mote.drawX, b.mote.drawY);
                ctx.stroke();
                links += 1;
            }
        }

        ctx.globalAlpha = 1;

        for (const { mote, glow } of activeMotes) {
            const twinkle = 0.55 + 0.45 * Math.sin(seconds * mote.speed * 4 + mote.phase);
            const alpha = palette.moteBase * mote.alpha * twinkle * (0.5 + glow * 1.4);

            if (alpha <= 0.005) {
                continue;
            }

            const radius = mote.r * (1 + glow * 0.7);
            const halo = radius * 5.5;
            const sprite = mote.accent ? sprites.accent : sprites.light;
            const rgb = mote.accent ? palette.accent : palette.dot;

            ctx.globalAlpha = alpha * (palette.dark ? 0.7 : 0.48);
            ctx.drawImage(sprite, mote.drawX - halo / 2, mote.drawY - halo / 2, halo, halo);

            ctx.globalAlpha = alpha;
            ctx.fillStyle = rgba(rgb, 1);
            ctx.beginPath();
            ctx.arc(mote.drawX, mote.drawY, radius, 0, TAU);
            ctx.fill();
        }

        ctx.globalAlpha = 1;
    };

    const frame = (now) => {
        if (!running) {
            return;
        }

        const delta = last ? Math.min((now - last) / 1000, 0.05) : 0.016;
        last = now;

        const follow = 1 - Math.exp(-delta * 6);
        pointer.x += (pointer.tx - pointer.x) * follow;
        pointer.y += (pointer.ty - pointer.y) * follow;
        pointer.active += ((pointer.target === 1 ? 1 : 0) - pointer.active) * (1 - Math.exp(-delta * 4));

        updateMotes(delta, now);
        render(now);

        raf = window.requestAnimationFrame(frame);
    };

    const stop = () => {
        running = false;

        if (raf) {
            window.cancelAnimationFrame(raf);
            raf = 0;
        }
    };

    const start = () => {
        if (running) {
            return;
        }

        running = true;
        last = 0;
        canvas.classList.add('is-ready');

        if (reduceMotion.matches) {
            updateMotes(0, 0);
            render(0);
            return;
        }

        raf = window.requestAnimationFrame(frame);
    };

    const resize = () => {
        const rect = canvas.getBoundingClientRect();
        width = Math.max(1, rect.width);
        height = Math.max(1, rect.height);

        const baseDpr = Math.min(window.devicePixelRatio || 1, 2);
        const longest = Math.max(width, height) * baseDpr;
        dpr = longest > MAX_DIM ? (baseDpr * MAX_DIM) / longest : baseDpr;

        canvas.width = Math.max(1, Math.round(width * dpr));
        canvas.height = Math.max(1, Math.round(height * dpr));
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        pointer.x = width / 2;
        pointer.y = height / 2;
        pointer.tx = width / 2;
        pointer.ty = height / 2;

        rebuildPalette();
        buildMotes();
        render(performance.now());

        canvas.classList.add('is-ready');
    };

    const onPointerMove = (event) => {
        if (reduceMotion.matches || !finePointer.matches) {
            return;
        }

        pointer.tx = event.clientX;
        pointer.ty = event.clientY;
        pointer.target = 1;
    };

    const onPointerLeave = () => {
        pointer.target = 0;
    };

    const onVisibility = () => {
        if (document.hidden) {
            stop();
        } else {
            start();
        }
    };

    const onResize = () => {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(resize, 140);
    };

    window.addEventListener('pointermove', onPointerMove, { passive: true });
    document.documentElement.addEventListener('pointerleave', onPointerLeave);
    document.addEventListener('visibilitychange', onVisibility);
    window.addEventListener('resize', onResize);

    darkMode.addEventListener('change', () => {
        rebuildPalette();

        if (reduceMotion.matches) {
            render(performance.now());
        }
    });

    reduceMotion.addEventListener('change', () => {
        stop();
        start();
    });

    resize();
    start();
})();
