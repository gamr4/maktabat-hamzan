<!-- Slim single-line greeting bar -->
@include('dashboard.partials._greeting-bar', [
    'name' => Auth::user()->name ?? 'Client',
    'subtext' => 'Bienvenue sur votre espace client',
    'actions' => '
        <a href="' . route('profile.show') . '" class="greeting-btn">
            <i class="ti ti-user-circle" aria-hidden="true"></i>
            <span>Mes coordonnées</span>
        </a>
        <button type="button" onclick="window.print()" class="greeting-btn">
            <i class="ti ti-printer" aria-hidden="true"></i>
            <span>Imprimer</span>
        </button>
    ',
])

<!-- Indicateurs clés clients (3 KPI Cards épurées) -->
<div class="stats-grid stats-grid--three">
    <x-stat-card
        label="Mes commandes"
        :value="$stats['commandes']"
        icon="shopping-cart"
        href="#commandes-section"
        accent
        :trend="$trends['commandes'] ?? null"
        :trend-type="$trends['commandes_type'] ?? 'neutral'"
        sparkline="orders"
    />
    <x-stat-card
        label="Total des achats"
        :value="number_format($stats['total_depense'] ?? 0, 2, ',', ' ')"
        unit="MAD"
        icon="wallet"
        :trend="$trends['depense'] ?? null"
        :trend-type="$trends['depense_type'] ?? 'neutral'"
        sparkline="clients"
    />
    <x-stat-card
        label="Économies réalisées"
        :value="number_format($stats['total_economies'] ?? 0, 2, ',', ' ')"
        unit="MAD"
        icon="tag"
        :trend="$trends['economies'] ?? 'Remise négociée'"
        :trend-type="$trends['economies_type'] ?? 'up'"
        sparkline="orders"
    />
</div>

