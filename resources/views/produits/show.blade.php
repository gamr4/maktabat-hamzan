@extends('layouts.app')

@section('title', 'Fiche produit')
@section('eyebrow', 'Catalogue')

@section('actions')
    <a class="btn btn-primary" href="{{ route('produits.edit', $produit->code_produit) }}">
        <i class="ti ti-edit" aria-hidden="true"></i> Modifier
    </a>
    <a class="btn btn-ghost" href="{{ route('produits.index') }}">Retour</a>
@endsection

@section('content')
    <section class="card card--flush" aria-labelledby="product-title">
        <div class="detail-head">
            <div>
                <h2 id="product-title">
                    {{ $produit->designation }}
                    <span class="code-chip">{{ $produit->code_produit }}</span>
                </h2>
                <p>{{ $produit->fournisseur?->nom_fournisseur ?? 'Fournisseur non renseigné' }}</p>
            </div>
        </div>

        <div class="card__body">
            <dl class="meta-list">
                <div class="meta-row">
                    <dt>Famille</dt>
                    <dd>{{ $produit->famille }}</dd>
                </div>
                <div class="meta-row">
                    <dt>Prix unitaire</dt>
                    <dd class="mono"><x-money :value="$produit->prix_unitaire" /></dd>
                </div>
                <div class="meta-row">
                    <dt>Fournisseur</dt>
                    <dd>
                        @if ($produit->fournisseur)
                            <a href="{{ route('fournisseurs.show', $produit->fournisseur->code_fournisseur) }}">
                                {{ $produit->fournisseur->nom_fournisseur }}
                            </a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div class="meta-row">
                    <dt>Commandes</dt>
                    <dd class="mono">{{ $produit->commandes->count() }}</dd>
                </div>
            </dl>
        </div>
    </section>
@endsection
