<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $data = ['user' => $user];

        if ($user->isClient()) {
            $clients = $user->clients()->withCount('commandes')->get();
            $commandes = Commande::forUser($user)
                ->with(['client', 'produits'])
                ->get();

            $data['clients'] = $clients;
            $data['clientSummary'] = [
                'commandes' => $commandes->count(),
                'total_depense' => $commandes->sum(fn (Commande $commande) => $commande->montantNet()),
                'remise' => (int) ($clients->max('remise_speciale') ?? 0),
            ];
        } else {
            $data['platformStats'] = [
                'clients' => Client::count(),
                'produits' => Produit::count(),
                'commandes' => Commande::count(),
                'fournisseurs' => $user->isAdmin() ? Fournisseur::count() : null,
            ];
        }

        return view('profile.index', $data);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'name.required' => 'Le nom complet est obligatoire.',
            'email.required' => "L'adresse e-mail est obligatoire.",
            'email.email' => "L'adresse e-mail n'est pas valide.",
            'email.unique' => 'Cette adresse e-mail est déjà utilisée par un autre compte.',
        ]);

        $user->update($validated);

        return back()->with('success', 'Vos informations personnelles ont été mises à jour.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.min' => 'Le nouveau mot de passe doit comporter au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}
