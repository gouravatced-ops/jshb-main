<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JESA Dashboard')</title>
    <meta name="description" content="Login to the Jharkhand Engineering Service Association portal." />
    <link rel="https://computered.co.in/jesa/apple-touch-icon" sizes="180x180" href="{{asset(config('panel.faviconIcon'))}}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @php
        $themeName = config('panel.theme', 'forest-green');
        $theme = config('panel.themes.' . $themeName) 
            ?? config('panel.themes.gov-indigo');
    @endphp

    <style>
        :root {
            --primary-color: {{ $theme['primary-color'] ?? '#000' }};
            --primary-hover: {{ $theme['primary-hover'] ?? '#000' }};
            --sidebar-bg: {{ $theme['sidebar-bg'] ?? '#000' }};
            --sidebar-secondary: {{ $theme['sidebar-secondary'] ?? '#000' }};
            --sidebar-hover: {{ $theme['sidebar-hover'] ?? '#000' }};
            --sidebar-active: {{ $theme['sidebar-active'] ?? '#000' }};
            --sidebar-active-secondary: {{ $theme['sidebar-active-secondary'] ?? '#000' }};
        }
    </style>
</head>

<body>
    <!-- LOADER -->
    <div id="loader-overlay">
        <!-- House Scene -->
        <div class="loader-house-scene">
            <!-- Ground -->
            <div class="loader-ground"></div>
            <div class="loader-ground-line"></div>

            <!-- Roof -->
            <div class="loader-roof"></div>
            <div class="loader-roof-inner"></div>

            <!-- House Body with Bricks -->
            <div class="loader-house-body">
                <div class="loader-brick-row">
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                </div>
                <div class="loader-brick-row">
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                </div>
                <div class="loader-brick-row">
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                </div>
                <div class="loader-brick-row">
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                </div>
                <div class="loader-brick-row">
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                    <div class="loader-brick"></div>
                </div>
            </div>

            <!-- Door -->
            <div class="loader-door">
                <div class="loader-door-knob"></div>
            </div>

            <!-- Windows -->
            <div class="loader-window loader-window-left loader-window-lit">
                <div class="loader-window-cross-h"></div>
                <div class="loader-window-cross-v"></div>
            </div>
            <!-- <div class="loader-window loader-window-right">
                <div class="loader-window-cross-h"></div>
                <div class="loader-window-cross-v"></div>
            </div> -->

            <!-- Chimney -->
            <div class="loader-chimney"></div>

            <!-- Smoke -->
            <div class="loader-smoke">
                <div class="loader-smoke-puff"></div>
                <div class="loader-smoke-puff"></div>
                <div class="loader-smoke-puff"></div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="loader-progress-wrap">
            <div class="loader-progress-meta">
                <span class="loader-progress-label">Loading</span>
                <!-- <span class="loader-progress-pct"><span id="loaderPercent">0</span>%</span> -->
            </div>
            <div class="loader-progress-track">
                <div class="loader-progress-bar" id="loaderBar"></div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR -->
    <x-sidebar></x-sidebar>

    <!-- HEADER -->
    <x-header></x-header>

    <!-- MAIN CONTENT -->
    <main id="main">
        @yield('content')
    </main>

    <x-footer></x-footer>

    <!-- Password Reset Modal -->
    <x-password-reset-modal></x-password-reset-modal>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/fieldvalidation.js') }}"></script>
    <script>
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => el.remove());
        }, 3000);
    </script>
</body>

</html>
