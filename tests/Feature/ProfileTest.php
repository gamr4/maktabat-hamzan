<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile(): void
    {
        $this->get(route('profile.show'))->assertRedirect(route('login'));
    }

    public function test_every_role_can_access_their_profile(): void
    {
        $users = [
            User::factory()->admin()->create(['name' => 'Alice Admin']),
            User::factory()->gestionnaire()->create(['name' => 'Bob Gestionnaire']),
            User::factory()->client()->create(['name' => 'Charlie Client']),
        ];

        foreach ($users as $user) {
            $response = $this->actingAs($user)->get(route('profile.show'));

            $response->assertOk();
            $response->assertSee($user->name);
            $response->assertSee($user->email);
            $response->assertSee($user->role_label);
        }
    }

    public function test_user_can_update_name_and_email(): void
    {
        $user = User::factory()->admin()->create([
            'name' => 'Ancien Nom',
            'email' => 'ancien@papeterie.test',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nouveau Nom',
            'email' => 'nouveau@papeterie.test',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nouveau Nom',
            'email' => 'nouveau@papeterie.test',
        ]);
    }

    public function test_user_cannot_take_another_users_email(): void
    {
        User::factory()->create(['email' => 'already@papeterie.test']);
        $user = User::factory()->admin()->create(['email' => 'mine@papeterie.test']);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Mon Nom',
            'email' => 'already@papeterie.test',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'mine@papeterie.test',
        ]);
    }

    public function test_user_can_change_password(): void
    {
        $user = User::factory()->admin()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function test_password_change_requires_the_current_password(): void
    {
        $user = User::factory()->admin()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('old-password', $user->refresh()->password));
    }

    public function test_password_confirmation_must_match(): void
    {
        $user = User::factory()->admin()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('old-password', $user->refresh()->password));
    }

    public function test_client_only_sees_their_own_linked_client_accounts(): void
    {
        $clientUser = User::factory()->client()->create();
        $otherUser = User::factory()->client()->create();

        Client::factory()->create([
            'user_id' => $clientUser->id,
            'nom_client' => 'Ma Societe',
        ]);

        Client::factory()->create([
            'user_id' => $otherUser->id,
            'nom_client' => 'Autre Societe',
        ]);

        $response = $this->actingAs($clientUser)->get(route('profile.show'));

        $response->assertOk();
        $response->assertSee('Ma Societe');
        $response->assertDontSee('Autre Societe');
    }

    public function test_admin_sees_supplier_privileges_but_gestionnaire_does_not(): void
    {
        $admin = User::factory()->admin()->create();
        $gestionnaire = User::factory()->gestionnaire()->create();

        $this->actingAs($admin)->get(route('profile.show'))
            ->assertOk()
            ->assertSee('Accès au répertoire des fournisseurs.');

        $this->actingAs($gestionnaire)->get(route('profile.show'))
            ->assertOk()
            ->assertSee('Accès opérationnel sans gestion des fournisseurs.')
            ->assertDontSee('Accès au répertoire des fournisseurs.');
    }

    public function test_sidebar_contains_profile_link_for_every_role(): void
    {
        $users = [
            User::factory()->admin()->create(),
            User::factory()->gestionnaire()->create(),
            User::factory()->client()->create(),
        ];

        foreach ($users as $user) {
            $this->actingAs($user)->get(route('dashboard'))
                ->assertOk()
                ->assertSee(route('profile.show'));
        }
    }

    public function test_legacy_portal_profile_redirects_to_profile(): void
    {
        $user = User::factory()->client()->create();

        $this->actingAs($user)->get('/portail/profil')
            ->assertRedirect(route('profile.show'));
    }
}
