@php
    $fournisseur = $fournisseur ?? null;
@endphp

<div class="card form-card">
    <form action="{{ $action }}" method="POST" class="form-grid">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <x-input name="nom_fournisseur" label="Nom" :value="$fournisseur?->nom_fournisseur" required
            autocomplete="organization" />
        <x-input name="rue_fournisseur" label="Rue" :value="$fournisseur?->rue_fournisseur" required
            autocomplete="street-address" />
        <x-input name="code_postal_fournisseur" label="Code postal"
            :value="$fournisseur?->code_postal_fournisseur" required autocomplete="postal-code" />
        <x-input name="ville_fournisseur" label="Ville" :value="$fournisseur?->ville_fournisseur" required
            autocomplete="address-level2" />
        <x-input name="pays_fournisseur" label="Pays" :value="$fournisseur?->pays_fournisseur"
            autocomplete="country-name" />
        <x-input name="tel_fournisseur" label="Téléphone" type="tel" :value="$fournisseur?->tel_fournisseur"
            autocomplete="tel" />
        <x-input name="contact_fournisseur" label="Contact" :value="$fournisseur?->contact_fournisseur"
            autocomplete="name" />

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-device-floppy" aria-hidden="true"></i> Enregistrer
            </button>
            <a href="{{ route('fournisseurs.index') }}" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>
