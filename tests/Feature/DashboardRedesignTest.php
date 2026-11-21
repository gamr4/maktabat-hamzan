<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRedesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_and_dashboard_render_upgraded_ui_elements(): void
    {
        $user = User::factory()->create([
            'name' => 'Abdrahman',
            'email' => 'abdrahman@gmail.com',
        ]);

        $client = Client::factory()->create();
        $produit = Produit::factory()->create();
        $commande = Commande::factory()->create([
            'code_client' => $client->code_client,
            'condition' => 'LIV',
        ]);
        $commande->produits()->sync([$produit->code_produit => ['quantite' => 2]]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);

        // Sidebar checks
        $response->assertSee('GÉNÉRAL');
        $response->assertSee('GESTION');
        $response->assertSee('Rechercher...');
        $response->assertSee('⌘K');
        $response->assertSee('Tableau de bord');
        $response->assertSee('Commandes');
        $response->assertSee('Clients');
        $response->assertSee('Produits');
        $response->assertSee('Fournisseurs');

        // Profile card checks
        $response->assertSee('Abdrahman');
        $response->assertSee('Administrateur');
        $response->assertSee('sidebar-collapse-btn');
        $response->assertSee('profile-card__btn--logout');

        // Dashboard greeting bar checks
        $response->assertSee('greeting-bar');
        $response->assertSee('Catalogue');

        // KPI checks
        $response->assertSee('+12% ce mois');
        $response->assertSee('stat-card__sparkline');
        $response->assertSee('stat-card__icon--glow');
    }

    public function test_orders_page_renders_status_pills_and_product_preview(): void
    {
        $user = User::factory()->create([
            'name' => 'Abdrahman',
            'email' => 'abdrahman@gmail.com',
        ]);

        $client = Client::factory()->create(['nom_client' => 'Entreprise Test']);
        $produit = Produit::factory()->create(['designation' => 'Cahier Grand Format']);
        $commande = Commande::factory()->create([
            'code_client' => $client->code_client,
            'condition' => 'LIV',
        ]);
        $commande->produits()->sync([$produit->code_produit => ['quantite' => 3]]);

        $response = $this->actingAs($user)->get(route('commandes.index'));

        $response->assertStatus(200);

        // Status pill check
        $response->assertSee('status-pill');
        $response->assertSee('status-pill__dot');
        $response->assertSee('Livraison');

        // Product preview check
        $response->assertSee('order-card__preview');
        $response->assertSee('Cahier Grand Format');
    }
}
