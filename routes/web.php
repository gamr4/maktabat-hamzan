<?php

use App\Http\Controllers\{
    AuthController,
    ClientController,
    CommandeController,
    DashboardController,
    FournisseurController,
    PortailController,
    ProduitController,
    ProfileController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return view('landing');
    }

    return redirect()->route('dashboard');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Self-service profile for every role
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Client portal: profile only (order history moved to dashboard/sidebar)
    Route::middleware('role:client')->group(function () {
        Route::get('/portail/profil', [PortailController::class, 'profile'])->name('portail.profil');
    });

    // Order creation & details: staff use it normally, clients are scoped to their own account
    Route::middleware('role:admin,gestionnaire,client')->group(function () {
        Route::get('commandes/create', [CommandeController::class, 'create'])->name('commandes.create');
        Route::post('commandes', [CommandeController::class, 'store'])->name('commandes.store');
        Route::get('commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
    });

    // Legacy portal profile link
    Route::redirect('/portail/profil', '/profil')->name('portail.profil');

    // Management routes: Admin & Gestionnaire
    Route::middleware('role:admin,gestionnaire')->group(function () {
        Route::resource('clients', ClientController::class);
        Route::post('clients/{client}/account', [ClientController::class, 'createAccount'])->name('clients.account');
        Route::resource('produits', ProduitController::class);
        Route::resource('commandes', CommandeController::class)->except(['create', 'store', 'show']);
    });

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('fournisseurs', FournisseurController::class);
    });

    // Portal routes redirect cleanly to the role dashboard
    Route::any('/portail/{any?}', function () {
        return redirect()->route('dashboard');
    })->where('any', '.*')->name('portail.dashboard');
});
