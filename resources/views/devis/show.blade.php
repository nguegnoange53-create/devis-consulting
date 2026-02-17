@extends('layouts.sidebar')

@section('title', 'Détails du Devis - YA Consulting')

@section('styles')
<style>
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
    .info-group { background: #f9fafb; border-radius: 12px; padding: 20px; }
    .info-group h3 { margin-top: 0; color: #1e1b4b; border-bottom: 2px solid #e5e7eb; padding-bottom: 12px; font-size: 1em; margin-bottom: 16px; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding: 4px 0; }
    .info-label { font-weight: 600; color: #6b7280; font-size: 0.88em; }
    .info-value { color: #1f2937; font-size: 0.92em; }

    .totals-section { margin-top: 30px; display: flex; justify-content: flex-end; }
    .totals-box { background: linear-gradient(135deg, #f9fafb, #f3f4f6); padding: 24px; border-radius: 14px; width: 320px; border: 2px solid #e5e7eb; }
    .total-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.92em; color: #374151; }
    .total-row.final { font-size: 1.15em; font-weight: 700; color: #4f46e5; border-top: 2px solid #c7d2fe; padding-top: 12px; margin-top: 12px; }

    @media (max-width: 700px) { .info-grid { grid-template-columns: 1fr; } }

    @media print {
        .sidebar, .mobile-toggle, .btn-group, .page-header .badge { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
        body { background: white; }
        .content-card { box-shadow: none; border: none; padding: 0; margin: 0; }
        .page-header { margin-bottom: 20px; }
        .totals-box { background: transparent; border: none; }
    }
</style>
@endsection

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1>Devis #{{ $devis->numero }}</h1>
            <span class="badge {{ $devis->statut === 'accepte' ? 'badge-success' : ($devis->statut === 'refuse' ? 'badge-danger' : 'badge-warning') }}" style="margin-top: 8px;">
                {{ ucfirst($devis->statut) }}
            </span>
        </div>
        <div class="btn-group">
            <a href="{{ route('devis.index') }}" class="btn btn-secondary">← Retour</a>
            <a href="{{ route('devis.edit', $devis->id) }}" class="btn btn-warning">✏️ Éditer</a>
            <a href="{{ route('devis.download', $devis->id) }}" class="btn btn-info">📥 PDF</a>
            <button onclick="window.print()" class="btn btn-primary" style="background: linear-gradient(135deg, #4f46e5, #3730a3);">🖨 Imprimer le Document</button>
            <form action="{{ route('devis.transformer', $devis->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Transformer ce devis en facture ?');">
                @csrf
                <button type="submit" class="btn btn-success">⚡ Convertir en Facture</button>
            </form>
        </div>
    </div>

    <!-- Logo et Informations de l'entreprise -->
    <div class="content-card" style="margin-bottom: 20px;">
        <div style="display: flex; align-items: flex-start;">
            <div style="flex: 1;">
                @if(isset($settings) && $settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo {{ $settings->nom_entreprise ?? 'YA Consulting' }}" style="max-height: 100px; max-width: 200px; margin-bottom: 15px;">
                @endif
                <div style="margin-top: 10px;">
                    <strong style="font-size: 1.1em; color: #1e1b4b;">{{ $settings->nom_entreprise ?? 'YA Consulting' }}</strong><br>
                    <span style="color: #6b7280;">{{ $settings->adresse ?? 'Adresse non définie' }}</span><br>
                    @if(isset($settings->email) && $settings->email)
                        <span style="color: #6b7280;">{{ $settings->email }}</span><br>
                    @endif
                    @if(isset($settings->telephone) && $settings->telephone)
                        <span style="color: #6b7280;">{{ $settings->telephone }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Client & Info -->
    <div class="content-card">
        <div class="info-grid">
            <div class="info-group">
                <h3>👤 Informations Client</h3>
                @if($devis->client->logo)
                    <div style="margin-bottom: 15px;">
                        <img src="{{ asset('storage/' . $devis->client->logo) }}" alt="Logo {{ $devis->client->raison_sociale }}" style="max-height: 80px; border-radius: 8px; border: 1px solid #e5e7eb;">
                    </div>
                @endif
                <div class="info-row"><span class="info-label">Client</span> <span class="info-value">{{ $devis->client->raison_sociale }}</span></div>
                <div class="info-row"><span class="info-label">Email</span> <span class="info-value">{{ $devis->client->email ?? 'N/A' }}</span></div>
                <div class="info-row"><span class="info-label">Téléphone</span> <span class="info-value">{{ $devis->client->telephone ?? 'N/A' }}</span></div>
                <div class="info-row"><span class="info-label">Adresse</span> <span class="info-value">{{ $devis->client->adresse ?? 'N/A' }}</span></div>
            </div>
            <div class="info-group">
                <h3>📄 Détails du Devis</h3>
                <div class="info-row"><span class="info-label">Date d'émission</span> <span class="info-value">{{ \Carbon\Carbon::parse($devis->date_emission)->format('d/m/Y') }}</span></div>
                <div class="info-row"><span class="info-label">Créé le</span> <span class="info-value">{{ $devis->created_at->format('d/m/Y H:i') }}</span></div>
            </div>
        </div>

        <!-- Lignes du devis -->
        <h3 style="color: #1e1b4b; margin-bottom: 8px;">🛒 Articles / Services</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Désignation</th>
                    <th style="width: 15%; text-align: center;">Quantité</th>
                    <th style="width: 15%; text-align: right;">Prix Unitaire</th>
                    <th style="width: 20%; text-align: right;">Total HT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($devis->lignes as $ligne)
                <tr>
                    <td><strong>{{ $ligne->designation }}</strong></td>
                    <td style="text-align: center;">{{ $ligne->quantite }}</td>
                    <td style="text-align: right;">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td style="text-align: right;"><strong>{{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="totals-section">
            <div class="totals-box">
                <div class="total-row">
                    <span>Total HT</span>
                    <span>{{ number_format($devis->total_ht, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="total-row">
                    <span>TVA (18%)</span>
                    <span>{{ number_format($devis->total_tva, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="total-row final">
                    <span>Total TTC</span>
                    <span>{{ number_format($devis->total_ttc, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('print')) {
            window.print();
        }
    }
</script>
@endsection
