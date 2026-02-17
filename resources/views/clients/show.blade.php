@extends('layouts.sidebar')

@section('title', 'Détails Client - ' . $client->raison_sociale)

@section('content')
    <div class="page-header">
        <h1>👤 {{ $client->raison_sociale }}</h1>
        <div class="btn-group">
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">← Retour</a>
            <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">✏️ Modifier</a>
        </div>
    </div>

    <div class="content-card">
        <h3 style="color: #1e1b4b; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px;">Informations Générales</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <p><strong>Email :</strong> {{ $client->email }}</p>
                <p><strong>Téléphone :</strong> {{ $client->telephone }}</p>
            </div>
            <div>
                <p><strong>Adresse :</strong> {{ $client->adresse }}</p>
                <p><strong>RCCM / CC :</strong> {{ $client->rccm_cc ?? 'Non renseigné' }}</p>
            </div>
        </div>
    </div>

    <div class="content-card">
        <h3 style="color: #1e1b4b; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px;">📜 Historique des Devis</h3>
        @if($devis->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Date</th>
                        <th>Montant TTC</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devis as $d)
                        <tr>
                            <td><strong>{{ $d->numero }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($d->date_emission)->format('d/m/Y') }}</td>
                            <td>{{ number_format($d->total_ttc, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($d->statut === 'accepte') <span class="badge badge-success">Accepté</span>
                                @elseif($d->statut === 'refuse') <span class="badge badge-danger">Refusé</span>
                                @else <span class="badge badge-warning">En attente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('devis.show', $d->id) }}" class="btn btn-info btn-sm">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #6b7280; font-style: italic;">Aucun devis enregistré pour ce client.</p>
        @endif
    </div>

    <div class="content-card">
        <h3 style="color: #1e1b4b; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px;">🧾 Historique des Factures</h3>
        @if($factures->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Date</th>
                        <th>Montant TTC</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factures as $f)
                        <tr>
                            <td><strong>{{ $f->numero }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($f->date_emission)->format('d/m/Y') }}</td>
                            <td>{{ number_format($f->total_ttc, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($f->statut === 'payé') <span class="badge badge-success">Payée</span>
                                @else <span class="badge badge-warning">En attente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('factures.download', $f->id) }}" class="btn btn-primary btn-sm">PDF</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #6b7280; font-style: italic;">Aucune facture enregistrée pour ce client.</p>
        @endif
    </div>
@endsection
