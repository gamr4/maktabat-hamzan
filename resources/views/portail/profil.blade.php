@extends('layouts.portail')

@section('title', 'Mon Profil & Comptes')
@section('eyebrow', 'Paramètres de compte')
@section('content-width', '1080px')

@section('breadcrumbs')
    <a href="{{ route('portail.dashboard') }}">Portail</a>
    <span class="sep">/</span>
    <span>Profil & Comptes</span>
@endsection

@section('actions')
    <a href="{{ route('portail.dashboard') }}" class="btn btn-ghost">
        <i class="ti ti-arrow-left" aria-hidden="true"></i> Retour au tableau de bord
    </a>
@endsection

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- User Information Card -->
        <div class="card">
            <h2 class="card__title" style="margin-bottom: 1.25rem;"><i class="ti ti-user-check"></i> Informations Utilisateur</h2>
            <dl class="def-list" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <dt style="color: #8b949e; margin: 0;">Nom complet</dt>
                    <dd style="margin: 0;"><strong>{{ $user->name }}</strong></dd>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <dt style="color: #8b949e; margin: 0;">Adresse email</dt>
                    <dd style="margin: 0;"><strong>{{ $user->email }}</strong></dd>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <dt style="color: #8b949e; margin: 0;">Rôle système</dt>
                    <dd style="margin: 0;"><span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;">{{ $user->role_label }}</span></dd>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0;">
                    <dt style="color: #8b949e; margin: 0;">Membre depuis</dt>
                    <dd style="margin: 0;"><x-date :value="$user->created_at" /></dd>
                </div>
            </dl>
        </div>

        <!-- Security & Password Change Card -->
        <div class="card">
            <h2 class="card__title" style="margin-bottom: 1.25rem;"><i class="ti ti-shield-lock"></i> Modifier mon mot de passe</h2>
            <form method="POST" action="{{ route('portail.profil.password') }}" style="display: flex; flex-direction: column; gap: 0.85rem;">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" style="display: block; font-size: 0.78rem; font-weight: 500; color: #8b949e; margin-bottom: 0.35rem;">Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" class="input" required autocomplete="current-password">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div>
                        <label for="password" style="display: block; font-size: 0.78rem; font-weight: 500; color: #8b949e; margin-bottom: 0.35rem;">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" class="input" required autocomplete="new-password">
                    </div>
                    <div>
                        <label for="password_confirmation" style="display: block; font-size: 0.78rem; font-weight: 500; color: #8b949e; margin-bottom: 0.35rem;">Confirmation</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input" required autocomplete="new-password">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 0.5rem;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ti ti-key" aria-hidden="true"></i> Mettre à jour le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Linked Client Entities Section -->
    <section class="card card--flush" aria-labelledby="linked-clients-title">
        <div class="card__head">
            <div>
                <h2 class="card__title" id="linked-clients-title">Fiches clients associées</h2>
                <p class="section-note">Vos entités commerciales et adresses de facturation enregistrées.</p>
            </div>
            <div style="font-size: 0.825rem; color: #8b949e;">
                {{ $clients->count() }} compte(s) lié(s)
            </div>
        </div>

        @if ($clients->isEmpty())
            <x-empty-state icon="building" title="Aucune entité rattachée"
                text="Aucune fiche client n'est encore liée à cet utilisateur.">
            </x-empty-state>
        @else
            <div class="table-wrap">
                <table class="table">
                    <caption class="sr-only">Comptes clients liés</caption>
                    <thead>
                        <tr>
                            <th scope="col">Code Client</th>
                            <th scope="col">Raison Sociale</th>
                            <th scope="col">Adresse</th>
                            <th scope="col">Code Postal & Ville</th>
                            <th scope="col">Type</th>
                            <th scope="col" class="num">Remise</th>
                            <th scope="col" class="num">Commandes</th>
                            <th scope="col" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td>
                                    <div style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                        <span class="cell-code">{{ $client->code_client }}</span>
                                        <button type="button" class="btn-copy" data-copy="{{ $client->code_client }}" title="Copier le code" aria-label="Copier le code client">
                                            <i class="ti ti-copy" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                                <td><strong>{{ $client->nom_client }}</strong></td>
                                <td>{{ $client->rue_client }}</td>
                                <td>{{ $client->code_postal_client }} {{ $client->ville_client }}</td>
                                <td><span class="badge">{{ $client->type_client }}</span></td>
                                <td class="num"><strong>{{ $client->remise_speciale }}%</strong></td>
                                <td class="num"><strong>{{ $client->commandes_count ?? 0 }}</strong></td>
                                <td class="text-right">
                                    <a href="{{ route('portail.commandes', ['client_code' => $client->code_client]) }}" class="btn btn-ghost btn-sm" title="Voir les commandes de ce compte">
                                        Commandes <i class="ti ti-chevron-right" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
