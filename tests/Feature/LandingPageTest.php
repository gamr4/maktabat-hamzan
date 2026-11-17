<?php

namespace Tests\Feature;

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
}
