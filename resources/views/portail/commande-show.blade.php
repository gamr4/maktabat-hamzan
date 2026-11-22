@extends('layouts.portail')

@section('title', 'Commande ' . $commande->numero_commande)
@section('eyebrow', 'Détails & Facturation')
@section('content-width', '1000px')

@section('breadcrumbs')
    <a href="{{ route('portail.dashboard') }}">Portail</a>
    <span class="sep">/</span>
    <a href="{{ route('portail.commandes') }}">Mes Commandes</a>
    <span class="sep">/</span>
    <span>{{ $commande->numero_commande }}</span>
@endsection

@section('actions')
    <a href="{{ route('portail.commandes') }}" class="btn btn-ghost">
        <i class="ti ti-arrow-left" aria-hidden="true"></i> Retour aux commandes
    </a>
    <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="ti ti-printer" aria-hidden="true"></i> Imprimer le bon de commande
    </button>
@endsection

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card">
            <h2 class="card__title" style="margin-bottom: 1rem;"><i class="ti ti-info-circle"></i> Informations Générales</h2>
            <dl class="def-list" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <dt style="color: #8b949e; margin: 0;">N° Commande</dt>
                    <dd style="margin: 0; display: inline-flex; align-items: center; gap: 0.4rem;">
                        <strong>{{ $commande->numero_commande }}</strong>
                        <button type="button" class="btn-copy" data-copy="{{ $commande->numero_commande }}" title="Copier le numéro" aria-label="Copier le numéro">
                            <i class="ti ti-copy" aria-hidden="true"></i>
                        </button>
                    </dd>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <dt style="color: #8b949e; margin: 0;">Date d'émission</dt>
                    <dd style="margin: 0;"><x-date :value="$commande->date_commande" /></dd>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0;">
                    <dt style="color: #8b949e; margin: 0;">Condition de règlement</dt>
                    <dd style="margin: 0;"><x-condition-badge :condition="$commande->condition" /></dd>
                </div>
            </dl>
        </div>

        <div class="card">
            <h2 class="card__title" style="margin-bottom: 1rem;"><i class="ti ti-building"></i> Informations Client Associé</h2>
            <dl class="def-list" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <dt style="color: #8b949e; margin: 0;">Raison Sociale</dt>
                    <dd style="margin: 0;"><strong>{{ $commande->client?->nom_client ?? '—' }}</strong></dd>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <dt style="color: #8b949e; margin: 0;">Adresse</dt>
                    <dd style="margin: 0;">{{ $commande->client?->rue_client }}, {{ $commande->client?->ville_client }} ({{ $commande->client?->code_postal_client }})</dd>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0;">
                    <dt style="color: #8b949e; margin: 0;">Remise contractuelle</dt>
                    <dd style="margin: 0;">
                        <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;">
                            {{ $commande->client?->remise_speciale ?? 0 }}%
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Articles Section -->
    <section class="card card--flush" aria-labelledby="articles-title">
        <div class="card__head">
            <div>
                <h2 class="card__title" id="articles-title">Articles commandés</h2>
                <p class="section-note">{{ $commande->produits->count() }} référence(s) dans cette commande.</p>
            </div>
            <div style="font-size: 0.85rem; color: #8b949e;">
                Total articles : <strong>{{ $commande->produits->sum('pivot.quantite') }}</strong>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <caption class="sr-only">Détail des articles de la commande</caption>
                <thead>
                    <tr>
                        <th scope="col">Code Produit</th>
                        <th scope="col">Désignation</th>
                        <th scope="col">Fournisseur</th>
                        <th scope="col" class="num">Prix Unitaire</th>
                        <th scope="col" class="num">Quantité</th>
                        <th scope="col" class="num">Sous-total HT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commande->produits as $produit)
                        @php
                            $subtotal = $produit->prix_unitaire * $produit->pivot->quantite;
                        @endphp
                        <tr>
                            <td>
                                <div style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                    <span class="cell-code">{{ $produit->code_produit }}</span>
                                    <button type="button" class="btn-copy" data-copy="{{ $produit->code_produit }}" title="Copier la référence" aria-label="Copier la référence">
                                        <i class="ti ti-copy" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                            <td><strong>{{ $produit->designation }}</strong></td>
                            <td style="color: #8b949e; font-size: 0.875rem;">
                                {{ $produit->fournisseur?->nom_fournisseur ?? 'Papeterie Hamzan' }}
                            </td>
                            <td class="num"><x-money :value="$produit->prix_unitaire" /></td>
                            <td class="num"><strong>{{ $produit->pivot->quantite }}</strong></td>
                            <td class="num"><x-money :value="$subtotal" /></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $remisePourcentage = $commande->client?->remise_speciale ?? 0;
                        $totalBrut = $commande->montantBrut();
                        $montantRemise = $commande->montantRemise();
                        $totalNet = $commande->montantNet();
                    @endphp
                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: 500; color: #8b949e;">Total brut :</td>
                        <td class="num"><x-money :value="$totalBrut" /></td>
                    </tr>
                    @if ($remisePourcentage > 0)
                        <tr>
                            <td colspan="5" style="text-align: right; font-weight: 500; color: #38bdf8;">Remise spéciale client ({{ $remisePourcentage }}%) :</td>
                            <td class="num" style="color: #38bdf8;">- <x-money :value="$montantRemise" /></td>
                        </tr>
                    @endif
                    <tr style="border-top: 2px solid rgba(255, 255, 255, 0.15);">
                        <td colspan="5" style="text-align: right; font-weight: 700; font-size: 1.1rem;">Montant Total Net TTC :</td>
                        <td class="num" style="font-weight: 700; font-size: 1.15rem; color: #38bdf8;"><x-money :value="$totalNet" /></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
@endsection
