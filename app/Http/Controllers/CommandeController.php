<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CommandeController extends Controller
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
        $condition = $request->input('condition');

        $commandes = Commande::with(['client', 'produits'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('numero_commande', 'like', "%{$search}%")
                        ->orWhereHas('client', fn ($client) => $client->where('nom_client', 'like', "%{$search}%"));
                });
            })
            ->when(in_array($condition, ['LIV', 'T30'], true), fn ($query) => $query->where('condition', $condition))
            ->orderByDesc('date_commande')
            ->orderByDesc('numero_commande')
            ->paginate(12)
            ->withQueryString();

        return view('commandes.index', compact('commandes', 'search', 'condition'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->isClient()) {
            $client = $this->clientAccountFor($user);

            if (! $client) {
                return redirect()
                    ->route('dashboard')
                    ->withErrors("Votre compte n'est rattaché à aucune fiche client. Veuillez contacter la papeterie.");
            }

            return view('commandes.create', [
                'clients' => collect([$client]),
                'produits' => Produit::orderBy('designation')->get(),
                'lockedClient' => $client,
                'cancelRoute' => route('dashboard'),
                'submitLabel' => 'Envoyer la commande',
                'showAdminLinks' => false,
            ]);
        }

        $this->checkStaffAccess();

        return view('commandes.create', [
            'clients' => Client::orderBy('nom_client')->get(),
            'produits' => Produit::orderBy('designation')->get(),
            'lockedClient' => null,
            'cancelRoute' => route('commandes.index'),
            'submitLabel' => 'Enregistrer',
            'showAdminLinks' => true,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $lockedClient = null;

        if ($user->isClient()) {
            $lockedClient = $this->clientAccountFor($user);

            if (! $lockedClient) {
                return redirect()
                    ->route('dashboard')
                    ->withErrors("Votre compte n'est rattaché à aucune fiche client. Veuillez contacter la papeterie.");
            }
        } else {
            $this->checkStaffAccess();
        }

        $data = $this->validateCommande($request, $lockedClient === null);

        // Clients always order for their own account, regardless of submitted data.
        $data['code_client'] = $lockedClient?->code_client ?? $data['code_client'];

        $commande = DB::transaction(function () use ($data) {
            $commande = Commande::create([
                'date_commande' => $data['date_commande'],
                'condition' => $data['condition'],
                'code_client' => $data['code_client'],
            ]);

            $commande->produits()->sync(
                $this->formatProduits($data['produits'])
            );

            return $commande;
        });

        return redirect()
            ->route('commandes.show', $commande->getKey())
            ->with('success', 'Commande créée avec succès.');
    }

    public function show(string $commande)
    {
        $user = auth()->user();
        $isClientView = $user->isClient();

        if ($isClientView) {
            $commande = Commande::forUser($user)
                ->with(['client', 'produits'])
                ->findOrFail($commande);
        } else {
            $this->checkStaffAccess();
            $commande = Commande::with(['client', 'produits'])
                ->findOrFail($commande);
        }

        return view('commandes.show', compact('commande', 'isClientView'));
    }

    public function edit(string $commande)
    {
        $this->checkStaffAccess();

        $commande = Commande::with('produits')
            ->findOrFail($commande);

        return view('commandes.edit', [
            'commande' => $commande,
            'clients' => Client::orderBy('nom_client')->get(),
            'produits' => Produit::orderBy('designation')->get(),
        ]);
    }

    public function update(Request $request, string $commande)
    {
        $this->checkStaffAccess();

        $commande = Commande::findOrFail($commande);
        $data = $this->validateCommande($request);

        DB::transaction(function () use ($commande, $data) {
            $commande->update([
                'date_commande' => $data['date_commande'],
                'condition' => $data['condition'],
                'code_client' => $data['code_client'],
            ]);

            $commande->produits()->sync(
                $this->formatProduits($data['produits'])
            );
        });

        return redirect()
            ->route('commandes.show', $commande->getKey())
            ->with('success', 'Commande modifiée avec succès.');
    }

    public function destroy(string $commande)
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Seul un administrateur peut supprimer une commande.');
        }

        $commande = Commande::findOrFail($commande);

        DB::transaction(function () use ($commande) {
            $commande->produits()->detach();
            $commande->delete();
        });

        return redirect()
            ->route('commandes.index')
            ->with('success', 'Commande supprimée avec succès.');
    }

    private function clientAccountFor(User $user): ?Client
    {
        return $user->clients()->orderBy('code_client')->first();
    }

    private function validateCommande(Request $request, bool $requireClient = true): array
    {
        $rules = [
            'date_commande' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'condition' => [
                'required',
                Rule::in(['LIV', 'T30']),
            ],
            'produits' => [
                'required',
                'array',
                'min:1',
            ],
            'produits.*.code_produit' => [
                'required',
                'distinct',
                Rule::exists('produits', 'code_produit'),
            ],
            'produits.*.quantite' => [
                'required',
                'integer',
                'min:1',
            ],
        ];

        if ($requireClient) {
            $rules['code_client'] = [
                'required',
                Rule::exists('clients', 'code_client'),
            ];
        }

        return $request->validate($rules);
    }

    private function formatProduits(array $produits): array
    {
        $result = [];

        foreach ($produits as $produit) {
            $result[$produit['code_produit']] = [
                'quantite' => $produit['quantite'],
            ];
        }

        return $result;
    }
}
