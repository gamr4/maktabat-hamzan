<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FournisseurController extends Controller
{
    protected function checkPermission(): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé : réservé aux administrateurs.');
        }
    }

    public function index(Request $request)
    {
        $this->checkPermission();

        $search = trim((string) $request->input('q'));

        $fournisseurs = Fournisseur::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nom_fournisseur', 'like', "%{$search}%")
                        ->orWhere('code_fournisseur', 'like', "%{$search}%")
                        ->orWhere('ville_fournisseur', 'like', "%{$search}%")
                        ->orWhere('pays_fournisseur', 'like', "%{$search}%");
                });
            })
            ->orderBy('code_fournisseur')
            ->paginate(12)
            ->withQueryString();

        return view('fournisseurs.index', compact('fournisseurs', 'search'));
    }

    public function create()
    {
        $this->checkPermission();

        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $this->checkPermission();

        Fournisseur::create($request->validate([
            'nom_fournisseur' => ['required', 'string', 'max:255'],
            'rue_fournisseur' => ['required', 'string', 'max:255'],
            'code_postal_fournisseur' => ['required', 'string', 'max:20'],
            'ville_fournisseur' => ['required', 'string', 'max:255'],
            'pays_fournisseur' => ['nullable', 'string', 'max:255'],
            'tel_fournisseur' => ['nullable', 'string', 'max:255'],
            'contact_fournisseur' => ['nullable', 'string', 'max:255'],
        ]));

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur ajouté.');
    }

    public function show(string $id)
    {
        $this->checkPermission();

        $fournisseur = Fournisseur::with('produits')->findOrFail($id);

        return view('fournisseurs.show', compact('fournisseur'));
    }

    public function edit(string $id)
    {
        $this->checkPermission();

        $fournisseur = Fournisseur::findOrFail($id);

        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, string $id)
    {
        $this->checkPermission();

        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->update($request->validate([
            'nom_fournisseur' => ['required', 'string', 'max:255'],
            'rue_fournisseur' => ['required', 'string', 'max:255'],
            'code_postal_fournisseur' => ['required', 'string', 'max:20'],
            'ville_fournisseur' => ['required', 'string', 'max:255'],
            'pays_fournisseur' => ['nullable', 'string', 'max:255'],
            'tel_fournisseur' => ['nullable', 'string', 'max:255'],
            'contact_fournisseur' => ['nullable', 'string', 'max:255'],
        ]));

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur mis à jour.');
    }

    public function destroy(string $id)
    {
        $this->checkPermission();

        $fournisseur = Fournisseur::findOrFail($id);
        if ($fournisseur->produits()->exists()) {
            return back()->withErrors('Ce fournisseur a des produits et ne peut pas être supprimé.');
        }
        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }
}
