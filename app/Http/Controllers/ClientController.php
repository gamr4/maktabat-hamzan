<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    protected function checkStaffAccess(): void
    {
        if (! auth()->check() || ! in_array(auth()->user()->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true)) {
            abort(403, 'Accès non autorisé : rôle insuffisant.');
        }
    }

    public function index(Request $request)
    {
        $this->checkStaffAccess();

        $search = trim((string) $request->input('q'));
        $type = $request->input('type');

        $clients = Client::query()
            ->with('user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nom_client', 'like', "%{$search}%")
                        ->orWhere('code_client', 'like', "%{$search}%")
                        ->orWhere('ville_client', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$search}%"));
                });
            })
            ->when(in_array($type, ['PU', 'PA', 'LI', 'AS', 'DI'], true), fn ($query) => $query->where('type_client', $type))
            ->orderBy('code_client')
            ->paginate(12)
            ->withQueryString();

        return view('clients.index', compact('clients', 'search', 'type'));
    }

    public function create()
    {
        $this->checkStaffAccess();

        return view('clients.create');
    }

    public function store(Request $request)
    {
        $this->checkStaffAccess();

        $data = $request->validate([
            'nom_client' => ['required', 'string', 'max:255'],
            'rue_client' => ['required', 'string', 'max:255'],
            'code_postal_client' => ['required', 'string', 'max:20'],
            'ville_client' => ['required', 'string', 'max:255'],
            'type_client' => ['required', 'in:PU,PA,LI,AS,DI'],
            'remise_speciale' => ['required', 'integer', 'min:0', 'max:100'],
            'user_id' => ['nullable', 'exists:users,id'],
            'create_account' => ['nullable', 'boolean'],
            'account_email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
        ]);

        $createAccount = $request->boolean('create_account') || ! empty($request->input('account_email'));
        $accountEmail = $request->input('account_email');

        $client = Client::create($data);

        if ($createAccount && ! $client->user_id) {
            $email = $accountEmail ?: (strtolower($client->code_client) . '@papeterie.test');
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

        return redirect()->route('clients.index')->with('success', 'Client ajouté.');
    }

    public function show(string $id)
    {
        $this->checkStaffAccess();

        $client = Client::with(['commandes', 'user'])->findOrFail($id);

        return view('clients.show', compact('client'));
    }

    public function edit(string $id)
    {
        $this->checkStaffAccess();

        $client = Client::findOrFail($id);

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, string $id)
    {
        $this->checkStaffAccess();

        $client = Client::findOrFail($id);
        $client->update($request->validate([
            'nom_client' => ['required', 'string', 'max:255'],
            'rue_client' => ['required', 'string', 'max:255'],
            'code_postal_client' => ['required', 'string', 'max:20'],
            'ville_client' => ['required', 'string', 'max:255'],
            'type_client' => ['required', 'in:PU,PA,LI,AS,DI'],
            'remise_speciale' => ['required', 'integer', 'min:0', 'max:100'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]));

        return redirect()->route('clients.index')->with('success', 'Client mis à jour.');
    }

    public function destroy(string $id)
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Seul un administrateur peut supprimer un client.');
        }

        $client = Client::findOrFail($id);
        if ($client->commandes()->exists()) {
            return back()->withErrors('Ce client a des commandes et ne peut pas être supprimé.');
        }
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client supprimé.');
    }

    public function createAccount(Request $request, string $id)
    {
        $this->checkStaffAccess();

        $client = Client::findOrFail($id);

        $request->validate([
            'email' => ['nullable', 'email'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $email = $request->input('email') ?: (strtolower($client->code_client) . '@papeterie.test');
        $password = $request->input('password') ?: 'password';

        if ($client->user) {
            $client->user->update([
                'password' => Hash::make($password),
            ]);
            $msg = "Mot de passe réinitialisé avec succès pour {$client->user->email}.";
        } else {
            // Check if user with email already exists
            $user = User::where('email', $email)->first();
            if ($user) {
                $client->update(['user_id' => $user->id]);
                $msg = "Client rattaché au compte existant {$email}.";
            } else {
                $user = User::create([
                    'name' => $client->nom_client,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => User::ROLE_CLIENT,
                ]);
                $client->update(['user_id' => $user->id]);
                $msg = "Compte utilisateur créé avec succès ({$email}).";
            }
        }

        return back()->with('success', $msg);
    }

    /* =========================================================================
     *  CLIENT PORTAL PROXY METHODS (kept for backwards compatibility)
     * ========================================================================= */

    public function portalIndex()
    {
        return app(PortailController::class)->index();
    }

    public function portalCommandes(Request $request)
    {
        return app(PortailController::class)->commandes($request);
    }

    public function portalCommandeShow(string $id)
    {
        return app(PortailController::class)->commandeShow($id);
    }

    public function portalProfile()
    {
        return app(PortailController::class)->profile();
    }
}
