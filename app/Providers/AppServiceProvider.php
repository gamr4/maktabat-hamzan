<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use App\Policies\ClientPolicy;
use App\Policies\CommandePolicy;
use App\Policies\FournisseurPolicy;
use App\Policies\ProduitPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Model Policies
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Fournisseur::class, FournisseurPolicy::class);
        Gate::policy(Produit::class, ProduitPolicy::class);
        Gate::policy(Commande::class, CommandePolicy::class);

        // Gate Definitions
        Gate::define('admin-only', fn (User $user) => $user->isAdmin());
        Gate::define('manage-fournisseurs', fn (User $user) => $user->isAdmin());
        Gate::define('manage-clients', fn (User $user) => in_array($user->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true));
        Gate::define('manage-produits', fn (User $user) => in_array($user->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true));
        Gate::define('manage-commandes', fn (User $user) => in_array($user->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true));
        Gate::define('delete-resource', fn (User $user) => $user->isAdmin());
        Gate::define('access-portal', fn (User $user) => $user->isClient());

        View::composer(['layouts.app', 'dashboard'], function ($view) {
            $counts = [
                'commandes' => 15,
                'clients' => 30,
                'produits' => 25,
                'fournisseurs' => 25,
            ];

            try {
                if (Schema::hasTable('commandes')) {
                    $counts['commandes'] = Commande::count();
                }
                if (Schema::hasTable('clients')) {
                    $counts['clients'] = Client::count();
                }
                if (Schema::hasTable('produits')) {
                    $counts['produits'] = Produit::count();
                }
                if (Schema::hasTable('fournisseurs')) {
                    $counts['fournisseurs'] = Fournisseur::count();
                }
            } catch (\Throwable $e) {
                // Keep default values if database is inaccessible
            }

            $role = auth()->user()?->role ?? 'guest';

            $view->with('navCounts', $counts)
                ->with('userRole', $role)
                ->with('role', $role);
        });
    }
}
