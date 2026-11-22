<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleInfrastructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_defaults_to_admin_role(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('admin', $user->role);
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isGestionnaire());
        $this->assertFalse($user->isClient());
    }

    public function test_admin_can_access_all_management_routes(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('clients.index'))->assertOk();
        $this->actingAs($admin)->get(route('produits.index'))->assertOk();
        $this->actingAs($admin)->get(route('commandes.index'))->assertOk();
        $this->actingAs($admin)->get(route('fournisseurs.index'))->assertOk();
    }

    public function test_gestionnaire_can_access_operations_but_forbidden_from_fournisseurs(): void
    {
        $gestionnaire = User::factory()->gestionnaire()->create();

        $this->actingAs($gestionnaire)->get(route('dashboard'))->assertOk();
        $this->actingAs($gestionnaire)->get(route('clients.index'))->assertOk();
        $this->actingAs($gestionnaire)->get(route('produits.index'))->assertOk();
        $this->actingAs($gestionnaire)->get(route('commandes.index'))->assertOk();

        // Fournisseurs is restricted to admin only
        $this->actingAs($gestionnaire)->get(route('fournisseurs.index'))->assertForbidden();
    }

    public function test_client_is_forbidden_from_all_admin_management_routes(): void
    {
        $client = User::factory()->client()->create();

        $this->actingAs($client)->get(route('clients.index'))->assertForbidden();
        $this->actingAs($client)->get(route('produits.index'))->assertForbidden();
        $this->actingAs($client)->get(route('commandes.index'))->assertForbidden();
        $this->actingAs($client)->get(route('fournisseurs.index'))->assertForbidden();
    }
}
