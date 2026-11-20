<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F4F6F8" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1B1F24" media="(prefers-color-scheme: dark)">
    <meta name="robots" content="noindex">
    <title>Connexion · Papeterie</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Roboto:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth">
    <div class="auth-ambient" aria-hidden="true">
        <canvas class="auth-field" data-auth-field></canvas>
        <span class="auth-ambient__glow auth-ambient__glow--warm"></span>
        <span class="auth-ambient__glow auth-ambient__glow--cool"></span>
    </div>

    <a class="skip-link" href="#auth-main">Aller au contenu</a>

    <main class="auth-shell" id="auth-main">
        <section class="auth-aside" aria-labelledby="auth-title">
            <a href="{{ route('home') }}" class="brand" aria-label="Papeterie, retour à l'accueil">
                <span class="brand-mark" aria-hidden="true"><i class="ti ti-packages"></i></span>
                <span class="brand-text">
                    <strong>Papeterie</strong>
                    <small>Gestion des commandes</small>
                </span>
            </a>

            <div class="auth-intro">
                <p class="auth-intro__eyebrow"><span class="status-dot" aria-hidden="true"></span> Espace professionnel</p>
                <h1 id="auth-title">Votre gestion, au même endroit.</h1>
                <p>Retrouvez vos clients, fournisseurs, produits et commandes dans une console unique.</p>
            </div>

            <p class="auth-schematic">Console · Accès sécurisé</p>
        <ul class="auth-benefits" aria-label="Fonctionnalités principales">
            <li><i class="ti ti-check" aria-hidden="true"></i> Catalogues organisés</li>
            <li><i class="ti ti-check" aria-hidden="true"></i> Commandes centralisées</li>
            <li><i class="ti ti-check" aria-hidden="true"></i> Accès sécurisé</li>
        </ul>

        </section>

        <section class="auth-panel" aria-labelledby="login-title">
            <p class="auth-panel__eyebrow"><i class="ti ti-lock" aria-hidden="true"></i> Accès sécurisé</p>
            <h2 id="login-title">Connexion</h2>
            <p>Connectez-vous pour accéder à votre espace.</p>

            <form action="{{ route('login') }}" method="POST" class="auth-form">
                @csrf
                @php($passwordInvalid = $errors->has('password'))

                <x-input name="email" label="Adresse e-mail" type="email" autocomplete="username" required autofocus />

                <div class="field">
                    <label for="password">
                        Mot de passe<span class="req" aria-hidden="true">*</span>
                    </label>

                    <div class="input-affix">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            @if ($passwordInvalid) aria-invalid="true" aria-describedby="password-error" @endif
                            @class(['input', 'is-invalid' => $passwordInvalid])
                        >
                        <button
                            type="button"
                            class="pw-toggle"
                            data-pw-toggle
                            aria-controls="password"
                            aria-label="Afficher le mot de passe"
                            aria-pressed="false"
                        >
                            <i class="ti ti-eye" aria-hidden="true"></i>
                        </button>
                    </div>

                    <p class="pw-caps" data-pw-caps>
                        <i class="ti ti-alert-triangle" aria-hidden="true"></i>
                        Verrouillage des majuscules activé
                    </p>

                    @if ($passwordInvalid)
                        <p class="field-error" id="password-error">
                            <i class="ti ti-alert-circle" aria-hidden="true"></i>
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>

                <label class="remember-me">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    <span>Rester connecté sur cet appareil</span>
                </label>

                <button type="submit" class="btn btn-primary" data-submit>
                    <span class="btn-label">Se connecter</span>
                    <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    <span class="btn-spinner" aria-hidden="true"></span>
                </button>
            </form>

            <a href="{{ route('home') }}" class="auth-home">
                <i class="ti ti-arrow-left" aria-hidden="true"></i> Retour à l’accueil
            </a>
        </section>
    </main>

    <script src="{{ asset('js/auth.js') }}" defer></script>
    <script src="{{ asset('js/auth-ambient.js') }}" defer></script>
</body>

</html>
