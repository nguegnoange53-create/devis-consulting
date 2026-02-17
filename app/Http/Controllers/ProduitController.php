<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produits = Produit::orderBy('designation')->paginate(20);

        return view('produits.index', compact('produits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Afficher le formulaire de création avec la liste des produits existants
        $produits = Produit::orderBy('designation')->get();
        return view('produits.create', compact('produits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'designation' => 'required|string',
            'prix_unitaire_ht' => 'required|numeric',
            'type' => 'required|in:produit,service',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        \App\Models\Produit::create($validated);

        return redirect()->route('produits.index')->with('success', 'Produit ajouté !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produit = Produit::findOrFail($id);
        return view('produits.edit', compact('produit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produit = Produit::findOrFail($id);

        $validated = $request->validate([
            'designation' => 'required|string',
            'prix_unitaire_ht' => 'required|numeric',
            'type' => 'required|in:produit,service',
            'description' => 'nullable|string',
        ]);

        $produit->update($validated);

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès !');
    }
}
