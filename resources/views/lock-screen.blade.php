<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lock Screen | {{ config('panel.organization') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            position: relative;
            min-height: 100vh;
            background: url('/img/background-3.png') no-repeat center/cover;
            overflow: hidden;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* 👇 White gradient overlay */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            background: transparent;
            z-index: 1;
        }

        .lock-screen-card {
            position: relative;
            z-index: 2;
        }

        .lock-screen-card {
            width: min(480px, calc(100% - 32px));
            padding: 32px;
            border-radius: 24px;
            background: radial-gradient(circle at top left, rgba(255, 255, 255, 0.08), transparent 28%),
                radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.06), transparent 24%),
                linear-gradient(135deg, #022c22, #064e3b);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(18px);
        }

        .lock-screen-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .lock-screen-logo i {
            font-size: 1.8rem;
            color: #10b981;
        }

        .lock-screen-title {
            margin: 0 0 12px;
            font-size: 28px;
            font-weight: 700;
        }

        .lock-screen-subtitle {
            margin: 0;
            color: #d1d5db;
            font-size: 14px;
        }

        .lock-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            display: grid;
            place-items: center;
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin: 0 auto 18px;
            overflow: hidden;
        }

        .lock-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .lock-user-name {
            text-align: center;
            font-size: 22px;
            margin: 0 0 6px;
        }

        .lock-user-email {
            text-align: center;
            margin: 0;
            color: #efeff0;
            font-size: 15px;
            /* opacity: 0.85; */
        }

        .lock-screen-form {
            display: grid;
            gap: 16px;
            margin-top: 20px;
            /* 👈 more breathing space */
        }

        .lock-screen-form input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            font-size: 15px;
        }

        .lock-screen-form input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .lock-screen-form button {
            width: 100%;
            padding: 14px 16px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .lock-screen-form button:hover {
            transform: translateY(-1px);
        }

        .lock-screen-error {
            color: #fecaca;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="lock-screen-card">
        <div class="lock-screen-logo">
            <i class="fa-solid fa-lock"></i>
            <div>
                <div class="lock-screen-title">Session Locked</div>
                <p class="lock-screen-subtitle">Enter password to unlock your dashboard.</p>
            </div>
        </div>

        <div class="lock-avatar">
            @if($user->photo)
            <img src="{{ asset('storage/photos/' . $user->photo) }}" alt="{{ $user->name }}">
            @else
            {{ strtoupper(substr($user->name, 0, 2)) }}
            @endif
        </div>

        <h3 class="lock-user-name">{{ $user->name }}</h3>
        <p class="lock-user-email">{{ $user->email }}</p>

        <form action="{{ route('lock.unlock') }}" method="POST" class="lock-screen-form">
            @csrf
            <input type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
            @if($errors->has('password'))
            <div class="lock-screen-error">{{ $errors->first('password') }}</div>
            @endif
            @if(session('error'))
            <div class="lock-screen-error">{{ session('error') }}</div>
            @endif
            <button type="submit">Unlock</button>
        </form>
    </div>
</body>

</html>