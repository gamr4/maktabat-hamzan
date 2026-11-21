(() => {
    // Confirmation dialogs
    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[data-confirm]');

        if (form && !window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });

    // Dynamic order rows
    const rows = document.getElementById('commande-products');
    const addButton = document.getElementById('add-product');
    const template = document.getElementById('commande-row-template');

    if (rows && addButton && template) {
        const nextIndex = () =>
            [...rows.querySelectorAll('select[name]')].reduce((max, select) => {
                const match = select.name.match(/\[(\d+)\]/);

                return Math.max(max, match ? Number(match[1]) : -1);
            }, -1) + 1;

        const syncRemoveButtons = () => {
            const count = rows.querySelectorAll('.order-row').length;

            rows.querySelectorAll('.remove-row').forEach((button) => {
                button.disabled = count <= 1;
            });
        };

        syncRemoveButtons();

        addButton.addEventListener('click', () => {
            const index = nextIndex();
            const fragment = template.content.cloneNode(true);

            fragment.querySelectorAll('[name]').forEach((field) => {
                field.name = field.name.replace('__INDEX__', index);
            });

            rows.appendChild(fragment);
            syncRemoveButtons();
            rows.lastElementChild?.querySelector('select')?.focus();
        });

        rows.addEventListener('click', (event) => {
            const button = event.target.closest('.remove-row');

            if (!button) {
                return;
            }

            if (rows.querySelectorAll('.order-row').length > 1) {
                button.closest('.order-row').remove();
                syncRemoveButtons();
            }
        });
    }

    // Sidebar Collapse
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');

    if (sidebar && sidebarCollapseBtn) {
        const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('sidebar--collapsed');
            const icon = sidebarCollapseBtn.querySelector('i');
            if (icon) {
                icon.className = 'ti ti-layout-sidebar-left-expand';
            }
            sidebarCollapseBtn.setAttribute('title', 'Agrandir la barre');
            sidebarCollapseBtn.setAttribute('aria-label', 'Agrandir la barre latérale');
        }

        sidebarCollapseBtn.addEventListener('click', () => {
            const willCollapse = !sidebar.classList.contains('sidebar--collapsed');
            sidebar.classList.toggle('sidebar--collapsed', willCollapse);
            localStorage.setItem('sidebar_collapsed', willCollapse ? 'true' : 'false');

            const icon = sidebarCollapseBtn.querySelector('i');
            if (icon) {
                icon.className = willCollapse ? 'ti ti-layout-sidebar-left-expand' : 'ti ti-layout-sidebar-left-collapse';
            }
            sidebarCollapseBtn.setAttribute('title', willCollapse ? 'Agrandir la barre' : 'Réduire la barre');
            sidebarCollapseBtn.setAttribute('aria-label', willCollapse ? 'Agrandir la barre latérale' : 'Réduire la barre latérale');
        });
    }

    // Command Palette (⌘K / Ctrl+K)
    const cmdPalette = document.getElementById('cmdPalette');
    const cmdPaletteTrigger = document.getElementById('cmdPaletteTrigger');
    const cmdPaletteInput = document.getElementById('cmdPaletteInput');
    const cmdPaletteResults = document.getElementById('cmdPaletteResults');

    if (cmdPalette && cmdPaletteInput) {
        const openPalette = () => {
            cmdPalette.removeAttribute('hidden');
            cmdPaletteInput.value = '';
            filterPaletteItems('');
            requestAnimationFrame(() => cmdPaletteInput.focus());
        };

        const closePalette = () => {
            cmdPalette.setAttribute('hidden', '');
        };

        const filterPaletteItems = (query) => {
            const q = query.toLowerCase().trim();
            const items = cmdPaletteResults?.querySelectorAll('.cmd-palette__item') || [];
            items.forEach((item) => {
                const text = item.textContent?.toLowerCase() || '';
                const match = !q || text.includes(q);
                item.style.display = match ? 'flex' : 'none';
            });

            // Hide empty groups
            const groups = cmdPaletteResults?.querySelectorAll('.cmd-palette__group') || [];
            groups.forEach((group) => {
                const visibleCount = [...group.querySelectorAll('.cmd-palette__item')].filter(el => el.style.display !== 'none').length;
                group.style.display = visibleCount > 0 ? 'block' : 'none';
            });
        };

        cmdPaletteTrigger?.addEventListener('click', (e) => {
            e.preventDefault();
            openPalette();
        });

        cmdPalette.addEventListener('click', (e) => {
            if (e.target.closest('[data-close-cmd]')) {
                closePalette();
            }
        });

        cmdPaletteInput.addEventListener('input', (e) => {
            filterPaletteItems(e.target.value);
        });

        window.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && (e.key === 'k' || e.key === 'K')) {
                e.preventDefault();
                if (cmdPalette.hasAttribute('hidden')) {
                    openPalette();
                } else {
                    closePalette();
                }
            } else if (e.key === 'Escape' && !cmdPalette.hasAttribute('hidden')) {
                e.preventDefault();
                closePalette();
            }
        });
    }
})();
