<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lock Screen | {{ config('panel.organization') }}</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* MODERN DESIGN SYSTEM — GREEN THEME, GLASS + BLUR, SMOOTH */
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            position: relative;
            background: url('/img/background.png') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: white;
        }

        /* Main card container — elevated with glassmorphic features */
        .lock-screen-card {
            position: relative;
            z-index: 2;
            width: min(480px, calc(100% - 32px));
            padding: 36px 32px 42px;
            border-radius: 40px;
            background: radial-gradient(circle at 30% 10%, rgba(16, 185, 129, 0.12), rgba(4, 120, 87, 0.08) 70%),
                linear-gradient(135deg, rgba(2, 44, 34, 0.78), rgba(6, 78, 59, 0.82));
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(16, 185, 129, 0.35);
            box-shadow: 0 35px 70px -20px rgba(0, 0, 0, 0.45), 0 0 0 0.5px rgba(16, 185, 129, 0.2) inset;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .lock-screen-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 40px 80px -18px rgba(0, 0, 0, 0.55);
            border-color: rgba(16, 185, 129, 0.5);
        }

        /* Logo / brand area */
        .lock-screen-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 28px;
            background: rgba(0, 0, 0, 0.2);
            width: fit-content;
            padding: 8px 20px;
            border-radius: 60px;
            margin-left: auto;
            margin-right: auto;
            backdrop-filter: blur(4px);
        }

        .lock-screen-logo i {
            font-size: 1.9rem;
            color: #6ee7b7;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .lock-screen-logo span {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.2px;
            background: linear-gradient(135deg, #e2f3e8, #a7f3d0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* title & subtitle group */
        .lock-screen-title {
            margin: 0 0 8px;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(130deg, #ffffff, #bef5d5);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .lock-screen-subtitle {
            margin: 0 0 16px;
            color: #cbd5e6;
            font-size: 14px;
            font-weight: 500;
            opacity: 0.85;
        }

        /* avatar with modern presence */
        .lock-avatar {
            width: 102px;
            height: 102px;
            border-radius: 50%;
            background: linear-gradient(145deg, #10b98130, #064e3b80);
            display: grid;
            place-items: center;
            font-size: 38px;
            font-weight: 700;
            color: white;
            margin: 8px auto 20px;
            overflow: hidden;
            border: 2px solid rgba(16, 185, 129, 0.6);
            box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(2px);
        }

        .lock-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* user info */
        .lock-user-name {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 6px;
            letter-spacing: -0.2px;
        }

        .lock-user-email {
            text-align: center;
            margin: 0;
        }

        /* form modern & sleek */
        .lock-screen-form {
            display: grid;
            gap: 18px;
            margin-top: 28px;
        }

        /* input wrapper with icon */
        .input-icon-group {
            position: relative;
            width: 100%;
        }

        .input-icon-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ae6b4;
            font-size: 1.1rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .lock-screen-form input {
            width: 100%;
            padding: 15px 16px 15px 46px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 44px;
            background: rgba(10, 30, 24, 0.5);
            color: white;
            font-size: 15px;
            font-weight: 500;
            backdrop-filter: blur(8px);
            transition: all 0.2s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .lock-screen-form input:focus {
            outline: none;
            border-color: #34d399;
            background: rgba(16, 185, 129, 0.2);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
        }

        .lock-screen-form input::placeholder {
            color: rgba(220, 252, 231, 0.75);
            font-weight: 400;
        }

        /* modern button with shine and hover */
        .lock-screen-form button {
            width: 100%;
            padding: 15px 16px;
            border: none;
            border-radius: 44px;
            font-size: 16px;
            font-weight: 700;
            color: white;
            background: linear-gradient(105deg, #10b981 0%, #059669 100%);
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.2px;
        }

        .lock-screen-form button i {
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .lock-screen-form button:hover {
            background: linear-gradient(105deg, #16c48a 0%, #0a855e 100%);
            transform: scale(1.01);
            box-shadow: 0 12px 22px -8px #0a3b2e;
        }

        .lock-screen-form button:hover i {
            transform: translateX(3px);
        }

        .lock-screen-form button:active {
            transform: scale(0.98);
        }

        /* error alert (emerald tone) */
        .lock-screen-error {
            color: #fecaca;
            font-size: 13px;
            text-align: center;
            background: rgba(185, 28, 28, 0.18);
            backdrop-filter: blur(4px);
            padding: 8px 12px;
            border-radius: 60px;
            margin-top: 8px;
            font-weight: 500;
        }

        /* additional micro-interactions & responsiveness */
        @media (max-width: 540px) {
            .lock-screen-card {
                padding: 28px 22px 36px;
                border-radius: 32px;
            }

            .lock-screen-title {
                font-size: 26px;
            }

            .lock-avatar {
                width: 82px;
                height: 82px;
                font-size: 30px;
            }

            .lock-user-name {
                font-size: 20px;
            }

            .lock-screen-form input {
                padding: 13px 16px 13px 44px;
            }
        }

        /* nice animated background shift (optional vignette) */
        @keyframes subtleBreathing {
            0% {
                backdrop-filter: blur(10px);
            }

            100% {
                backdrop-filter: blur(11px);
            }
        }

        /* custom scroll (just in case) */
        ::-webkit-scrollbar {
            width: 6px;
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