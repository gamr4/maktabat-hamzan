@extends('layouts.app')

@section('title', 'Fiche fournisseur')
@section('eyebrow', 'Répertoire')

@section('actions')
    <a class="btn btn-primary" href="{{ route('fournisseurs.edit', $fournisseur->code_fournisseur) }}">
        <i class="ti ti-edit" aria-hidden="true"></i> Modifier
    </a>
    <a class="btn btn-ghost" href="{{ route('fournisseurs.index') }}">Retour</a>
@endsection

@section('content')
    <div class="stack">
        <section class="card card--flush" aria-labelledby="supplier-title">
            <div class="detail-head">
                <div>
                    <h2 id="supplier-title">
                        {{ $fournisseur->nom_fournisseur }}
                        <span class="code-chip">{{ $fournisseur->code_fournisseur }}</span>
                    </h2>
                    <p>{{ $fournisseur->rue_fournisseur }}, {{ $fournisseur->code_postal_fournisseur }}
                        {{ $fournisseur->ville_fournisseur }}</p>
                </div>
            </div>

            <div class="card__body">
                <dl class="meta-list">
                    <div class="meta-row">
                        <dt>Pays</dt>
                        <dd>{{ $fournisseur->pays_fournisseur ?? '—' }}</dd>
                    </div>
                    <div class="meta-row">
                        <dt>Téléphone</dt>
                        <dd class="mono">{{ $fournisseur->tel_fournisseur ?? '—' }}</dd>
                    </div>
                    <div class="meta-row">
                        <dt>Contact</dt>
                        <dd>{{ $fournisseur->contact_fournisseur ?? '—' }}</dd>
                    </div>
                    <div class="meta-row">
                        <dt>Produits</dt>
                        <dd class="mono">{{ $fournisseur->produits->count() }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        <section class="card card--flush" aria-labelledby="supplier-products-title">
            <div class="card__head">
                <div>
                    <h2 class="card__title" id="supplier-products-title">Produits du fournisseur</h2>
                    <p class="section-note">{{ $fournisseur->produits->count() }} produit(s) rattaché(s).</p>
                </div>
                <a class="btn btn-ghost btn-sm" href="{{ route('produits.create') }}">
                    <i class="ti ti-plus" aria-hidden="true"></i> Nouveau produit
                </a>
            </div>

            @if ($fournisseur->produits->isEmpty())
                <x-empty-state icon="box" title="Aucun produit"
                    text="Ajoutez un produit pour rattacher ce fournisseur au catalogue." />
            @else
                <div class="table-wrap">
                    <table class="table">
                        <caption class="sr-only">Produits du fournisseur</caption>
                        <caption class="sr-only">Produits fournis par {{ $fournisseur->nom_fournisseur }}</caption>
                        <thead>
                            <tr>
                                <th scope="col">Code</th>
                                <th scope="col">Désignation</th>
                                <th scope="col">Famille</th>
                                <th scope="col" class="num">Prix</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fournisseur->produits as $produit)
                                <tr>
                                    <td>
                                        <a class="cell-code"
                                            href="{{ route('produits.show', $produit->code_produit) }}">
                                            {{ $produit->code_produit }}
                                        </a>
                                    </td>
                                    <td>{{ $produit->designation }}</td>
                                    <td><span class="badge badge--neutral">{{ $produit->famille }}</span></td>
                                    <td class="num"><x-money :value="$produit->prix_unitaire" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
