<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'YA Consulting - Facturation')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.4); }

        .sidebar-header {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h2 {
            color: #fff;
            font-size: 1.3em;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .sidebar-header p {
            color: rgba(255,255,255,0.5);
            font-size: 0.75em;
            margin-top: 4px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow-y: auto;
        }

        .nav-section-title {
            color: rgba(255,255,255,0.4);
            font-size: 0.7em;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            padding: 16px 12px 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: rgba(255,255,255,0.7);
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            box-shadow: 0 0 20px rgba(99,102,241,0.3);
        }

        .nav-icon { font-size: 1.2em; width: 28px; text-align: center; }

        .nav-badge {
            margin-left: auto;
            background: rgba(255,255,255,0.2);
            color: #fff;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: rgba(255,255,255,0.8);
            padding: 8px;
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        .user-profile:hover { background: rgba(255,255,255,0.1); }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85em;
        }

        .user-info .user-name { font-weight: 600; font-size: 0.85em; color: #fff; }
        .user-info .user-role { font-size: 0.7em; color: rgba(255,255,255,0.45); }

        .logout-btn {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 8px;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            background: transparent;
            color: rgba(255,255,255,0.6);
            font-size: 0.8em;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(220,53,69,0.3);
            border-color: rgba(220,53,69,0.5);
            color: #ff8a8a;
        }

        /* ================= MAIN CONTENT ================= */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 32px;
            min-height: 100vh;
        }

        /* ================= SHARED PAGE STYLES ================= */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 1.8em;
            font-weight: 700;
            color: #1e1b4b;
        }

        .page-header-sub {
            color: #6b7280;
            font-size: 0.9em;
        }

        .content-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        /* Alerts / Flash Messages */
        .alert {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; border-left: 4px solid #10b981; }
        .alert-error { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border-left: 4px solid #ef4444; }

        /* ================= GLOBAL BUTTONS ================= */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.85em;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            line-height: 1.4;
        }
        .btn:active { transform: scale(0.96); }

        /* Primary (Indigo Gradient) */
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #4f46e5);
            color: #fff;
            box-shadow: 0 2px 8px rgba(79,70,229,0.3);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd6, #4338ca);
            box-shadow: 0 4px 16px rgba(79,70,229,0.45);
            transform: translateY(-2px);
        }

        /* Success (Green Gradient) */
        .btn-success {
            background: linear-gradient(135deg, #34d399, #059669);
            color: #fff;
            box-shadow: 0 2px 8px rgba(5,150,105,0.3);
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #2bc48d, #047857);
            box-shadow: 0 4px 16px rgba(5,150,105,0.45);
            transform: translateY(-2px);
        }

        /* Info (Cyan Gradient) */
        .btn-info {
            background: linear-gradient(135deg, #22d3ee, #0891b2);
            color: #fff;
            box-shadow: 0 2px 8px rgba(8,145,178,0.3);
        }
        .btn-info:hover {
            background: linear-gradient(135deg, #17c2dd, #0e7490);
            box-shadow: 0 4px 16px rgba(8,145,178,0.45);
            transform: translateY(-2px);
        }

        /* Danger (Red Gradient) */
        .btn-danger {
            background: linear-gradient(135deg, #f87171, #dc2626);
            color: #fff;
            box-shadow: 0 2px 8px rgba(220,38,38,0.3);
        }
        .btn-danger:hover {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            box-shadow: 0 4px 16px rgba(220,38,38,0.45);
            transform: translateY(-2px);
        }

        /* Warning (Amber Gradient) */
        .btn-warning {
            background: linear-gradient(135deg, #fbbf24, #d97706);
            color: #fff;
            box-shadow: 0 2px 8px rgba(217,119,6,0.3);
        }
        .btn-warning:hover {
            background: linear-gradient(135deg, #f59e0b, #b45309);
            box-shadow: 0 4px 16px rgba(217,119,6,0.45);
            transform: translateY(-2px);
        }

        /* Secondary (Neutral) */
        .btn-secondary {
            background: linear-gradient(135deg, #9ca3af, #6b7280);
            color: #fff;
            box-shadow: 0 2px 8px rgba(107,114,128,0.3);
        }
        .btn-secondary:hover {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            box-shadow: 0 4px 16px rgba(107,114,128,0.45);
            transform: translateY(-2px);
        }

        /* Outline Variant */
        .btn-outline {
            background: transparent;
            border: 2px solid #4f46e5;
            color: #4f46e5;
            box-shadow: none;
        }
        .btn-outline:hover {
            background: #4f46e5;
            color: #fff;
            box-shadow: 0 4px 16px rgba(79,70,229,0.3);
            transform: translateY(-2px);
        }

        /* Button Sizes */
        .btn-sm { padding: 6px 12px; font-size: 0.78em; border-radius: 8px; }
        .btn-lg { padding: 12px 28px; font-size: 1em; border-radius: 12px; }

        /* Button Group */
        .btn-group { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

        /* ================= GLOBAL TABLE ================= */
        table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 16px; }
        thead th {
            background: linear-gradient(135deg, #1e1b4b, #312e81);
            color: #fff;
            padding: 14px 16px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }
        thead th:first-child { border-radius: 12px 0 0 0; }
        thead th:last-child { border-radius: 0 12px 0 0; }
        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.9em;
            color: #374151;
            vertical-align: middle;
        }
        tbody tr { transition: all 0.2s ease; }
        tbody tr:hover { background: #f8fafc; }
        tbody tr:last-child td:first-child { border-radius: 0 0 0 12px; }
        tbody tr:last-child td:last-child { border-radius: 0 0 12px 0; }

        /* ================= GLOBAL BADGES ================= */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78em;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .badge-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
        .badge-warning { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
        .badge-danger { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; }
        .badge-info { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; }

        /* ================= GLOBAL FORMS ================= */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.88em;
            color: #374151;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 0.92em;
            color: #1f2937;
            background: #fff;
            transition: all 0.2s ease;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        /* ================= EMPTY STATES ================= */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }
        .empty-state-icon { font-size: 3em; margin-bottom: 16px; opacity: 0.5; }
        .empty-state h2 { color: #6b7280; font-size: 1.2em; margin-bottom: 8px; }
        .empty-state p { font-size: 0.9em; }
        .empty-state a { color: #4f46e5; font-weight: 600; }
        .empty-state a:hover { text-decoration: underline; }

        /* ================= MOBILE ================= */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 200;
            background: #1e1b4b;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 1.2em;
            cursor: pointer;
        }

        @media (max-width: 900px) {
            .mobile-toggle { display: block; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; padding-top: 70px; }
        }
    </style>
    @yield('styles')
</head>
<body>

<!-- Mobile Toggle -->
<button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')">☰</button>

<!-- ========= SIDEBAR ========= -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>YA Consulting</h2>
        <p>Logiciel de Facturation</p>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-section-title">Principal</span>

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Tableau de Bord
        </a>

        <span class="nav-section-title">Gestion</span>

        <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Clients
            <span class="nav-badge">{{ \App\Models\Client::count() }}</span>
        </a>

        <a href="{{ route('produits.index') }}" class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}">
            <span class="nav-icon">📦</span> Produits
        </a>

        <span class="nav-section-title">Documents</span>

        <a href="{{ route('devis.index') }}" class="nav-link {{ request()->routeIs('devis.index') || request()->routeIs('devis.show') ? 'active' : '' }}">
            <span class="nav-icon">📄</span> Devis
            <span class="nav-badge">{{ \App\Models\Document::where('type','devis')->count() }}</span>
        </a>

        <a href="{{ route('devis.create') }}" class="nav-link {{ request()->routeIs('devis.create') ? 'active' : '' }}">
            <span class="nav-icon">✨</span> Nouveau Devis
        </a>

        <a href="{{ route('factures.index') }}" class="nav-link {{ request()->routeIs('factures.*') ? 'active' : '' }}">
            <span class="nav-icon">🧾</span> Factures
            <span class="nav-badge">{{ \App\Models\Document::where('type','facture')->count() }}</span>
        </a>

        <span class="nav-section-title">Configuration</span>

        <a href="{{ route('settings.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <span class="nav-icon">⚙️</span> Paramètres
        </a>

        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="nav-icon">👤</span> Mon Profil
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}" class="user-profile">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name ?? 'Utilisateur' }}</div>
                <div class="user-role">Administrateur</div>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">🚪 Déconnexion</button>
        </form>
    </div>
</aside>

<!-- ========= MAIN CONTENT ========= -->
<main class="main-content">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">✗ {{ session('error') }}</div>
    @endif

    @yield('content')

</main>

@yield('scripts')
</body>
</html>
