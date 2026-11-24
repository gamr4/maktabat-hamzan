<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PortailController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        // Scoped to the authenticated user's linked clients
        $clients = $user->clients()->withCount('commandes')->get();
        $clientCodes = $clients->pluck('code_client');

        $latestCommandes = Commande::whereIn('code_client', $clientCodes)
            ->with(['client', 'produits'])
            ->latest('date_commande')
            ->take(5)
            ->get();

        $totalCommandesCount = Commande::whereIn('code_client', $clientCodes)->count();

        // Calculate total spent efficiently using database aggregation
        $totalSpent = DB::table('ligne_commandes')
            ->join('commandes', 'commandes.numero_commande', '=', 'ligne_commandes.numero_commande')
            ->join('produits', 'produits.code_produit', '=', 'ligne_commandes.code_produit')
            ->whereIn('commandes.code_client', $clientCodes)
            ->selectRaw('SUM(ligne_commandes.quantite * produits.prix_unitaire) as total')
            ->value('total') ?? 0;

        // Calculate month-over-month real trends
        $startThisMonth = now()->startOfMonth()->toDateString();
        $startLastMonth = now()->subMonth()->startOfMonth()->toDateString();
        $endLastMonth = now()->subMonth()->endOfMonth()->toDateString();

        $commandesThisMonth = Commande::whereIn('code_client', $clientCodes)
            ->where('date_commande', '>=', $startThisMonth)
            ->count();

        $commandesLastMonth = Commande::whereIn('code_client', $clientCodes)
            ->whereBetween('date_commande', [$startLastMonth, $endLastMonth])
            ->count();

        if ($commandesLastMonth > 0) {
            $diffPct = round((($commandesThisMonth - $commandesLastMonth) / $commandesLastMonth) * 100);
            $trendOrdersLabel = ($diffPct >= 0 ? '+' : '') . $diffPct . '% ce mois';
            $trendOrdersType = $diffPct > 0 ? 'up' : ($diffPct < 0 ? 'down' : 'neutral');
        } elseif ($commandesThisMonth > 0) {
            $trendOrdersLabel = "+{$commandesThisMonth} ce mois";
            $trendOrdersType = 'up';
        } else {
            $trendOrdersLabel = 'Historique des commandes';
            $trendOrdersType = 'neutral';
        }

        $spentThisMonth = DB::table('ligne_commandes')
            ->join('commandes', 'commandes.numero_commande', '=', 'ligne_commandes.numero_commande')
            ->join('produits', 'produits.code_produit', '=', 'ligne_commandes.code_produit')
            ->whereIn('commandes.code_client', $clientCodes)
            ->where('commandes.date_commande', '>=', $startThisMonth)
            ->selectRaw('SUM(ligne_commandes.quantite * produits.prix_unitaire) as total')
            ->value('total') ?? 0;

        $spentLastMonth = DB::table('ligne_commandes')
            ->join('commandes', 'commandes.numero_commande', '=', 'ligne_commandes.numero_commande')
            ->join('produits', 'produits.code_produit', '=', 'ligne_commandes.code_produit')
            ->whereIn('commandes.code_client', $clientCodes)
            ->whereBetween('commandes.date_commande', [$startLastMonth, $endLastMonth])
            ->selectRaw('SUM(ligne_commandes.quantite * produits.prix_unitaire) as total')
            ->value('total') ?? 0;

        if ($spentLastMonth > 0) {
            $diffSpentPct = round((($spentThisMonth - $spentLastMonth) / $spentLastMonth) * 100);
            $trendSpentLabel = ($diffSpentPct >= 0 ? '+' : '') . $diffSpentPct . '% ce mois';
            $trendSpentType = $diffSpentPct > 0 ? 'up' : ($diffSpentPct < 0 ? 'down' : 'neutral');
        } elseif ($spentThisMonth > 0) {
            $trendSpentLabel = 'Activité ce mois';
            $trendSpentType = 'up';
        } else {
            $trendSpentLabel = 'Montant cumulé TTC';
            $trendSpentType = 'neutral';
        }

        $stats = [
            'commandes' => $totalCommandesCount,
            'clients' => $clients->count(),
            'total_depense' => (float) $totalSpent,
            'trend_commandes_label' => $trendOrdersLabel,
            'trend_commandes_type' => $trendOrdersType,
            'trend_depense_label' => $trendSpentLabel,
            'trend_depense_type' => $trendSpentType,
        ];

        return view('portail.dashboard', compact('clients', 'latestCommandes', 'stats'));
    }

    public function commandeShow(string $id)
    {
        $user = auth()->user();

        // Scope to user orders directly to prevent unauthorized timing leaks
        $commande = Commande::forUser($user)
            ->with(['client', 'produits.fournisseur'])
            ->find($id);

        if (! $commande) {
            // Check if commande exists for another client -> 403
            if (Commande::where('numero_commande', $id)->exists()) {
                abort(403, 'Accès non autorisé à cette commande.');
            }
            abort(404, 'Commande introuvable.');
        }

        return view('portail.commande-show', compact('commande'));
    }

    public function profile()
    {
        $user = auth()->user();
        $clients = $user->clients()->withCount('commandes')->get();

        return view('portail.profil', compact('user', 'clients'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.min' => 'Le nouveau mot de passe doit comporter au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}
