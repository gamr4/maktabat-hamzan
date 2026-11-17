<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_guests_can_see_the_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Toute la papeterie tient dans');
        $response->assertSee('Accéder à la console');
        $response->assertSee('Clients');
        $response->assertSee('Fournisseurs');
        $response->assertSee('Produits');
        $response->assertSee('Commandes');
    }

    public function test_authenticated_users_are_redirected_to_the_dashboard(): void
    {
        $user = new User([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('dashboard'));
    }
}