<!-- Architecture épurée centrée sur les commandes -->
<div class="client-dashboard-layout">
    <!-- Section principale : Historique complet des commandes -->
    <div class="client-dashboard-main">
        <section class="card card--flush" id="commandes-section" aria-labelledby="latest-orders-title">
            <div class="card__head">
                <div>
                    <h2 class="card__title" id="latest-orders-title">Mes commandes</h2>
                    <p class="section-note">
                        Historique de vos commandes passées auprès de la papeterie
                        @if(isset($clients) && $clients->isNotEmpty())
                            pour <strong>{{ $clients->pluck('nom_client')->join(', ') }}</strong>
                        @endif.
                    </p>
                </div>
                <span class="badge badge--neutral">
                    {{ $latestCommandes->count() }} commande(s) affichée(s)
                </span>
            </div>

            @if ($latestCommandes->isEmpty())
                <x-empty-state
                    icon="receipt"
                    title="Aucune commande enregistrée"
                    text="Passez votre première commande : elle apparaîtra ensuite dans cet historique.">
                    <a href="{{ route('commandes.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus" aria-hidden="true"></i> Nouvelle commande
                    </a>
                </x-empty-state>
            @else
                <div class="table-wrap table-wrap--client">
                    <table class="table client-orders-table">
                        <caption class="sr-only">Mes commandes</caption>
                        <thead>
                            <tr>
                                <th scope="col" style="width: 125px;">Numéro</th>
                                <th scope="col" style="width: 110px;">Date</th>
                                <th scope="col" style="width: 170px;">Client</th>
                                <th scope="col" style="width: 125px;">Règlement</th>
                                <th scope="col">Articles commandés</th>
                                <th scope="col" class="num" style="width: 135px;">Montant Net</th>
                                <th scope="col" class="text-right" style="width: 90px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latestCommandes as $commande)
                                <tr>
                                    <td>
                                        <a href="{{ route('commandes.show', $commande->numero_commande) }}" class="cell-code" title="Consulter la commande {{ $commande->numero_commande }}">
                                            {{ $commande->numero_commande }}
                                        </a>
                                    </td>
                                    <td><x-date :value="$commande->date_commande" /></td>
                                    <td style="color: #cbd5e1; font-weight: 500;">
                                        {{ $commande->client?->nom_client ?? '—' }}
                                    </td>
                                    <td><x-condition-badge :condition="$commande->condition" /></td>
                                    <td>
                                        <div class="order-items-preview">
                                            <span class="order-items-count">
                                                <i class="ti ti-package" aria-hidden="true"></i>
                                                {{ $commande->produits->sum('pivot.quantite') }} art.
                                            </span>
                                            @if ($commande->produits->isNotEmpty())
                                                <span class="order-items-text" title="{{ $commande->produits->map(fn($p) => $p->designation . ' (×' . $p->pivot->quantite . ')')->join(', ') }}">
                                                    {{ $commande->produits->take(2)->map(fn($p) => $p->designation . ($p->pivot->quantite > 1 ? ' ×' . $p->pivot->quantite : ''))->join(' · ') }}{{ $commande->produits->count() > 2 ? ' · +' . ($commande->produits->count() - 2) : '' }}
                                                </span>
                                            @else
                                                <span class="text-faint" style="font-size: 0.75rem;">Aucun article</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="num">
                                        <strong style="color: #f8fafc;"><x-money :value="$commande->montantNet()" /></strong>
                                        @if ($commande->client && $commande->client->remise_speciale > 0)
                                            <div style="font-size: 0.72rem; color: #8b949e; display: flex; align-items: center; justify-content: flex-end; gap: 0.35rem;">
                                                <span style="text-decoration: line-through;"><x-money :value="$commande->montantBrut()" /></span>
                                                <span style="color: #38bdf8; font-weight: 600;">-{{ $commande->client->remise_speciale }}%</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('commandes.show', $commande->numero_commande) }}" class="btn btn-ghost btn-sm" title="Voir les détails de la commande {{ $commande->numero_commande }}">
                                            Détails <i class="ti ti-chevron-right" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding: 0.85rem 1.25rem; background: rgba(255, 255, 255, 0.02); border-top: 1px solid rgba(255, 255, 255, 0.05); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; font-size: 0.825rem; color: #8b949e;">
                    <span>Affichage des <strong>{{ $latestCommandes->count() }}</strong> dernières commandes</span>
                    <span>Total des commandes affichées : <strong style="color: #f8fafc;"><x-money :value="$latestCommandes->sum(fn ($c) => $c->montantNet())" /></strong></span>
                </div>
            @endif
        </section>
    </div>

    <!-- Section secondaire : Assistance rapide -->
    <div class="client-dashboard-aside">
        <section class="card card--flush" aria-labelledby="client-support-title">
            <div class="client-support-card">
                <div>
                    <h2 class="card__title" id="client-support-title" style="font-size: 0.95rem; margin: 0 0 0.2rem 0;">Besoin d'aide ?</h2>
                    <p class="section-note" style="margin: 0;">Une question sur vos commandes ou une demande de devis sur mesure.</p>
                </div>
                <div class="client-support-card__items">
                    <div class="client-support-row">
                        <i class="ti ti-phone" aria-hidden="true"></i>
                        <span>Téléphone : <a href="tel:+212522000000" class="client-support-link"><strong>+212 5 22 00 00 00</strong></a></span>
                    </div>
                    <div class="client-support-row">
                        <i class="ti ti-mail" aria-hidden="true"></i>
                        <span>Email : <a href="mailto:contact@papeterie-hamzan.ma" class="client-support-link"><strong>contact@papeterie-hamzan.ma</strong></a></span>
                    </div>
                    <div class="client-support-row">
                        <i class="ti ti-clock" aria-hidden="true"></i>
                        <span>Horaires : <strong>Lun - Ven (08h30 - 18h30)</strong></span>
                    </div>
                </div>
                <a href="mailto:contact@papeterie-hamzan.ma?subject=Demande%20Espace%20Client" class="btn btn-ghost btn-sm">
                    <i class="ti ti-send" aria-hidden="true"></i> Contacter le support
                </a>
            </div>
        </section>
    </div>
</div>
