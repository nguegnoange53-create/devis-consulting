@extends('layouts.sidebar')

@section('title', 'Catalogue des Produits - YA Consulting')

@section('content')
    <div class="page-header">
        <h1>📦 Catalogue des Produits / Services</h1>
        <a href="{{ route('produits.create') }}" class="btn btn-success btn-lg">+ Nouveau Produit</a>
    </div>

    <div class="content-card">
        @if($produits->count())
            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Prix unitaire HT (FCFA)</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produits as $produit)
                        <tr>
                            <td><strong>{{ $produit->designation }}</strong></td>
                            <td>{{ number_format($produit->prix_unitaire_ht, 0, ',', ' ') }}</td>
                            <td>
                                @if($produit->type === 'produit')
                                    <span class="badge badge-info">📦 Produit</span>
                                @else
                                    <span class="badge badge-success">⚙️ Service</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-warning btn-sm" href="{{ route('produits.edit', $produit) }}">✏️ Éditer</a>
                                    <form action="{{ route('produits.destroy', $produit) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer ce produit ?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">🗑 Suppr.</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top: 16px;">{{ $produits->links() }}</div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h2>Aucun produit trouvé</h2>
                <p>Commencez par <a href="{{ route('produits.create') }}">ajouter un nouveau produit</a></p>
            </div>
        @endif
    </div>
@endsection
