<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account - SEDD MSME Marketing Plan</title>
    <style>
        :root {
            --bg: #f8fafc; --card: #ffffff; --ink: #0f172a; --mute: #64748b; --line: #e2e8f0;
            --brand: #2563eb; --brand-soft: #eff6ff; --brand-dark: #1d4ed8;
            --bad: #dc2626; --bad-soft: #fef2f2;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font: 14.5px/1.55 system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 100%);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-container {
            width: 100%;
            max-width: 460px;
        }
        .auth-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 36px 32px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        }
        .auth-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .emblem {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin: 0 auto 12px;
            box-shadow: 0 4px 8px -2px rgba(37, 99, 235, 0.3);
        }
        h1 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 6px;
        }
        .sub {
            font-size: 13px;
            color: var(--mute);
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 18px;
        }
        label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }
        input[type=email], input[type=password], input[type=text] {
            font: inherit;
            padding: 10px 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
            color: var(--ink);
            width: 100%;
            transition: all 0.15s ease;
        }
        input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .btn-submit {
            width: 100%;
            background: var(--brand);
            color: #ffffff;
            font: inherit;
            font-size: 14.5px;
            font-weight: 600;
            padding: 11px;
            border-radius: 8px;
            border: 1px solid var(--brand-dark);
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.15s ease;
            margin-top: 6px;
        }
        .btn-submit:hover {
            background: var(--brand-dark);
        }
        .note {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 18px;
        }
        .note.bad {
            background: var(--bad-soft);
            color: var(--bad);
            border: 1px solid #fecaca;
        }
        .auth-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 13px;
            color: var(--mute);
        }
        .auth-footer a {
            color: var(--brand);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="emblem">SEDD</div>
                <h1>Create User Account</h1>
                <div class="sub">Register to manage MSME Marketing Plans and Sales Forecasts</div>
            </div>

            @if($errors->any())
                <div class="note bad">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Kamal Perera">
                </div>

                <div class="form-group">
                    <label>E-mail Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. kamal@business.lk">
                </div>

                <div class="form-group">
                    <label>Password (at least 6 characters)</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn-submit">Register & Sign In</button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </div>
        </div>
    </div>
</body>
</html>
