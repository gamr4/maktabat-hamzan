<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#13161a">
    <title>403 · Accès Refusé · Papeterie</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Roboto:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--bg-body, #0e1116);
            color: var(--text-main, #e6edf3);
            text-align: center;
        }

        .error-card {
            max-width: 480px;
            width: 100%;
            background: var(--bg-surface, #161b22);
            border: 1px solid var(--border-subtle, rgba(255, 255, 255, 0.08));
            border-radius: var(--radius-md, 12px);
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .error-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
            color: #fff;
        }

        .error-msg {
            color: var(--text-muted, #8b949e);
            font-size: 0.925rem;
            line-height: 1.5;
            margin-bottom: 2rem;
        }

        .error-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>

<body class="app-body">
    <div class="error-page">
        <div class="error-card">
            <div class="error-icon" aria-hidden="true">
                <i class="ti ti-shield-x"></i>
            </div>
            <h1 class="error-title">Accès non autorisé</h1>
            <p class="error-msg">
                {{ $exception->getMessage() ?: 'Vous ne disposez pas des permissions nécessaires pour accéder à cette ressource avec votre rôle actuel.' }}
            </p>
            <div class="error-actions">
                @auth
                    @if (auth()->user()->isClient())
                        <a href="{{ route('portail.dashboard') }}" class="btn btn-primary">
                            <i class="ti ti-home" aria-hidden="true"></i> Retour à mon portail
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="ti ti-home" aria-hidden="true"></i> Retour au tableau de bord
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="ti ti-login" aria-hidden="true"></i> Se connecter
                    </a>
                @endauth
                <button type="button" onclick="history.back()" class="btn btn-ghost">
                    Page précédente
                </button>
            </div>
        </div>
    </div>
</body>

</html>
