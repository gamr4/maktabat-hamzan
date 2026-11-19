@php
    $commande = $commande ?? null;

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
                    <a href="{{ route('clients.create') }}">Créez un client</a>.
                @endif
                @if ($produits->isEmpty())
                    <a href="{{ route('produits.create') }}">Créez un produit</a>.
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
            <x-select name="code_client" label="Client" :options="$clientOptions"
                :selected="$commande?->code_client" placeholder="Choisir un client" required />

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
                <i class="ti ti-device-floppy" aria-hidden="true"></i> Enregistrer
            </button>
            <a href="{{ route('commandes.index') }}" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>
