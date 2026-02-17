@extends('layouts.sidebar')

@section('title', 'Modifier Client - ' . $client->raison_sociale)

@section('content')
    <div class="page-header">
        <h1>✏️ Modifier : {{ $client->raison_sociale }}</h1>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">← Annuler</a>
    </div>

    <div class="content-card">
        <form action="{{ route('clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Raison sociale *</label>
                <input type="text" name="raison_sociale" value="{{ old('raison_sociale', $client->raison_sociale) }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="{{ old('email', $client->email) }}" required>
                </div>
                <div class="form-group">
                    <label>Téléphone *</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $client->telephone) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Adresse *</label>
                <textarea name="adresse" rows="3" required>{{ old('adresse', $client->adresse) }}</textarea>
            </div>

            <div class="form-group">
                <label>RCCM / Compte Contribuable</label>
                <input type="text" name="rccm_cc" value="{{ old('rccm_cc', $client->rccm_cc) }}">
            </div>

            <div class="btn-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary btn-lg">💾 Enregistrer les modifications</button>
            </div>
        </form>
    </div>
@endsection
