@extends('layouts.sidebar')

@section('title', 'Factures - YA Consulting')

@section('content')
    <div class="page-header">
        <h1>🧾 Factures</h1>
        <div class="btn-group">
            <a href="{{ route('factures.export') }}" class="btn btn-success">📊 Exporter CSV</a>
            <a href="{{ route('devis.index') }}" class="btn btn-secondary">← Revenir aux Devis</a>
        </div>
    </div>

    <div class="content-card">
        @if($factures->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Client</th>
                        <th>Date d'émission</th>
                        <th>Montant TTC</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factures as $facture)
                        <tr>
                            <td><strong>{{ $facture->numero ?? 'N/A' }}</strong></td>
                            <td>{{ $facture->client->raison_sociale ?? 'Client supprimé' }}</td>
                            <td>{{ \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') }}</td>
                            <td><strong>{{ number_format($facture->total_ttc ?? 0, 2, ',', ' ') }} FCFA</strong></td>
                            <td>
                                @if($facture->statut === 'payé')
                                    <span class="badge badge-success">✓ Payée</span>
                                @elseif($facture->statut === 'accepte')
                                    <span class="badge badge-info">✓ Acceptée</span>
                                @elseif($facture->statut === 'refuse')
                                    <span class="badge badge-danger">✗ Refusée</span>
                                @else
                                    <span class="badge badge-warning">⏱ En attente</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('devis.show', $facture->id) }}" class="btn btn-info btn-sm">👁 Voir</a>
                                    <a href="{{ route('devis.show', $facture->id) }}?print=1" target="_blank" class="btn btn-secondary btn-sm" style="background: #6b7280; color: white;">🖨 Impr.</a>
                                    <a href="{{ route('factures.download', $facture->id) }}" class="btn btn-primary btn-sm">📥 PDF</a>
                                    @if($facture->statut !== 'payé')
                                        <form action="{{ route('facture.payer', $facture->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">💰 Payée</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('devis.destroy', $facture->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette facture ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑 Suppr.</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🧾</div>
                <h2>Aucune facture pour le moment</h2>
                <p>Les factures générées à partir des devis apparaîtront ici.</p>
                <br>
                <a href="{{ route('devis.index') }}" class="btn btn-primary">📄 Voir les Devis</a>
            </div>
        @endif
    </div>
@endsection
