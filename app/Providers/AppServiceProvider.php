<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Produit;
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
        View::composer('layouts.app', function ($view) {
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

            $view->with('navCounts', $counts);
        });
    }
}

