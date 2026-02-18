<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 2cm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a2a5a; margin: 0; padding: 0; }
        
        /* Entête */
        .header-table { width: 100%; border: none; margin-bottom: 20px; }
        .logo { width: 120px; }
        .ref-doc { text-align: right; font-weight: bold; font-size: 14px; }
        
        /* Infos Émetteur et Client */
        .section-info { width: 100%; margin-bottom: 30px; }
        .title-box { 
            border: 3px solid #1a2a5a; 
            text-align: center; 
            padding: 10px; 
            width: 50%; 
            margin: 20px auto;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Tableau des articles */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { 
            background-color: #a9a9a9; 
            border: 1.5px solid #000; 
            padding: 5px; 
            text-transform: uppercase; 
            font-size: 10px;
        }
        .items-table td { border: 1.5px solid #000; padding: 5px; color: #000; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Totaux et Signature */
        .footer-section { width: 100%; margin-top: 30px; }
        .total-table { width: 60%; float: right; border-collapse: collapse; }
        .total-table td { border: 1.5px solid #000; padding: 5px; font-weight: bold; }

        /* Pied de page bleu */
        .footer-blue { 
            position: fixed; 
            bottom: -1cm; 
            left: 0; 
            right: 0; 
            text-align: center; 
            color: #00acee; 
            font-size: 9px;
            line-height: 1.2;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                @if(isset($settings) && $settings->logo)
                    <img src="{{ public_path('storage/' . $settings->logo) }}" class="logo">
                @else
                    <img src="{{ public_path('images/logo-ya.png') }}" class="logo">
                @endif
            </td>
            <td class="ref-doc">
                N° {{ str_pad($devis->id, 5, '0', STR_PAD_LEFT) }}-YA
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 40%; vertical-align: top;">
                <p><strong>Émetteur :</strong></p>
                <p><strong>{{ $settings->nom_entreprise ?? 'YA CONSULTING' }}</strong><br>
                {{ $settings->adresse ?? 'Abidjan' }}<br><br>
                Tél.: {{ $settings->telephone ?? '+225 01 52 22 63 12' }}<br>
                Email: {{ $settings->email ?? 'courriel@ya-consulting.com' }}<br>
                @if(isset($settings->site_web) && $settings->site_web)
                    Site web : {{ $settings->site_web }}
                @else
                    Site web : www.ya-consulting.com
                @endif
                </p>
            </td>
            <td style="text-align: center; vertical-align: top;">
                <p>{{ \Carbon\Carbon::parse($devis->date_emission)->format('d/m/Y') }}</p>
            </td>
            <td style="width: 40%; text-align: right; vertical-align: top;">
                <p><strong>Destinataire</strong></p>
                @if(isset($devis->client->logo) && $devis->client->logo)
                    <img src="{{ public_path('storage/' . $devis->client->logo) }}" style="width: 80px; height: auto; margin-bottom: 5px; float: right; clear: both;"><br>
                @endif
                <p style="clear: both;"><strong>{{ $devis->client->raison_sociale }}</strong><br>
                {{ $devis->client->adresse ?? '' }}</p>
            </td>
        </tr>
    </table>

    <div class="title-box">
        {{ $devis->titre_document ?? (($devis->type ?? 'devis') == 'facture' ? 'FACTURE PROFORMA' : 'DEVIS') }}
    </div>

    <p style="margin-left: 10px;"><strong>Objet :</strong> {{ $devis->objet ?? 'Prestations de services / Ventes de matériels' }}</p>
    <p style="margin-left: 10px; font-weight: bold; text-align: right;">Montants exprimés en FCFA BCEAO</p>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45%;">DESIGNATION</th>
                <th style="width: 20%;">P.U.H.T</th>
                <th style="width: 10%;">Qté</th>
                <th style="width: 25%;">Total HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devis->lignes as $ligne)
            <tr>
                <td>{{ $ligne->designation }}</td>
                <td class="text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }}</td>
                <td class="text-center">{{ $ligne->quantite }}</td>
                <td class="text-right">{{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <div style="float: left; width: 30%; text-align: center;">
            @if(isset($settings) && $settings->cachet)
                <img src="{{ public_path('storage/' . $settings->cachet) }}" style="width: 150px; opacity: 0.9; margin-top: -20px;">
            @endif
        </div>
        
        <table class="total-table">
            <tr>
                <td style="width: 40%;">TOTAL HT</td>
                <td class="text-right">{{ number_format($devis->total_ht, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td>TVA (18%)</td>
                <td class="text-right">{{ number_format($devis->total_tva, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td>TOTAL TTC</td>
                <td class="text-right">{{ number_format($devis->total_ttc, 0, ',', ' ') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer-blue">
        YA CONSULTING-RCCM: N CI-ABJ-2020-B-13747, NCC: 2046187R, Siège social: Riviera Palmeraie, Cocody, Abidjan, Côte d'Ivoire.<br>
        Tél: (225) 01 52 22 63 12, (225) 05 65 24 69 74, Email: courriel@ya-consulting.com, www.ya-consulting.com
    </div>

</body>
</html>