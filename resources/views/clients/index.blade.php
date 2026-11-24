@extends('layouts.app')

@section('title', 'Clients')
@section('eyebrow', 'Répertoire')

@section('actions')
    <a class="btn btn-primary" href="{{ route('clients.create') }}">
        <i class="ti ti-plus" aria-hidden="true"></i> Nouveau client
    </a>
@endsection

@section('content')
    <section class="catalogue" aria-labelledby="clients-catalogue-title">
        <div class="catalogue__intro">
            <div>
                <p class="catalogue__kicker"><i class="ti ti-address-book" aria-hidden="true"></i> Carnet clients</p>
                <h2 id="clients-catalogue-title">Les relations au centre.</h2>
                <p>Un annuaire plus lisible pour retrouver rapidement la bonne personne, la bonne ville et les conditions associées.</p>
            </div>
            <p class="catalogue__count"><strong>{{ $clients->total() }}</strong> client{{ $clients->total() > 1 ? 's' : '' }}</p>
        </div>
        @if ($clients->isNotEmpty())
            <form class="catalogue-tools" method="GET" role="search">
                <label class="catalogue-search"><i class="ti ti-search" aria-hidden="true"></i><span class="sr-only">Rechercher un client</span><input name="q" value="{{ $search }}" placeholder="Rechercher un client ou une ville…"></label>
                <label class="catalogue-filter"><span class="sr-only">Filtrer par type</span><select name="type" onchange="this.form.submit()"><option value="">Tous les types</option>@foreach (['PU', 'PA', 'LI', 'AS', 'DI'] as $item)<option value="{{ $item }}" @selected($type === $item)>{{ $item }}</option>@endforeach</select></label>
                <button class="btn btn-ghost btn-sm" type="submit">Rechercher</button>
                @if ($search || $type)<a class="catalogue-reset" href="{{ route('clients.index') }}">Réinitialiser</a>@endif
            </form>
            <div class="catalogue-grid">
                @foreach ($clients as $client)
                    <article class="catalogue-card directory-card directory-card--client">
                        <header class="directory-card__head">
                            <div class="person-card__avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($client->nom_client, 0, 1)) }}</div>
                            <div class="directory-card__heading">
                                <div class="catalogue-card__meta">
                                    <span class="code-chip">{{ $client->code_client }}</span>
                                    <span class="badge badge--neutral" title="{{ config('papeterie.client_types.' . $client->type_client) }}">{{ $client->type_client }}</span>
                                </div>
                                <h3><a href="{{ route('clients.show', $client->code_client) }}">{{ $client->nom_client }}</a></h3>
                            </div>
                        </header>
                        <div class="catalogue-card__body directory-card__body">
                            <p class="directory-card__row"><i class="ti ti-map-pin" aria-hidden="true"></i><span>{{ $client->ville_client }}, {{ $client->code_postal_client }}</span></p>
                            @if ($client->user)
                                <p class="directory-card__access directory-card__access--active" title="Compte utilisateur actif">
                                    <i class="ti ti-user-check" aria-hidden="true"></i><span>{{ $client->user->email }}</span>
                                </p>
                            @else
                                <p class="directory-card__access">
                                    <i class="ti ti-user-off" aria-hidden="true"></i><span>Aucun compte utilisateur associé</span>
                                </p>
                            @endif
                        </div>
                        <footer class="catalogue-card__footer directory-card__footer">
                            <span class="directory-card__metric"><i class="ti ti-percentage" aria-hidden="true"></i> Remise <strong>{{ $client->remise_speciale }} %</strong></span>
                            <div class="table-actions">
                                <a class="icon-btn" href="{{ route('clients.show', $client->code_client) }}" title="Voir la fiche" aria-label="Voir {{ $client->nom_client }}"><i class="ti ti-arrow-up-right" aria-hidden="true"></i></a>
                                <a class="icon-btn" href="{{ route('clients.edit', $client->code_client) }}" title="Modifier" aria-label="Modifier {{ $client->nom_client }}"><i class="ti ti-edit" aria-hidden="true"></i></a>
                                <form class="inline-form" action="{{ route('clients.destroy', $client->code_client) }}" method="POST" data-confirm="Voulez-vous vraiment supprimer ce client ?">@csrf @method('DELETE')<button type="submit" class="icon-btn icon-btn--danger" title="Supprimer" aria-label="Supprimer {{ $client->nom_client }}"><i class="ti ti-trash" aria-hidden="true"></i></button></form>
                            </div>
                        </footer>
                    </article>
                @endforeach
            </div>
            <x-pagination :paginator="$clients" />
        @else
            <div class="card"><x-empty-state icon="users" title="Aucun client" text="Ajoutez un premier client pour commencer un carnet."><a class="btn btn-primary btn-sm" href="{{ route('clients.create') }}">Ajouter un client</a></x-empty-state></div>
        @endif
    </section>

    <section class="legacy-table">
    <section class="card card--flush" aria-labelledby="clients-title">
        <h2 class="sr-only" id="clients-title">Liste des clients</h2>

        @if ($clients->isEmpty())
            <x-empty-state icon="users" title="Aucun client"
                text="Ajoutez un premier client pour commencer un carnet.">
                <a class="btn btn-primary btn-sm" href="{{ route('clients.create') }}">Ajouter un client</a>
            </x-empty-state>
        @else
            <div class="table-wrap">
                <table class="table">
                    <caption class="sr-only">Liste des clients</caption>
                    <caption class="sr-only">Clients enregistrés</caption>
                    <thead>
                        <tr>
                            <th scope="col">Code</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Adresse</th>
                            <th scope="col">Ville</th>
                            <th scope="col">Compte d'accès</th>
                            <th scope="col">Type</th>
                            <th scope="col" class="num">Remise</th>
                            <th scope="col" class="cell-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td class="cell-code">{{ $client->code_client }}</td>
                                <td>{{ $client->nom_client }}</td>
                                <td>{{ $client->rue_client }}, {{ $client->code_postal_client }}</td>
                                <td>{{ $client->ville_client }}</td>
                                <td>
                                    @if ($client->user)
                                        <span class="badge" style="background: rgba(52, 211, 153, 0.15); color: #34d399; font-size: 0.75rem;">
                                            <i class="ti ti-user-check" aria-hidden="true"></i> {{ $client->user->email }}
                                        </span>
                                    @else
                                        <span class="badge badge--neutral" style="font-size: 0.75rem;">
                                            <i class="ti ti-user-x" aria-hidden="true"></i> Sans compte
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge--neutral"
                                        title="{{ config('papeterie.client_types.' . $client->type_client) }}">
                                        {{ $client->type_client }}
                                    </span>
                                </td>
                                <td class="num">{{ $client->remise_speciale }} %</td>
                                <td class="cell-actions">
                                    <div class="table-actions">
                                        <a class="icon-btn" href="{{ route('clients.show', $client->code_client) }}"
                                            title="Voir la fiche" aria-label="Voir {{ $client->nom_client }}">
                                            <i class="ti ti-eye" aria-hidden="true"></i>
                                        </a>
                                        <a class="icon-btn" href="{{ route('clients.edit', $client->code_client) }}"
                                            title="Modifier" aria-label="Modifier {{ $client->nom_client }}">
                                            <i class="ti ti-edit" aria-hidden="true"></i>
                                        </a>
                                        <form class="inline-form"
                                            action="{{ route('clients.destroy', $client->code_client) }}" method="POST"
                                            data-confirm="Voulez-vous vraiment supprimer ce client ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn icon-btn--danger"
                                                title="Supprimer" aria-label="Supprimer {{ $client->nom_client }}">
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

            <x-pagination :paginator="$clients" />
        @endif
    </section>
    </section>
@endsection
