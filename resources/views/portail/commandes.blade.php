@extends('layouts.portail')

@section('title', 'Mes Commandes')
@section('eyebrow', 'Historique & Suivi')

@section('breadcrumbs')
    <a href="{{ route('portail.dashboard') }}">Portail</a>
    <span class="sep">/</span>
    <span>Mes Commandes</span>
@endsection

@section('actions')
    <a href="{{ route('portail.dashboard') }}" class="btn btn-ghost">
        <i class="ti ti-arrow-left" aria-hidden="true"></i> Tableau de bord
    </a>
    <button type="button" class="btn btn-secondary" onclick="window.print()">
        <i class="ti ti-printer" aria-hidden="true"></i> Imprimer
    </button>
@endsection

@section('content')
    <!-- Comprehensive Filter Panel -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('portail.commandes') }}" style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; align-items: flex-end;">
                <div>
                    <label for="filter-q" style="display: block; font-size: 0.78rem; font-weight: 500; color: #8b949e; margin-bottom: 0.35rem;">
                        <i class="ti ti-search" aria-hidden="true"></i> Recherche libre
                    </label>
                    <input type="text" id="filter-q" name="q" value="{{ $search }}" class="input" placeholder="N° commande, client, etc.">
                </div>

                @if(isset($userClients) && $userClients->count() > 1)
                    <div>
                        <label for="filter-client" style="display: block; font-size: 0.78rem; font-weight: 500; color: #8b949e; margin-bottom: 0.35rem;">
                            <i class="ti ti-building" aria-hidden="true"></i> Compte client
                        </label>
                        <select id="filter-client" name="client_code" class="input">
                            <option value="">Tous mes comptes</option>
                            @foreach ($userClients as $uClient)
                                <option value="{{ $uClient->code_client }}" @selected(request('client_code') === $uClient->code_client)>
                                    {{ $uClient->nom_client }} ({{ $uClient->code_client }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label for="filter-condition" style="display: block; font-size: 0.78rem; font-weight: 500; color: #8b949e; margin-bottom: 0.35rem;">
                        <i class="ti ti-credit-card" aria-hidden="true"></i> Condition de règlement
                    </label>
                    <select id="filter-condition" name="condition" class="input">
                        <option value="">Toutes les conditions</option>
                        <option value="LIV" @selected(request('condition') === 'LIV')>Au comptant (LIV)</option>
                        <option value="T30" @selected(request('condition') === 'T30')>30 jours (T30)</option>
                    </select>
                </div>

                <div>
                    <label for="filter-from" style="display: block; font-size: 0.78rem; font-weight: 500; color: #8b949e; margin-bottom: 0.35rem;">
                        <i class="ti ti-calendar" aria-hidden="true"></i> Du
                    </label>
                    <input type="date" id="filter-from" name="date_from" value="{{ request('date_from') }}" class="input">
                </div>

                <div>
                    <label for="filter-to" style="display: block; font-size: 0.78rem; font-weight: 500; color: #8b949e; margin-bottom: 0.35rem;">
                        <i class="ti ti-calendar" aria-hidden="true"></i> Au
                    </label>
                    <input type="date" id="filter-to" name="date_to" value="{{ request('date_to') }}" class="input">
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; align-items: center; justify-content: flex-end; flex-wrap: wrap; border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 0.75rem;">
                <input type="hidden" name="sort" value="{{ $sort ?? 'date_commande' }}">
                <input type="hidden" name="dir" value="{{ $dir ?? 'desc' }}">

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="ti ti-filter" aria-hidden="true"></i> Appliquer les filtres
                </button>
                @if ($search || request('condition') || request('client_code') || request('date_from') || request('date_to'))
                    <a href="{{ route('portail.commandes') }}" class="btn btn-ghost btn-sm">
                        <i class="ti ti-x" aria-hidden="true"></i> Réinitialiser
                    </a>
                @endif
            </div>

            <!-- Active filter pills -->
            @php
                $hasFilters = $search || request('condition') || request('client_code') || request('date_from') || request('date_to');
            @endphp
            @if ($hasFilters)
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; padding-top: 0.25rem;">
                    <span style="font-size: 0.75rem; color: #8b949e;">Filtres actifs :</span>
                    @if ($search)
                        <span class="filter-pill">
                            Texte: "{{ $search }}"
                            <a href="{{ route('portail.commandes', request()->except('q')) }}" style="color: inherit; text-decoration: none;">&times;</a>
                        </span>
                    @endif
                    @if (request('condition'))
                        <span class="filter-pill">
                            Condition: {{ request('condition') === 'LIV' ? 'Au comptant' : '30 jours' }}
                            <a href="{{ route('portail.commandes', request()->except('condition')) }}" style="color: inherit; text-decoration: none;">&times;</a>
                        </span>
                    @endif
                    @if (request('client_code'))
                        <span class="filter-pill">
                            Compte: {{ request('client_code') }}
                            <a href="{{ route('portail.commandes', request()->except('client_code')) }}" style="color: inherit; text-decoration: none;">&times;</a>
                        </span>
                    @endif
                    @if (request('date_from') || request('date_to'))
                        <span class="filter-pill">
                            Période: {{ request('date_from') ?? 'Début' }} → {{ request('date_to') ?? 'Fin' }}
                            <a href="{{ route('portail.commandes', request()->except(['date_from', 'date_to'])) }}" style="color: inherit; text-decoration: none;">&times;</a>
                        </span>
                    @endif
                </div>
            @endif
        </form>
    </div>

    <!-- Orders List Section -->
    <section class="card card--flush" aria-labelledby="all-orders-title">
        <div class="card__head">
            <div>
                <h2 class="card__title" id="all-orders-title">Liste de vos commandes</h2>
                <p class="section-note">{{ $commandes->total() }} commande(s) trouvée(s).</p>
            </div>
            <div style="font-size: 0.8rem; color: #8b949e;">
                Page {{ $commandes->currentPage() }} sur {{ max($commandes->lastPage(), 1) }}
            </div>
        </div>

        @if ($commandes->isEmpty())
            <x-empty-state icon="receipt" title="Aucune commande trouvée"
                text="Aucune commande ne correspond à vos critères de recherche ou votre historique est vide.">
                @if ($hasFilters)
                    <a href="{{ route('portail.commandes') }}" class="btn btn-ghost btn-sm">Effacer tous les filtres</a>
                @endif
            </x-empty-state>
        @else
            @php
                $nextDateDir = ($sort === 'date_commande' && $dir === 'desc') ? 'asc' : 'desc';
                $nextNumDir = ($sort === 'numero_commande' && $dir === 'desc') ? 'asc' : 'desc';
            @endphp
            <div class="table-wrap">
                <table class="table">
                    <caption class="sr-only">Liste des commandes</caption>
                    <thead>
                        <tr>
                            <th scope="col">
                                <a href="{{ route('portail.commandes', array_merge(request()->query(), ['sort' => 'numero_commande', 'dir' => $nextNumDir])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                                    Numéro
                                    @if ($sort === 'numero_commande')
                                        <i class="ti ti-chevron-{{ $dir === 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ route('portail.commandes', array_merge(request()->query(), ['sort' => 'date_commande', 'dir' => $nextDateDir])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                                    Date de commande
                                    @if ($sort === 'date_commande')
                                        <i class="ti ti-chevron-{{ $dir === 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">Compte Client</th>
                            <th scope="col">Condition</th>
                            <th scope="col" class="num">Articles</th>
                            <th scope="col" class="num">Montant Total</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commandes as $commande)
                            <tr>
                                <td>
                                    <div style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                        <span class="cell-code">{{ $commande->numero_commande }}</span>
                                        <button type="button" class="btn-copy" data-copy="{{ $commande->numero_commande }}" title="Copier le numéro" aria-label="Copier le numéro">
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
                                        Détails <i class="ti ti-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 1rem 1.5rem;">
                <x-pagination :paginator="$commandes" />
            </div>
        @endif
    </section>
@endsection
