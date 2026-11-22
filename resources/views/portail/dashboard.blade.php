@extends('layouts.portail')

@section('title', 'Tableau de bord Client')
@section('eyebrow', 'Vue d’ensemble')

@section('actions')
    <a href="{{ route('dashboard') }}" class="btn btn-ghost">
        <i class="ti ti-layout-dashboard" aria-hidden="true"></i> Tableau de bord
    </a>
    <a href="{{ route('portail.commandes') }}" class="btn btn-primary">
        <i class="ti ti-receipt" aria-hidden="true"></i> Voir toutes mes commandes
    </a>
@endsection

@section('content')
    <!-- KPI cards with dynamic real trends -->
    <div class="stats-grid">
        <x-stat-card
            label="Mes Commandes"
            :value="$stats['commandes'] ?? 0"
            icon="shopping-cart"
            :href="route('portail.commandes')"
            accent
            :trend="$stats['trend_commandes_label'] ?? 'Historique des commandes'"
            :trend-type="$stats['trend_commandes_type'] ?? 'neutral'"
            sparkline="orders"
        />
        <x-stat-card
            label="Total des achats"
            :value="number_format($stats['total_depense'] ?? 0, 2, ',', ' ')"
            unit="MAD"
            icon="wallet"
            :href="route('portail.commandes')"
            :trend="$stats['trend_depense_label'] ?? 'Montant cumulé TTC'"
            :trend-type="$stats['trend_depense_type'] ?? 'neutral'"
            sparkline="clients"
        />
        <x-stat-card
            label="Comptes Rattachés"
            :value="$stats['clients'] ?? 0"
            icon="building"
            :href="route('portail.profil')"
            trend="Fiches clients associées"
            trend-type="neutral"
            sparkline="suppliers"
        />
    </div>

    <!-- Mes fiches clients rattachées -->
    <section class="card card--flush" style="margin-bottom: 2rem;" aria-labelledby="clients-heading">
        <div class="card__head">
            <div>
                <h2 class="card__title" id="clients-heading">Mes comptes clients associés</h2>
                <p class="section-note">Vos entités ou comptes autorisés dans notre papeterie.</p>
            </div>
            <a href="{{ route('portail.profil') }}" class="btn btn-ghost btn-sm">Gérer mes profils</a>
        </div>
        @if ($clients->isEmpty())
            <x-empty-state icon="building" title="Aucun compte client associé"
                text="Votre compte utilisateur n'est pas encore lié à une fiche client. Veuillez contacter votre administrateur.">
            </x-empty-state>
        @else
            <div style="padding: 1.25rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                @foreach ($clients as $client)
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: var(--radius-sm, 8px); padding: 1.2rem; display: flex; flex-direction: column; justify-content: space-between; gap: 0.75rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem;">
                            <div>
                                <span class="cell-code" style="font-size: 0.78rem; display: inline-block; margin-bottom: 0.35rem;">{{ $client->code_client }}</span>
                                <h3 style="font-size: 1.05rem; font-weight: 600; margin: 0; color: #fff;">{{ $client->nom_client }}</h3>
                            </div>
                            <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;">{{ $client->type_client }}</span>
                        </div>
                        <p style="font-size: 0.85rem; color: #8b949e; margin: 0;">
                            <i class="ti ti-map-pin" aria-hidden="true" style="font-size: 0.95rem;"></i>
                            {{ $client->ville_client }} ({{ $client->code_postal_client }})
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255, 255, 255, 0.06); padding-top: 0.75rem; font-size: 0.825rem;">
                            <span style="color: #38bdf8; font-weight: 500;">
                                Remise : <strong>{{ $client->remise_speciale }}%</strong>
                            </span>
                            <span style="color: #8b949e;">
                                <strong>{{ $client->commandes_count ?? 0 }}</strong> commande(s)
                            </span>
                        </div>
                        <div style="padding-top: 0.25rem;">
                            <a href="{{ route('portail.commandes', ['client_code' => $client->code_client]) }}" class="btn btn-ghost btn-sm" style="width: 100%; justify-content: center; font-size: 0.8rem;">
                                <i class="ti ti-receipt" aria-hidden="true"></i> Voir les commandes de ce compte
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Dernières commandes -->
    <section class="card card--flush" aria-labelledby="orders-heading">
        <div class="card__head">
            <div>
                <h2 class="card__title" id="orders-heading">Dernières commandes</h2>
                <p class="section-note">Vos 5 commandes les plus récentes.</p>
            </div>
            <a class="btn btn-ghost btn-sm" href="{{ route('portail.commandes') }}">
                Toutes les commandes <i class="ti ti-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        @if ($latestCommandes->isEmpty())
            <x-empty-state icon="receipt" title="Aucune commande passée"
                text="Vos commandes validées apparaîtront ici.">
            </x-empty-state>
        @else
            <div class="table-wrap">
                <table class="table">
                    <caption class="sr-only">Dernières commandes du client</caption>
                    <thead>
                        <tr>
                            <th scope="col">N° Commande</th>
                            <th scope="col">Date</th>
                            <th scope="col">Compte</th>
                            <th scope="col">Condition</th>
                            <th scope="col" class="num">Articles</th>
                            <th scope="col" class="num">Montant Total</th>
                            <th scope="col" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestCommandes as $commande)
                            <tr>
                                <td>
                                    <div style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                        <span class="cell-code">{{ $commande->numero_commande }}</span>
                                        <button type="button" class="btn-copy" data-copy="{{ $commande->numero_commande }}" title="Copier le numéro" aria-label="Copier le numéro de commande">
                                            <i class="ti ti-copy" aria-hidden="true"></i>
                                        </button>
                                    </div>
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
                                <td class="text-right">
                                    <a class="btn btn-ghost btn-sm" href="{{ route('portail.commandes.show', $commande->getKey()) }}">
                                        Consulter <i class="ti ti-chevron-right" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
