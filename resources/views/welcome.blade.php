<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>devis-YAconsulting</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600" rel="stylesheet" />
        <style>
            body {
                font-family: 'Instrument Sans', sans-serif;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Ton dégradé bleu/violet */
                color: white;
                text-align: center;
            }
            .container {
                padding: 20px;
            }
            h1 { font-size: 3rem; margin-bottom: 10px; }
            p { font-size: 1.2rem; opacity: 0.9; margin-bottom: 30px; }
            .btn {
                display: inline-block;
                padding: 12px 30px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: bold;
                transition: transform 0.2s;
                margin: 10px;
            }
            .btn:hover { transform: scale(1.05); }
            .btn-primary { background: white; color: #764ba2; }
            .btn-outline { border: 2px solid white; color: white; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>devis-YAconsulting</h1>
            <p>Bienvenue dans votre espace de gestion professionnel de vos devis&factures</p>

            @if (Route::has('login'))
                <div class="actions">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            Accéder au Tableau de Bord →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Se connecter
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline">
                                S'inscrire
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </body>
</html>