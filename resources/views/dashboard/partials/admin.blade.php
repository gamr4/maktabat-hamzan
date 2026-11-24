<!-- Slim single-line greeting bar -->
@include('dashboard.partials._greeting-bar', [
    'name' => Auth::user()->name ?? 'Administrateur',
    'subtext' => 'Console de gestion opérationnelle (Admin)',
    'variant' => 'admin',
])

<div class="stats-grid">
    <x-stat-card label="Commandes" :value="$stats['commandes'] ?? 15" icon="shopping-cart"
        :href="route('commandes.index')" accent :trend="$trends['commandes'] ?? '+12% ce mois'" :trend-type="$trends['commandes_type'] ?? 'up'" sparkline="orders" />
    <x-stat-card label="Chiffre d'affaires" :value="number_format($stats['ca_mois'] ?? 0, 2, ',', ' ')" unit="MAD" icon="wallet"
        :href="route('commandes.index')" trend="Ce mois-ci (HT)" trend-type="neutral" sparkline="clients" />
    <x-stat-card label="Clients" :value="$stats['clients'] ?? 25" icon="users"
        :href="route('clients.index')" :trend="$trends['clients'] ?? '+8% ce mois'" :trend-type="$trends['clients_type'] ?? 'up'" sparkline="clients" />
    <x-stat-card label="Fournisseurs" :value="$stats['fournisseurs'] ?? 30" icon="truck-delivery"
        :href="route('fournisseurs.index')" :trend="$trends['fournisseurs'] ?? 'Partenaires actifs'" :trend-type="$trends['fournisseurs_type'] ?? 'neutral'" sparkline="suppliers" />
    <x-stat-card label="Produits" :value="$stats['produits'] ?? 25" icon="box"
        :href="route('produits.index')" :trend="$trends['produits'] ?? '+5% ce mois'" :trend-type="$trends['produits_type'] ?? 'up'" sparkline="products" />
</div>

<section class="card card--flush dashboard-data-card dashboard-data-card--orders" aria-labelledby="latest-orders-title">
    <div class="card__head dashboard-data-card__head">
        <div class="dashboard-data-card__heading">
            <span class="dashboard-data-card__icon" aria-hidden="true"><i class="ti ti-receipt-2"></i></span>
            <div>
                <p class="dashboard-data-card__eyebrow">Suivi des ventes</p>
                <h2 class="card__title" id="latest-orders-title">Dernières commandes</h2>
                <p class="section-note">Les cinq commandes les plus récentes de l'entreprise.</p>
            </div>
        </div>
        <div class="dashboard-data-card__actions">
            <span class="dashboard-data-card__count"><span class="status-dot" aria-hidden="true"></span>{{ $latestCommandes->count() }} récente{{ $latestCommandes->count() > 1 ? 's' : '' }}</span>
            <a class="btn btn-ghost btn-sm" href="{{ route('commandes.index') }}">
                Tout voir <i class="ti ti-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    @if ($latestCommandes->isEmpty())
        <x-empty-state icon="receipt" title="Aucune commande"
            text="Créez une première commande pour la voir apparaître ici.">
            <a class="btn btn-primary btn-sm" href="{{ route('commandes.create') }}">Créer une commande</a>
        </x-empty-state>
    @else
        <div class="table-wrap dashboard-data-card__table">
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
    <section class="card card--flush dashboard-data-card dashboard-data-card--clients" style="margin-top: 1.75rem;" aria-labelledby="top-clients-title">
        <div class="card__head dashboard-data-card__head">
            <div class="dashboard-data-card__heading">
                <span class="dashboard-data-card__icon" aria-hidden="true"><i class="ti ti-users-group"></i></span>
                <div>
                    <p class="dashboard-data-card__eyebrow">Relations actives</p>
                    <h2 class="card__title" id="top-clients-title">Principaux clients</h2>
                    <p class="section-note">Clients enregistrés les plus actifs par nombre de commandes.</p>
                </div>
            </div>
            <div class="dashboard-data-card__actions">
                <span class="dashboard-data-card__count">Top {{ $topClients->count() }}</span>
                <a class="btn btn-ghost btn-sm" href="{{ route('clients.index') }}">
                    Tous les clients <i class="ti ti-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <div class="table-wrap dashboard-data-card__table">
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
