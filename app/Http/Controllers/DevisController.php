<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Client;
use App\Models\Produit;
use App\Models\DocumentLigne;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

class DevisController extends Controller
{
    // Affiche la liste des devis [cite: 46]
    public function index()
    {
        $devis = Document::where('type', 'devis')->with('client')->get();
        return view('devis.index', compact('devis'));
    }

    // Affiche la liste des factures
    public function facturesIndex()
    {
        $factures = Document::where('type', 'facture')->with('client')->get();
        return view('factures.index', compact('factures'));
    }

    // Affiche le formulaire de création [cite: 47]
    public function create()
    {
        $clients = Client::all();
        $produits = Produit::all();
        $devis = Document::where('type', 'devis')->with('client')->get();
        return view('devis.create', compact('clients', 'produits', 'devis'));
    }

    // Enregistre le devis — formulaire tout-en-un
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // ---- 1. Résoudre le Client ----
            if ($request->client_mode === 'new') {
                $request->validate([
                    'new_raison_sociale' => 'required|string',
                    'new_telephone' => 'required|string',
                    'new_email' => 'required|email',
                    'new_adresse' => 'required|string',
                    'new_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);

                $logoPath = null;
                if ($request->hasFile('new_logo')) {
                    $logoPath = $request->file('new_logo')->store('clients/logos', 'public');
                }

                $client = Client::create([
                    'raison_sociale' => $request->new_raison_sociale,
                    'telephone' => $request->new_telephone,
                    'email' => $request->new_email,
                    'adresse' => $request->new_adresse,
                    'rccm_cc' => $request->new_rccm_cc,
                    'logo' => $logoPath,
                    'user_id' => auth()->id(),
                ]);
            } else {
                $request->validate([
                    'client_id' => [
                        'required',
                        Rule::exists('clients', 'id')->where(function ($query) {
                            return $query->where('user_id', auth()->id());
                        }),
                    ],
                ]);
                $client = Client::findOrFail($request->client_id);
            }

            // ---- 2. Calculer les totaux à partir des lignes ----
            $total_ht = 0;
            $lignesData = [];

            foreach ($request->lignes as $ligne) {
                if ($request->product_mode === 'new') {
                    // Saisie directe : désignation + prix tapés par l'utilisateur
                    $designation = $ligne['designation'] ?? '';
                    $quantite = floatval($ligne['quantite'] ?? 1);
                    $prix_unitaire = floatval($ligne['prix_unitaire'] ?? 0);

                    if (empty($designation) || $prix_unitaire <= 0) continue;

                    // Créer aussi le produit dans le catalogue pour réutilisation future
                    $produit = Produit::firstOrCreate(
                        ['designation' => $designation],
                        [
                            'prix_unitaire_ht' => $prix_unitaire,
                            'type' => $ligne['type'] ?? 'produit',
                            'user_id' => auth()->id(),
                        ]
                    );
                } else {
                    // Depuis le catalogue
                    $produit_id = $ligne['produit_id'] ?? null;
                    if (!$produit_id) continue;

                    $produit = Produit::find($produit_id);
                    if (!$produit) continue;

                    $designation = $produit->designation;
                    $quantite = floatval($ligne['quantite_cat'] ?? 1);
                    $prix_unitaire = floatval($ligne['prix_unitaire_cat'] ?? $produit->prix_unitaire_ht);
                }

                $total_ht += $quantite * $prix_unitaire;
                $lignesData[] = [
                    'designation' => $designation,
                    'quantite' => $quantite,
                    'prix_unitaire' => $prix_unitaire,
                ];
            }

            if (empty($lignesData)) {
                DB::rollback();
                return back()->with('error', 'Veuillez ajouter au moins un article avec une désignation et un prix.');
            }

            $taux_tva = 0.18;
            $total_tva = $total_ht * $taux_tva;
            $total_ttc = $total_ht + $total_tva;

            // ---- 3. Créer le document Devis ----
            $devis = Document::create([
                'type' => 'devis',
                'numero' => 'DEV-' . now()->format('Ymd') . '-' . rand(100, 999),
                'client_id' => $client->id,
                'date_emission' => $request->date_emission,
                'objet' => $request->objet,
                'titre_document' => $request->titre_document,
                'taux_tva' => 18,
                'statut' => 'Brouillon',
                'total_ht' => $total_ht,
                'total_tva' => $total_tva,
                'total_ttc' => $total_ttc,
                'user_id' => auth()->id(),
            ]);

            // ---- 4. Créer les lignes du document ----
            foreach ($lignesData as $data) {
                DocumentLigne::create([
                    'document_id' => $devis->id,
                    'designation' => $data['designation'],
                    'quantite' => $data['quantite'],
                    'prix_unitaire' => $data['prix_unitaire'],
                ]);
            }

            DB::commit();
            return redirect()->route('devis.index')->with('success', 'Devis créé avec succès ! Le client et les produits ont été enregistrés.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    // Exporter les factures en CSV
    public function exportFactures()
    {
        $fileName = 'factures_export_' . date('Y-m-d') . '.csv';
        $factures = Document::where('type', 'facture')->with('client')->orderBy('date_emission', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Numéro', 'Client', 'Date Emission', 'Total HT', 'Total TVA', 'Total TTC', 'Statut'];

        $callback = function() use($factures, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';'); // Excel prefers semicolon in some regions

            foreach ($factures as $facture) {
                $row['Numéro']  = $facture->numero;
                $row['Client']    = $facture->client->raison_sociale ?? 'Inconnu';
                $row['Date']    = $facture->date_emission;
                $row['HT']  = $facture->total_ht;
                $row['TVA']  = $facture->total_tva;
                $row['TTC']  = $facture->total_ttc;
                $row['Statut']  = $facture->statut;

                fputcsv($file, array_values($row), ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function download($id) { $devis = \App\Models\Document::with(['client', 'lignes'])->findOrFail($id);
     $settings = \App\Models\Setting::first(); 
     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('devis.pdf', compact('devis', 'settings')); 
     return $pdf->download('Devis_' . $devis->numero . '.pdf'); }

     public function transformerEnFacture($id)
     {
         $devis = \App\Models\Document::findOrFail($id);

         // Vérifier si la facture existe déjà
         $facture_numero = str_replace('DEV', 'FAC', $devis->numero);
         
         if (Document::where('numero', $facture_numero)->exists()) {
             return redirect()->route('devis.index')->with('error', 'Une facture a déjà été générée pour ce devis !');
         }

         // On crée la facture en copiant le devis
         $facture = $devis->replicate();
    $facture->type = 'facture';
    $facture->numero = $facture_numero;
    $facture->statut = 'en attente';
    $facture->titre_document = 'FACTURE';
    $facture->save();

         // On copie aussi toutes les lignes du devis vers la facture
         foreach ($devis->lignes as $ligne) {
             $nouvelleLigne = $ligne->replicate();
             $nouvelleLigne->document_id = $facture->id;
             $nouvelleLigne->save();
         }

         return redirect()->route('devis.index')->with('success', 'Facture générée avec succès !');
     }

// Affiche un devis spécifique
public function show($id)
{
    $devis = Document::with(['client', 'lignes'])->findOrFail($id);
    $settings = \App\Models\Setting::first();
    return view('devis.show', compact('devis', 'settings'));
}

// Affiche le formulaire d'édition
public function edit($id)
{
    $devis = Document::with('lignes')->findOrFail($id);
    $clients = Client::all();
    $produits = Produit::all();
    return view('devis.edit', compact('devis', 'clients', 'produits'));
}

// Met à jour un devis existant
public function update(Request $request, $id)
{
    $request->validate([
        'client_id' => [
            'required',
            Rule::exists('clients', 'id')->where(function ($query) {
                return $query->where('user_id', auth()->id());
            }),
        ],
        'date_emission' => 'required|date',
        'lignes' => 'required|array|min:1',
        'lignes.*.produit_id' => 'required',
        'lignes.*.quantite' => 'required|numeric|min:1',
        'lignes.*.prix_unitaire' => 'required|numeric',
    ]);

    try {
        DB::beginTransaction();

        $devis = Document::findOrFail($id);

        // Recalcul des totaux
        $total_ht = 0;
        foreach ($request->lignes as $ligne) {
            $total_ht += $ligne['quantite'] * $ligne['prix_unitaire'];
        }

        $taux_tva = 0.18;
        $total_tva = $total_ht * $taux_tva;
        $total_ttc = $total_ht + $total_tva;

        // Mise à jour du document
        $devis->update([
            'client_id' => $request->client_id,
            'date_emission' => $request->date_emission,
            'objet' => $request->objet,
            'lieu' => $request->lieu,
            'titre_document' => $request->titre_document,
            'total_ht' => $total_ht,
            'total_tva' => $total_tva,
            'total_ttc' => $total_ttc,
        ]);

        // Supprime les anciennes lignes et recrée les nouvelles
        $devis->lignes()->delete();

        foreach ($request->lignes as $ligne) {
            $produit = Produit::find($ligne['produit_id']);
            DocumentLigne::create([
                'document_id' => $devis->id,
                'designation' => $produit->designation,
                'quantite' => $ligne['quantite'],
                'prix_unitaire' => $ligne['prix_unitaire'],
            ]);
        }

        DB::commit();
        return redirect()->route('devis.index')->with('success', 'Devis mis à jour avec succès !');

    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
    }
}

// Supprime un devis
public function destroy($id)
{
    $devis = Document::findOrFail($id);
    $devis->lignes()->delete();
    $devis->delete();
    
    return redirect()->route('devis.index')->with('success', 'Devis supprimé avec succès !');
}
public function marquerCommePaye($id) 
{
    $facture = \App\Models\Document::findOrFail($id);
    $facture->statut = 'payé';
    $facture->save();

    return redirect()->route('factures.index')->with('success', 'Facture marquée comme payée !');
}
}

