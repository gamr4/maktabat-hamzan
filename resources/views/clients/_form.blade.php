@php
    $client = $client ?? null;
@endphp

<div class="card form-card">
    <form action="{{ $action }}" method="POST" class="form-grid">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <x-input name="nom_client" label="Nom du client" :value="$client?->nom_client" required
            autocomplete="organization" />
        <x-input name="rue_client" label="Rue" :value="$client?->rue_client" required
            autocomplete="street-address" />
        <x-input name="code_postal_client" label="Code postal" :value="$client?->code_postal_client" required
            autocomplete="postal-code" />
        <x-input name="ville_client" label="Ville" :value="$client?->ville_client" required
            autocomplete="address-level2" />

        <x-select name="type_client" label="Type de client" :options="config('papeterie.client_types')"
            :selected="$client?->type_client" required />
        <x-input name="remise_speciale" label="Remise spéciale (%)" type="number" min="0" max="100"
            inputmode="numeric" :value="$client?->remise_speciale ?? 0" required />

        @if (! $client)
            <div style="grid-column: 1 / -1; background: rgba(56, 189, 248, 0.05); border: 1px solid rgba(56, 189, 248, 0.2); border-radius: var(--radius-sm, 8px); padding: 0.85rem 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; cursor: pointer; color: #e6edf3;">
                    <input type="checkbox" name="create_account" value="1" checked style="width: auto;">
                    <span>Créer automatiquement un compte d'accès pour ce client</span>
                </label>
                <p style="font-size: 0.8rem; color: #8b949e; margin: 0.35rem 0 0 1.5rem;">
                    Un compte de connexion sera généré avec le mot de passe initial <code>password</code>.
                </p>
            </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-device-floppy" aria-hidden="true"></i> Enregistrer
            </button>
            <a href="{{ route('clients.index') }}" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>
