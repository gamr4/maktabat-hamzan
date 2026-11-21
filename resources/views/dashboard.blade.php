@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('eyebrow', 'Vue d’ensemble')

@section('actions')
    <a class="btn btn-primary" href="{{ route('commandes.create') }}">
        <i class="ti ti-plus" aria-hidden="true"></i> Nouvelle commande
    </a>
@endsection

@section('content')
    <!-- Slim single-line greeting bar -->
    <section class="greeting-bar" aria-labelledby="greeting-title">
        <div class="greeting-bar__left">
            <span class="status-indicator-dot" aria-hidden="true"></span>
            <h2 id="greeting-title" class="greeting-bar__title">
                Bonjour, <strong>{{ Auth::user()->name ?? 'Abdrahman' }}</strong>
                <span class="greeting-bar__sub">— Console de gestion opérationnelle</span>
            </h2>
        </div>
        <div class="greeting-bar__actions" aria-label="Accès rapides">
            <a href="{{ route('produits.index') }}" class="greeting-btn">
                <i class="ti ti-box" aria-hidden="true"></i>
                <span>Catalogue</span>
            </a>
            <a href="{{ route('clients.index') }}" class="greeting-btn">
                <i class="ti ti-users" aria-hidden="true"></i>
                <span>Clients</span>
            </a>
        </div>
    </section>

    <!-- Enriched 4 KPI Cards -->
    <div class="stats-grid">
        <x-stat-card label="Commandes" :value="$stats['commandes'] ?? 15" icon="shopping-cart"
            :href="route('commandes.index')" accent trend="+12% ce mois" trend-type="up" sparkline="orders" />
        <x-stat-card label="Clients" :value="$stats['clients'] ?? 25" icon="users"
            :href="route('clients.index')" trend="+8% ce mois" trend-type="up" sparkline="clients" />
        <x-stat-card label="Fournisseurs" :value="$stats['fournisseurs'] ?? 30" icon="truck-delivery"
            :href="route('fournisseurs.index')" trend="Partenaires actifs" trend-type="neutral" sparkline="suppliers" />
        <x-stat-card label="Produits" :value="$stats['produits'] ?? 25" icon="box"
            :href="route('produits.index')" trend="+5% ce mois" trend-type="up" sparkline="products" />
    </div>

    <section class="card card--flush" aria-labelledby="latest-orders-title">
        <div class="card__head">
            <div>
                <h2 class="card__title" id="latest-orders-title">Dernières commandes</h2>
                <p class="section-note">Les cinq commandes les plus récentes.</p>
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
                    <caption class="sr-only">Cinq dernières commandes</caption>
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
@endsection
