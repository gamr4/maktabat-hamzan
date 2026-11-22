@extends('layouts.app')

@section('title', 'Fiche client')
@section('eyebrow', 'Répertoire')

@section('actions')
    <a class="btn btn-primary" href="{{ route('clients.edit', $client->code_client) }}">
        <i class="ti ti-edit" aria-hidden="true"></i> Modifier
    </a>
    <a class="btn btn-ghost" href="{{ route('clients.index') }}">Retour</a>
@endsection

@section('content')
    <div class="stack">
        <section class="card card--flush" aria-labelledby="client-title">
            <div class="detail-head">
                <div>
                    <h2 id="client-title">
                        {{ $client->nom_client }}
                        <span class="code-chip">{{ $client->code_client }}</span>
                    </h2>
                    <p>{{ $client->rue_client }}, {{ $client->code_postal_client }} {{ $client->ville_client }}</p>
                </div>
            </div>

            <div class="card__body">
                <dl class="meta-list">
                    <div class="meta-row">
                        <dt>Type de client</dt>
                        <dd>{{ config('papeterie.client_types.' . $client->type_client) ?? $client->type_client }}</dd>
                    </div>
                    <div class="meta-row">
                        <dt>Remise spéciale</dt>
                        <dd class="mono">{{ $client->remise_speciale }} %</dd>
                    </div>
                    <div class="meta-row">
                        <dt>Commandes</dt>
                        <dd class="mono">{{ $client->commandes->count() }}</dd>
                    </div>
                    <div class="meta-row">
                        <dt>Compte utilisateur</dt>
                        <dd>
                            @if ($client->user)
                                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                    <span class="badge" style="background: rgba(52, 211, 153, 0.15); color: #34d399;">
                                        <i class="ti ti-user-check" aria-hidden="true"></i> {{ $client->user->email }}
                                    </span>
                                    <span style="font-size: 0.8rem; color: #8b949e;">({{ $client->user->name }})</span>
                                    <form method="POST" action="{{ route('clients.account', $client->code_client) }}" style="margin: 0;" onsubmit="return confirm('Réinitialiser le mot de passe pour ce compte ?');">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                            <i class="ti ti-key" aria-hidden="true"></i> Réinitialiser mdp
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                    <span class="badge badge--neutral">Aucun compte lié</span>
                                    <form method="POST" action="{{ route('clients.account', $client->code_client) }}" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" style="font-size: 0.75rem; padding: 0.25rem 0.6rem;">
                                            <i class="ti ti-user-plus" aria-hidden="true"></i> Créer le compte client
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        <section class="card card--flush" aria-labelledby="client-orders-title">
            <div class="card__head">
                <div>
                    <h2 class="card__title" id="client-orders-title">Commandes du client</h2>
                    <p class="section-note">{{ $client->commandes->count() }} commande(s) enregistrée(s).</p>
                </div>
                <a class="btn btn-ghost btn-sm" href="{{ route('commandes.create') }}">
                    <i class="ti ti-plus" aria-hidden="true"></i> Nouvelle commande
                </a>
            </div>

            @if ($client->commandes->isEmpty())
                <x-empty-state icon="receipt" title="Aucune commande"
                    text="Ce client n’a pas encore de commande enregistrée." />
            @else
                <div class="table-wrap">
                    <table class="table">
                        <caption class="sr-only">Commandes du client</caption>
                        <caption class="sr-only">Commandes de {{ $client->nom_client }}</caption>
                        <thead>
                            <tr>
                                <th scope="col">Numéro</th>
                                <th scope="col">Date</th>
                                <th scope="col">Condition</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($client->commandes as $commande)
                                <tr>
                                    <td>
                                        <a class="cell-code"
                                            href="{{ route('commandes.show', $commande->numero_commande) }}">
                                            {{ $commande->numero_commande }}
                                        </a>
                                    </td>
                                    <td><x-date :value="$commande->date_commande" /></td>
                                    <td><x-condition-badge :condition="$commande->condition" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
