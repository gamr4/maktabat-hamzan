<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#13161a">
    <title>@yield('title', 'Tableau de bord') · Papeterie</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Roboto:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>

<body class="app-body">
    <a class="skip-link" href="#main">Aller au contenu</a>

    <div class="app" id="appRoot">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar__header">
                <a href="{{ route('dashboard') }}" class="brand" aria-label="Papeterie, tableau de bord">
                    <span class="brand-mark" aria-hidden="true"><i class="ti ti-packages"></i></span>
                    <span class="brand-text">
                        <strong>Papeterie</strong>
                        <small>Console de gestion</small>
                    </span>
                </a>

                <!-- Compact search / command palette trigger -->
                <button type="button" class="sidebar-search-btn" id="cmdPaletteTrigger" aria-label="Rechercher... (⌘K)">
                    <i class="ti ti-search" aria-hidden="true"></i>
                    <span class="sidebar-search-btn__text">Rechercher...</span>
                    <kbd class="sidebar-search-btn__kbd">⌘K</kbd>
                </button>
            </div>

            <div class="sidebar-controls">
                <button type="button" class="sidebar-toggle" id="sidebarCollapseBtn" title="Réduire la barre" aria-label="Réduire la barre latérale">
                    <span class="sidebar-toggle__label">Réduire la barre</span>
                    <i class="ti ti-layout-sidebar-left-collapse" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Grouped Navigation with micro-headers and tightened py-2 spacing -->
            <nav class="app-nav" aria-label="Navigation principale">
                <div class="nav-group">
                    <span class="nav-microhead">GÉNÉRAL</span>
                    <ul class="nav-group__list">
                        <li>
                            <a href="{{ route('dashboard') }}" @class(['nav-link', 'is-active' => request()->routeIs('dashboard')]) @if (request()->routeIs('dashboard')) aria-current="page" @endif>
                                <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                                <span class="nav-link__label">Tableau de bord</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.show') }}" @class(['nav-link', 'is-active' => request()->routeIs('profile.*')]) @if (request()->routeIs('profile.*')) aria-current="page" @endif>
                                <i class="ti ti-user-circle" aria-hidden="true"></i>
                                <span class="nav-link__label">Mon profil</span>
                            </a>
                        </li>
                    </ul>
                </div>

                @if ($role === 'client')
                <div class="nav-group">
                    <span class="nav-microhead">MON ESPACE</span>
                    <ul class="nav-group__list">
                        <li>
                            <a href="{{ route('commandes.create') }}" @class(['nav-link', 'is-active' => request()->routeIs('commandes.create')]) @if (request()->routeIs('commandes.create')) aria-current="page" @endif>
                                <i class="ti ti-plus" aria-hidden="true"></i>
                                <span class="nav-link__label">Nouvelle commande</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif

                @if ($role === 'admin' || $role === 'gestionnaire')
                <div class="nav-group">
                    <span class="nav-microhead">GESTION</span>
                    <ul class="nav-group__list">
                        <li>
                            <a href="{{ route('commandes.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('commandes.*')]) @if (request()->routeIs('commandes.*')) aria-current="page" @endif>
                                <i class="ti ti-shopping-cart" aria-hidden="true"></i>
                                <span class="nav-link__label">Commandes</span>
                                <span class="nav-link__badge">{{ $navCounts['commandes'] ?? 15 }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('clients.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('clients.*')]) @if (request()->routeIs('clients.*')) aria-current="page" @endif>
                                <i class="ti ti-users" aria-hidden="true"></i>
                                <span class="nav-link__label">Clients</span>
                                <span class="nav-link__badge">{{ $navCounts['clients'] ?? 30 }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('produits.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('produits.*')]) @if (request()->routeIs('produits.*')) aria-current="page" @endif>
                                <i class="ti ti-box" aria-hidden="true"></i>
                                <span class="nav-link__label">Produits</span>
                                <span class="nav-link__badge">{{ $navCounts['produits'] ?? 25 }}</span>
                            </a>
                        </li>
                        @if ($role === 'admin')
                        <li>
                            <a href="{{ route('fournisseurs.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('fournisseurs.*')]) @if (request()->routeIs('fournisseurs.*')) aria-current="page" @endif>
                                <i class="ti ti-truck-delivery" aria-hidden="true"></i>
                                <span class="nav-link__label">Fournisseurs</span>
                                <span class="nav-link__badge">{{ $navCounts['fournisseurs'] ?? 25 }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                @endif
            </nav>

            <!-- Upgraded bottom profile card -->
            <div class="sidebar-foot">
                <div class="profile-card">
                    <a href="{{ route('profile.show') }}" class="profile-card__identity" title="Voir mon profil" aria-label="Voir mon profil">
                        <div class="profile-card__avatar" aria-hidden="true">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="profile-card__info">
                            <span class="profile-card__name" title="{{ Auth::user()->name ?? 'Utilisateur' }}">{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                            <span class="profile-card__role">{{ Auth::user()->role_label ?? 'Administrateur' }}</span>
                        </div>
                    </a>
                    <div class="profile-card__actions">
                        <form action="{{ route('logout') }}" method="POST" class="profile-card__logout-form">
                            @csrf
                            <button type="submit" class="profile-card__btn profile-card__btn--logout" title="Se déconnecter" aria-label="Se déconnecter">
                                <i class="ti ti-logout" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <main class="main" id="main">
            <header class="page-head">
                <div>
                    <p class="eyebrow">@yield('eyebrow', 'Console')</p>
                    <h1 class="page-title">@yield('title', 'Tableau de bord')</h1>
                </div>
                @hasSection('actions')
                    <div class="page-head__actions">
                        @yield('actions')
                    </div>
                @endif
            </header>

            @if (session('success'))
                <div class="alert alert-success" role="status">
                    <i class="ti ti-circle-check" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="ti ti-alert-circle" aria-hidden="true"></i>
                    <div>
                        <strong>Corrigez les points suivants</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Command Palette Modal -->
    <div class="cmd-palette" id="cmdPalette" hidden aria-modal="true" role="dialog" aria-label="Palette de commandes">
        <div class="cmd-palette__backdrop" data-close-cmd></div>
        <div class="cmd-palette__container">
            <div class="cmd-palette__search">
                <i class="ti ti-search" aria-hidden="true"></i>
                <input type="text" id="cmdPaletteInput" class="cmd-palette__input" placeholder="Rechercher une section, commande, client..." autocomplete="off">
                <kbd class="cmd-palette__kbd">ESC</kbd>
            </div>
            <div class="cmd-palette__results" id="cmdPaletteResults">
                <div class="cmd-palette__group">
                    <span class="cmd-palette__group-title">Navigation</span>
                    <a href="{{ route('dashboard') }}" class="cmd-palette__item">
                        <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                        <span>Tableau de bord</span>
                        <kbd>↵</kbd>
                    </a>
                    <a href="{{ route('profile.show') }}" class="cmd-palette__item">
                        <i class="ti ti-user-circle" aria-hidden="true"></i>
                        <span>Mon profil</span>
                        <kbd>↵</kbd>
                    </a>
                    @if ($role === 'admin' || $role === 'gestionnaire')
                    <a href="{{ route('commandes.index') }}" class="cmd-palette__item">
                        <i class="ti ti-shopping-cart" aria-hidden="true"></i>
                        <span>Commandes</span>
                        <span class="cmd-palette__badge">{{ $navCounts['commandes'] ?? 15 }}</span>
                    </a>
                    <a href="{{ route('clients.index') }}" class="cmd-palette__item">
                        <i class="ti ti-users" aria-hidden="true"></i>
                        <span>Clients</span>
                        <span class="cmd-palette__badge">{{ $navCounts['clients'] ?? 30 }}</span>
                    </a>
                    <a href="{{ route('produits.index') }}" class="cmd-palette__item">
                        <i class="ti ti-box" aria-hidden="true"></i>
                        <span>Produits</span>
                        <span class="cmd-palette__badge">{{ $navCounts['produits'] ?? 25 }}</span>
                    </a>
                    @endif
                    @if ($role === 'admin')
                    <a href="{{ route('fournisseurs.index') }}" class="cmd-palette__item">
                        <i class="ti ti-truck-delivery" aria-hidden="true"></i>
                        <span>Fournisseurs</span>
                        <span class="cmd-palette__badge">{{ $navCounts['fournisseurs'] ?? 25 }}</span>
                    </a>
                    @endif
                    @if ($role === 'client')
                    <a href="{{ route('dashboard') }}" class="cmd-palette__item">
                        <i class="ti ti-receipt" aria-hidden="true"></i>
                        <span>Mes commandes</span>
                    </a>
                    <a href="{{ route('commandes.create') }}" class="cmd-palette__item">
                        <i class="ti ti-plus" aria-hidden="true"></i>
                        <span>Nouvelle commande</span>
                    </a>
                    @endif
                </div>
                @if ($role === 'admin' || $role === 'gestionnaire')
                <div class="cmd-palette__group">
                    <span class="cmd-palette__group-title">Actions rapides</span>
                    <a href="{{ route('commandes.create') }}" class="cmd-palette__item">
                        <i class="ti ti-plus" aria-hidden="true"></i>
                        <span>Nouvelle commande</span>
                    </a>
                    <a href="{{ route('clients.create') }}" class="cmd-palette__item">
                        <i class="ti ti-user-plus" aria-hidden="true"></i>
                        <span>Nouveau client</span>
                    </a>
                    <a href="{{ route('produits.create') }}" class="cmd-palette__item">
                        <i class="ti ti-box-plus" aria-hidden="true"></i>
                        <span>Nouveau produit</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>

</html>
