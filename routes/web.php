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

    // Legacy portal profile links
    Route::redirect('/portail/profil', '/profil')->name('portail.profil');

    // Management routes: Admin & Gestionnaire
    Route::middleware('role:admin,gestionnaire')->group(function () {
        Route::resource('clients', ClientController::class);
        Route::post('clients/{client}/account', [ClientController::class, 'createAccount'])->name('clients.account');
        Route::resource('produits', ProduitController::class);
        Route::resource('commandes', CommandeController::class);
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
