<?php

namespace App\Http\Controllers;

use App\Models\Document;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord avec les statistiques
     */
    public function index()
    {
        // Nombre total de devis
        $totalDevis = Document::where('type', 'devis')->count();

        // Nombre total de factures
        $totalFactures = Document::where('type', 'facture')->count();

        // Chiffre d'affaires prévu (total des factures)
        $CA_Prevu = Document::where('type', 'facture')->sum('total_ttc');

        // Chiffre d'affaires encaissé (factures payées)
        $CA_Encaisse = Document::where('type', 'facture')
            ->where('statut', 'payé')
            ->sum('total_ttc');

        // Calcul du pourcentage de devis transformés en factures
        $devisTransformes = $totalFactures; // hypothèse: chaque facture correspond à un devis transformé
        $percentTransformed = 0;
        if ($totalDevis > 0) {
            $percentTransformed = round(($devisTransformes / $totalDevis) * 100, 1);
            if ($percentTransformed > 100) {
                $percentTransformed = 100;
            }
        }
        // Factures en retard (> 7 jours et non payées)
        $facturesEnRetard = Document::where('type', 'facture')
            ->where('statut', '!=', 'payé')
            ->where('date_emission', '<', now()->subDays(7))
            ->with('client')
            ->orderBy('date_emission', 'asc')
            ->get();

        return view('dashboard', compact('totalDevis', 'totalFactures', 'CA_Prevu', 'CA_Encaisse', 'percentTransformed', 'facturesEnRetard'));
    }
}
