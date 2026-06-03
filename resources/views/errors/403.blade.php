<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 | Permission Denied</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Plus Jakarta Sans", sans-serif;
            /* Green theme background: fresh radial gradient with deep green and light mint tones */
            background: radial-gradient(circle at top, #e8f3e8 0%, #c8e6c9 30%, #e0f2e9 100%);
            color: #1e293b;
        }
        .card {
            width: min(92vw, 620px);
            background: rgba(255,255,255,.94);
            border: 1px solid rgba(34, 139, 34, 0.22);
            border-radius: 28px;
            box-shadow: 0 30px 70px rgba(15,23,42,.12), 0 0 0 1px rgba(40, 167, 69, 0.1);
            padding: 40px;
            text-align: center;
            backdrop-filter: blur(0px);
            transition: all 0.2s ease;
        }
        .code {
            font-size: 72px;
            line-height: 1;
            font-weight: 800;
            /* Green theme accent for error code */
            color: #af2d05;
            margin-bottom: 12px;
            text-shadow: 2px 2px 6px rgba(40, 100, 50, 0.08);
        }
        .title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #1c3e2b;
        }
        .text {
            color: #2f5740;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 24px;
            background: rgba(212, 237, 218, 0.35);
            padding: 12px 16px;
            border-radius: 24px;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn {
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 999px;
            font-weight: 700;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary {
            background: #2e7d32;
            color: #fff;
            box-shadow: 0 2px 6px rgba(46, 125, 50, 0.3);
            border: none;
        }
        .btn-primary:hover {
            background: #1b5e20;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(30, 90, 30, 0.3);
        }
        .btn-secondary {
            background: #f1f9ef;
            color: #1e4a2f;
            border: 1px solid #9bcd9b;
            backdrop-filter: blur(2px);
        }
        .btn-secondary:hover {
            background: #e2f3e0;
            border-color: #5fad5f;
            transform: translateY(-1px);
        }
        /* subtle green leaf ornament (optional) */
        .card::before {
            content: "";
            position: absolute;
            font-size: 28px;
            opacity: 0.1;
            pointer-events: none;
            transform: rotate(-15deg);
            left: 16px;
            top: 16px;
        }
        .card {
            position: relative;
            overflow: hidden;
        }
        /* make the background even more fresh */
        @media (max-width: 540px) {
            .card {
                padding: 32px 20px;
            }
            .code {
                font-size: 56px;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="code">403</div>
        <div class="title">Permission Denied</div>
        <div class="text">You do not have permission to open this page. Please sign in with the correct role or return to your dashboard.</div>
        <div class="actions">
            <!-- The original template uses Laravel-style dynamic routing. I have kept the original URLs intact
                 but added green styling. The links remain dynamic based on auth/role detection -->
            <a class="btn btn-primary" href="{{ auth()->check() ? route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'dashboard') : route('login') }}">Go to Dashboard</a>
            <a class="btn btn-secondary" href="{{ route('login') }}">Login Page</a>
        </div>
    </div>
</body>
</html>