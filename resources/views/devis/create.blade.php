@extends('layouts.sidebar')

@section('title', 'Créer un Devis - YA Consulting')

@section('styles')
<style>
    .section-title {
        color: #1e1b4b;
        font-size: 1.1em;
        font-weight: 700;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .toggle-section {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .toggle-btn {
        padding: 8px 18px;
        border-radius: 20px;
        border: 2px solid #e5e7eb;
        background: #fff;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 0.85em;
        color: #6b7280;
        transition: all 0.2s ease;
    }

    .toggle-btn.active {
        background: linear-gradient(135deg, #667eea, #4f46e5);
        color: #fff;
        border-color: #4f46e5;
        box-shadow: 0 2px 8px rgba(79,70,229,0.3);
    }

    .toggle-btn:hover:not(.active) {
        border-color: #667eea;
        color: #4f46e5;
    }

    .hidden { display: none !important; }

    .ligne-card {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 14px;
        position: relative;
        transition: all 0.2s ease;
    }
    .ligne-card:hover { border-color: #c7d2fe; }

    .ligne-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }
    .ligne-number {
        font-weight: 700;
        color: #4f46e5;
        font-size: 0.9em;
    }

    .ligne-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 14px;
    }

    .totals {
        margin-top: 30px;
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        padding: 24px;
        border-radius: 14px;
        text-align: right;
        border: 2px solid #e5e7eb;
    }
    .totals p { margin: 6px 0; font-size: 1em; color: #374151; }
    .total-ttc { color: #4f46e5 !important; font-size: 1.3em !important; font-weight: 700; }

    .step-indicator {
        display: flex;
        gap: 0;
        margin-bottom: 32px;
    }
    .step {
        flex: 1;
        text-align: center;
        padding: 14px 10px;
        background: #f3f4f6;
        color: #9ca3af;
        font-weight: 600;
        font-size: 0.85em;
        position: relative;
        transition: all 0.3s ease;
    }
    .step:first-child { border-radius: 12px 0 0 12px; }
    .step:last-child { border-radius: 0 12px 12px 0; }
    .step.active {
        background: linear-gradient(135deg, #667eea, #4f46e5);
        color: #fff;
    }
    .step .step-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        font-size: 0.8em;
        margin-right: 6px;
    }
    .step:not(.active) .step-num {
        background: #d1d5db;
        color: #6b7280;
    }

    @media (max-width: 700px) {
        .ligne-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
    <div class="page-header">
        <h1>✨ Créer un Devis</h1>
        <a href="{{ route('devis.index') }}" class="btn btn-secondary">← Retour à la liste</a>
    </div>

    <!-- Progress Steps -->
    <div class="step-indicator">
        <div class="step active"><span class="step-num">1</span> Client</div>
        <div class="step active"><span class="step-num">2</span> Produits / Services</div>
        <div class="step active"><span class="step-num">3</span> Générer</div>
    </div>

    <form action="{{ route('devis.store') }}" method="POST" id="devisForm" enctype="multipart/form-data">
    @csrf

    <!-- ============ SECTION 1 : CLIENT ============ -->
    <div class="content-card">
        <div class="section-title">👤 Informations du Client</div>

        <div class="toggle-section">
            <button type="button" class="toggle-btn active" onclick="toggleClientMode('new')">+ Nouveau Client</button>
            <button type="button" class="toggle-btn" onclick="toggleClientMode('existing')">📋 Client existant</button>
        </div>

        <!-- Mode : Nouveau Client -->
        <div id="client-new">
            <input type="hidden" name="client_mode" id="client_mode" value="new">

            <div class="form-group">
                <label>Raison sociale *</label>
                <input type="text" name="new_raison_sociale" placeholder="Ex: Entreprise ABC SARL">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Téléphone *</label>
                    <input type="text" name="new_telephone" placeholder="Ex: +237 6XX XXX XXX">
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="new_email" placeholder="contact@example.com">
                </div>
            </div>

            <div class="form-group">
                <label>Adresse *</label>
                <textarea name="new_adresse" rows="2" placeholder="Rue, Ville, Pays"></textarea>
            </div>

            <div class="form-group">
                <label>RCCM / Compte Contribuable (Optionnel)</label>
                <input type="text" name="new_rccm_cc" placeholder="Ex: RC/CM/YYYY/XXX">
            </div>

            <div class="form-group">
                <label>Logo du Client (Optionnel)</label>
                <input type="file" name="new_logo" accept="image/*" class="form-control">
                <small style="color: #6b7280;">Format recommandé: PNG ou JPG, max 2Mo.</small>
            </div>
        </div>

        <!-- Mode : Client existant -->
        <div id="client-existing" class="hidden">
            <div class="form-group">
                <label>Sélectionner un client</label>
                <select name="client_id">
                    <option value="">-- Choisir un client existant --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->raison_sociale }} ({{ $client->email }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- ============ SECTION 2 : DATE ============ -->
    <div class="content-card">
        <div class="section-title">📅 Informations du Devis</div>
        <div class="form-row">
            <div class="form-group">
                <label>Date d'émission *</label>
                <input type="date" name="date_emission" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>Objet / Référence (Optionnel)</label>
                <input type="text" name="objet" placeholder="Ex: Fourniture de matériel informatique">
            </div>
        </div>
        <div class="form-group">
            <label>Titre à afficher sur le document</label>
            <select name="titre_document">
                <option value="DEVIS">DEVIS</option>
                <option value="FACTURE PROFORMA">FACTURE PROFORMA</option>
                <option value="FACTURE">FACTURE</option>
            </select>
        </div>
    </div>

    <!-- ============ SECTION 3 : PRODUITS ============ -->
    <div class="content-card">
        <div class="section-title">🛒 Articles / Services</div>

        <div class="toggle-section">
            <button type="button" class="toggle-btn active" onclick="toggleProductMode('new')">+ Saisie directe</button>
            <button type="button" class="toggle-btn" onclick="toggleProductMode('existing')">📋 Depuis le catalogue</button>
        </div>

        <input type="hidden" name="product_mode" id="product_mode" value="new">

        <div id="lignes-container">
            <!-- Ligne 1 -->
            <div class="ligne-card" data-index="0">
                <div class="ligne-header">
                    <span class="ligne-number">Article #1</span>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeLigne(this)" style="display:none;">✕ Supprimer</button>
                </div>

                <!-- Saisie directe -->
                <div class="ligne-new">
                    <div class="form-group">
                        <label>Désignation *</label>
                        <input type="text" name="lignes[0][designation]" placeholder="Ex: MacBook Pro 14 pouces" class="designation-input">
                    </div>
                    <div class="ligne-grid">
                        <div class="form-group">
                            <label>Type</label>
                            <select name="lignes[0][type]">
                                <option value="produit">📦 Produit</option>
                                <option value="service">⚙️ Service</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantité *</label>
                            <input type="number" name="lignes[0][quantite]" class="qty-input" value="1" min="1">
                        </div>
                        <div class="form-group">
                            <label>Prix Unitaire HT (FCFA) *</label>
                            <input type="number" name="lignes[0][prix_unitaire]" class="price-input" step="0.01" placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- Depuis catalogue -->
                <div class="ligne-existing hidden">
                    <div class="ligne-grid">
                        <div class="form-group">
                            <label>Sélectionner un produit</label>
                            <select name="lignes[0][produit_id]" class="product-select" onchange="updatePrice(this)">
                                <option value="">-- Choisir --</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->id }}" data-price="{{ $produit->prix_unitaire_ht }}" data-name="{{ $produit->designation }}">
                                        {{ $produit->designation }} — {{ number_format($produit->prix_unitaire_ht, 0, ',', ' ') }} FCFA
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantité *</label>
                            <input type="number" name="lignes[0][quantite_cat]" class="qty-input-cat" value="1" min="1">
                        </div>
                        <div class="form-group">
                            <label>Prix Unitaire HT</label>
                            <input type="number" name="lignes[0][prix_unitaire_cat]" class="price-input-cat" step="0.01" placeholder="Auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-success" onclick="addLigne()" style="margin-top: 8px;">
            + Ajouter un article
        </button>

        <!-- Totaux -->
        <div class="totals">
            <p>Total HT : <strong><span id="display-ht">0</span> FCFA</strong></p>
            <p>TVA (18%) : <strong><span id="display-tva">0</span> FCFA</strong></p>
            <p class="total-ttc">Total TTC : <span id="display-ttc">0</span> FCFA</p>
        </div>
    </div>

    <!-- ============ SUBMIT ============ -->
    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; padding: 16px; font-size: 1.1em;">
        📄 Générer et Enregistrer le Devis
    </button>

    </form>
@endsection

@section('scripts')
<script>
    let lineCount = 1;
    let productMode = 'new';

    // ---- Toggle Client Mode ----
    function toggleClientMode(mode) {
        document.getElementById('client_mode').value = mode;
        document.getElementById('client-new').classList.toggle('hidden', mode !== 'new');
        document.getElementById('client-existing').classList.toggle('hidden', mode !== 'existing');

        // Update toggle buttons
        document.querySelectorAll('.content-card:nth-child(1) .toggle-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
    }

    // ---- Toggle Product Mode ----
    function toggleProductMode(mode) {
        productMode = mode;
        document.getElementById('product_mode').value = mode;

        document.querySelectorAll('.ligne-new').forEach(el => el.classList.toggle('hidden', mode !== 'new'));
        document.querySelectorAll('.ligne-existing').forEach(el => el.classList.toggle('hidden', mode !== 'existing'));

        // Update toggle buttons
        const section = document.querySelector('.content-card:nth-child(3)');
        section.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        calculateTotals();
    }

    // ---- Add new line ----
    function addLigne() {
        const container = document.getElementById('lignes-container');
        const firstCard = container.querySelector('.ligne-card');
        const newCard = firstCard.cloneNode(true);

        newCard.dataset.index = lineCount;
        newCard.querySelector('.ligne-number').textContent = 'Article #' + (lineCount + 1);

        // Show remove button
        newCard.querySelector('.btn-danger').style.display = '';

        // Update all input names
        newCard.querySelectorAll('input, select, textarea').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, '[' + lineCount + ']');
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else if (el.classList.contains('qty-input') || el.classList.contains('qty-input-cat')) el.value = 1;
            else el.value = '';
        });

        // Respect current product mode
        newCard.querySelector('.ligne-new').classList.toggle('hidden', productMode !== 'new');
        newCard.querySelector('.ligne-existing').classList.toggle('hidden', productMode !== 'existing');

        container.appendChild(newCard);
        lineCount++;
        attachListeners();
        updateLineNumbers();
    }

    // ---- Remove line ----
    function removeLigne(btn) {
        const container = document.getElementById('lignes-container');
        if (container.children.length > 1) {
            btn.closest('.ligne-card').remove();
            updateLineNumbers();
            calculateTotals();
        }
    }

    // ---- Update line numbers ----
    function updateLineNumbers() {
        document.querySelectorAll('.ligne-card').forEach((card, i) => {
            card.querySelector('.ligne-number').textContent = 'Article #' + (i + 1);
            // Show/hide remove button
            card.querySelector('.btn-danger').style.display = i === 0 && document.querySelectorAll('.ligne-card').length === 1 ? 'none' : '';
        });
    }

    // ---- Auto-fill price from catalogue ----
    function updatePrice(selectEl) {
        const option = selectEl.options[selectEl.selectedIndex];
        const price = option.getAttribute('data-price');
        const card = selectEl.closest('.ligne-card');
        if (price) card.querySelector('.price-input-cat').value = price;
        calculateTotals();
    }

    // ---- Calculate totals ----
    function calculateTotals() {
        let totalHT = 0;

        document.querySelectorAll('.ligne-card').forEach(card => {
            let qty, price;

            if (productMode === 'new') {
                qty = parseFloat(card.querySelector('.qty-input')?.value) || 0;
                price = parseFloat(card.querySelector('.price-input')?.value) || 0;
            } else {
                qty = parseFloat(card.querySelector('.qty-input-cat')?.value) || 0;
                price = parseFloat(card.querySelector('.price-input-cat')?.value) || 0;
            }

            totalHT += qty * price;
        });

        const tva = totalHT * 0.18;
        const ttc = totalHT + tva;

        document.getElementById('display-ht').innerText = new Intl.NumberFormat('fr-FR').format(totalHT);
        document.getElementById('display-tva').innerText = new Intl.NumberFormat('fr-FR').format(tva);
        document.getElementById('display-ttc').innerText = new Intl.NumberFormat('fr-FR').format(ttc);
    }

    // ---- Attach listeners ----
    function attachListeners() {
        document.querySelectorAll('.qty-input, .price-input, .qty-input-cat, .price-input-cat').forEach(input => {
            input.removeEventListener('input', calculateTotals);
            input.addEventListener('input', calculateTotals);
        });
    }

    attachListeners();
</script>
@endsection