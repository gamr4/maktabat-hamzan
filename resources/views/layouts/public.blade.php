<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F4F6F8">
    <meta name="description" content="@yield('meta_description', 'Papeterie réunit clients, fournisseurs, produits et commandes dans une seule console de gestion.')">
    <title>@yield('title', 'Papeterie — Gestion des commandes')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Roboto:wght@400;500;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body class="landing">
    <a class="skip-link" href="#main">Aller au contenu</a>
    <div class="schematic-grid" aria-hidden="true"></div>

    <header class="site-header" data-header>
        <div class="shell header-inner">
            <a href="{{ route('home') }}" class="brand" aria-label="Papeterie, retour à l'accueil">
                <span class="brand-mark" aria-hidden="true"><i class="ti ti-packages"></i></span>
                <span class="brand-text">
                    <strong>Papeterie</strong>
                    <small>Gestion des commandes</small>
                </span>
            </a>

            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false"
                aria-controls="site-nav" aria-label="Ouvrir le menu">
                <i class="ti ti-menu-2" aria-hidden="true"></i>
            </button>

            <nav class="site-nav" id="site-nav" data-nav aria-label="Navigation principale">
                <a href="#modules">Modules</a>
                <a href="#flux">Flux</a>
                <a href="#architecture">Architecture</a>
                @auth
                    <a class="nav-cta" href="{{ route('dashboard') }}">Tableau de bord <i class="ti ti-arrow-right"
                            aria-hidden="true"></i></a>
                @else
                    <a class="nav-cta" href="{{ route('login') }}">Se connecter <i class="ti ti-arrow-right"
                            aria-hidden="true"></i></a>
                @endauth
            </nav>

            <div class="header-actions">
                @auth
                    <a class="btn btn-primary btn-sm" href="{{ route('dashboard') }}">
                        Tableau de bord <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    </a>
                @else
                    <a class="btn btn-primary btn-sm" href="{{ route('login') }}">
                        Se connecter <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="shell footer-inner">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="brand" aria-label="Papeterie, retour à l'accueil">
                    <span class="brand-mark" aria-hidden="true"><i class="ti ti-packages"></i></span>
                    <span class="brand-text">
                        <strong>Papeterie</strong>
                        <small>Gestion des commandes</small>
                    </span>
                </a>
                <p>Clients, fournisseurs, produits et commandes sur un même référentiel.</p>
            </div>

            <nav class="footer-nav" aria-label="Navigation du pied de page">
                <a href="#modules">Modules</a>
                <a href="#flux">Flux</a>
                <a href="#architecture">Architecture</a>
                @auth
                    <a href="{{ route('dashboard') }}">Tableau de bord</a>
                @else
                    <a href="{{ route('login') }}">Connexion</a>
                @endauth
            </nav>

            <div class="footer-meta">
                <span>&copy; {{ date('Y') }} Papeterie</span>
                <span class="mono">Laravel · Blade · MySQL</span>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/landing.js') }}" defer></script>
</body>

</html>
