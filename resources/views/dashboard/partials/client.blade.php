<!-- Slim single-line greeting bar -->
<section class="greeting-bar" aria-labelledby="greeting-title">
    <div class="greeting-bar__left">
        <span class="status-indicator-dot" aria-hidden="true"></span>
        <h2 id="greeting-title" class="greeting-bar__title">
            Bonjour, <strong>{{ Auth::user()->name ?? 'Client' }}</strong>
            <span class="greeting-bar__sub">— Bienvenue sur votre espace client</span>
        </h2>
    </div>
    <div class="greeting-bar__actions" aria-label="Accès rapides">
        <button type="button" class="greeting-btn" onclick="window.print()">
            <i class="ti ti-printer" aria-hidden="true"></i>
            <span>Imprimer</span>
        </button>
    </div>
</section>

<!-- Client Scoped KPI Cards -->
<div class="stats-grid">
    <x-stat-card label="Mes commandes" :value="$stats['commandes'] ?? 0" icon="shopping-cart"
        :href="route('dashboard')" accent trend="Historique des commandes" trend-type="neutral" sparkline="orders" />
    <x-stat-card label="Total des achats" :value="number_format($stats['total_depense'] ?? 0, 2, ',', ' ')" unit="MAD" icon="wallet"
        :href="route('dashboard')" trend="Montant cumulé Net" trend-type="up" sparkline="clients" />
    <x-stat-card label="Articles disponibles" :value="$stats['produits'] ?? 0" icon="box"
        :href="route('dashboard')" trend="Catalogue actif" trend-type="neutral" sparkline="products" />
</div>

<!-- Mes comptes clients associés -->
@if(isset($clients) && $clients->isNotEmpty())
    <section class="card card--flush" style="margin-bottom: 1.75rem;" aria-labelledby="linked-clients-title">
        <div class="card__head">
            <div>
                <h2 class="card__title" id="linked-clients-title">Mes fiches clients rattachées</h2>
                <p class="section-note">Vos entités commerciales et adresses de facturation enregistrées.</p>
            </div>
            <span style="font-size: 0.8rem; color: #8b949e;">{{ $clients->count() }} compte(s) associé(s)</span>
        </div>
        <div style="padding: 1.25rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
            @foreach ($clients as $c)
                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: var(--radius-sm, 8px); padding: 1.15rem; display: flex; flex-direction: column; justify-content: space-between; gap: 0.65rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem;">
                        <div>
                            <span class="cell-code" style="font-size: 0.78rem; display: inline-block; margin-bottom: 0.25rem;">{{ $c->code_client }}</span>
                            <h3 style="font-size: 1rem; font-weight: 600; margin: 0; color: #fff;">{{ $c->nom_client }}</h3>
                        </div>
                        <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;">{{ $c->type_client }}</span>
                    </div>
                    <p style="font-size: 0.825rem; color: #8b949e; margin: 0;">
                        <i class="ti ti-map-pin" aria-hidden="true" style="font-size: 0.9rem;"></i>
                        {{ $c->ville_client }} ({{ $c->code_postal_client }})
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255, 255, 255, 0.06); padding-top: 0.65rem; font-size: 0.8rem;">
                        <span style="color: #38bdf8; font-weight: 500;">
                            Remise : <strong>{{ $c->remise_speciale }}%</strong>
                        </span>
                        <span style="color: #8b949e;">
                            <strong>{{ $c->commandes_count ?? 0 }}</strong> commande(s)
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif

<!-- Mes dernières commandes -->
<section class="card card--flush" aria-labelledby="latest-orders-title">
    <div class="card__head">
        <div>
            <h2 class="card__title" id="latest-orders-title">Mes commandes</h2>
            <p class="section-note">Historique de vos commandes passées auprès de la papeterie.</p>
        </div>
        <span style="font-size: 0.8rem; color: #8b949e;">{{ $latestCommandes->count() }} commande(s) affichée(s)</span>
    </div>

    @if ($latestCommandes->isEmpty())
        <x-empty-state icon="receipt" title="Aucune commande enregistrée"
            text="Vos commandes validées apparaîtront ici dès leur enregistrement par l'équipe.">
        </x-empty-state>
    @else
        <div class="table-wrap">
            <table class="table">
                <caption class="sr-only">Mes commandes</caption>
                <thead>
                    <tr>
                        <th scope="col">Numéro</th>
                        <th scope="col">Date</th>
                        <th scope="col">Compte Client</th>
                        <th scope="col">Condition</th>
                        <th scope="col" class="num">Articles</th>
                        <th scope="col" class="num">Montant Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestCommandes as $commande)
                        <tr>
                            <td>
                                <span class="cell-code">{{ $commande->numero_commande }}</span>
                            </td>
                            <td><x-date :value="$commande->date_commande" /></td>
                            <td>{{ $commande->client?->nom_client ?? '—' }}</td>
                            <td><x-condition-badge :condition="$commande->condition" /></td>
                            <td class="num">{{ $commande->produits->sum('pivot.quantite') }}</td>
                            <td class="num">
                                <strong><x-money :value="$commande->montantNet()" /></strong>
                                @if ($commande->client && $commande->client->remise_speciale > 0)
                                    <div style="font-size: 0.72rem; color: #8b949e; text-decoration: line-through;">
                                        <x-money :value="$commande->montantBrut()" />
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @if ($commande->produits->isNotEmpty())
                            <tr style="background: rgba(255, 255, 255, 0.015); border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                                <td colspan="6" style="padding: 0.45rem 1.25rem 0.65rem; font-size: 0.8rem; color: #8b949e;">
                                    <div style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap;">
                                        <span style="font-weight: 500; color: #c9d1d9;"><i class="ti ti-package" aria-hidden="true"></i> Contenu :</span>
                                        @foreach ($commande->produits as $p)
                                            <span style="background: rgba(255, 255, 255, 0.04); padding: 0.15rem 0.5rem; border-radius: 4px;">
                                                {{ $p->designation }} × <strong>{{ $p->pivot->quantite }}</strong> (<x-money :value="$p->prix_unitaire * $p->pivot->quantite" />)
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
