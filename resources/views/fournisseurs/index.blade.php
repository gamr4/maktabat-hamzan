@extends('layouts.app')

@section('title', 'Fournisseurs')
@section('eyebrow', 'Répertoire')

@section('actions')
    <a class="btn btn-primary" href="{{ route('fournisseurs.create') }}">
        <i class="ti ti-plus" aria-hidden="true"></i> Nouveau fournisseur
    </a>
@endsection

@section('content')
    <section class="catalogue" aria-labelledby="suppliers-catalogue-title">
        <div class="catalogue__intro">
            <div>
                <p class="catalogue__kicker"><i class="ti ti-building-factory-2" aria-hidden="true"></i> Réseau partenaires</p>
                <h2 id="suppliers-catalogue-title">Des sources fiables, visibles.</h2>
                <p>Gardez les contacts de votre réseau à portée de main et donnez plus de contexte à chaque fournisseur.</p>
            </div>
            <p class="catalogue__count"><strong>{{ $fournisseurs->total() }}</strong> partenaire{{ $fournisseurs->total() > 1 ? 's' : '' }}</p>
        </div>
        @if ($fournisseurs->isNotEmpty())
            <form class="catalogue-tools" method="GET" role="search">
                <label class="catalogue-search"><i class="ti ti-search" aria-hidden="true"></i><span class="sr-only">Rechercher un fournisseur</span><input name="q" value="{{ $search }}" placeholder="Rechercher un partenaire ou un lieu…"></label>
                <button class="btn btn-ghost btn-sm" type="submit">Rechercher</button>
                @if ($search)<a class="catalogue-reset" href="{{ route('fournisseurs.index') }}">Réinitialiser</a>@endif
            </form>
            <div class="catalogue-grid">
                @foreach ($fournisseurs as $fournisseur)
                    <article class="catalogue-card supplier-card">
                        <div class="supplier-card__icon" aria-hidden="true"><i class="ti ti-building-store"></i></div>
                        <div class="catalogue-card__body">
                            <div class="catalogue-card__meta"><span class="code-chip">{{ $fournisseur->code_fournisseur }}</span><span class="catalogue-card__location">{{ $fournisseur->pays_fournisseur ?? 'International' }}</span></div>
                            <h3><a href="{{ route('fournisseurs.show', $fournisseur->code_fournisseur) }}">{{ $fournisseur->nom_fournisseur }}</a></h3>
                            <p class="catalogue-card__source"><i class="ti ti-map-pin" aria-hidden="true"></i> {{ $fournisseur->ville_fournisseur }} · {{ $fournisseur->contact_fournisseur ?? 'Contact à renseigner' }}</p>
                        </div>
                        <footer class="catalogue-card__footer"><span class="catalogue-card__detail mono">{{ $fournisseur->tel_fournisseur ?? '—' }}</span><div class="table-actions"><a class="icon-btn" href="{{ route('fournisseurs.show', $fournisseur->code_fournisseur) }}" title="Voir la fiche" aria-label="Voir {{ $fournisseur->nom_fournisseur }}"><i class="ti ti-arrow-up-right" aria-hidden="true"></i></a><a class="icon-btn" href="{{ route('fournisseurs.edit', $fournisseur->code_fournisseur) }}" title="Modifier" aria-label="Modifier {{ $fournisseur->nom_fournisseur }}"><i class="ti ti-edit" aria-hidden="true"></i></a><form class="inline-form" action="{{ route('fournisseurs.destroy', $fournisseur->code_fournisseur) }}" method="POST" data-confirm="Voulez-vous vraiment supprimer ce fournisseur ?">@csrf @method('DELETE')<button type="submit" class="icon-btn icon-btn--danger" title="Supprimer" aria-label="Supprimer {{ $fournisseur->nom_fournisseur }}"><i class="ti ti-trash" aria-hidden="true"></i></button></form></div></footer>
                    </article>
                @endforeach
            </div>
            <x-pagination :paginator="$fournisseurs" />
        @else
            <div class="card"><x-empty-state icon="truck-delivery" title="Aucun fournisseur" text="Ajoutez un fournisseur pour relier le catalogue à sa source."><a class="btn btn-primary btn-sm" href="{{ route('fournisseurs.create') }}">Ajouter un fournisseur</a></x-empty-state></div>
        @endif
    </section>

    <section class="legacy-table">
    <section class="card card--flush" aria-labelledby="suppliers-title">
        <h2 class="sr-only" id="suppliers-title">Liste des fournisseurs</h2>

        @if ($fournisseurs->isEmpty())
            <x-empty-state icon="truck-delivery" title="Aucun fournisseur"
                text="Ajoutez un fournisseur pour relier le catalogue à sa source.">
                <a class="btn btn-primary btn-sm" href="{{ route('fournisseurs.create') }}">Ajouter un fournisseur</a>
            </x-empty-state>
        @else
            <div class="table-wrap">
                <table class="table">
                    <caption class="sr-only">Liste des fournisseurs</caption>
                    <caption class="sr-only">Fournisseurs enregistrés</caption>
                    <thead>
                        <tr>
                            <th scope="col">Code</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Adresse</th>
                            <th scope="col">Ville</th>
                            <th scope="col">Pays</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Contact</th>
                            <th scope="col" class="cell-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fournisseurs as $fournisseur)
                            <tr>
                                <td class="cell-code">{{ $fournisseur->code_fournisseur }}</td>
                                <td>{{ $fournisseur->nom_fournisseur }}</td>
                                <td>{{ $fournisseur->rue_fournisseur }}, {{ $fournisseur->code_postal_fournisseur }}</td>
                                <td>{{ $fournisseur->ville_fournisseur }}</td>
                                <td>{{ $fournisseur->pays_fournisseur ?? '—' }}</td>
                                <td class="mono">{{ $fournisseur->tel_fournisseur ?? '—' }}</td>
                                <td>{{ $fournisseur->contact_fournisseur ?? '—' }}</td>
                                <td class="cell-actions">
                                    <div class="table-actions">
                                        <a class="icon-btn"
                                            href="{{ route('fournisseurs.show', $fournisseur->code_fournisseur) }}"
                                            title="Voir la fiche"
                                            aria-label="Voir {{ $fournisseur->nom_fournisseur }}">
                                            <i class="ti ti-eye" aria-hidden="true"></i>
                                        </a>
                                        <a class="icon-btn"
                                            href="{{ route('fournisseurs.edit', $fournisseur->code_fournisseur) }}"
                                            title="Modifier" aria-label="Modifier {{ $fournisseur->nom_fournisseur }}">
                                            <i class="ti ti-edit" aria-hidden="true"></i>
                                        </a>
                                        <form class="inline-form"
                                            action="{{ route('fournisseurs.destroy', $fournisseur->code_fournisseur) }}"
                                            method="POST"
                                            data-confirm="Voulez-vous vraiment supprimer ce fournisseur ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn icon-btn--danger"
                                                title="Supprimer"
                                                aria-label="Supprimer {{ $fournisseur->nom_fournisseur }}">
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

            <x-pagination :paginator="$fournisseurs" />
        @endif
    </section>
    </section>
@endsection
