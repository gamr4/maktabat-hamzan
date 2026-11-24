<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_accesses_own_dashboard_and_sees_scoped_data(): void
    {
        $clientUser = User::factory()->client()->create(['name' => 'Hamza Client']);
        $otherUser = User::factory()->client()->create(['name' => 'Other Client']);

        $clientRecord = Client::factory()->create([
            'user_id' => $clientUser->id,
            'nom_client' => 'Societe Mon Client',
        ]);

        $otherClientRecord = Client::factory()->create([
            'user_id' => $otherUser->id,
            'nom_client' => 'Societe Autre Client',
        ]);

        $fournisseur = Fournisseur::factory()->create();
        $produit = Produit::factory()->create(['code_fournisseur' => $fournisseur->code_fournisseur]);

        $orderMine = Commande::factory()->create([
            'code_client' => $clientRecord->code_client,
            'numero_commande' => 'Cmd-MINE-001',
            'condition' => 'LIV',
        ]);
        $orderMine->produits()->sync([$produit->code_produit => ['quantite' => 2]]);

        $orderOther = Commande::factory()->create([
            'code_client' => $otherClientRecord->code_client,
            'numero_commande' => 'Cmd-OTHER-999',
            'condition' => 'T30',
        ]);
        $orderOther->produits()->sync([$produit->code_produit => ['quantite' => 5]]);

        // Client gets their dedicated client dashboard partial at /dashboard
        $response = $this->actingAs($clientUser)->get(route('dashboard'));
        $response->assertOk();
        $response->assertSee('Societe Mon Client');
        $response->assertSee('Cmd-MINE-001');
        $response->assertDontSee('Cmd-OTHER-999');
        $response->assertSee('Bienvenue sur votre espace client');
    }

    public function test_client_dashboard_renders_organized_layout_and_kpis(): void
    {
        $clientUser = User::factory()->client()->create(['name' => 'Sara Alami']);
        $clientRecord = Client::factory()->create([
            'user_id' => $clientUser->id,
            'nom_client' => 'Alami Editions',
            'remise_speciale' => 15,
        ]);

        $fournisseur = Fournisseur::factory()->create();
        $produit = Produit::factory()->create([
            'code_fournisseur' => $fournisseur->code_fournisseur,
            'prix_unitaire' => 100,
            'designation' => 'Ramette Papier A4',
        ]);

        $order = Commande::factory()->create([
            'code_client' => $clientRecord->code_client,
            'numero_commande' => 'Cmd-ALAMI-100',
            'condition' => 'LIV',
        ]);
        $order->produits()->sync([$produit->code_produit => ['quantite' => 4]]);

        $response = $this->actingAs($clientUser)->get(route('dashboard'));

        $response->assertOk();
        // Layout structure
        $response->assertSee('client-dashboard-layout');
        $response->assertSee('client-dashboard-main');
        $response->assertSee('client-dashboard-aside');

        // Cohesive KPIs
        $response->assertSee('Mes commandes');
        $response->assertSee('Total des achats');
        $response->assertSee('Économies réalisées');
        $response->assertDontSee('Comptes rattachés');

        // Order table elements & actions
        $response->assertSee('Cmd-ALAMI-100');
        $response->assertSee(route('commandes.show', 'Cmd-ALAMI-100'));
        $response->assertSee('Détails');
        $response->assertSee('Ramette Papier A4');

        // Scoped entity & removed cards verified
        $response->assertSee('Alami Editions');
        $response->assertDontSee('Conditions commerciales');
        $response->assertDontSee('Facturation simplifiée');
        $response->assertDontSee('Mes fiches clients rattachées');
    }

    public function test_client_login_redirects_to_dashboard(): void
    {
        $clientUser = User::factory()->client()->create([
            'email' => 'client@papeterie.test',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'client@papeterie.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_admin_login_redirects_to_dashboard(): void
    {
        $adminUser = User::factory()->admin()->create([
            'email' => 'admin@papeterie.test',
            'password' => 'password',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@papeterie.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_portal_routes_redirect_to_dashboard(): void
    {
        $clientUser = User::factory()->client()->create();

        $response = $this->actingAs($clientUser)->get('/portail');
        $response->assertRedirect(route('dashboard'));
    }

    public function test_admin_can_create_account_for_client_entity(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create([
            'user_id' => null,
            'nom_client' => 'Entreprise Sans Compte',
        ]);

        $this->assertNull($client->user_id);

        $response = $this->actingAs($admin)->post(route('clients.account', $client->code_client), [
            'email' => 'custom.email@papeterie.test',
            'password' => 'secret123',
        ]);

        $response->assertSessionHas('success');

        $client->refresh();
        $this->assertNotNull($client->user_id);
        $this->assertEquals('custom.email@papeterie.test', $client->user->email);
        $this->assertEquals(User::ROLE_CLIENT, $client->user->role);
        $this->assertTrue(Hash::check('secret123', $client->user->password));
    }

    public function test_admin_can_reset_password_for_existing_client_account(): void
    {
        $admin = User::factory()->admin()->create();
        $existingUser = User::factory()->client()->create([
            'email' => 'hamza@papeterie.test',
            'password' => bcrypt('oldpassword'),
        ]);

        $client = Client::factory()->create([
            'user_id' => $existingUser->id,
            'nom_client' => 'Hamza Entreprise',
        ]);

        $response = $this->actingAs($admin)->post(route('clients.account', $client->code_client), [
            'password' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');

        $existingUser->refresh();
        $this->assertTrue(Hash::check('newpassword123', $existingUser->password));
    }

    public function test_client_index_displays_user_account_status(): void
    {
        $admin = User::factory()->admin()->create();
        $clientWithAccount = Client::factory()->create(['nom_client' => 'Client Avec']);
        $clientUser = User::factory()->client()->create(['email' => 'avec@papeterie.test']);
        $clientWithAccount->update(['user_id' => $clientUser->id]);

        $response = $this->actingAs($admin)->get(route('clients.index'));
        $response->assertOk();
        $response->assertSee('avec@papeterie.test');
    }
}
