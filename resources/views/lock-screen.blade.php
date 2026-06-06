<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lock Screen | {{ config('panel.organization') }}</title>
    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* CLASSIC NAVY BLUE & DARK YELLOW THEME - SOLID COLORS */
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0a1a2f;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            position: relative;
        }

        /* Simple background pattern */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                repeating-linear-gradient(45deg, rgba(245, 197, 24, 0.02) 0px, rgba(245, 197, 24, 0.02) 2px, transparent 2px, transparent 8px);
            pointer-events: none;
        }

        /* Main card container - solid navy */
        .lock-screen-card {
            position: relative;
            z-index: 2;
            width: min(480px, calc(100% - 32px));
            padding: 40px 36px 48px;
            background: #0d1f36;
            border: 2px solid #d4a800;
            box-shadow: 12px 12px 0 rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .lock-screen-card:hover {
            transform: translate(-2px, -2px);
            box-shadow: 16px 16px 0 rgba(0, 0, 0, 0.25);
        }

        /* Logo / brand area */
        .lock-screen-logo {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid #d4a800;
        }

        .lock-screen-logo i {
            font-size: 2.5rem;
            color: #d4a800;
            margin-bottom: 12px;
            display: inline-block;
        }

        .lock-screen-title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #d4a800;
            margin: 0 0 8px;
        }

        .lock-screen-subtitle {
            font-size: 13px;
            color: #8a9bb0;
            font-weight: 500;
        }

        /* Avatar - classic circle */
        .lock-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #d4a800;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: 700;
            color: #0a1a2f;
            margin: 0 auto 20px;
            overflow: hidden;
            border: 3px solid #d4a800;
        }

        .lock-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* User info */
        .lock-user-name {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 6px;
            color: #ffffff;
        }

        .lock-user-email {
            text-align: center;
            font-size: 13px;
            color: #8a9bb0;
            margin: 0 0 8px;
        }

        /* Form */
        .lock-screen-form {
            margin-top: 28px;
        }

        /* Input field */
        .lock-screen-form input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #2a3f5a;
            background: #061220;
            color: #ffffff;
            font-size: 15px;
            font-weight: 500;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.2s ease;
            margin-bottom: 16px;
        }

        .lock-screen-form input:focus {
            outline: none;
            border-color: #d4a800;
            background: #0a1a2f;
        }

        .lock-screen-form input::placeholder {
            color: #5a6e85;
            font-weight: 400;
        }

        /* Button - solid dark yellow */
        .lock-screen-form button {
            width: 100%;
            padding: 14px 18px;
            border: none;
            background: #d4a800;
            color: #0a1a2f;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .lock-screen-form button i {
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .lock-screen-form button:hover {
            background: #b89200;
            transform: translateY(-1px);
        }

        .lock-screen-form button:active {
            transform: translateY(1px);
        }

        /* Error message */
        .lock-screen-error {
            color: #d4a800;
            font-size: 13px;
            text-align: center;
            background: rgba(212, 168, 0, 0.1);
            padding: 10px 12px;
            margin-top: 12px;
            border-left: 3px solid #d4a800;
            font-weight: 500;
        }

        /* Simple divider */
        .lock-divider {
            height: 1px;
            background: #2a3f5a;
            margin: 24px 0 0;
        }

        /* Responsive */
        @media (max-width: 540px) {
            .lock-screen-card {
                padding: 28px 24px 36px;
            }

            .lock-screen-title {
                font-size: 24px;
            }

            .lock-avatar {
                width: 80px;
                height: 80px;
                font-size: 32px;
            }

            .lock-user-name {
                font-size: 20px;
            }

            .lock-screen-form input,
            .lock-screen-form button {
                padding: 12px 16px;
            }
        }

        /* Classic scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0a1a2f;
        }

        ::-webkit-scrollbar-thumb {
            background: #d4a800;
            border-radius: 0;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #b89200;
        }
    </style>
</head>

<body>
    <div class="lock-screen-card">
        <div class="lock-screen-logo">
            <i class="fa-solid fa-lock"></i>
            <div class="lock-screen-title">Session Locked</div>
            <p class="lock-screen-subtitle">Enter password to unlock your dashboard</p>
        </div>

        <div class="lock-avatar">
            @if ($user->photo)
                <img src="{{ asset('storage/photos/' . $user->photo) }}" alt="{{ $user->name }}">
            @else
                {{ strtoupper(substr($user->name, 0, 2)) }}
            @endif
        </div>

        <h3 class="lock-user-name">{{ $user->name }}</h3>
        <p class="lock-user-email">{{ $user->email }}</p>

        <form action="{{ route('lock.unlock') }}" method="POST" class="lock-screen-form">
            @csrf
            <input type="password" name="password" placeholder="Enter your password" autocomplete="current-password"
                required>
            @if ($errors->has('password'))
                <div class="lock-screen-error">{{ $errors->first('password') }}</div>
            @endif
            @if (session('error'))
                <div class="lock-screen-error">{{ session('error') }}</div>
            @endif
            <button type="submit">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                Unlock Dashboard
            </button>
        </form>
        <div class="lock-divider"></div>
    </div>
</body>

</html>
