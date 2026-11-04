<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SEDD - MSME Practical Marketing Plan')</title>
    <style>
        :root {
            --bg: #f8fafc; --card: #ffffff; --ink: #0f172a; --mute: #64748b; --line: #e2e8f0;
            --brand: #2563eb; --brand-soft: #eff6ff; --brand-dark: #1d4ed8;
            --accent: #059669; --accent-soft: #ecfdf5;
            --ok: #16a34a; --ok-soft: #f0fdf4;
            --warn: #d97706; --warn-soft: #fffbeb;
            --bad: #dc2626; --bad-soft: #fef2f2;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font: 14.5px/1.55 system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: var(--bg); color: var(--ink); -webkit-font-smoothing: antialiased; }
        
        /* Modern, Professional Header */
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

        /* Buttons & Actions */
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
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0 1px 0 rgba(255, 255, 255, 0.15);
            transition: all 0.15s ease;
        }
        .btn-create-plan span.icon {
            font-size: 16px;
            line-height: 1;
            font-weight: 700;
            color: #ffffff;
        }
        .btn-create-plan:hover {
            background: #1d4ed8;
            border-color: #1e40af;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.25);
            color: #ffffff !important;
        }

        main { max-width: 1440px; margin: 28px auto 80px; padding: 0 24px; }
        
        .card { background: var(--card); border: 1px solid var(--line); border-radius: 10px; padding: 24px 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); margin-bottom: 24px; }
        .section-header { border-bottom: 2px solid var(--brand-soft); padding-bottom: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: baseline; }
        .section-title { font-size: 17px; font-weight: 700; color: #1e3a8a; margin: 0; display: flex; align-items: center; gap: 8px; }
        .section-title .sec-num { background: #dbeafe; color: #1e40af; border-radius: 5px; padding: 2px 8px; font-size: 13px; font-weight: 700; }
        
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label { font-size: 13px; font-weight: 600; color: #334155; }
        .form-group.col-span-2 { grid-column: span 2; }
        .form-group.col-span-full { grid-column: 1 / -1; }
        
        input[type=text], input[type=number], input[type=date], input[type=email], select, textarea {
            font: inherit; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; color: var(--ink); width: 100%; transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        textarea { resize: vertical; min-height: 80px; }
        
        table.sedd-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13.5px; }
        table.sedd-table th, table.sedd-table td { border: 1px solid #e2e8f0; padding: 9px 12px; }
        table.sedd-table th { background: #f8fafc; font-weight: 600; color: #334155; text-align: left; }
        table.sedd-table td input, table.sedd-table td select, table.sedd-table td textarea { padding: 6px 9px; font-size: 13px; }
        table.sedd-table th.num, table.sedd-table td.num { text-align: right; }
        table.sedd-table tr:hover td { background-color: #f8fafc; }
        .total-row { background: #f1f5f9; font-weight: 700; }
        
        .btn { font: inherit; font-size: 13.5px; font-weight: 600; padding: 8px 16px; border-radius: 7px; border: 1px solid #2563eb; background: #2563eb; color: #ffffff !important; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.15s ease; }
        .btn:hover { background: #1d4ed8; color: #ffffff !important; }
        .btn.ghost { background: #ffffff; color: #2563eb !important; border-color: #cbd5e1; }
        .btn.ghost:hover { background: #f8fafc; border-color: #94a3b8; }
        .btn.success { background: #16a34a; border-color: #15803d; color: #ffffff !important; }
        .btn.success:hover { background: #15803d; }
        .btn.danger { background: #ffffff; border-color: #fca5a5; color: #dc2626 !important; padding: 4px 8px; font-size: 12px; }
        .btn.danger:hover { background: #fef2f2; border-color: #ef4444; }
        .btn.add-row { background: #ffffff; border: 1px dashed #2563eb; color: #2563eb !important; padding: 6px 14px; font-size: 12.5px; margin-top: 10px; border-radius: 6px; font-weight: 600; }
        .btn.add-row:hover { background: #eff6ff; }
        
        .note { padding: 12px 18px; border-radius: 8px; margin-bottom: 22px; font-size: 14px; display: flex; align-items: center; justify-content: space-between; }
        .note.ok { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        
        /* Print styles */
        @media print {
            header.app-header, .btn, .add-row, .remove-btn, .no-print { display: none !important; }
            body { background: #fff; font-size: 12pt; color: #000; }
            main { margin: 0; max-width: 100%; padding: 0; }
            .card { border: none; padding: 0; margin-bottom: 24px; box-shadow: none; page-break-inside: avoid; }
            table.sedd-table th { background: #f0f0f0 !important; color: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table.sedd-table th, table.sedd-table td { border-color: #777 !important; }
        }
    </style>
    @yield('styles')
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
                    <span class="icon">+</span>
                    <span>New Marketing Plan</span>
                </a>
            </div>
        </div>
    </header>

    <main>
        @if(session('status'))
            <div class="note ok no-print">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
    
    @yield('scripts')
</body>
</html>
