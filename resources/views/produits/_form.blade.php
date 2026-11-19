@php
    $produit = $produit ?? null;
    $familles = ['PA' => 'PA', 'BU' => 'BU', 'FO' => 'FO', 'LI' => 'LI'];
@endphp

<div class="card form-card">
    @if ($fournisseurs->isEmpty())
        <div class="alert alert-info">
            <i class="ti ti-info-circle" aria-hidden="true"></i>
            <span>Aucun fournisseur disponible. <a href="{{ route('fournisseurs.create') }}">Créez un fournisseur</a>
                avant d’ajouter un produit.</span>
        </div>
    @endif

    <form action="{{ $action }}" method="POST" class="form-grid">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <x-input name="designation" label="Désignation" :value="$produit?->designation" required />
        <x-input name="prix_unitaire" label="Prix unitaire (DH)" type="number" step="0.01" min="0"
            inputmode="decimal" :value="$produit?->prix_unitaire" required />
        <x-select name="famille" label="Famille" :options="$familles" :selected="$produit?->famille" required />
        <x-select name="code_fournisseur" label="Fournisseur"
            :options="$fournisseurs->pluck('nom_fournisseur', 'code_fournisseur')->all()"
            :selected="$produit?->code_fournisseur" required />

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-device-floppy" aria-hidden="true"></i> Enregistrer
            </button>
            <a href="{{ route('produits.index') }}" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>
