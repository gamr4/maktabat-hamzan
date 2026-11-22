<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Espace client et consultation des commandes Papeterie">
    <meta name="theme-color" content="#13161a">
    <title>@yield('title', 'Portail Client') · Papeterie</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Roboto:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    <style>
        .portal-layout {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--bg-body, #0e1116);
            color: var(--text-main, #e6edf3);
        }

        .portal-header {
            background: var(--bg-surface, #161b22);
            border-bottom: 1px solid var(--border-subtle, rgba(255, 255, 255, 0.08));
            padding: 0.85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(8px);
            gap: 1rem;
        }

        .portal-header__left {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .portal-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        .portal-brand__badge {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        .portal-nav {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .portal-nav__link {
            padding: 0.5rem 0.9rem;
            border-radius: 0.5rem;
            color: var(--text-muted, #8b949e);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.15s ease;
            position: relative;
        }

        .portal-nav__link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }

        .portal-nav__link.is-active {
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.12);
            font-weight: 600;
            box-shadow: inset 0 -2px 0 0 #38bdf8;
        }

        .portal-user {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            flex-shrink: 0;
        }

        .portal-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0369a1, #0284c7);
            color: #ffffff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.25);
        }

        .portal-main {
            flex: 1;
            width: 100%;
            margin: 0 auto;
            padding: 2rem 1.5rem 3rem;
            box-sizing: border-box;
        }

        .portal-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.825rem;
            color: #8b949e;
            margin-bottom: 0.75rem;
        }

        .portal-breadcrumbs a {
            color: #8b949e;
            text-decoration: none;
            transition: color 0.15s;
        }

        .portal-breadcrumbs a:hover {
            color: #38bdf8;
        }

        .portal-breadcrumbs .sep {
            opacity: 0.5;
        }

        .portal-pagehead {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* Filter pills */
        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(56, 189, 248, 0.12);
            border: 1px solid rgba(56, 189, 248, 0.25);
            color: #38bdf8;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .filter-pill button {
            background: transparent;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
        }

        /* Copy Button Styles */
        .btn-copy {
            background: transparent;
            border: none;
            color: #8b949e;
            cursor: pointer;
            padding: 0.2rem 0.35rem;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            transition: all 0.15s;
            vertical-align: middle;
        }

        .btn-copy:hover {
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.1);
        }

        .btn-copy.copied {
            color: #34d399;
        }

        /* Portal Footer */
        .portal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding: 1.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: #8b949e;
            background: var(--bg-surface, #161b22);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .portal-footer a {
            color: #8b949e;
            text-decoration: none;
        }

        .portal-footer a:hover {
            color: #38bdf8;
        }

        /* Responsive Mobile tweaks */
        @media (max-width: 768px) {
            .portal-header {
                padding: 0.75rem 1rem;
            }
            .portal-header__left {
                gap: 0.75rem;
                width: 100%;
                justify-content: space-between;
            }
            .portal-nav {
                width: 100%;
                overflow-x: auto;
                padding-top: 0.4rem;
                border-top: 1px solid rgba(255, 255, 255, 0.05);
            }
            .portal-nav__link {
                padding: 0.4rem 0.65rem;
                font-size: 0.8rem;
                white-space: nowrap;
            }
            .portal-user__info {
                display: none;
            }
            .portal-user a.btn span {
                display: none;
            }
            .portal-footer {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Print styles */
        @media print {
            .portal-header,
            .portal-footer,
            .btn,
            .btn-ghost,
            .greeting-bar,
            .stats-grid,
            form,
            .btn-copy,
            .portal-breadcrumbs,
            a[href] {
                display: none !important;
            }

            body, .portal-layout, .portal-main {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .card {
                border: 1px solid #ccc !important;
                background: #ffffff !important;
                color: #000000 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }

            .table {
                border-collapse: collapse !important;
                width: 100% !important;
            }

            .table th, .table td {
                color: #000000 !important;
                border: 1px solid #ddd !important;
                padding: 6px 10px !important;
            }
        }
    </style>
</head>

<body class="app-body">
    <a class="skip-link" href="#main">Aller au contenu</a>

    <div class="portal-layout">
        <!-- Client Portal Navigation (No Admin Chrome) -->
        <header class="portal-header">
            <div class="portal-header__left">
                <a href="{{ route('portail.dashboard') }}" class="portal-brand">
                    <span class="brand-mark" style="width: 32px; height: 32px; font-size: 1.1rem;"><i class="ti ti-packages"></i></span>
                    <strong style="font-size: 1.05rem;">Papeterie</strong>
                    <span class="portal-brand__badge">Portail Client</span>
                </a>

                <nav class="portal-nav" aria-label="Navigation client">
                    <a href="{{ route('portail.dashboard') }}" @class(['portal-nav__link', 'is-active' => request()->routeIs('portail.dashboard')])>
                        <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                        <span>Vue d'ensemble</span>
                    </a>
                    <a href="{{ route('portail.commandes') }}" @class(['portal-nav__link', 'is-active' => request()->routeIs('portail.commandes*')])>
                        <i class="ti ti-receipt" aria-hidden="true"></i>
                        <span>Mes Commandes</span>
                    </a>
                    <a href="{{ route('portail.profil') }}" @class(['portal-nav__link', 'is-active' => request()->routeIs('portail.profil')])>
                        <i class="ti ti-user" aria-hidden="true"></i>
                        <span>Profil & Comptes</span>
                    </a>
                </nav>
            </div>

            <div class="portal-user">
                <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm" title="Retourner au tableau de bord principal" style="gap: 0.4rem;">
                    <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                    <span>Tableau de bord</span>
                </a>

                <div class="portal-user__info" style="text-align: right; line-height: 1.2;">
                    <span style="font-weight: 600; font-size: 0.875rem; display: block;">{{ Auth::user()->name }}</span>
                    <span style="font-size: 0.75rem; color: #8b949e;">{{ Auth::user()->role_label }}</span>
                </div>
                <div class="portal-avatar" aria-hidden="true" title="{{ Auth::user()->name }}">
                    {{ strtoupper(substr(Auth::user()->name ?? 'C', 0, 1)) }}
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;" onsubmit="return confirm('Confirmer la déconnexion ?');">
                    @csrf
                    <button type="submit" class="profile-card__btn profile-card__btn--logout" title="Se déconnecter" aria-label="Se déconnecter">
                        <i class="ti ti-logout" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="portal-main" id="main" style="max-width: @yield('content-width', '1200px')">
            @hasSection('breadcrumbs')
                <nav class="portal-breadcrumbs" aria-label="Fil d'ariane">
                    @yield('breadcrumbs')
                </nav>
            @endif

            @if (session('success'))
                <div class="alert alert-success" role="status" style="margin-bottom: 1.5rem;">
                    <i class="ti ti-circle-check" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert" style="margin-bottom: 1.5rem;">
                    <i class="ti ti-alert-circle" aria-hidden="true"></i>
                    <div>
                        <strong>Une erreur est survenue :</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="portal-pagehead">
                <div>
                    <p class="eyebrow">@yield('eyebrow', 'Espace Client')</p>
                    <h1 class="page-title">@yield('title', 'Tableau de bord')</h1>
                </div>
                @hasSection('actions')
                    <div>
                        @yield('actions')
                    </div>
                @endif
            </div>

            @yield('content')
        </main>

        <footer class="portal-footer">
            <div>
                © {{ date('Y') }} <strong>Maktabat Hamzan</strong> · Papeterie & Fournitures Professionnelles.
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('portail.dashboard') }}">Accueil Client</a>
                <a href="{{ route('portail.commandes') }}">Commandes</a>
                <a href="{{ route('portail.profil') }}">Sécurité</a>
            </div>
        </footer>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Reusable Copy to Clipboard functionality
            document.querySelectorAll('[data-copy]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const textToCopy = this.getAttribute('data-copy');
                    if (!textToCopy) return;

                    navigator.clipboard.writeText(textToCopy).then(() => {
                        const originalHtml = this.innerHTML;
                        this.classList.add('copied');
                        this.innerHTML = '<i class="ti ti-check" aria-hidden="true"></i>';
                        setTimeout(() => {
                            this.innerHTML = originalHtml;
                            this.classList.remove('copied');
                        }, 1800);
                    }).catch(err => {
                        console.error('Erreur de copie:', err);
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
