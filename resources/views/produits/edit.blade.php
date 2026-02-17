@extends('layouts.sidebar')

@section('title', 'Modifier le Produit - YA Consulting')

@section('content')
    <div class="page-header">
        <h1>Modifier le Produit / Service</h1>
        <a href="{{ route('produits.index') }}" class="btn btn-secondary">← Retour au catalogue</a>
    </div>

    <div class="content-card">
        <h3 style="color: #1e1b4b; margin-top: 0; margin-bottom: 24px; font-size: 1.1em;">✏️ Modifier : {{ $produit->designation }}</h3>
        <form action="{{ route('produits.update', $produit->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Désignation *</label>
                <input type="text" name="designation" value="{{ old('designation', $produit->designation) }}" required placeholder="Ex: Ordinateur portable, Installation réseau">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Prix Unitaire HT (FCFA) *</label>
                    <input type="number" step="0.01" name="prix_unitaire_ht" value="{{ old('prix_unitaire_ht', $produit->prix_unitaire_ht) }}" required placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Type *</label>
                    <select name="type" required>
                        <option value="produit" {{ (old('type', $produit->type) == 'produit') ? 'selected' : '' }}>📦 Produit</option>
                        <option value="service" {{ (old('type', $produit->type) == 'service') ? 'selected' : '' }}>⚙️ Service</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Description (Optionnelle)</label>
                <textarea name="description" rows="3" placeholder="Détails supplémentaires...">{{ old('description', $produit->description) }}</textarea>
            </div>

            <div class="btn-group" style="margin-top: 28px;">
                <button type="submit" class="btn btn-success btn-lg">✓ Mettre à jour</button>
                <a href="{{ route('produits.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
