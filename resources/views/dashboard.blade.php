@extends('layouts.sidebar')

@section('title', 'Tableau de Bord - YA Consulting')

@section('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
    }

    .stat-card:nth-child(1)::before { background: linear-gradient(90deg, #667eea, #764ba2); }
    .stat-card:nth-child(2)::before { background: linear-gradient(90deg, #f093fb, #f5576c); }
    .stat-card:nth-child(3)::before { background: linear-gradient(90deg, #4ade80, #06b6d4); }
    .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #fbbf24, #f59e0b); }

    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .stat-card .stat-icon { font-size: 2em; margin-bottom: 12px; }
    .stat-card .stat-label { color: #6b7280; font-size: 0.8em; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-card .stat-value { font-size: 1.8em; font-weight: 700; color: #1e1b4b; margin-top: 4px; }

    .progress-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        margin-bottom: 32px;
    }
    .progress-section h3 { color: #374151; font-size: 1em; font-weight: 600; margin-bottom: 12px; }
    .progress-bar-bg { background: #e5e7eb; border-radius: 8px; overflow: hidden; height: 12px; }
    .progress-bar-fill { height: 12px; background: linear-gradient(90deg, #4ade80, #06b6d4); border-radius: 8px; transition: width 0.8s ease; }
    .progress-label { color: #6b7280; font-size: 0.85em; margin-top: 8px; }

    .section-title { font-size: 1.2em; font-weight: 700; color: #1e1b4b; margin-bottom: 20px; }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .action-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }
    .action-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }

    .action-card-icon {
        padding: 32px 20px;
        text-align: center;
        font-size: 3em;
        min-height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .action-card:nth-child(1) .action-card-icon { background: linear-gradient(135deg, #667eea, #764ba2); }
    .action-card:nth-child(2) .action-card-icon { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .action-card:nth-child(3) .action-card-icon { background: linear-gradient(135deg, #4ade80, #22d3ee); }

    .action-card-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .action-card-body h3 { font-size: 1.1em; color: #1e1b4b; margin-bottom: 6px; }
    .action-card-body p { color: #6b7280; font-size: 0.85em; line-height: 1.5; flex-grow: 1; }

    .action-card-footer {
        padding: 14px 20px;
        border-top: 1px solid #f3f4f6;
        color: #667eea;
        font-weight: 600;
        font-size: 0.85em;
        transition: all 0.2s ease;
    }
    .action-card:hover .action-card-footer { color: #4338ca; padding-left: 24px; }

    .main-footer { text-align: center; color: #9ca3af; padding-top: 24px; border-top: 1px solid #e5e7eb; font-size: 0.8em; }

    @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 500px) { .stats-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
    <!-- Top Bar -->
    <div class="page-header">
        <div>
            <h1>Tableau de Bord</h1>
            <p class="page-header-sub">Bienvenue, {{ Auth::user()->name ?? 'Utilisateur' }} 👋</p>
        </div>
        <div>
            <a href="{{ route('devis.create') }}" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9em;">
                + Nouveau Devis
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📄</div>
            <div class="stat-label">Total Devis</div>
            <div class="stat-value">{{ $totalDevis ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🧾</div>
            <div class="stat-label">Total Factures</div>
            <div class="stat-value">{{ $totalFactures ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-label">CA Prévu</div>
            <div class="stat-value">{{ number_format($CA_Prevu ?? 0, 0, ',', ' ') }} <span style="font-size: 0.4em; color: #6b7280;">FCFA</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-label">CA Encaissé</div>
            <div class="stat-value">{{ number_format($CA_Encaisse ?? 0, 0, ',', ' ') }} <span style="font-size: 0.4em; color: #6b7280;">FCFA</span></div>
        </div>
    </div>

    <!-- Progress -->
    <div class="progress-section">
        <h3>Progression des devis transformés en factures</h3>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width: {{ $percentTransformed ?? 0 }}%;"></div>
        </div>
        <p class="progress-label">{{ $percentTransformed ?? 0 }}% des devis transformés en factures</p>
    </div>

    <!-- Factures en Retard -->
    @if(isset($facturesEnRetard) && $facturesEnRetard->count() > 0)
    <div class="content-card" style="border-left: 4px solid #ef4444; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h2 style="color: #991b1b; font-size: 1.1em; margin: 0; display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-block; width: 10px; height: 10px; background: #ef4444; border-radius: 50%; animation: pulse 1.5s infinite;"></span>
                🚨 Factures en Retard
                <span class="badge badge-danger">{{ $facturesEnRetard->count() }}</span>
            </h2>
            <a href="{{ route('factures.index') }}" class="btn btn-danger btn-sm">Voir toutes les factures →</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Date d'émission</th>
                    <th>Retard</th>
                    <th>Montant TTC</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facturesEnRetard as $facture)
                <tr>
                    <td><strong>{{ $facture->numero }}</strong></td>
                    <td>{{ $facture->client->raison_sociale ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') }}</td>
                    <td>
                        @php $jours = \Carbon\Carbon::parse($facture->date_emission)->diffInDays(now()); @endphp
                        <span class="badge {{ $jours > 30 ? 'badge-danger' : 'badge-warning' }}">
                            {{ $jours }} jours
                        </span>
                    </td>
                    <td><strong>{{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA</strong></td>
                    <td>
                        <form action="{{ route('facture.payer', $facture->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">💰 Marquer payée</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <style>@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }</style>
    </div>
    @endif

    <!-- Quick Actions -->
    <h2 class="section-title">Accès Rapide</h2>
    <div class="actions-grid">
        <a href="{{ route('devis.create') }}" class="action-card">
            <div class="action-card-icon">✨</div>
            <div class="action-card-body">
                <h3>Créer un Devis</h3>
                <p>Créez rapidement un nouveau devis professionnel.</p>
            </div>
            <div class="action-card-footer">Créer maintenant →</div>
        </a>
        <a href="{{ route('devis.index') }}" class="action-card">
            <div class="action-card-icon">📋</div>
            <div class="action-card-body">
                <h3>Gérer les Devis</h3>
                <p>Consultez, éditez et suivez vos devis existants.</p>
            </div>
            <div class="action-card-footer">Voir les devis →</div>
        </a>
        <a href="{{ route('factures.index') }}" class="action-card">
            <div class="action-card-icon">🧾</div>
            <div class="action-card-body">
                <h3>Gérer les Factures</h3>
                <p>Suivez vos factures et leur statut de paiement.</p>
            </div>
            <div class="action-card-footer">Voir les factures →</div>
        </a>
    </div>

    <div class="main-footer">
        <p>© 2026 YA Consulting - Logiciel de Facturation | Version 1.0</p>
    </div>
@endsection
