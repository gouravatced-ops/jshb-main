<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JSHB Dashboard')</title>
    <meta name="description" content="Jharkhand State Housing Board | Admin Portal" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon')) }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

    <!-- TOASTER -->
    <div class="toaster-wrap" id="toasterWrap"></div>

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

    <!-- SECONDARY LOADER -->
    <div id="secondary-loader-overlay" aria-hidden="true">
        <div class="secondary-loader-card">
            <div class="secondary-bearing-loader">
                <div class="secondary-bearing-outer"></div>
                <div class="secondary-bearing-mid"></div>
                <div class="secondary-bearing-inner"></div>
            </div>
            <div class="secondary-loader-text" id="secondaryLoaderText">Processing...</div>
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

    <!-- GLOBAL IMAGE POPUP MODAL -->
    <div id="globalImageModal" class="image-modal">
        <span class="image-modal-close">&times;</span>
        <img class="image-modal-content" id="globalImageModalImg">
        <div class="image-modal-caption" id="globalImageCaption"></div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/fieldvalidation.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const modal = document.getElementById('globalImageModal');
            const modalImg = document.getElementById('globalImageModalImg');
            const caption = document.getElementById('globalImageCaption');
            const closeBtn = document.querySelector('.image-modal-close');

            // ALL IMAGE POPUP CLASS
            document.querySelectorAll('.imagePopupModal').forEach(function(img) {

                img.addEventListener('click', function() {

                    if (!this.src) return;

                    modal.style.display = 'block';
                    modalImg.src = this.src;
                    caption.innerText = this.alt || '';
                });
            });

            // CLOSE BUTTON
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            // OUTSIDE CLICK CLOSE
            modal.addEventListener('click', function(e) {

                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });

            // ESC CLOSE
            document.addEventListener('keydown', function(e) {

                if (e.key === 'Escape') {
                    modal.style.display = 'none';
                }
            });

        });
    </script>
    <script>
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => el.remove());
        }, 3000);
    </script>
</body>

</html>