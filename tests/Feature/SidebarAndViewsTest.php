<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarAndViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_displays_fournisseurs_only_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $gestionnaire = User::factory()->gestionnaire()->create();

        // Admin should see Fournisseurs link
        $adminResponse = $this->actingAs($admin)->get(route('dashboard'));
        $adminResponse->assertOk();
        $adminResponse->assertSee(route('fournisseurs.index'));

        // Gestionnaire should not see Fournisseurs link
        $gestionnaireResponse = $this->actingAs($gestionnaire)->get(route('dashboard'));
        $gestionnaireResponse->assertOk();
        $gestionnaireResponse->assertDontSee(route('fournisseurs.index'));
    }

    public function test_profile_card_shows_dynamic_role_label(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Alice Admin']);
        $gestionnaire = User::factory()->gestionnaire()->create(['name' => 'Bob Gestionnaire']);
        $client = User::factory()->client()->create(['name' => 'Charlie Client']);

        $this->actingAs($admin)->get(route('dashboard'))
            ->assertSee('Administrateur');

        $this->actingAs($gestionnaire)->get(route('dashboard'))
            ->assertSee('Gestionnaire');

        $this->actingAs($client)->get(route('dashboard'))
            ->assertSee('Client');
    }

    public function test_dashboard_renders_role_specific_partials(): void
    {
        $admin = User::factory()->admin()->create();
        $gestionnaire = User::factory()->gestionnaire()->create();
        $client = User::factory()->client()->create();

        // Admin dashboard partial has "Console de gestion opérationnelle (Admin)"
        $this->actingAs($admin)->get(route('dashboard'))
            ->assertSee('Console de gestion opérationnelle (Admin)');

        // Gestionnaire partial has "Console de gestion opérationnelle (Gestionnaire)"
        $this->actingAs($gestionnaire)->get(route('dashboard'))
            ->assertSee('Console de gestion opérationnelle (Gestionnaire)');

        // Client partial has "Bienvenue sur votre espace client"
        $this->actingAs($client)->get(route('dashboard'))
            ->assertSee('Bienvenue sur votre espace client');
    }
}
