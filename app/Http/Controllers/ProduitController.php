<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    protected function checkStaffAccess(): void
    {
        if (! auth()->check() || ! in_array(auth()->user()->role, [User::ROLE_ADMIN, User::ROLE_GESTIONNAIRE], true)) {
            abort(403, 'Accès non autorisé : rôle insuffisant.');
        }
    }

    public function index()
    {
        $this->checkStaffAccess();

        $search = trim((string) request('q'));
        $famille = request('famille');

        $produits = Produit::with('fournisseur')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('designation', 'like', "%{$search}%")
                        ->orWhere('code_produit', 'like', "%{$search}%")
                        ->orWhereHas('fournisseur', fn ($supplier) => $supplier->where('nom_fournisseur', 'like', "%{$search}%"));
                });
            })
            ->when(in_array($famille, ['PA', 'BU', 'FO', 'LI'], true), fn ($query) => $query->where('famille', $famille))
            ->orderBy('code_produit')
            ->paginate(12)
            ->withQueryString();

        return view('produits.index', compact('produits', 'search', 'famille'));
    }

    public function create()
    {
        $this->checkStaffAccess();

        $fournisseurs = Fournisseur::orderBy('nom_fournisseur')->get();

        return view('produits.create', compact('fournisseurs'));
    }

    public function store(Request $request)
    {
        $this->checkStaffAccess();

        Produit::create($request->validate([
            'designation' => ['required', 'string', 'max:255'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
            'famille' => ['required', 'in:PA,BU,FO,LI'],
            'code_fournisseur' => ['required', 'exists:fournisseurs,code_fournisseur'],
        ]));

        return redirect()->route('produits.index')->with('success', 'Produit ajouté.');
    }

    public function show(string $id)
    {
        $this->checkStaffAccess();

        $produit = Produit::with('fournisseur')->findOrFail($id);

        return view('produits.show', compact('produit'));
    }

    public function edit(string $id)
    {
        $this->checkStaffAccess();

        $produit = Produit::findOrFail($id);
        $fournisseurs = Fournisseur::orderBy('nom_fournisseur')->get();

        return view('produits.edit', compact('produit', 'fournisseurs'));
    }

    public function update(Request $request, string $id)
    {
        $this->checkStaffAccess();

        $produit = Produit::findOrFail($id);
        $produit->update($request->validate([
            'designation' => ['required', 'string', 'max:255'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
            'famille' => ['required', 'in:PA,BU,FO,LI'],
            'code_fournisseur' => ['required', 'exists:fournisseurs,code_fournisseur'],
        ]));

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(string $id)
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Seul un administrateur peut supprimer un produit.');
        }

        $produit = Produit::findOrFail($id);
        if ($produit->commandes()->exists()) {
            return back()->withErrors('Ce produit est utilisé dans des commandes et ne peut pas être supprimé.');
        }
        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé.');
    }
}
