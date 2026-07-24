<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JSHB Dashboard')</title>
    <meta name="description" content="Jharkhand State Housing Board | Admin Portal" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon')) }}">
    
    @if(Auth::check())
    <meta name="user-id" content="{{ Auth::id() }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

    <!-- First Login Setup Modal -->
    <x-first-login-setup-modal></x-first-login-setup-modal>

    <!-- Quick PIN Settings Modal -->
    <x-quick-pin-modal></x-quick-pin-modal>

    <!-- Internal Password Update Modal -->
    <x-internal-password-modal></x-internal-password-modal>

    <!-- GLOBAL IMAGE POPUP MODAL -->
    <div id="globalImageModal" class="image-modal">
        <span class="image-modal-close">&times;</span>
        <img class="image-modal-content" id="globalImageModalImg">
        <div class="image-modal-caption" id="globalImageCaption"></div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/fieldvalidation.js') }}"></script>
    <script src="{{ asset('js/chart.umd.min.js') }}"></script>
    <script>
        // Sparkline helper
        function sparkline(id, data, color) {
            new Chart(document.getElementById(id), {
                type: 'line',
                data: {
                    labels: data.map((_, i) => i),
                    datasets: [{
                        data,
                        borderColor: color,
                        borderWidth: 1.5,
                        pointRadius: 0,
                        fill: true,
                        backgroundColor: color + '22',
                        tension: 0.4
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    },
                    animation: false,
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        sparkline('sparkline1', [10, 14, 12, 16, 20, 18, 22, 24, 21, 26, 28, 30], '#1a7a6e');
        sparkline('sparkline2', [8, 10, 9, 12, 11, 15, 13, 16, 14, 17, 16, 18], '#1a7a4a');
        sparkline('sparkline3', [15, 20, 18, 25, 22, 30, 27, 32, 29, 35, 33, 38], '#f5c518');
        sparkline('sparkline4', [30, 35, 32, 40, 38, 44, 42, 48, 45, 50, 48, 54], '#0f1b2d');

        // Transactions line chart
        new Chart(document.getElementById('txnChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Amount (in Cr)',
                    data: [13, 19, 9, 25, 23, 31, 30, 43, 42, 38, 45, 55],
                    borderColor: '#1a7a4a',
                    backgroundColor: 'rgba(26,122,74,0.08)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#1a7a4a',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            font: {
                                size: 11
                            },
                            color: '#6b7a8d'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6b7a8d'
                        }
                    },
                    y: {
                        grid: {
                            color: '#f0f2f5'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6b7a8d'
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: true
            }
        });

        // Allottees bar chart
        new Chart(document.getElementById('allotChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Allottees',
                    data: [310, 290, 420, 300, 300, 340, 295, 300, 420, 300, 295, 550],
                    backgroundColor: '#0f1b2d',
                    borderRadius: 1,
                    barThickness: 14
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            font: {
                                size: 11
                            },
                            color: '#6b7a8d'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6b7a8d'
                        }
                    },
                    y: {
                        grid: {
                            color: '#f0f2f5'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6b7a8d'
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: true
            }
        });
    </script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userIdMeta = document.querySelector('meta[name="user-id"]');
            if (userIdMeta && window.Echo) {
                const userId = userIdMeta.getAttribute('content');
                
                window.Echo.private('App.Models.User.' + userId)
                    .listen('.EngineerNotification', (e) => {
                        console.log('Realtime Notification Received:', e);
                        
                        // Show Toaster
                        const toasterWrap = document.getElementById('toasterWrap');
                        if (toasterWrap) {
                            const toast = document.createElement('div');
                            toast.className = 'custom-toast slide-in bg-white shadow-lg rounded-lg border-l-4 p-4 mb-3';
                            toast.style.borderLeftColor = '#3b82f6';
                            toast.style.width = '300px';
                            toast.style.position = 'relative';
                            toast.innerHTML = `
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #1e293b;"><i class="fa-solid fa-bell text-primary me-2"></i>New Notification</h6>
                                        <p class="mb-0 small" style="color: #475569;">${e.notification.subject || 'You have a new update.'}</p>
                                    </div>
                                    <button type="button" class="btn-close btn-sm" onclick="this.parentElement.parentElement.remove()" style="font-size: 0.65rem;"></button>
                                </div>
                                ${e.notification.link ? `<div class="mt-2 text-end"><a href="${e.notification.link}" class="btn btn-sm btn-primary py-1 px-2" style="font-size: 0.75rem;">View</a></div>` : ''}
                            `;
                            toasterWrap.appendChild(toast);
                            
                            setTimeout(() => {
                                if(toast.parentElement) toast.remove();
                            }, 8000);
                        }

                        // Play a sound (optional)
                        try {
                            const audio = new Audio('/sounds/notification.mp3');
                            audio.play().catch(e => {});
                        } catch(err) {}
                        
                        // Increment badge if present
                        const badge = document.querySelector('.notification-badge');
                        if (badge) {
                            let count = parseInt(badge.innerText) || 0;
                            badge.innerText = count + 1;
                            badge.style.display = 'inline-block';
                        }
                    });
            }
        });
    </script>
</body>

</html>
