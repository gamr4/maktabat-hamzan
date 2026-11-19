(() => {
    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[data-confirm]');

        if (form && !window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });

    const rows = document.getElementById('commande-products');
    const addButton = document.getElementById('add-product');
    const template = document.getElementById('commande-row-template');

    if (!rows || !addButton || !template) {
        return;
    }

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
})();
