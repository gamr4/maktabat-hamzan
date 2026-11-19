<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F4F6F8" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1B1F24" media="(prefers-color-scheme: dark)">
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

    <div class="app">
        <aside class="sidebar">
            <a href="{{ route('dashboard') }}" class="brand" aria-label="Papeterie, tableau de bord">
                <span class="brand-mark" aria-hidden="true"><i class="ti ti-packages"></i></span>
                <span class="brand-text">
                    <strong>Papeterie</strong>
                    <small>Console de gestion</small>
                </span>
            </a>

            <nav class="app-nav" aria-label="Navigation principale">
                <a href="{{ route('dashboard') }}" @class(['is-active' => request()->routeIs('dashboard')]) @if (request()->routeIs('dashboard')) aria-current="page" @endif>
                    <i class="ti ti-layout-dashboard" aria-hidden="true"></i> Tableau de bord
                </a>
                <a href="{{ route('clients.index') }}" @class(['is-active' => request()->routeIs('clients.*')]) @if (request()->routeIs('clients.*')) aria-current="page" @endif>
                    <i class="ti ti-users" aria-hidden="true"></i> Clients
                </a>
                <a href="{{ route('fournisseurs.index') }}" @class(['is-active' => request()->routeIs('fournisseurs.*')]) @if (request()->routeIs('fournisseurs.*')) aria-current="page" @endif>
                    <i class="ti ti-truck-delivery" aria-hidden="true"></i> Fournisseurs
                </a>
                <a href="{{ route('produits.index') }}" @class(['is-active' => request()->routeIs('produits.*')]) @if (request()->routeIs('produits.*')) aria-current="page" @endif>
                    <i class="ti ti-box" aria-hidden="true"></i> Produits
                </a>
                <a href="{{ route('commandes.index') }}" @class(['is-active' => request()->routeIs('commandes.*')]) @if (request()->routeIs('commandes.*')) aria-current="page" @endif>
                    <i class="ti ti-shopping-cart" aria-hidden="true"></i> Commandes
                </a>
            </nav>

            <div class="sidebar-foot">
                <div class="user">
                    <i class="ti ti-user-circle" aria-hidden="true"></i>
                    <span class="user-name">{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="icon-btn" title="Se déconnecter" aria-label="Se déconnecter">
                        <i class="ti ti-logout" aria-hidden="true"></i>
                    </button>
                </form>
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

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>

</html>
