@extends('layouts.app')

@section('title', 'Mon profil')
@section('eyebrow', 'Paramètres de compte')

@section('actions')
    <a class="btn btn-ghost" href="{{ route('dashboard') }}">
        <i class="ti ti-arrow-left" aria-hidden="true"></i> Tableau de bord
    </a>
    <form action="{{ route('logout') }}" method="POST" class="profile-logout-form" data-confirm="Confirmer la déconnexion ?">
        @csrf
        <button type="submit" class="btn btn-ghost">
            <i class="ti ti-logout" aria-hidden="true"></i> Se déconnecter
        </button>
    </form>
@endsection

@section('content')
    <div class="profile-page stack">
        <section class="card profile-hero" aria-labelledby="profile-hero-title">
            <div class="profile-hero__glow" aria-hidden="true"></div>
            <div class="profile-hero__identity">
                <div class="profile-hero__avatar" aria-hidden="true">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="profile-hero__body">
                    <p class="eyebrow">Espace personnel</p>
                    <h2 class="profile-hero__name" id="profile-hero-title">{{ $user->name }}</h2>
                    <p class="profile-hero__email"><i class="ti ti-mail" aria-hidden="true"></i>{{ $user->email }}</p>
                </div>
            </div>
            <div class="profile-hero__details">
                <span class="badge profile-hero__role">{{ $user->role_label }}</span>
                <div class="profile-hero__meta">
                    <span class="profile-hero__meta-item">
                        <i class="ti ti-calendar" aria-hidden="true"></i>
                        Membre depuis <x-date :value="$user->created_at" />
                    </span>
                    <span class="profile-hero__meta-item profile-hero__meta-item--active">
                        <span class="status-dot" aria-hidden="true"></span>
                        Compte actif
                    </span>
                </div>
            </div>
        </section>

        <div class="profile-grid">
            <section class="card profile-panel" aria-labelledby="profile-info-title">
                <div class="profile-section-head">
                    <div class="profile-section-head__copy">
                        <span class="profile-section-head__icon" aria-hidden="true"><i class="ti ti-user-edit"></i></span>
                        <div>
                        <h2 class="card__title" id="profile-info-title">
                            <i class="ti ti-user-edit" aria-hidden="true"></i>
                            Informations personnelles
                        </h2>
                        <p class="section-note">Ces informations servent à vous identifier dans la console.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                    @csrf
                    @method('PUT')

                    <x-input name="name" label="Nom complet" :value="$user->name" required autocomplete="name" />
                    <x-input name="email" label="Adresse e-mail" type="email" :value="$user->email" required
                        autocomplete="email" hint="Cette adresse est utilisée pour vous connecter." />

                    <div class="profile-form__actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy" aria-hidden="true"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </section>

            <section class="card profile-panel" aria-labelledby="profile-password-title">
                <div class="profile-section-head">
                    <div class="profile-section-head__copy">
                        <span class="profile-section-head__icon profile-section-head__icon--secure" aria-hidden="true"><i class="ti ti-shield-lock"></i></span>
                        <div>
                        <h2 class="card__title" id="profile-password-title">
                            <i class="ti ti-shield-lock" aria-hidden="true"></i>
                            Modifier mon mot de passe
                        </h2>
                        <p class="section-note">Choisissez un mot de passe de 8 caractères minimum.</p>
                        </div>
                    </div>
                    <span class="profile-security-label"><i class="ti ti-lock" aria-hidden="true"></i> Sécurisé</span>
                </div>

                <form method="POST" action="{{ route('profile.password') }}" class="profile-form">
                    @csrf
                    @method('PUT')

                    <x-input name="current_password" label="Mot de passe actuel" type="password" required
                        autocomplete="current-password" />
                    <x-input name="password" label="Nouveau mot de passe" type="password" required
                        autocomplete="new-password" hint="8 caractères minimum." />
                    <x-input name="password_confirmation" label="Confirmation du nouveau mot de passe" type="password"
                        required autocomplete="new-password" />

                    <div class="profile-form__actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-key" aria-hidden="true"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </section>
        </div>

        @if ($user->isAdmin() || $user->isGestionnaire())
            <section class="card" aria-labelledby="profile-scope-title">
                <div class="profile-section-head">
                    <div>
                        <h2 class="card__title" id="profile-scope-title">
                            <i class="ti ti-shield-check" aria-hidden="true"></i>
                            Votre périmètre
                        </h2>
                        <p class="section-note">Accès accordés à votre rôle dans la console de gestion.</p>
                    </div>
                    <span class="badge profile-hero__role">{{ $user->role_label }}</span>
                </div>

                <div class="profile-stat-grid">
                    <a class="profile-stat" href="{{ route('clients.index') }}">
                        <span class="profile-stat__icon" aria-hidden="true"><i class="ti ti-users"></i></span>
                        <span class="profile-stat__body">
                            <span class="profile-stat__value">{{ number_format($platformStats['clients'], 0, ',', ' ') }}</span>
                            <span class="profile-stat__label">Clients</span>
                        </span>
                        <i class="ti ti-chevron-right profile-stat__chevron" aria-hidden="true"></i>
                    </a>

                    <a class="profile-stat" href="{{ route('produits.index') }}">
                        <span class="profile-stat__icon" aria-hidden="true"><i class="ti ti-box"></i></span>
                        <span class="profile-stat__body">
                            <span class="profile-stat__value">{{ number_format($platformStats['produits'], 0, ',', ' ') }}</span>
                            <span class="profile-stat__label">Produits</span>
                        </span>
                        <i class="ti ti-chevron-right profile-stat__chevron" aria-hidden="true"></i>
                    </a>

                    <a class="profile-stat" href="{{ route('commandes.index') }}">
                        <span class="profile-stat__icon" aria-hidden="true"><i class="ti ti-shopping-cart"></i></span>
                        <span class="profile-stat__body">
                            <span class="profile-stat__value">{{ number_format($platformStats['commandes'], 0, ',', ' ') }}</span>
                            <span class="profile-stat__label">Commandes</span>
                        </span>
                        <i class="ti ti-chevron-right profile-stat__chevron" aria-hidden="true"></i>
                    </a>

                    @if ($user->isAdmin())
                        <a class="profile-stat" href="{{ route('fournisseurs.index') }}">
                            <span class="profile-stat__icon" aria-hidden="true"><i class="ti ti-truck-delivery"></i></span>
                            <span class="profile-stat__body">
                                <span class="profile-stat__value">{{ number_format($platformStats['fournisseurs'], 0, ',', ' ') }}</span>
                                <span class="profile-stat__label">Fournisseurs</span>
                            </span>
                            <i class="ti ti-chevron-right profile-stat__chevron" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>

                <div class="profile-permissions">
                    <h3 class="profile-permissions__title">Privilèges</h3>
                    <ul class="profile-permissions__list">
                        <li>
                            <i class="ti ti-check" aria-hidden="true"></i>
                            Gestion complète des clients, produits et commandes.
                        </li>
                        @if ($user->isAdmin())
                            <li>
                                <i class="ti ti-check" aria-hidden="true"></i>
                                Accès au répertoire des fournisseurs.
                            </li>
                            <li>
                                <i class="ti ti-check" aria-hidden="true"></i>
                                Autorisation de supprimer les enregistrements.
                            </li>
                        @else
                            <li>
                                <i class="ti ti-check" aria-hidden="true"></i>
                                Accès opérationnel sans gestion des fournisseurs.
                            </li>
                            <li>
                                <i class="ti ti-check" aria-hidden="true"></i>
                                Les suppressions sont réservées à l’administrateur.
                            </li>
                        @endif
                    </ul>
                </div>
            </section>
        @else
            <section class="card" aria-labelledby="profile-client-title">
                <div class="profile-section-head">
                    <div>
                        <h2 class="card__title" id="profile-client-title">
                            <i class="ti ti-user-circle" aria-hidden="true"></i>
                            Mon espace client
                        </h2>
                        <p class="section-note">Un résumé de votre activité et des comptes qui vous sont rattachés.</p>
                    </div>
                    <span class="badge profile-hero__role">Client</span>
                </div>

                <div class="profile-stat-grid profile-stat-grid--three">
                    <a class="profile-stat" href="{{ route('dashboard') }}">
                        <span class="profile-stat__icon" aria-hidden="true"><i class="ti ti-shopping-cart"></i></span>
                        <span class="profile-stat__body">
                            <span class="profile-stat__value">{{ number_format($clientSummary['commandes'], 0, ',', ' ') }}</span>
                            <span class="profile-stat__label">Commandes</span>
                        </span>
                        <i class="ti ti-chevron-right profile-stat__chevron" aria-hidden="true"></i>
                    </a>

                    <div class="profile-stat">
                        <span class="profile-stat__icon" aria-hidden="true"><i class="ti ti-wallet"></i></span>
                        <span class="profile-stat__body">
                            <span class="profile-stat__value"><x-money :value="$clientSummary['total_depense']" /></span>
                            <span class="profile-stat__label">Total des achats</span>
                        </span>
                    </div>

                    <div class="profile-stat">
                        <span class="profile-stat__icon" aria-hidden="true"><i class="ti ti-percentage"></i></span>
                        <span class="profile-stat__body">
                            <span class="profile-stat__value">{{ $clientSummary['remise'] }} %</span>
                            <span class="profile-stat__label">Remise maximale</span>
                        </span>
                    </div>
                </div>

                <div class="profile-permissions">
                    <h3 class="profile-permissions__title">Privilèges</h3>
                    <ul class="profile-permissions__list">
                        <li>
                            <i class="ti ti-check" aria-hidden="true"></i>
                            Consultation de votre tableau de bord et de vos commandes.
                        </li>
                        <li>
                            <i class="ti ti-check" aria-hidden="true"></i>
                            Consultation des fiches clients qui vous sont rattachées.
                        </li>
                        <li>
                            <i class="ti ti-check" aria-hidden="true"></i>
                            Modification de vos informations de connexion et de votre mot de passe.
                        </li>
                    </ul>
                </div>
            </section>

            <section class="card card--flush" aria-labelledby="profile-clients-title">
                <div class="card__head">
                    <div>
                        <h2 class="card__title" id="profile-clients-title">Fiches clients associées</h2>
                        <p class="section-note">Vos entités commerciales et adresses de facturation enregistrées.</p>
                    </div>
                    <span class="section-note">{{ $clients->count() }} compte(s) lié(s)</span>
                </div>

                @if ($clients->isEmpty())
                    <x-empty-state icon="building" title="Aucune entité rattachée"
                        text="Aucune fiche client n'est encore liée à cet utilisateur." />
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
                                        <td><span class="cell-code">{{ $client->code_client }}</span></td>
                                        <td><strong>{{ $client->nom_client }}</strong></td>
                                        <td>{{ $client->rue_client }}</td>
                                        <td>{{ $client->code_postal_client }} {{ $client->ville_client }}</td>
                                        <td>
                                            <span class="badge badge--neutral">
                                                {{ config('papeterie.client_types.' . $client->type_client) ?? $client->type_client }}
                                            </span>
                                        </td>
                                        <td class="num"><strong>{{ $client->remise_speciale }}%</strong></td>
                                        <td class="num"><strong>{{ $client->commandes_count ?? 0 }}</strong></td>
                                        <td class="text-right">
                                            <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm"
                                                title="Voir mes commandes">
                                                Commandes <i class="ti ti-chevron-right" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="profile-readonly-note">
                        <i class="ti ti-info-circle" aria-hidden="true"></i>
                        Les fiches clients sont gérées par l’équipe. Contactez-nous pour toute modification.
                    </div>
                @endif
            </section>
        @endif
    </div>
@endsection
