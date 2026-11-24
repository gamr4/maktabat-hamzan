<!-- Slim single-line greeting bar -->
@include('dashboard.partials._greeting-bar', [
    'name' => Auth::user()->name ?? 'Gestionnaire',
    'subtext' => 'Console de gestion opérationnelle (Gestionnaire)',
    'actions' => '
        <a href="' . route("produits.index") . '" class="greeting-btn">
            <i class="ti ti-box" aria-hidden="true"></i>
            <span>Catalogue</span>
        </a>
        <a href="' . route("clients.index") . '" class="greeting-btn">
            <i class="ti ti-users" aria-hidden="true"></i>
            <span>Clients</span>
        </a>
        <a href="' . route("commandes.create") . '" class="greeting-btn" style="color: #38bdf8; border-color: rgba(56, 189, 248, 0.3);">
            <i class="ti ti-plus" aria-hidden="true"></i>
            <span>Nouvelle commande</span>
        </a>
    ',
])

<div class="stats-grid">
    <x-stat-card label="Commandes" :value="$stats['commandes'] ?? 15" icon="shopping-cart"
        :href="route('commandes.index')" accent :trend="$trends['commandes'] ?? '+12% ce mois'" :trend-type="$trends['commandes_type'] ?? 'up'" sparkline="orders" />
    <x-stat-card label="Chiffre d'affaires" :value="number_format($stats['ca_mois'] ?? 0, 2, ',', ' ')" unit="MAD" icon="wallet"
        :href="route('commandes.index')" trend="Ce mois-ci (HT)" trend-type="neutral" sparkline="clients" />
    <x-stat-card label="Clients" :value="$stats['clients'] ?? 25" icon="users"
        :href="route('clients.index')" :trend="$trends['clients'] ?? '+8% ce mois'" :trend-type="$trends['clients_type'] ?? 'up'" sparkline="clients" />
    <x-stat-card label="Produits" :value="$stats['produits'] ?? 25" icon="box"
        :href="route('produits.index')" :trend="$trends['produits'] ?? '+5% ce mois'" :trend-type="$trends['produits_type'] ?? 'up'" sparkline="products" />
</div>

<section class="card card--flush" aria-labelledby="latest-orders-title">
    <div class="card__head">
        <div>
            <h2 class="card__title" id="latest-orders-title">Dernières commandes</h2>
            <p class="section-note">Commandes récentes à traiter.</p>
        </div>
        <a class="btn btn-ghost btn-sm" href="{{ route('commandes.index') }}">
            Tout voir <i class="ti ti-arrow-right" aria-hidden="true"></i>
        </a>
    </div>

    @if ($latestCommandes->isEmpty())
        <x-empty-state icon="receipt" title="Aucune commande"
            text="Créez une première commande pour la voir apparaître ici.">
            <a class="btn btn-primary btn-sm" href="{{ route('commandes.create') }}">Créer une commande</a>
        </x-empty-state>
    @else
        <div class="table-wrap">
            <table class="table">
                <caption class="sr-only">Dernières commandes</caption>
                <thead>
                    <tr>
                        <th scope="col">Numéro</th>
                        <th scope="col">Date</th>
                        <th scope="col">Client</th>
                        <th scope="col">Condition</th>
                        <th scope="col" class="num">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestCommandes as $commande)
                        <tr>
                            <td>
                                <a class="cell-code" href="{{ route('commandes.show', $commande) }}">
                                    {{ $commande->numero_commande }}
                                </a>
                            </td>
                            <td><x-date :value="$commande->date_commande" /></td>
                            <td>{{ $commande->client?->nom_client ?? '—' }}</td>
                            <td><x-condition-badge :condition="$commande->condition" /></td>
                            <td class="num">
                                <x-money :value="$commande->produits->sum(fn ($produit) => $produit->prix_unitaire * $produit->pivot->quantite)" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>

@if(isset($topClients) && $topClients->isNotEmpty())
    <section class="card card--flush" style="margin-top: 1.75rem;" aria-labelledby="top-clients-title">
        <div class="card__head">
            <div>
                <h2 class="card__title" id="top-clients-title">Principaux clients</h2>
                <p class="section-note">Clients enregistrés les plus actifs par nombre de commandes.</p>
            </div>
            <a class="btn btn-ghost btn-sm" href="{{ route('clients.index') }}">
                Tous les clients <i class="ti ti-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
        <div class="table-wrap">
            <table class="table">
                <caption class="sr-only">Principaux clients</caption>
                <thead>
                    <tr>
                        <th scope="col">Code Client</th>
                        <th scope="col">Raison Sociale</th>
                        <th scope="col">Ville</th>
                        <th scope="col">Type</th>
                        <th scope="col" class="num">Remise</th>
                        <th scope="col" class="num">Commandes</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topClients as $tClient)
                        <tr>
                            <td><span class="cell-code">{{ $tClient->code_client }}</span></td>
                            <td><strong>{{ $tClient->nom_client }}</strong></td>
                            <td>{{ $tClient->ville_client ?? '—' }}</td>
                            <td><span class="badge">{{ $tClient->type_client }}</span></td>
                            <td class="num"><strong>{{ $tClient->remise_speciale }}%</strong></td>
                            <td class="num"><strong>{{ $tClient->commandes_count ?? 0 }}</strong></td>
                            <td class="text-right">
                                <a href="{{ route('clients.show', $tClient) }}" class="btn btn-ghost btn-sm">
                                    Consulter <i class="ti ti-chevron-right" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif
