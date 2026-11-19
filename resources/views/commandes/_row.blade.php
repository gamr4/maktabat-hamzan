<div class="order-row">
    <label class="field">
        <span class="sr-only">Produit</span>
        <select name="produits[{{ $index }}][code_produit]" class="select" required>
            <option value="">Choisir un produit</option>
            @foreach ($productOptions as $value => $text)
                <option value="{{ $value }}" @selected(($row['code_produit'] ?? '') === $value)>{{ $text }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span class="sr-only">Quantité</span>
        <input type="number" name="produits[{{ $index }}][quantite]" class="input" min="1" step="1"
            value="{{ $row['quantite'] ?? 1 }}" required>
    </label>

    <button type="button" class="btn btn-ghost remove-row" aria-label="Retirer ce produit">
        <i class="ti ti-trash" aria-hidden="true"></i> Retirer
    </button>
</div>
