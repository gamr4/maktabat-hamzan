@extends("layouts.app")

@section("eyebrow", "Console")
@section("title", "Tableau de bord")

@section("content")
    <section class="workspace-hero" aria-labelledby="workspace-title">
        <div>
            <p class="workspace-hero__eyebrow"><span class="status-dot status-dot--pulse" aria-hidden="true"></span> Espace de travail</p>
            <h2 id="workspace-title">Votre activité, claire et prête à avancer.</h2>
            <p>Suivez le rythme de la papeterie, ajoutez une commande ou ouvrez directement la bonne collection.</p>
        </div>
        <div class="workspace-hero__links" aria-label="Accès rapides">
            <a href="{{ route("produits.index") }}" class="hero-link">
                <i class="ti ti-box" aria-hidden="true"></i>
                <span>Catalogue</span>
                <i class="ti ti-arrow-right" aria-hidden="true"></i>
            </a>
            <a href="{{ route("clients.index") }}" class="hero-link">
                <i class="ti ti-users" aria-hidden="true"></i>
                <span>Clients</span>
                <i class="ti ti-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </section>

    {{-- Enhanced Stats Grid with staggered entrance animation --}}
    <div class="stats-grid" data-stagger>
        <x-stat-card label="Commandes" :value="$commandes ?? 0" icon="shopping-cart" :href="route("commandes.index")" accent trend="+12%" trend-label="vs mois dernier" />
        <x-stat-card label="Clients" :value="$clients ?? 0" icon="users" :href="route("clients.index")" trend="+5" trend-label="nouveaux" />
        <x-stat-card label="Fournisseurs" :value="$fournisseurs ?? 0" icon="truck-delivery" :href="route("fournisseurs.index")" trend="2" trend-label="actifs" />
        <x-stat-card label="Produits" :value="$produits ?? 0" icon="box" :href="route("produits.index")" trend="+8" trend-label="en stock" />
    </div>

    {{-- Quick Actions Panel --}}
    <section class="quick-actions" aria-labelledby="quick-actions-title">
        <div class="quick-actions__header">
            <h2 class="card__title" id="quick-actions-title">Actions rapides</h2>
            <p class="section-note">Raccourcis vers les actions les plus courantes.</p>
        </div>
        <div class="quick-actions__grid">
            <a href="{{ route("commandes.create") }}" class="action-card" data-action="create-order">
                <div class="action-card__icon" aria-hidden="true"><i class="ti ti-shopping-cart-plus"></i></div>
                <div class="action-card__content">
                    <strong>Nouvelle commande</strong>
                    <span>Créer une commande client</span>
                </div>
                <i class="ti ti-arrow-right action-card__arrow" aria-hidden="true"></i>
            </a>
            <a href="{{ route("produits.create") }}" class="action-card" data-action="add-product">
                <div class="action-card__icon" aria-hidden="true"><i class="ti ti-box-plus"></i></div>
                <div class="action-card__content">
                    <strong>Ajouter un produit</strong>
                    <span>Enrichir le catalogue</span>
                </div>
                <i class="ti ti-arrow-right action-card__arrow" aria-hidden="true"></i>
            </a>
            <a href="{{ route("clients.create") }}" class="action-card" data-action="add-client">
                <div class="action-card__icon" aria-hidden="true"><i class="ti ti-user-plus"></i></div>
                <div class="action-card__content">
                    <strong>Nouveau client</strong>
                    <span>Enregistrer un client</span>
                </div>
                <i class="ti ti-arrow-right action-card__arrow" aria-hidden="true"></i>
            </a>
            <a href="{{ route("fournisseurs.create") }}" class="action-card" data-action="add-supplier">
                <div class="action-card__icon" aria-hidden="true"><i class="ti ti-truck-plus"></i></div>
                <div class="action-card__content">
                    <strong>Nouveau fournisseur</strong>
                    <span>Référencer un fournisseur</span>
                </div>
                <i class="ti ti-arrow-right action-card__arrow" aria-hidden="true"></i>
            </a>
        </div>
    </section>

    {{-- Recent Orders Table with enhanced interaction --}}
    <section class="card card--flush" aria-labelledby="latest-orders-title">
        <div class="card__head">
            <div>
                <h2 class="card__title" id="latest-orders-title">Dernières commandes</h2>
                <p class="section-note">Les cinq commandes les plus récentes.</p>
            </div>
            <a class="btn btn-ghost btn-sm" href="{{ route("commandes.index") }}">
                Tout voir <i class="ti ti-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
        <div class="table-wrap">
            <table class="table table--interactive">
                <thead>
                    <tr>
                        <th scope="col">Commande</th>
                        <th scope="col">Client</th>
                        <th scope="col">Montant</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Date</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (isset($latestCommandes) ? $latestCommandes : collect()) as $commande
                        <tr data-href="{{ route("commandes.show", $commande) }}">
                            <td>
                                <span class="code-chip">{{ $commande->reference ?? "CMD-" . $commande->id }}</span>
                            </td>
                            <td>{{ $commande->client->name ?? "Client inconnu" }}</td>
                            <td>
                                <span class="money">{{ $commande->total_amount ?? 0 }}</span>
                            </td>
                            <td>
                                <x-condition-badge :condition="$commande->status ?? "pending"" />
                            </td>
                            <td>
                                <x-date :date="$commande->created_at ?? now()" format="short" />
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route("commandes.show", $commande) }}" class="icon-btn" aria-label="Voir la commande">
                                        <i class="ti ti-eye" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route("commandes.edit", $commande) }}" class="icon-btn" aria-label="Modifier la commande">
                                        <i class="ti ti-edit" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: var(--space-6); color: var(--color-text-muted);">
                                Aucune commande récente
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Activity Feed (conditional section) --}}
    @if (isset($recentActivity) && $recentActivity->isNotEmpty())
        <section class="card card--flush" aria-labelledby="activity-title">
            <div class="card__head">
                <div>
                    <h2 class="card__title" id="activity-title">Activité récente</h2>
                    <p class="section-note">Les dernières actions dans le système.</p>
                </div>
            </div>
            <div class="activity-feed">
                @foreach ($recentActivity as $activity)
                    <div class="activity-item" data-reveal>
                        <div class="activity-item__icon" style="--activity-color: {{ $activity["color"] ?? "var(--color-accent)" }}">
                            <i class="{{ $activity["icon"] ?? "ti ti-activity" }}" aria-hidden="true"></i>
                        </div>
                        <div class="activity-item__content">
                            <p class="activity-item__text">{{ $activity["text"] }}</p>
                            <time class="activity-item__time" datetime="{{ $activity["timestamp"]->toIso8601String() }}">
                                {{ $activity["timestamp"]->diffForHumans() }}
                            </time>
                        </div>
                        @if (isset($activity["link"]))
                            <a href="{{ $activity["link"] }}" class="activity-item__link" aria-label="Voir les détails">
                                <i class="ti ti-arrow-right" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection

