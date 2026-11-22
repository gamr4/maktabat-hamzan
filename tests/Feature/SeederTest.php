<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_users_for_all_roles(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'abdrahman@gmail.com',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'gestionnaire@papeterie.test',
            'role' => 'gestionnaire',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'client@papeterie.test',
            'role' => 'client',
        ]);

        $clientUser = User::where('email', 'client@papeterie.test')->first();
        $this->assertNotNull($clientUser);
        $this->assertTrue($clientUser->clients()->exists());
    }
}
