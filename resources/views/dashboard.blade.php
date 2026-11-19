@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('eyebrow', 'Vue d’ensemble')

@section('actions')
    <a class="btn btn-primary" href="{{ route('commandes.create') }}">
        <i class="ti ti-plus" aria-hidden="true"></i> Nouvelle commande
    </a>
@endsection

@section('content')
    <section class="workspace-hero" aria-labelledby="workspace-title">
        <div>
            <p class="workspace-hero__eyebrow"><span class="status-dot" aria-hidden="true"></span> Espace de travail</p>
            <h2 id="workspace-title">Votre activité, claire et prête à avancer.</h2>
            <p>Suivez le rythme de la papeterie, ajoutez une commande ou ouvrez directement la bonne collection.</p>
        </div>
        <div class="workspace-hero__links" aria-label="Accès rapides">
            <a href="{{ route('produits.index') }}"><i class="ti ti-box" aria-hidden="true"></i> Catalogue</a>
            <a href="{{ route('clients.index') }}"><i class="ti ti-users" aria-hidden="true"></i> Clients</a>
        </div>
    </section>

    <div class="stats-grid">
        <x-stat-card label="Commandes" :value="$stats['commandes'] ?? 0" icon="shopping-cart"
            :href="route('commandes.index')" accent />
        <x-stat-card label="Clients" :value="$stats['clients'] ?? 0" icon="users"
            :href="route('clients.index')" />
        <x-stat-card label="Fournisseurs" :value="$stats['fournisseurs'] ?? 0" icon="truck-delivery"
            :href="route('fournisseurs.index')" />
        <x-stat-card label="Produits" :value="$stats['produits'] ?? 0" icon="box"
            :href="route('produits.index')" />
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
