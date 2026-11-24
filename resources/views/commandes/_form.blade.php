@php
    $commande = $commande ?? null;
    $lockedClient = $lockedClient ?? null;
    $cancelRoute = $cancelRoute ?? route('commandes.index');
    $submitLabel = $submitLabel ?? 'Enregistrer';
    $showAdminLinks = $showAdminLinks ?? true;

    $clientOptions = $clients
        ->mapWithKeys(fn ($client) => [$client->code_client => $client->nom_client . ' (' . $client->code_client . ')'])
        ->all();

    $productOptions = $produits
        ->mapWithKeys(fn ($produit) => [
            $produit->code_produit => $produit->designation . ' — ' . number_format($produit->prix_unitaire, 2, ',', ' ') . ' DH',
        ])
        ->all();

    $rows = old('produits');

    if ($rows === null && $commande) {
        $rows = $commande->produits
            ->map(fn ($produit) => [
                'code_produit' => $produit->code_produit,
                'quantite' => $produit->pivot->quantite,
            ])
            ->all();
    }

    $rows = $rows ?: [['code_produit' => '', 'quantite' => 1]];
@endphp

<div class="card form-card">
    @if ($clients->isEmpty() || $produits->isEmpty())
        <div class="alert alert-info">
            <i class="ti ti-info-circle" aria-hidden="true"></i>
            <span>
                Une commande a besoin d’un client et d’un produit.
                @if ($clients->isEmpty())
                    @if ($showAdminLinks)
                        <a href="{{ route('clients.create') }}">Créez un client</a>.
                    @else
                        Aucune fiche client n’est rattachée à votre compte. Contactez la papeterie.
                    @endif
                @endif
                @if ($produits->isEmpty())
                    @if ($showAdminLinks)
                        <a href="{{ route('produits.create') }}">Créez un produit</a>.
                    @else
                        Aucun produit n’est disponible pour le moment. Contactez la papeterie.
                    @endif
                @endif
            </span>
        </div>
    @endif

    <form action="{{ $action }}" method="POST">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="form-grid">
            @if ($lockedClient)
                <div class="field">
                    <label for="code_client_display">Client</label>
                    <input type="text" id="code_client_display" class="input"
                        value="{{ $lockedClient->nom_client }} ({{ $lockedClient->code_client }})" readonly>
                    <p class="field-hint">Cette commande sera créée automatiquement pour votre compte.</p>
                </div>
            @else
                <x-select name="code_client" label="Client" :options="$clientOptions"
                    :selected="$commande?->code_client" placeholder="Choisir un client" required />
            @endif

            <x-input name="date_commande" label="Date de commande" type="date"
                :value="$commande ? \Illuminate\Support\Carbon::parse($commande->date_commande)->format('Y-m-d') : now()->format('Y-m-d')"
                :max="now()->format('Y-m-d')" required />

            <x-select name="condition" label="Condition" :options="['LIV' => 'LIV', 'T30' => 'T30']"
                :selected="$commande?->condition ?? 'LIV'" required />
        </div>

        <h2 class="form-section-title">Produits commandés</h2>

        <div id="commande-products">
            @foreach ($rows as $row)
                @include('commandes._row', [
                    'index' => $loop->index,
                    'row' => $row,
                    'productOptions' => $productOptions,
                ])
            @endforeach
        </div>

        <template id="commande-row-template">
            @include('commandes._row', [
                'index' => '__INDEX__',
                'row' => ['code_produit' => '', 'quantite' => 1],
                'productOptions' => $productOptions,
            ])
        </template>

        <button type="button" id="add-product" class="btn btn-ghost">
            <i class="ti ti-plus" aria-hidden="true"></i> Ajouter un produit
        </button>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-device-floppy" aria-hidden="true"></i> {{ $submitLabel }}
            </button>
            <a href="{{ $cancelRoute }}" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>
