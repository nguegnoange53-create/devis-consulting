@extends('layouts.sidebar')

@section('title', 'Paramètres - YA Consulting')

@section('styles')
<style>
    .logo-preview { max-width: 120px; margin-bottom: 12px; border-radius: 10px; border: 2px solid #e5e7eb; }
</style>
@endsection

@section('content')
    <div class="page-header">
        <h1>⚙️ Paramètres de l'entreprise</h1>
    </div>

    <div class="content-card">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nom de l'entreprise</label>
                <input type="text" name="nom_entreprise" value="{{ $settings->nom_entreprise ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Adresse physique</label>
                <textarea name="adresse" rows="3" required>{{ $settings->adresse ?? '' }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="tel" name="telephone" value="{{ $settings->telephone ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $settings->email ?? '' }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>RCCM/CC (optionnel)</label>
                <input type="text" name="rccm_cc" value="{{ $settings->rccm_cc ?? '' }}">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Taux TVA par défaut (%)</label>
                    <input type="number" name="tva_defaut" value="{{ $settings->tva_defaut ?? 18.00 }}" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Devise</label>
                    <input type="text" name="devise" value="{{ $settings->devise ?? 'FCFA' }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Logo de l'entreprise</label>
                @if(isset($settings->logo))
                    <img src="{{ asset('storage/' . $settings->logo) }}" class="logo-preview" alt="Logo actuel">
                @endif
                <input type="file" name="logo">
            </div>

            <div class="form-group">
                <label>Cachet / Tampon (affiché sur les factures)</label>
                @if(isset($settings->cachet))
                    <img src="{{ asset('storage/' . $settings->cachet) }}" class="logo-preview" alt="Cachet actuel">
                @endif
                <input type="file" name="cachet">
            </div>

            <button type="submit" class="btn btn-primary btn-lg">💾 Enregistrer les modifications</button>
        </form>
    </div>
@endsection