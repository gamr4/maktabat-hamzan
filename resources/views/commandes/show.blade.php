@extends('layouts.app')

@php
    $isClientView = $isClientView ?? false;
@endphp

@section('title', 'Commande ' . $commande->numero_commande)
@section('eyebrow', 'Bon de commande')

@section('actions')
    @unless ($isClientView)
        <a class="btn btn-primary" href="{{ route('commandes.edit', $commande->numero_commande) }}">
            <i class="ti ti-edit" aria-hidden="true"></i> Modifier
        </a>
    @endunless
    <a class="btn btn-ghost" href="{{ $isClientView ? route('dashboard') : route('commandes.index') }}">
        {{ $isClientView ? 'Mes commandes' : 'Retour' }}
    </a>
@endsection

@section('content')
    <div class="stack">
        <section class="card card--flush" aria-labelledby="order-title">
            <div class="detail-head">
                <div>
                    <h2 id="order-title">
                        {{ $commande->numero_commande }}
                        <x-condition-badge :condition="$commande->condition" />
                    </h2>
                    <p>
                        @if ($commande->client)
                            @if ($isClientView)
                                {{ $commande->client->nom_client }}
                            @else
                                <a href="{{ route('clients.show', $commande->client->code_client) }}">
                                    {{ $commande->client->nom_client }}
                                </a>
                            @endif
                        @else
                            Client non renseigné
                        @endif
                        · <x-date :value="$commande->date_commande" />
                    </p>
                </div>
            </div>

            <div class="card__body">
                <dl class="meta-list">
                    <div class="meta-row">
                        <dt>Client</dt>
                        <dd>{{ $commande->client?->nom_client ?? '—' }}</dd>
                    </div>
                    <div class="meta-row">
                        <dt>Code client</dt>
                        <dd class="mono">{{ $commande->code_client }}</dd>
                    </div>
                    <div class="meta-row">
                        <dt>Date de commande</dt>
                        <dd><x-date :value="$commande->date_commande" /></dd>
                    </div>
                    <div class="meta-row">
                        <dt>Condition</dt>
                        <dd><x-condition-badge :condition="$commande->condition" /></dd>
                    </div>
                </dl>
            </div>
        </section>

        <section class="card card--flush" aria-labelledby="order-lines-title">
            <div class="card__head">
                <div>
                    <h2 class="card__title" id="order-lines-title">Produits commandés</h2>
                    <p class="section-note">{{ $commande->produits->count() }} ligne(s) de commande.</p>
                </div>
            </div>

            @if ($commande->produits->isEmpty())
                <x-empty-state icon="box" title="Aucun produit"
                    text="Cette commande ne contient encore aucune ligne.">
                    @unless ($isClientView)
                        <a class="btn btn-primary btn-sm"
                            href="{{ route('commandes.edit', $commande->numero_commande) }}">Ajouter des produits</a>
                    @endunless
                </x-empty-state>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <caption class="sr-only">Lignes de la commande</caption>
                        <caption class="sr-only">Lignes de la commande {{ $commande->numero_commande }}</caption>
                        <thead>
                            <tr>
                                <th scope="col">Code produit</th>
                                <th scope="col">Désignation</th>
                                <th scope="col" class="num">Quantité</th>
                                <th scope="col" class="num">Prix unitaire</th>
                                <th scope="col" class="num">Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commande->produits as $produit)
                                <tr>
                                    <td class="cell-code">{{ $produit->code_produit }}</td>
                                    <td>{{ $produit->designation }}</td>
                                    <td class="num">{{ $produit->pivot->quantite }}</td>
                                    <td class="num"><x-money :value="$produit->prix_unitaire" /></td>
                                    <td class="num">
                                        <x-money :value="$produit->prix_unitaire * $produit->pivot->quantite" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="totals">
                    <span>Total de la commande</span>
                    <strong>
                        <x-money :value="$commande->produits->sum(
                            fn ($produit) => $produit->prix_unitaire * $produit->pivot->quantite,
                        )" />
                    </strong>
                </div>
            @endif
        </section>
    </div>
@endsection
