<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Page d'accueil publique

Route::get('/debug', function () {
    return [
        'path' => request()->path(),
        'url' => request()->url(),
        'root' => request()->root(),
        'fullUrl' => request()->fullUrl(),
    ];
});

Route::get('/', function () {
    return view('welcome');
});


// TOUTES LES ROUTES SÉCURISÉES (DOIVENT ÊTRE ICI)
Route::middleware('auth')->group(function () {

    // Tableau de bord après connexion
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


    // Routes spécifiques pour les devis (avant la ressource pour éviter les conflits)
    Route::get('/devis/{id}/download', [DevisController::class, 'download'])->name('devis.download');
    Route::post('/devis/{id}/transformer', [DevisController::class, 'transformerEnFacture'])->name('devis.transformer');
    Route::get('/factures', [DevisController::class, 'facturesIndex'])->name('factures.index');
    Route::get('/factures/export', [DevisController::class, 'exportFactures'])->name('factures.export');
    Route::get('/factures/{id}/download', [DevisController::class, 'download'])->name('factures.download');
    
    // Gestion des ressources métier
    Route::resource('clients', ClientController::class);
    Route::resource('produits', ProduitController::class);
    Route::resource('devis', DevisController::class);

    // Paramètres de l'entreprise (Logo, Adresse, etc.)
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Gestion du compte utilisateur (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/facture/{id}/payer', [DevisController::class, 'marquerCommePaye'])->name('facture.payer');
});

require __DIR__.'/auth.php';