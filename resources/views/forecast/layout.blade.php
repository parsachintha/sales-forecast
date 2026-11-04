<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sales Forecast')</title>
    <style>
        :root {
            --bg: #f8fafc; --card: #ffffff; --ink: #0f172a; --mute: #64748b; --line: #e2e8f0;
            --brand: #2563eb; --brand-soft: #eff6ff; --brand-dark: #1d4ed8;
            --ok: #16a34a; --ok-soft: #f0fdf4;
            --warn: #d97706; --warn-soft: #fffbeb;
            --bad: #dc2626; --bad-soft: #fef2f2;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font: 14.5px/1.55 system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: var(--bg); color: var(--ink); -webkit-font-smoothing: antialiased; }
        
        /* Unified Header */
        header.app-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 24px;
            height: 68px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo-group {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }
        .logo-emblem {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            letter-spacing: 0.8px;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.25);
        }
        .logo-text-block {
            display: flex;
            flex-direction: column;
        }
        .agency-name {
            font-size: 15.5px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.25;
            letter-spacing: -0.2px;
        }
        .system-subtitle {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .system-subtitle .badge-gov {
            background: #f1f5f9;
            color: #475569;
            font-size: 10px;
            padding: 1px 5px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .nav-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-tabs {
            display: flex;
            align-items: center;
            gap: 4px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 8px;
            margin-right: 8px;
        }
        .nav-tabs a {
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            font-size: 13.5px;
            padding: 6px 14px;
            border-radius: 6px;
            transition: all 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .nav-tabs a:hover {
            color: #0f172a;
        }
        .nav-tabs a.active {
            background: #ffffff;
            color: #1e40af;
            font-weight: 600;
            box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        }

        .btn-create-plan {
            background: #2563eb;
            color: #ffffff !important;
            font-size: 13.5px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 7px;
            border: 1px solid #1d4ed8;
            cursor: pointer;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.15s ease;
        }
        .btn-create-plan:hover {
            background: #1d4ed8;
            color: #ffffff !important;
        }

        /* Page Layout & Components */
        main { max-width: 1440px; margin: 28px auto 80px; padding: 0 24px; }
        h1 { font-size: 22px; margin: 0 0 4px; color: #0f172a; }
        .mute { color: var(--mute); }
        .small { font-size: 13px; }
        .card { background: var(--card); border: 1px solid var(--line); border-radius: 10px; padding: 20px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .grid { display: grid; gap: 14px; }
        .stats { grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); margin: 18px 0; }
        .stat .k { color: var(--mute); font-size: 12px; text-transform: uppercase; letter-spacing: .04em; font-weight: 600; }
        .stat .v { font-size: 21px; font-weight: 700; margin-top: 2px; color: #0f172a; }
        .stat .s { color: var(--mute); font-size: 13px; }
        .head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
        .asof { display: flex; gap: 8px; align-items: flex-end; font-size: 13px; color: var(--mute); }
        .asof label { display: grid; gap: 2px; font-weight: 600; }
        input[type=text], input[type=number], input[type=date], select {
            font: inherit; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; color: var(--ink); width: 100%;
        }
        input:focus, select:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }
        .btn { font: inherit; font-weight: 600; padding: 8px 16px; border-radius: 7px; border: 1px solid #2563eb; background: #2563eb; color: #fff; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn:hover { background: #1d4ed8; }
        .btn.ghost { background: #fff; color: #2563eb; border-color: #cbd5e1; }
        .btn.ghost:hover { background: #f8fafc; border-color: #94a3b8; }
        .note { padding: 12px 18px; border-radius: 8px; margin: 14px 0; font-size: 14px; }
        .note.ok { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .note.warn { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .note.bad { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .months { grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); }
        .cell { display: grid; gap: 8px; border: 1px solid var(--line); border-radius: 8px; padding: 14px; }
        .cell.current { border-color: #2563eb; box-shadow: 0 0 0 2px #eff6ff; }
        .cell.past { background: #f8fafc; }
        .cell .top { display: flex; justify-content: space-between; align-items: center; }
        .cell .name { font-weight: 650; }
        .badge { font-size: 11px; padding: 2px 8px; border-radius: 99px; background: #f1f5f9; color: var(--mute); font-weight: 600; }
        .cell.current .badge { background: #dbeafe; color: #1e40af; }
        .cell .tgt { display: flex; gap: 8px; align-items: center; }
        .cell .tgt .prefix { color: var(--mute); font-size: 13px; font-weight: 600; }
        .fixed { display: flex; align-items: center; gap: 5px; font-size: 12px; color: var(--mute); white-space: nowrap; }
        .bar { height: 8px; background: #e2e8f0; border-radius: 99px; overflow: hidden; }
        .bar > span { display: block; height: 100%; background: #2563eb; border-radius: 99px; transition: width .25s; }
        .cell.past .bar > span { background: #94a3b8; }
        .remaining { font-weight: 600; font-size: 14px; }
        .remaining.good { color: #16a34a; }
        .remaining.short { color: #dc2626; }
        .actions { display: flex; gap: 10px; margin-top: 18px; flex-wrap: wrap; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th, td { text-align: left; padding: 10px 14px; border-bottom: 1px solid var(--line); }
        th { font-size: 12px; text-transform: uppercase; letter-spacing: .04em; color: #475569; background: #f8fafc; font-weight: 600; }
        td.num, th.num { text-align: right; }
        tr:hover td { background-color: #f8fafc; }
        .form { max-width: 520px; display: grid; gap: 14px; }
        .form label { display: grid; gap: 4px; font-weight: 600; font-size: 14px; }
        .form .hint { font-weight: 400; color: var(--mute); font-size: 12px; }
        .crumb { margin: 0 0 14px; font-size: 14px; }
        .crumb a { color: var(--mute); text-decoration: none; font-weight: 600; }
        .crumb a:hover { color: #2563eb; }
    </style>
</head>
<body>
    <header class="app-header no-print">
        <div class="header-container">
            <a href="{{ route('marketing-plan.index') }}" class="logo-group">
                <div class="logo-emblem">SEDD</div>
                <div class="logo-text-block">
                    <span class="agency-name">Small Enterprise Development Division</span>
                    <span class="system-subtitle">
                        <span class="badge-gov">Gov of Sri Lanka</span>
                        MSME Practical Marketing Plan
                    </span>
                </div>
            </a>
            
            <div class="nav-section">
                <nav class="nav-tabs">
                    <a href="{{ route('marketing-plan.index') }}" class="{{ request()->routeIs('marketing-plan.*') ? 'active' : '' }}">
                        📋 Marketing Plans
                    </a>
                    <a href="{{ route('forecast.index') }}" class="{{ request()->routeIs('forecast.*') ? 'active' : '' }}">
                        📊 Rep Forecasts
                    </a>
                </nav>

                <a href="{{ route('marketing-plan.create') }}" class="btn-create-plan">
                    <span style="font-size:16px; font-weight:700;">+</span>
                    <span>New Marketing Plan</span>
                </a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    
    @yield('scripts')
</body>
</html>
