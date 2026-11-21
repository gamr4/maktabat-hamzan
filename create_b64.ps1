
$fullContent = "@extends(`"layouts.app`")

@section(`"title`", `"Tableau de bord`")
@section(`"eyebrow`", `"Vue d`"ensemble`")

@section(`"actions`")
    <a class=`"btn btn-primary`" href=`"{{ route(`"commandes.create`") }}`">
        <i class=`"ti ti-plus`" aria-hidden=`"true`"></i> Nouvelle commande
    </a>
@endsection

@section(`"content`")
    {{-- Workspace Hero with enhanced interactivity --}}
    <section class=`"workspace-hero`" aria-labelledby=`"workspace-title`">
        <div>
            <p class=`"workspace-hero__eyebrow`"><span class=`"status-dot status-dot--pulse`" aria-hidden=`"true`"></span> Espace de travail</p>
            <h2 id=`"workspace-title`">Votre activité, claire et prête à avancer.</h2>
            <p>Suivez le rythme de la papeterie, ajoutez une commande ou ouvrez directement la bonne collection.</p>
        </div>
        <div class=`"workspace-hero__links`" aria-label=`"Accès rapides`">
            <a href=`"{{ route(`"produits.index`") }}`" class=`"hero-link`">
                <i class=`"ti ti-box`" aria-hidden=`"true`"></i>
                <span>Catalogue</span>
                <i class=`"ti ti-arrow-right`" aria-hidden=`"true`"></i>
            </a>
            <a href=`"{{ route(`"clients.index`") }}`" class=`"hero-link`">
                <i class=`"ti ti-users`" aria-hidden=`"true`"></i>
                <span>Clients</span>
                <i class=`"ti ti-arrow-right`" aria-hidden=`"true`"></i>
            </a>
        </div>
    </section>

    {{-- Enhanced Stats Grid with staggered entrance animation --}}
    <div class=`"stats-grid`" data-stagger>
        <x-stat-card label=`"Commandes`" :value=`"$stats[`"commandes`"] ?? 0`" icon=`"shopping-cart`"
            :href=`"route(`"commandes.index`")`" accent trend=`"+12%`" trend-label=`"vs mois dernier`" />
        <x-stat-card label=`"Clients`" :value=`"$stats[`"clients`"] ?? 0`" icon=`"users`"
            :href=`"route(`"clients.index`")`" trend=`"+5`" trend-label=`"nouveaux`" />
        <x-stat-card label=`"Fournisseurs`" :value=`"$stats[`"fournisseurs`"] ?? 0`" icon=`"truck-delivery`"
            :href=`"route(`"fournisseurs.index`")`" trend=`"2`" trend-label=`"actifs`" />
        <x-stat-card label=`"Produits`" :value=`"$stats[`"produits`"] ?? 0`" icon=`"box`"
            :href=`"route(`"produits.index`")`" trend=`"+8`" trend-label=`"en stock`" />
    </div>

    {{-- Quick Actions Panel --}}
    <section class=`"quick-actions`" aria-labelledby=`"quick-actions-title`">
        <div class=`"quick-actions__header`">
            <h2 class=`"card__title`" id=`"quick-actions-title`">Actions rapides</h2>
            <p class=`"section-note`">Raccourcis vers les tâches courantes.</p>
        </div>
        <div class=`"quick-actions__grid`">
            <a href=`"{{ route(`"commandes.create`") }}`" class=`"action-card`" data-action=`"create-order`">
                <div class=`"action-card__icon`" aria-hidden=`"true`"><i class=`"ti ti-shopping-cart-plus`"></i></div>
                <div class=`"action-card__content`">
                    <strong>Nouvelle commande</strong>
                    <span>Créer une commande client</span>
                </div>
                <i class=`"ti ti-arrow-right action-card__arrow`" aria-hidden=`"true`"></i>
            </a>
            <a href=`"{{ route(`"produits.create`") }}`" class=`"action-card`" data-action=`"add-product`">
                <div class=`"action-card__icon`" aria-hidden=`"true`"><i class=`"ti ti-box-plus`"></i></div>
                <div class=`"action-card__content`">
                    <strong>Ajouter un produit</strong>
                    <span>Enrichir le catalogue</span>
                </div>
                <i class=`"ti ti-arrow-right action-card__arrow`" aria-hidden=`"true`"></i>
            </a>
            <a href=`"{{ route(`"clients.create`") }}`" class=`"action-card`" data-action=`"add-client`">
                <div class=`"action-card__icon`" aria-hidden=`"true`"><i class=`"ti ti-user-plus`"></i></div>
                <div class=`"action-card__content`">
                    <strong>Nouveau client</strong>
                    <span>Enregistrer un client</span>
                </div>
                <i class=`"ti ti-arrow-right action-card__arrow`" aria-hidden=`"true`"></i>
            </a>
            <a href=`"{{ route(`"fournisseurs.create`") }}`" class=`"action-card`" data-action=`"add-supplier`">
                <div class=`"action-card__icon`" aria-hidden=`"true`"><i class=`"ti ti-truck-plus`"></i></div>
                <div class=`"action-card__content`">
                    <strong>Nouveau fournisseur</strong>
                    <span>Référencer un fournisseur</span>
                </div>
                <i class=`"ti ti-arrow-right action-card__arrow`" aria-hidden=`"true`"></i>
            </a>
        </div>
    </section

    {{-- Recent Orders with enhanced interactivity --}}
    <section class=`"card card--flush`" aria-labelledby=`"latest-orders-title`">
        <div class=`"card__head`">
            <div>
                <h2 class=`"card__title`" id=`"latest-orders-title`">Dernières commandes</h2>
                <p class=`"section-note`">Les cinq commandes les plus récentes.</p>
            </div>
            <a class=`"btn btn-ghost btn-sm`" href=`"{{ route(`"commandes.index`") }}`">
                Tout voir <i class=`"ti ti-arrow-right`" aria-hidden=`"true`"></i>
            </a>
        </div>

        @if ($latestCommandes->isEmpty())
            <x-empty-state icon=`"receipt`" title=`"Aucune commande`"
                text=`"Créez une première commande pour la voir apparaître ici.`">
                <a class=`"btn btn-primary btn-sm`" href=`"{{ route(`"commandes.create`") }}`">Créer une commande</a>
            </x-empty-state>
        @else
            <div class=`"table-wrap`">
                <table class=`"table table--interactive`">
                    <caption class=`"sr-only`">Dernières commandes</caption>
                    <caption class=`"sr-only`">Cinq dernières commandes</caption>
                    <thead>
                        <tr>
                            <th scope=`"col`">Numéro</th>
                            <th scope=`"col`">Date</th>
                            <th scope=`"col`">Client</th>
                            <th scope=`"col`">Condition</th>
                            <th scope=`"col`" class=`"num`">Montant</th>
                            <th scope=`"col`"><span class=`"sr-only`">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestCommandes as $commande)
                            <tr data-order-id=`"{{ $commande->id }}`" data-href=`"{{ route(`"commandes.show`", $commande) }}`">
                                <td>
                                    <a class=`"cell-code`" href=`"{{ route(`"commandes.show`", $commande) }}`">
                                        {{ $commande->numero_commande }}
                                    </a>
                                </td>
                                <td><x-date :value=`"$commande->date_commande`" /></td>
                                <td>{{ $commande->client?->nom_client ?? `"—`" }}</td>
                                <td><x-condition-badge :condition=`"$commande->condition`" /></td>
                                <td class=`"num`">
                                    <x-money :value=`"$commande->produits->sum(fn ($produit) => $produit->prix_unitaire * $produit->pivot->quantite)`" />
                                </td>
                                <td>
                                    <div class=`"row-actions`">
                                        <a href=`"{{ route(`"commandes.show`", $commande) }}`" class=`"icon-btn icon-btn--ghost`" title=`"Voir`" aria-label=`"Voir la commande {{ $commande->numero_commande }}`">
                                            <i class=`"ti ti-eye`" aria-hidden=`"true`"></i>
                                        </a>
                                        <a href=`"{{ route(`"commandes.edit`", $commande) }}`" class=`"icon-btn icon-btn--ghost`" title=`"Modifier`" aria-label=`"Modifier la commande {{ $commande->numero_commande }}`">
                                            <i class=`"ti ti-edit`" aria-hidden=`"true`"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section

    {{-- Activity Feed (if there are recent activities) --}}
    @if (isset($recentActivity) && $recentActivity->isNotEmpty())
    <section class=`"card card--flush`" aria-labelledby=`"activity-title`">
        <div class=`"card__head`">
            <div>
                <h2 class=`"card__title`" id=`"activity-title`">Activité récente</h2>
                <p class=`"section-note`">Les dernières actions dans le système.</p>
            </div>
        </div>
        <div class=`"activity-feed`">
            @foreach ($recentActivity as $activity)
                <div class=`"activity-item`" data-reveal>
                    <div class=`"activity-item__icon`" style=`"--activity-color: {{ $activity[`"color`"] ?? `"var(--color-accent)`" }}`">
                        <i class=`"{{ $activity[`"icon`"] ?? `"ti ti-activity`" }}`" aria-hidden=`"true`"></i>
                    </div>
                    <div class=`"activity-item__content`">
                        <p class=`"activity-item__text`">{{ $activity[`"text`"] }}</p>
                        <time class=`"activity-item__time`" datetime=`"{{ $activity[`"timestamp`"]->toIso8601String() }}`">
                            {{ $activity[`"timestamp`"]->diffForHumans() }}
                        </time>
                    </div>
                    @if (isset($activity[`"link`"]))
                        <a href=`"{{ $activity[`"link`"] }}`" class=`"activity-item__link`" aria-label=`"Voir les détails`">
                            <i class=`"ti ti-arrow-right`" aria-hidden=`"true`"></i>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
    @endif
@endsection

@push(`"scripts`")
<script>
// Dashboard-specific interactions
(() => {
    // Staggered entrance animation for stat cards
    const staggerContainer = document.querySelector(`"[data-stagger]`");
    if (staggerContainer) {
        const cards = staggerContainer.querySelectorAll(`".stat-card`");
        cards.forEach((card, index) => {
            card.style.animationDelay = index * 80 + `"ms`";
            card.classList.add(`"stat-card--enter`");
        });
    }

    // Row hover and click interactions for orders table
    const table = document.querySelector(`".table--interactive`");
    if (table) {
        table.addEventListener(`"click`", (e) => {
            const row = e.target.closest(`"tr[data-href]`");
            if (row && !e.target.closest(`".row-actions, .icon-btn, a`")) {
                window.location.href = row.dataset.href;
            }
        });

        // Keyboard navigation for rows
        table.addEventListener(`"keydown`", (e) => {
            const row = e.target.closest(`"tr[data-href]`");
            if (row && (e.key === `"Enter`" || e.key === `" "`")) {
                e.preventDefault();
                window.location.href = row.dataset.href;
            }
        });

        // Make rows focusable for keyboard
        table.querySelectorAll(`"tr[data-href]`").forEach(row => {
            row.setAttribute(`"tabindex`", `"0`");
            row.style.cursor = `"pointer`";
        });
    }

    // Quick action cards hover effect
    document.querySelectorAll(`".action-card`").forEach(card => {
        card.addEventListener(`"mouseenter`", () => {
            card.style.transform = `"translateY(-4px)`";
        });
        card.addEventListener(`"mouseleave`", () => {
            card.style.transform = `"translateY(0)`";
        });
    });

    // Hero links enhancement
    document.querySelectorAll(`".hero-link`").forEach(link => {
        link.addEventListener(`"mouseenter`", () => {
            link.style.transform = `"translateX(4px)`";
        });
        link.addEventListener(`"mouseleave`", () => {
            link.style.transform = `"translateX(0)`";
        });
    });

    // IntersectionObserver for reveal animations
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add(`"is-revealed`");
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: `"0px 0px -50px 0px`" });

    document.querySelectorAll(`"[data-reveal]`").forEach(el => {
        revealObserver.observe(el);
    });

    // Status dot pulse animation control
    const statusDot = document.querySelector(`".status-dot--pulse`");
    if (statusDot) {
        // Pause animation on hover of parent
        const hero = statusDot.closest(`".workspace-hero`");
        if (hero) {
            hero.addEventListener(`"mouseenter`", () => {
                statusDot.style.animationPlayState = `"paused`";
            });
            hero.addEventListener(`"mouseleave`", () => {
                statusDot.style.animationPlayState = `"running`";
            });
        }
    }
})();
</script>
@endpush"
$bytes = [System.Text.Encoding]::UTF8.GetBytes($fullContent)
$b64 = [System.Convert]::ToBase64String($bytes)
Set-Content -Path "C:\Users\Asus Rog\github\maktabat-hamzan\dashboard_full.b64" -Value $b64 -Encoding UTF8
Write-Host "b64 written"