@push("scripts")
<script>
// Dashboard-specific interactions
(() => {
    // Staggered entrance animation for stat cards
    const staggerContainer = document.querySelector("[data-stagger]");
    if (staggerContainer) {
        const cards = staggerContainer.querySelectorAll(".stat-card");
        cards.forEach((card, index) => {
            card.style.animationDelay = index * 80 + "ms";
            card.classList.add("stat-card--enter");
        });
    }

    // Row hover and click interactions for orders table
    const table = document.querySelector(".table--interactive");
    if (table) {
        table.addEventListener("click", (e) => {
            const row = e.target.closest("tr[data-href]");
            if (row && !e.target.closest(".row-actions, .icon-btn, a")) {
                window.location.href = row.dataset.href;
            }
        });

        // Keyboard navigation for rows
        table.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") {
                const row = e.target.closest("tr[data-href]");
                if (row) {
                    e.preventDefault();
                    window.location.href = row.dataset.href;
                }
            }
        });

        // Make rows focusable for keyboard
        table.querySelectorAll("tr[data-href]").forEach(row => {
            row.setAttribute("tabindex", "0");
            row.style.cursor = "pointer";
        });
    }

    // Quick action cards hover effect
    document.querySelectorAll(".action-card").forEach(card => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "translateY(-4px)";
        });
        card.addEventListener("mouseleave", () => {
            card.style.transform = "translateY(0)";
        });
    });

    // Hero links enhancement
    document.querySelectorAll(".hero-link").forEach(link => {
        link.addEventListener("mouseenter", () => {
            link.style.transform = "translateX(4px)";
        });
        link.addEventListener("mouseleave", () => {
            link.style.transform = "translateX(0)";
        });
    });

    // IntersectionObserver for reveal animations
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("is-revealed");
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

    document.querySelectorAll("[data-reveal]").forEach(el => {
        revealObserver.observe(el);
    });

    // Status dot pulse animation control
    const statusDot = document.querySelector(".status-dot--pulse");
    if (statusDot) {
        const hero = statusDot.closest(".workspace-hero");
        if (hero) {
            hero.addEventListener("mouseenter", () => {
                statusDot.style.animationPlayState = "paused";
            });
            hero.addEventListener("mouseleave", () => {
                statusDot.style.animationPlayState = "running";
            });
        }
    }
})();
</script>
@endpush
