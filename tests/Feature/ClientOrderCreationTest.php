<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientOrderCreationTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(): Produit
    {
        $fournisseur = Fournisseur::factory()->create();

        return Produit::factory()->create([
            'code_fournisseur' => $fournisseur->code_fournisseur,
            'designation' => 'Cahier 96 pages',
            'prix_unitaire' => 12.5,
        ]);
    }

    public function test_client_uses_the_same_order_form_as_staff(): void
    {
        $user = User::factory()->client()->create();
        Client::factory()->create([
            'user_id' => $user->id,
            'nom_client' => 'Mon Entreprise',
        ]);
        $this->makeProduct();

        $response = $this->actingAs($user)->get(route('commandes.create'));

        $response->assertOk();
        $response->assertSee('Nouvelle commande');
        $response->assertSee('Mon Entreprise');
        $response->assertSee('Cahier 96 pages');
        $response->assertSee('Cette commande sera créée automatiquement pour votre compte.');
        $response->assertDontSee('Choisir un client');
        $response->assertDontSee('name="code_client"');
    }

    public function test_client_can_create_an_order_for_their_own_client_account(): void
    {
        $user = User::factory()->client()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);
        $produit = $this->makeProduct();

        $response = $this->actingAs($user)->post(route('commandes.store'), [
            'date_commande' => now()->toDateString(),
            'condition' => 'LIV',
            'produits' => [
                ['code_produit' => $produit->code_produit, 'quantite' => 3],
            ],
        ]);

        $commande = Commande::firstOrFail();

        $response->assertRedirect(route('commandes.show', $commande->getKey()));
        $response->assertSessionHas('success');

        $this->assertSame($client->code_client, $commande->code_client);
        $this->assertSame('LIV', $commande->condition);
        $this->assertDatabaseHas('ligne_commandes', [
            'numero_commande' => $commande->numero_commande,
            'code_produit' => $produit->code_produit,
            'quantite' => 3,
        ]);
    }

    public function test_client_order_always_uses_their_own_account_even_when_code_client_is_tampered_with(): void
    {
        $user = User::factory()->client()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $otherClient = Client::factory()->create(['user_id' => null]);
        $produit = $this->makeProduct();

        $response = $this->actingAs($user)->post(route('commandes.store'), [
            'date_commande' => now()->toDateString(),
            'condition' => 'LIV',
            'code_client' => $otherClient->code_client,
            'produits' => [
                ['code_produit' => $produit->code_produit, 'quantite' => 1],
            ],
        ]);

        $commande = Commande::firstOrFail();

        $response->assertRedirect(route('commandes.show', $commande->getKey()));
        $this->assertSame($client->code_client, $commande->code_client);
        $this->assertNotSame($otherClient->code_client, $commande->code_client);
    }

    public function test_client_can_view_their_own_order_but_not_another_clients_order(): void
    {
        $user = User::factory()->client()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $otherClient = Client::factory()->create(['user_id' => null]);
        $produit = $this->makeProduct();

        $ownOrder = Commande::factory()->create([
            'code_client' => $client->code_client,
            'numero_commande' => 'Cmd-MINE-001',
        ]);
        $ownOrder->produits()->sync([$produit->code_produit => ['quantite' => 1]]);

        $otherOrder = Commande::factory()->create([
            'code_client' => $otherClient->code_client,
            'numero_commande' => 'Cmd-OTHER-001',
        ]);
        $otherOrder->produits()->sync([$produit->code_produit => ['quantite' => 1]]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Cmd-MINE-001')
            ->assertDontSee('Cmd-OTHER-001');

        $this->actingAs($user)
            ->get(route('commandes.show', $ownOrder->getKey()))
            ->assertOk()
            ->assertSee('Cmd-MINE-001')
            ->assertDontSee('Modifier');

        $this->actingAs($user)
            ->get(route('commandes.show', $otherOrder->getKey()))
            ->assertNotFound();
    }

    public function test_client_without_a_linked_account_is_redirected_from_creation(): void
    {
        $user = User::factory()->client()->create();

        $response = $this->actingAs($user)->get(route('commandes.create'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHasErrors();
    }

    public function test_staff_can_still_create_an_order_for_a_chosen_client(): void
    {
        $admin = User::factory()->admin()->create();
        $chosenClient = Client::factory()->create();
        $produit = $this->makeProduct();

        $response = $this->actingAs($admin)->post(route('commandes.store'), [
            'date_commande' => now()->toDateString(),
            'condition' => 'T30',
            'code_client' => $chosenClient->code_client,
            'produits' => [
                ['code_produit' => $produit->code_produit, 'quantite' => 2],
            ],
        ]);

        $commande = Commande::firstOrFail();

        $response->assertRedirect(route('commandes.show', $commande->getKey()));
        $this->assertSame($chosenClient->code_client, $commande->code_client);
    }
}
