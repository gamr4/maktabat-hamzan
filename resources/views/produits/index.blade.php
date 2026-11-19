@extends('layouts.app')

@section('title', 'Produits')
@section('eyebrow', 'Catalogue')

@section('actions')
    <a class="btn btn-primary" href="{{ route('produits.create') }}">
        <i class="ti ti-plus" aria-hidden="true"></i> Nouveau produit
    </a>
@endsection

@section('content')
    <section class="catalogue" aria-labelledby="products-catalogue-title">
        <div class="catalogue__intro">
            <div>
                <p class="catalogue__kicker"><i class="ti ti-sparkles" aria-hidden="true"></i> Collection active</p>
                <h2 id="products-catalogue-title">Tout le catalogue, d’un coup d’œil.</h2>
                <p>Parcourez les références, leurs familles et leurs sources sans vous perdre dans une feuille de calcul.</p>
            </div>
            <p class="catalogue__count"><strong>{{ $produits->total() }}</strong> référence{{ $produits->total() > 1 ? 's' : '' }}</p>
        </div>

        @if ($produits->isNotEmpty())
            <form class="catalogue-tools" method="GET" role="search">
                <label class="catalogue-search">
                    <i class="ti ti-search" aria-hidden="true"></i>
                    <span class="sr-only">Rechercher dans les produits</span>
                    <input name="q" value="{{ $search }}" placeholder="Rechercher une référence, un produit…">
                </label>
                <label class="catalogue-filter">
                    <span class="sr-only">Filtrer par famille</span>
                    <select name="famille" onchange="this.form.submit()">
                        <option value="">Toutes les familles</option>
                        @foreach (['PA', 'BU', 'FO', 'LI'] as $item)
                            <option value="{{ $item }}" @selected($famille === $item)>{{ $item }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="btn btn-ghost btn-sm" type="submit">Rechercher</button>
                @if ($search || $famille)<a class="catalogue-reset" href="{{ route('produits.index') }}">Réinitialiser</a>@endif
            </form>
            <div class="catalogue-grid catalogue-grid--products">
                @foreach ($produits as $produit)
                    <article class="catalogue-card product-card">
                        <div class="catalogue-card__visual product-card__visual" aria-hidden="true"><i class="ti ti-{{ match ($produit->famille) { 'PA' => 'pencil', 'BU' => 'briefcase', 'FO' => 'folders', default => 'book-2' } }}"></i><span>{{ $produit->famille }}</span></div>
                        <div class="catalogue-card__body">
                            <div class="catalogue-card__meta"><span class="code-chip">{{ $produit->code_produit }}</span><span class="catalogue-card__price"><x-money :value="$produit->prix_unitaire" /></span></div>
                            <h3><a href="{{ route('produits.show', $produit->code_produit) }}">{{ $produit->designation }}</a></h3>
                            <p class="catalogue-card__source"><i class="ti ti-building-store" aria-hidden="true"></i> {{ $produit->fournisseur?->nom_fournisseur ?? 'Fournisseur non renseigné' }}</p>
                        </div>
                        <footer class="catalogue-card__footer"><a class="text-link" href="{{ route('produits.show', $produit->code_produit) }}">Voir la fiche <i class="ti ti-arrow-up-right" aria-hidden="true"></i></a><div class="table-actions"><a class="icon-btn" href="{{ route('produits.edit', $produit->code_produit) }}" title="Modifier" aria-label="Modifier {{ $produit->designation }}"><i class="ti ti-edit" aria-hidden="true"></i></a><form class="inline-form" action="{{ route('produits.destroy', $produit->code_produit) }}" method="POST" data-confirm="Voulez-vous vraiment supprimer ce produit ?">@csrf @method('DELETE')<button type="submit" class="icon-btn icon-btn--danger" title="Supprimer" aria-label="Supprimer {{ $produit->designation }}"><i class="ti ti-trash" aria-hidden="true"></i></button></form></div></footer>
                    </article>
                @endforeach
            </div>
            <x-pagination :paginator="$produits" />
        @else
            <div class="card"><x-empty-state icon="box" title="Aucun produit" text="Ajoutez un produit pour commencer le catalogue."><a class="btn btn-primary btn-sm" href="{{ route('produits.create') }}">Ajouter un produit</a></x-empty-state></div>
        @endif
    </section>

    <section class="legacy-table">
    <section class="card card--flush" aria-labelledby="products-title">
        <h2 class="sr-only" id="products-title">Liste des produits</h2>

        @if ($produits->isEmpty())
            <x-empty-state icon="box" title="Aucun produit"
                text="Ajoutez un produit pour commencer le catalogue.">
                <a class="btn btn-primary btn-sm" href="{{ route('produits.create') }}">Ajouter un produit</a>
            </x-empty-state>
        @else
            <div class="table-wrap">
                <table class="table">
                    <caption class="sr-only">Liste des produits</caption>
                    <caption class="sr-only">Produits enregistrés</caption>
                    <thead>
                        <tr>
                            <th scope="col">Code</th>
                            <th scope="col">Désignation</th>
                            <th scope="col" class="num">Prix unitaire</th>
                            <th scope="col">Famille</th>
                            <th scope="col">Fournisseur</th>
                            <th scope="col" class="cell-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produits as $produit)
                            <tr>
                                <td class="cell-code">{{ $produit->code_produit }}</td>
                                <td>{{ $produit->designation }}</td>
                                <td class="num"><x-money :value="$produit->prix_unitaire" /></td>
                                <td><span class="badge badge--neutral">{{ $produit->famille }}</span></td>
                                <td>{{ $produit->fournisseur?->nom_fournisseur ?? '—' }}</td>
                                <td class="cell-actions">
                                    <div class="table-actions">
                                        <a class="icon-btn" href="{{ route('produits.show', $produit->code_produit) }}"
                                            title="Voir la fiche" aria-label="Voir {{ $produit->designation }}">
                                            <i class="ti ti-eye" aria-hidden="true"></i>
                                        </a>
                                        <a class="icon-btn" href="{{ route('produits.edit', $produit->code_produit) }}"
                                            title="Modifier" aria-label="Modifier {{ $produit->designation }}">
                                            <i class="ti ti-edit" aria-hidden="true"></i>
                                        </a>
                                        <form class="inline-form"
                                            action="{{ route('produits.destroy', $produit->code_produit) }}" method="POST"
                                            data-confirm="Voulez-vous vraiment supprimer ce produit ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn icon-btn--danger"
                                                title="Supprimer" aria-label="Supprimer {{ $produit->designation }}">
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

            <x-pagination :paginator="$produits" />
        @endif
    </section>
    </section>
@endsection
