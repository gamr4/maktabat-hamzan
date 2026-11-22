<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed one user per role for testing
        $admin = User::firstOrCreate(
            ['email' => 'abdrahman@gmail.com'],
            [
                'name' => 'Abdrahman',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );
        $admin->update(['role' => User::ROLE_ADMIN]);

        $adminAlt = User::firstOrCreate(
            ['email' => 'admin@papeterie.test'],
            [
                'name' => 'Administrateur Papeterie',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );
        $adminAlt->update(['role' => User::ROLE_ADMIN]);

        $gestionnaire = User::firstOrCreate(
            ['email' => 'gestionnaire@papeterie.test'],
            [
                'name' => 'Sarah Gestionnaire',
                'password' => Hash::make('password'),
                'role' => User::ROLE_GESTIONNAIRE,
            ]
        );
        $gestionnaire->update(['role' => User::ROLE_GESTIONNAIRE]);

        $clientUser = User::firstOrCreate(
            ['email' => 'client@papeterie.test'],
            [
                'name' => 'Hamza Client',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CLIENT,
            ]
        );
        $clientUser->update(['name' => 'Hamza Client', 'role' => User::ROLE_CLIENT]);

        // 2. Seed business resources
        // Ensure Hamza Client exists in clients list
        $hamzaClient = Client::where('user_id', $clientUser->id)->first();
        if (! $hamzaClient) {
            $firstClient = Client::first();
            if ($firstClient) {
                $firstClient->update([
                    'nom_client' => 'Hamza Client',
                    'user_id' => $clientUser->id,
                ]);
            } else {
                Client::factory()->create([
                    'nom_client' => 'Hamza Client',
                    'user_id' => $clientUser->id,
                ]);
            }
        } else {
            $hamzaClient->update(['nom_client' => 'Hamza Client']);
        }

        if (Client::count() < 10) {
            Client::factory()->count(10 - Client::count())->create();
        }

        // Ensure every client has a linked user account
        foreach (Client::whereNull('user_id')->get() as $client) {
            $email = strtolower($client->code_client) . '@papeterie.test';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $client->nom_client,
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_CLIENT,
                ]
            );
            $client->update(['user_id' => $user->id]);
        }

        if (Fournisseur::count() === 0) {
            Fournisseur::factory()->count(5)->create();
        }

        if (Produit::count() === 0) {
            Produit::factory()->count(25)->create();
        }

        if (Commande::count() === 0) {
            Commande::factory()->count(15)->create();
        }
    }
}
