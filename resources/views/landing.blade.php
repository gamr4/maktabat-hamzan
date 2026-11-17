@extends('layouts.public')

@section('title', 'Papeterie — Gestion des commandes')
@section('meta_description', 'Clients, fournisseurs, produits et commandes réunis dans une seule console. Codes générés automatiquement, lignes de commande et conditions LIV / T30.')

@section('content')
    {{-- Hero --}}
    <section class="hero">
        <div class="shell hero-grid">
            <div class="hero-copy">
                <p class="eyebrow"><span class="status-dot" aria-hidden="true"></span> Console de gestion · v1.0</p>

                <h1>Toute la papeterie tient dans <span class="accent-text">un seul tableau</span> de commande.</h1>

                <p class="hero-lede">
                    Clients, fournisseurs, produits et commandes partagent le même référentiel. Les codes sont
                    générés à l'enregistrement, les relations restent intactes et chaque commande garde sa trace.
                </p>

                <div class="hero-cta">
                    <a class="btn btn-primary" href="{{ route('login') }}">
                        Accéder à la console <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-ghost" href="#modules">Voir les modules</a>
                </div>

                <dl class="hero-meta" aria-label="Résumé du système">
                    <div>
                        <dt>Modules</dt>
                        <dd>04</dd>
                    </div>
                    <div>
                        <dt>Codes</dt>
                        <dd>Auto</dd>
                    </div>
                    <div>
                        <dt>Conditions</dt>
                        <dd>LIV / T30</dd>
                    </div>
                </dl>
            </div>

            <div class="hero-visual">
                <div class="hub-board"
                    role="img"
                    aria-label="Schéma du système : un noyau Papeterie relié aux modules Clients, Fournisseurs, Produits et Commandes.">
                    <div class="hub-grid" aria-hidden="true"></div>
                    <div class="hub-orbit hub-orbit--outer" aria-hidden="true"></div>
                    <div class="hub-orbit hub-orbit--inner" aria-hidden="true"></div>

                    <svg class="hub-lines" viewBox="0 0 540 540" preserveAspectRatio="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="flow-line" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0" stop-color="#EB5757" stop-opacity=".08" />
                                <stop offset=".5" stop-color="#EB5757" stop-opacity=".55" />
                                <stop offset="1" stop-color="#EB5757" stop-opacity=".08" />
                            </linearGradient>
                        </defs>
                        <path d="M270 270 L120 142" />
                        <path d="M270 270 L420 142" />
                        <path d="M270 270 L120 398" />
                        <path d="M270 270 L420 398" />
                    </svg>

                    <span class="board-caption board-caption--tl" aria-hidden="true">REF / 04</span>
                    <span class="board-caption board-caption--br" aria-hidden="true">SYNC / OK</span>
                    <span class="board-tick board-tick--tl" aria-hidden="true"></span>
                    <span class="board-tick board-tick--tr" aria-hidden="true"></span>
                    <span class="board-tick board-tick--bl" aria-hidden="true"></span>
                    <span class="board-tick board-tick--br" aria-hidden="true"></span>

                    <div class="hub-core">
                        <span class="hub-core-kicker">SYS / CORE</span>
                        <i class="ti ti-packages" aria-hidden="true"></i>
                        <strong>Papeterie</strong>
                        <span class="hub-core-code">ONLINE</span>
                    </div>

                    <div class="hub-node hub-node--clients">
                        <span class="node-icon"><i class="ti ti-users" aria-hidden="true"></i></span>
                        <span class="node-text">
                            <strong>Clients</strong>
                            <code>Cli-000001</code>
                        </span>
                    </div>

                    <div class="hub-node hub-node--fournisseurs">
                        <span class="node-icon"><i class="ti ti-truck-delivery" aria-hidden="true"></i></span>
                        <span class="node-text">
                            <strong>Fournisseurs</strong>
                            <code>Fr-000001</code>
                        </span>
                    </div>

                    <div class="hub-node hub-node--produits">
                        <span class="node-icon"><i class="ti ti-box" aria-hidden="true"></i></span>
                        <span class="node-text">
                            <strong>Produits</strong>
                            <code>Pr-000001</code>
                        </span>
                    </div>

                    <div class="hub-node hub-node--commandes">
                        <span class="node-icon"><i class="ti ti-shopping-cart" aria-hidden="true"></i></span>
                        <span class="node-text">
                            <strong>Commandes</strong>
                            <code>Com-000014</code>
                        </span>
                    </div>
                </div>

                <div class="visual-caption">
                    <span class="caption-index">SCHÉMA 01</span>
                    <span>Référentiel partagé</span>
                    <span class="caption-status"><span class="status-dot" aria-hidden="true"></span> Synchronisé</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Modules --}}
    <section class="section modules-section" id="modules">
        <div class="shell">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Modules</p>
                <h2>Quatre modules, un seul référentiel.</h2>
                <p>
                    Chaque fiche alimente les suivantes. Un client existe avant la commande, un produit garde son
                    fournisseur, et une commande relie les deux sans recopier de données.
                </p>
            </div>

            <article class="module-row" data-reveal>
                <div class="module-copy">
                    <p class="module-index"><span>01</span> Clients</p>
                    <h3>Un carnet client qui parle aux commandes.</h3>
                    <p>
                        Le code client est attribué à l'enregistrement. Les coordonnées, le type de compte et la
                        remise spéciale restent disponibles quand vient le moment de facturer.
                    </p>
                    <ul class="module-list">
                        <li><i class="ti ti-check" aria-hidden="true"></i> Codes <code>Cli-000001</code> générés
                            automatiquement</li>
                        <li><i class="ti ti-check" aria-hidden="true"></i> Historique consultable depuis chaque fiche
                        </li>
                        <li><i class="ti ti-check" aria-hidden="true"></i> Remise spéciale conservée avec le compte</li>
                    </ul>
                </div>

                <div class="module-visual">
                    <div class="spec-card">
                        <div class="spec-card-head">
                            <span class="spec-label">Fiche client</span>
                            <span class="code-chip">Cli-000001</span>
                        </div>

                        <div class="profile-row">
                            <span class="avatar"><i class="ti ti-user" aria-hidden="true"></i></span>
                            <div>
                                <strong>Atelier Rive Gauche</strong>
                                <span>12 rue des Écoles · Casablanca</span>
                            </div>
                        </div>

                        <dl class="spec-rows">
                            <div>
                                <dt>Type</dt>
                                <dd>Professionnel</dd>
                            </div>
                            <div>
                                <dt>Remise</dt>
                                <dd>5 %</dd>
                            </div>
                            <div>
                                <dt>Commandes</dt>
                                <dd>06</dd>
                            </div>
                            <div>
                                <dt>Dernière</dt>
                                <dd>Com-000014</dd>
                            </div>
                        </dl>

                        <div class="spec-footer">
                            <span class="status-pill"><span class="status-dot" aria-hidden="true"></span> Actif</span>
                            <span class="mono">MAJ 09:41</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="module-row" data-reveal>
                <div class="module-copy">
                    <p class="module-index"><span>02</span> Fournisseurs &amp; produits</p>
                    <h3>Le catalogue reste relié à sa source.</h3>
                    <p>
                        Un fournisseur porte plusieurs produits. La désignation, le prix unitaire et la famille sont
                        saisis une fois, puis réutilisés dans chaque ligne de commande.
                    </p>
                    <ul class="module-list">
                        <li><i class="ti ti-check" aria-hidden="true"></i> Relation fournisseur → produit explicite</li>
                        <li><i class="ti ti-check" aria-hidden="true"></i> Prix unitaire prêt pour le calcul des
                            sous-totaux</li>
                        <li><i class="ti ti-check" aria-hidden="true"></i> Famille de produit pour retrouver le
                            catalogue</li>
                    </ul>
                </div>

                <div class="module-visual">
                    <div class="spec-card">
                        <div class="spec-card-head">
                            <span class="spec-label">Catalogue</span>
                            <span class="code-chip">Fr-000001</span>
                        </div>

                        <div class="profile-row">
                            <span class="avatar"><i class="ti ti-truck-delivery" aria-hidden="true"></i></span>
                            <div>
                                <strong>Papeterie Atlas</strong>
                                <span>Grossiste · Casablanca</span>
                            </div>
                        </div>

                        <div class="relation" aria-hidden="true">
                            <span class="relation-line"></span>
                            <span class="relation-label">fournit</span>
                        </div>

                        <div class="linked-card">
                            <span class="linked-icon"><i class="ti ti-box" aria-hidden="true"></i></span>
                            <div class="linked-copy">
                                <strong>Ramette A4 80 g</strong>
                                <span>Papier · 500 feuilles</span>
                            </div>
                            <div class="linked-price">
                                <strong>45,00 DH</strong>
                                <code>Pr-000001</code>
                            </div>
                        </div>

                        <div class="spec-footer">
                            <span class="status-pill"><span class="status-dot" aria-hidden="true"></span> Lien
                                actif</span>
                            <span class="mono">PRIX / UNITAIRE</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="module-row" data-reveal>
                <div class="module-copy">
                    <p class="module-index"><span>03</span> Commandes</p>
                    <h3>La commande comme point de contrôle.</h3>
                    <p>
                        Un client, une date, une condition et des lignes de produits. Les sous-totaux se lisent
                        ligne par ligne, puis le total se rassemble en bas du document.
                    </p>
                    <ul class="module-list">
                        <li><i class="ti ti-check" aria-hidden="true"></i> Quantités et prix verrouillés par ligne</li>
                        <li><i class="ti ti-check" aria-hidden="true"></i> Condition <code>LIV</code> ou <code>T30</code>
                            visible d'un coup d'œil</li>
                        <li><i class="ti ti-check" aria-hidden="true"></i> Modification et suppression dans une
                            transaction</li>
                    </ul>
                </div>

                <div class="module-visual">
                    <div class="spec-card spec-card--order">
                        <div class="spec-card-head">
                            <span class="spec-label">Bon de commande</span>
                            <span class="code-chip">Com-000014</span>
                        </div>

                        <div class="profile-row">
                            <span class="avatar"><i class="ti ti-user" aria-hidden="true"></i></span>
                            <div>
                                <strong>Atelier Rive Gauche</strong>
                                <span>Cli-000001 · 12/11/2026</span>
                            </div>
                        </div>

                        <div class="order-lines">
                            <div class="order-line">
                                <span class="qty">10</span>
                                <span class="order-name">Ramette A4 80 g</span>
                                <span class="order-amount">450,00</span>
                            </div>
                            <div class="order-line">
                                <span class="qty">04</span>
                                <span class="order-name">Stylo bille bleu</span>
                                <span class="order-amount">50,00</span>
                            </div>
                            <div class="order-line">
                                <span class="qty">02</span>
                                <span class="order-name">Classeur à levier</span>
                                <span class="order-amount">160,00</span>
                            </div>
                        </div>

                        <div class="order-total">
                            <span>Total</span>
                            <strong>660,00 DH</strong>
                        </div>

                        <div class="spec-footer">
                            <span class="badge badge--liv">LIV</span>
                            <span class="mono">3 LIGNES / 660,00 DH</span>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>

    {{-- Flux --}}
    <section class="section flux-section" id="flux">
        <div class="shell flux-grid">
            <div class="flux-copy" data-reveal>
                <p class="eyebrow">Flux opérationnel</p>
                <h2>Une commande, quatre points de contrôle.</h2>
                <p>
                    Le parcours suit toujours le même sens : le client commande, le fournisseur alimente le
                    catalogue, le produit entre dans la ligne, et la commande rassemble le tout.
                </p>
                <a class="text-link" href="{{ route('login') }}">
                    Ouvrir la console <i class="ti ti-arrow-up-right" aria-hidden="true"></i>
                </a>
            </div>

            <ol class="flow-list">
                <li class="flow-step" data-reveal>
                    <span class="flow-num">01</span>
                    <div>
                        <h3>Le client entre dans le système</h3>
                        <p>Le compte est créé avec ses coordonnées, son type et sa remise. Son code suit la fiche
                            jusqu'à la commande.</p>
                        <code>Cli-000001</code>
                    </div>
                </li>
                <li class="flow-step" data-reveal style="--reveal-delay: 80ms;">
                    <span class="flow-num">02</span>
                    <div>
                        <h3>Le fournisseur alimente le catalogue</h3>
                        <p>Chaque produit garde le lien vers son fournisseur. Une source, une fiche, pas de double
                            saisie.</p>
                        <code>Fr-000001 → Pr-000001</code>
                    </div>
                </li>
                <li class="flow-step" data-reveal style="--reveal-delay: 160ms;">
                    <span class="flow-num">03</span>
                    <div>
                        <h3>Le produit rejoint la ligne</h3>
                        <p>Désignation, prix unitaire et quantité sont figés dans la ligne de commande au moment de
                            la saisie.</p>
                        <code>Pr-000001 × 10</code>
                    </div>
                </li>
                <li class="flow-step" data-reveal style="--reveal-delay: 240ms;">
                    <span class="flow-num">04</span>
                    <div>
                        <h3>La commande rassemble le tout</h3>
                        <p>Le document final réunit le client, les lignes, la condition et le total. Il reste
                            consultable et modifiable.</p>
                        <code>Com-000014 · LIV</code>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    {{-- Architecture --}}
    <section class="section arch-section" id="architecture">
        <div class="shell arch-grid">
            <div class="arch-copy" data-reveal>
                <p class="eyebrow">Sous le capot</p>
                <h2>Un schéma lisible, une base solide.</h2>
                <p>
                    Pas de couche décorative qui cache la logique. Les modèles portent les codes métier, les
                    contrôleurs valident les relations, et les vues Blade restent simples à suivre.
                </p>

                <ul class="arch-list">
                    <li>
                        <i class="ti ti-binary-tree" aria-hidden="true"></i>
                        <span><strong>Codes métier</strong> générés par le trait <code>HasCode</code></span>
                    </li>
                    <li>
                        <i class="ti ti-link" aria-hidden="true"></i>
                        <span><strong>Relations</strong> clients → commandes et fournisseurs → produits</span>
                    </li>
                    <li>
                        <i class="ti ti-lock" aria-hidden="true"></i>
                        <span><strong>Accès</strong> protégé par l'authentification Laravel</span>
                    </li>
                </ul>
            </div>

            <div class="spec-sheet" data-reveal>
                <div class="spec-sheet-head">
                    <span>Fiche technique</span>
                    <span class="mono">SYS / 01</span>
                </div>

                <dl class="spec-table">
                    <div>
                        <dt>Runtime</dt>
                        <dd>PHP 8.2+</dd>
                    </div>
                    <div>
                        <dt>Framework</dt>
                        <dd>Laravel 12</dd>
                    </div>
                    <div>
                        <dt>Vues</dt>
                        <dd>Blade</dd>
                    </div>
                    <div>
                        <dt>Base</dt>
                        <dd>MySQL</dd>
                    </div>
                    <div>
                        <dt>Auth</dt>
                        <dd>Session</dd>
                    </div>
                    <div>
                        <dt>Codes</dt>
                        <dd>Cli / Fr / Pr / Com</dd>
                    </div>
                    <div>
                        <dt>Icônes</dt>
                        <dd>Tabler</dd>
                    </div>
                </dl>

                <div class="spec-sheet-foot">
                    <span class="status-pill"><span class="status-dot" aria-hidden="true"></span> Opérationnel</span>
                    <span class="mono">4 MODULES</span>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="section cta-section">
        <div class="shell cta-panel" data-reveal>
            <div class="cta-copy">
                <p class="eyebrow">Accès sécurisé</p>
                <h2>La prochaine commande se prépare ici.</h2>
                <p>
                    Connectez-vous pour ouvrir le tableau de bord, saisir une commande ou mettre à jour le
                    catalogue.
                </p>
            </div>

            <div class="cta-actions">
                @auth
                    <a class="btn btn-primary" href="{{ route('dashboard') }}">
                        Tableau de bord <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    </a>
                @else
                    <a class="btn btn-primary" href="{{ route('login') }}">
                        Se connecter <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    </a>
                @endauth
                <a class="btn btn-ghost" href="#modules">Revoir les modules</a>
            </div>
        </div>
    </section>
@endsection
