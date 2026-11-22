<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerGuardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_gestionnaire_cannot_delete_client(): void
    {
        $gestionnaire = User::factory()->gestionnaire()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($gestionnaire)->delete(route('clients.destroy', $client->getKey()));

        $response->assertForbidden();
        $this->assertDatabaseHas('clients', ['code_client' => $client->code_client]);
    }

    public function test_admin_can_delete_client_without_orders(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($admin)->delete(route('clients.destroy', $client->getKey()));

        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseMissing('clients', ['code_client' => $client->code_client]);
    }

    public function test_gestionnaire_cannot_delete_produit(): void
    {
        $gestionnaire = User::factory()->gestionnaire()->create();
        $fournisseur = Fournisseur::factory()->create();
        $produit = Produit::factory()->create(['code_fournisseur' => $fournisseur->code_fournisseur]);

        $response = $this->actingAs($gestionnaire)->delete(route('produits.destroy', $produit->getKey()));

        $response->assertForbidden();
        $this->assertDatabaseHas('produits', ['code_produit' => $produit->code_produit]);
    }

    public function test_gestionnaire_cannot_delete_commande(): void
    {
        $gestionnaire = User::factory()->gestionnaire()->create();
        $client = Client::factory()->create();
        $commande = Commande::factory()->create(['code_client' => $client->code_client]);

        $response = $this->actingAs($gestionnaire)->delete(route('commandes.destroy', $commande->getKey()));

        $response->assertForbidden();
        $this->assertDatabaseHas('commandes', ['numero_commande' => $commande->numero_commande]);
    }
}
