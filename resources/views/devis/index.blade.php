@extends('layouts.sidebar')

@section('title', 'Devis - YA Consulting')

@section('content')
    <div class="page-header">
        <h1>📄 Devis</h1>
        <div class="btn-group">
            <a href="{{ route('factures.index') }}" class="btn btn-primary">🧾 Voir les Factures</a>
            <a href="{{ route('devis.create') }}" class="btn btn-success btn-lg">+ Créer un Devis</a>
        </div>
    </div>

    <div class="content-card">
        @if($devis->count() > 0)
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
                    @foreach($devis as $devis_item)
                        <tr>
                            <td><strong>{{ $devis_item->numero ?? 'N/A' }}</strong></td>
                            <td>{{ $devis_item->client->raison_sociale ?? 'Client supprimé' }}</td>
                            <td>{{ \Carbon\Carbon::parse($devis_item->date_emission)->format('d/m/Y') }}</td>
                            <td><strong>{{ number_format($devis_item->total_ttc ?? 0, 2, ',', ' ') }} FCFA</strong></td>
                            <td>
                                @if($devis_item->statut === 'accepte')
                                    <span class="badge badge-success">✓ Accepté</span>
                                @elseif($devis_item->statut === 'refuse')
                                    <span class="badge badge-danger">✗ Refusé</span>
                                @else
                                    <span class="badge badge-warning">⏱ En attente</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('devis.show', $devis_item->id) }}" class="btn btn-info btn-sm">👁 Voir</a>
                                    <a href="{{ route('devis.show', $devis_item->id) }}?print=1" target="_blank" class="btn btn-secondary btn-sm" style="background: #6b7280; color: white;">🖨 Impr.</a>
                                    <a href="{{ route('devis.download', $devis_item->id) }}" class="btn btn-primary btn-sm">📥 PDF</a>
                                    <a href="{{ route('devis.edit', $devis_item->id) }}" class="btn btn-warning btn-sm">✏️ Éditer</a>
                                    <form action="{{ route('devis.transformer', $devis_item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">⚡ Facturer</button>
                                    </form>
                                    <form action="{{ route('devis.destroy', $devis_item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?');">
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
                <div class="empty-state-icon">📄</div>
                <h2>Aucun devis pour le moment</h2>
                <p>Commencez par <a href="{{ route('devis.create') }}">créer un nouveau devis</a></p>
            </div>
        @endif
    </div>
@endsection
