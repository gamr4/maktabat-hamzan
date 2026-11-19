@extends('layouts.app')

@section('title', 'Commandes')
@section('eyebrow', 'Flux opérationnel')

@section('actions')
    <a class="btn btn-primary" href="{{ route('commandes.create') }}">
        <i class="ti ti-plus" aria-hidden="true"></i> Nouvelle commande
    </a>
@endsection

@section('content')
    <section class="catalogue orders-catalogue" aria-labelledby="orders-catalogue-title">
        <div class="catalogue__intro">
            <div>
                <p class="catalogue__kicker"><i class="ti ti-route" aria-hidden="true"></i> Flux opérationnel</p>
                <h2 id="orders-catalogue-title">Chaque commande a son contexte.</h2>
                <p>Visualisez les commandes récentes, leur client, leurs produits et leur état sans une table interminable.</p>
            </div>
            <p class="catalogue__count"><strong>{{ $commandes->total() }}</strong> commande{{ $commandes->total() > 1 ? 's' : '' }}</p>
        </div>
        @if ($commandes->isNotEmpty())
            <form class="catalogue-tools" method="GET" role="search">
                <label class="catalogue-search"><i class="ti ti-search" aria-hidden="true"></i><span class="sr-only">Rechercher une commande</span><input name="q" value="{{ $search }}" placeholder="Rechercher une commande ou un client…"></label>
                <label class="catalogue-filter"><span class="sr-only">Filtrer par condition</span><select name="condition" onchange="this.form.submit()"><option value="">Toutes les conditions</option><option value="LIV" @selected($condition === 'LIV')>Livraison</option><option value="T30" @selected($condition === 'T30')>30 jours</option></select></label>
                <button class="btn btn-ghost btn-sm" type="submit">Rechercher</button>
                @if ($search || $condition)<a class="catalogue-reset" href="{{ route('commandes.index') }}">Réinitialiser</a>@endif
            </form>
            <div class="orders-grid">
                @foreach ($commandes as $commande)
                    <article class="order-card">
                        <div class="order-card__head"><div><span class="code-chip">{{ $commande->numero_commande }}</span><h3><a href="{{ route('commandes.show', $commande->numero_commande) }}">{{ $commande->client?->nom_client ?? 'Client non renseigné' }}</a></h3></div><x-condition-badge :condition="$commande->condition" /></div>
                        <dl class="order-card__facts"><div><dt>Date</dt><dd><x-date :value="$commande->date_commande" /></dd></div><div><dt>Articles</dt><dd>{{ $commande->produits->sum('pivot.quantite') }}</dd></div><div><dt>Total</dt><dd><x-money :value="$commande->produits->sum(fn ($produit) => $produit->prix_unitaire * $produit->pivot->quantite)" /></dd></div></dl>
                        <footer class="catalogue-card__footer"><a class="text-link" href="{{ route('commandes.show', $commande->numero_commande) }}">Ouvrir <i class="ti ti-arrow-up-right" aria-hidden="true"></i></a><div class="table-actions"><a class="icon-btn" href="{{ route('commandes.edit', $commande->numero_commande) }}" title="Modifier" aria-label="Modifier la commande {{ $commande->numero_commande }}"><i class="ti ti-edit" aria-hidden="true"></i></a><form class="inline-form" action="{{ route('commandes.destroy', $commande->numero_commande) }}" method="POST" data-confirm="Voulez-vous vraiment supprimer cette commande ?">@csrf @method('DELETE')<button type="submit" class="icon-btn icon-btn--danger" title="Supprimer" aria-label="Supprimer la commande {{ $commande->numero_commande }}"><i class="ti ti-trash" aria-hidden="true"></i></button></form></div></footer>
                    </article>
                @endforeach
            </div>
            <x-pagination :paginator="$commandes" />
        @else
            <div class="card"><x-empty-state icon="receipt" title="Aucune commande" text="Créez une commande pour relier un client, des produits et une condition."><a class="btn btn-primary btn-sm" href="{{ route('commandes.create') }}">Créer une commande</a></x-empty-state></div>
        @endif
    </section>

    <section class="legacy-table">
    <section class="card card--flush" aria-labelledby="orders-title">
        <h2 class="sr-only" id="orders-title">Liste des commandes</h2>

        @if ($commandes->isEmpty())
            <x-empty-state icon="receipt" title="Aucune commande"
                text="Créez une commande pour relier un client, des produits et une condition.">
                <a class="btn btn-primary btn-sm" href="{{ route('commandes.create') }}">Créer une commande</a>
            </x-empty-state>
        @else
            <div class="table-wrap">
                <table class="table">
                    <caption class="sr-only">Liste des commandes</caption>
                    <caption class="sr-only">Commandes enregistrées</caption>
                    <thead>
                        <tr>
                            <th scope="col">Numéro</th>
                            <th scope="col">Date</th>
                            <th scope="col">Client</th>
                            <th scope="col">Condition</th>
                            <th scope="col" class="num">Quantité</th>
                            <th scope="col" class="num">Montant</th>
                            <th scope="col" class="cell-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commandes as $commande)
                            <tr>
                                <td>
                                    <a class="cell-code"
                                        href="{{ route('commandes.show', $commande->numero_commande) }}">
                                        {{ $commande->numero_commande }}
                                    </a>
                                </td>
                                <td><x-date :value="$commande->date_commande" /></td>
                                <td>{{ $commande->client?->nom_client ?? '—' }}</td>
                                <td><x-condition-badge :condition="$commande->condition" /></td>
                                <td class="num">{{ $commande->produits->sum('pivot.quantite') }}</td>
                                <td class="num">
                                    <x-money :value="$commande->produits->sum(
                                        fn ($produit) => $produit->prix_unitaire * $produit->pivot->quantite,
                                    )" />
                                </td>
                                <td class="cell-actions">
                                    <div class="table-actions">
                                        <a class="icon-btn"
                                            href="{{ route('commandes.show', $commande->numero_commande) }}"
                                            title="Voir la commande"
                                            aria-label="Voir la commande {{ $commande->numero_commande }}">
                                            <i class="ti ti-eye" aria-hidden="true"></i>
                                        </a>
                                        <a class="icon-btn"
                                            href="{{ route('commandes.edit', $commande->numero_commande) }}"
                                            title="Modifier"
                                            aria-label="Modifier la commande {{ $commande->numero_commande }}">
                                            <i class="ti ti-edit" aria-hidden="true"></i>
                                        </a>
                                        <form class="inline-form"
                                            action="{{ route('commandes.destroy', $commande->numero_commande) }}"
                                            method="POST"
                                            data-confirm="Voulez-vous vraiment supprimer cette commande ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn icon-btn--danger"
                                                title="Supprimer"
                                                aria-label="Supprimer la commande {{ $commande->numero_commande }}">
                                                <i class="ti ti-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$commandes" />
        @endif
    </section>
    </section>
@endsection
