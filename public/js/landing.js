(() => {
    const header = document.querySelector('[data-header]');
    const nav = document.querySelector('[data-nav]');
    const navToggle = document.querySelector('[data-nav-toggle]');

    const setNav = (open) => {
        if (!nav || !navToggle) {
            return;
        }

        nav.classList.toggle('is-open', open);
        navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        navToggle.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
        navToggle.innerHTML = open
            ? '<i class="ti ti-x" aria-hidden="true"></i>'
            : '<i class="ti ti-menu-2" aria-hidden="true"></i>';
    };

    if (nav && navToggle) {
        navToggle.addEventListener('click', () => {
            setNav(!nav.classList.contains('is-open'));
        });

        nav.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => setNav(false));
        });

        document.addEventListener('click', (event) => {
            if (!nav.classList.contains('is-open')) {
                return;
            }

            if (nav.contains(event.target) || navToggle.contains(event.target)) {
                return;
            }

            setNav(false);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape' || !nav.classList.contains('is-open')) {
                return;
            }

            setNav(false);
            navToggle.focus();
        });

        const wide = window.matchMedia('(min-width: 861px)');
        wide.addEventListener('change', (event) => {
            if (event.matches) {
                setNav(false);
            }
        });
    }

    if (header) {
        const updateHeader = () => {
            header.classList.toggle('is-scrolled', window.scrollY > 8);
        };

        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });
    }

    const sectionLinks = [...document.querySelectorAll('.site-nav a[href^="#"]')];
    const sections = sectionLinks
        .map((link) => {
            const id = link.getAttribute('href').slice(1);

            return { link, section: document.getElementById(id) };
        })
        .filter(({ section }) => section);

    if (sections.length > 0 && 'IntersectionObserver' in window) {
        const spy = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    sections.forEach(({ link, section }) => {
                        const isActive = section === entry.target;

                        link.classList.toggle('is-active', isActive);

                        if (isActive) {
                            link.setAttribute('aria-current', 'location');
                        } else {
                            link.removeAttribute('aria-current');
                        }
                    });
                });
            },
            {
                rootMargin: '-28% 0px -62% 0px',
                threshold: 0,
            }
        );

        sections.forEach(({ section }) => spy.observe(section));
    }

    const revealItems = document.querySelectorAll('[data-reveal]');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!reduceMotion && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            },
            {
                threshold: 0.14,
                rootMargin: '0px 0px -50px 0px',
            }
        );

        revealItems.forEach((item) => observer.observe(item));
    }
})();
