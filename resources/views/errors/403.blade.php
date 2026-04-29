<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 | Permission Denied</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Plus Jakarta Sans", sans-serif;
            background: radial-gradient(circle at top, #fff7ed 0%, #ffedd5 30%, #f8fafc 100%);
            color: #1e293b;
        }
        .card {
            width: min(92vw, 620px);
            background: rgba(255,255,255,.92);
            border: 1px solid rgba(148,163,184,.18);
            border-radius: 28px;
            box-shadow: 0 30px 70px rgba(15,23,42,.12);
            padding: 40px;
            text-align: center;
        }
        .code {
            font-size: 72px;
            line-height: 1;
            font-weight: 800;
            color: #ea580c;
            margin-bottom: 12px;
        }
        .title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .text {
            color: #64748b;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn {
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 999px;
            font-weight: 700;
        }
        .btn-primary {
            background: #ea580c;
            color: #fff;
        }
        .btn-secondary {
            background: #fff;
            color: #0f172a;
            border: 1px solid #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="code">403</div>
        <div class="title">Permission Denied</div>
        <div class="text">You do not have permission to open this page. Please sign in with the correct role or return to your dashboard.</div>
        <div class="actions">
            <a class="btn btn-primary" href="{{ auth()->check() ? route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'dashboard') : route('login') }}">Go to Dashboard</a>
            <a class="btn btn-secondary" href="{{ route('login') }}">Login Page</a>
        </div>
    </div>
</body>
</html>
