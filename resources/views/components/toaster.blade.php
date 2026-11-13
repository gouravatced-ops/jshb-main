{{-- Universal Toaster Component: Works without any external JavaScript --}}
@php
    $toasts = [];

    if (session()->has('success')) {
        $toasts[] = [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => session('success'),
            'icon'    => 'fa-circle-check',
            'color'   => '#10b981',
            'bg'      => '#ecfdf5',
            'border'  => '#a7f3d0'
        ];
    }

    if (session()->has('error')) {
        $toasts[] = [
            'type'    => 'error',
            'title'   => 'Error',
            'message' => session('error'),
            'icon'    => 'fa-circle-xmark',
            'color'   => '#ef4444',
            'bg'      => '#fef2f2',
            'border'  => '#fecaca'
        ];
    }

    if (session()->has('warning')) {
        $toasts[] = [
            'type'    => 'warning',
            'title'   => 'Warning',
            'message' => session('warning'),
            'icon'    => 'fa-triangle-exclamation',
            'color'   => '#f59e0b',
            'bg'      => '#fffbeb',
            'border'  => '#fde68a'
        ];
    }

    if (session()->has('info')) {
        $toasts[] = [
            'type'    => 'info',
            'title'   => 'Notice',
            'message' => session('info'),
            'icon'    => 'fa-circle-info',
            'color'   => '#0ea5e9',
            'bg'      => '#f0f9ff',
            'border'  => '#bae6fd'
        ];
    }

    if (session()->has('status')) {
        $toasts[] = [
            'type'    => 'info',
            'title'   => 'Status Update',
            'message' => session('status'),
            'icon'    => 'fa-bell',
            'color'   => '#6366f1',
            'bg'      => '#eef2ff',
            'border'  => '#c7d2fe'
        ];
    }

    $viewErrors = session()->get('errors') ?? \Illuminate\Support\Facades\View::shared('errors') ?? ($errors ?? null);
    if ($viewErrors && is_object($viewErrors) && method_exists($viewErrors, 'any') && $viewErrors->any()) {
        $errorMessages = $viewErrors->all();
        $messageText = count($errorMessages) === 1
            ? $errorMessages[0]
            : '<ul style="margin: 0; padding-left: 16px;">' . implode('', array_map(fn($m) => "<li>{$m}</li>", $errorMessages)) . '</ul>';

        $toasts[] = [
            'type'    => 'error',
            'title'   => 'Validation Error',
            'message' => $messageText,
            'icon'    => 'fa-triangle-exclamation',
            'color'   => '#dc2626',
            'bg'      => '#fef2f2',
            'border'  => '#fca5a5'
        ];
    }
@endphp

<div class="toaster-wrap" id="toasterWrap" aria-live="polite" aria-atomic="true">
    @foreach ($toasts as $index => $t)
        <div class="toast-item toast-{{ $t['type'] }} toast-auto-dismiss"
             role="alert"
             data-toast-id="{{ $index }}"
             style="border-left: 4px solid {{ $t['color'] }};">
            <div class="toast-icon-wrap" style="background: {{ $t['bg'] }}; color: {{ $t['color'] }}; border: 1px solid {{ $t['border'] }};">
                <i class="fa-solid {{ $t['icon'] }}"></i>
            </div>
            <div class="toast-content-wrap">
                <div class="toast-head-title">{{ $t['title'] }}</div>
                <div class="toast-head-msg">{!! $t['message'] !!}</div>
            </div>
            <button type="button" class="toast-dismiss-btn" onclick="this.closest('.toast-item').remove()" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="toast-progress-bar" style="background: {{ $t['color'] }};"></div>
        </div>
    @endforeach
</div>

<style>
    /* Standalone & Universal Toaster Styles */
    .toaster-wrap {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 999999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
        max-width: 420px;
        width: calc(100% - 40px);
    }

    .toast-item {
        pointer-events: auto;
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #ffffff;
        border-radius: 6px;
        padding: 12px 14px 14px 12px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.14), 0 2px 6px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        animation: toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .toast-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .toast-content-wrap {
        flex: 1;
        min-width: 0;
    }

    .toast-head-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 3px;
        letter-spacing: 0.2px;
    }

    .toast-head-msg {
        font-size: 12.5px;
        color: #475569;
        line-height: 1.45;
        word-break: break-word;
    }

    .toast-head-msg ul {
        margin: 4px 0 0;
        padding-left: 18px;
    }

    .toast-head-msg li {
        margin-bottom: 2px;
    }

    .toast-dismiss-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 2px 4px;
        font-size: 13px;
        line-height: 1;
        border-radius: 4px;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }

    .toast-dismiss-btn:hover {
        color: #0f172a;
        background: #f1f5f9;
    }

    .toast-progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        opacity: 0.85;
        animation: toastProgress 4.5s linear forwards;
    }

    @keyframes toastSlideIn {
        from {
            opacity: 0;
            transform: translateX(40px) scale(0.96);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    @keyframes toastProgress {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }

    @media (max-width: 480px) {
        .toaster-wrap {
            top: 12px;
            right: 12px;
            left: 12px;
            width: auto;
            max-width: none;
        }
    }
</style>

<script>
    // Self-dismissing timer without requiring any developer intervention
    (function() {
        const autoDismissToasts = document.querySelectorAll('.toast-auto-dismiss');
        if (autoDismissToasts.length > 0) {
            setTimeout(function() {
                autoDismissToasts.forEach(function(toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(30px)';
                    setTimeout(function() {
                        if (toast && toast.parentNode) {
                            toast.remove();
                        }
                    }, 350);
                });
            }, 4500);
        }

        // Global helper for optional programmatic toast triggering (if ever needed via JS)
        window.showToast = function(title, msg, type) {
            const wrap = document.getElementById('toasterWrap');
            if (!wrap) return;

            const config = {
                success: { color: '#10b981', bg: '#ecfdf5', border: '#a7f3d0', icon: 'fa-circle-check', title: title || 'Success' },
                error:   { color: '#ef4444', bg: '#fef2f2', border: '#fecaca', icon: 'fa-circle-xmark', title: title || 'Error' },
                warning: { color: '#f59e0b', bg: '#fffbeb', border: '#fde68a', icon: 'fa-triangle-exclamation', title: title || 'Warning' },
                info:    { color: '#0ea5e9', bg: '#f0f9ff', border: '#bae6fd', icon: 'fa-circle-info', title: title || 'Notice' }
            };

            const t = config[type] || config.info;
            const el = document.createElement('div');
            el.className = 'toast-item toast-auto-dismiss';
            el.style.borderLeft = `4px solid ${t.color}`;
            el.innerHTML = `
                <div class="toast-icon-wrap" style="background: ${t.bg}; color: ${t.color}; border: 1px solid ${t.border};">
                    <i class="fa-solid ${t.icon}"></i>
                </div>
                <div class="toast-content-wrap">
                    <div class="toast-head-title">${t.title}</div>
                    <div class="toast-head-msg">${msg}</div>
                </div>
                <button type="button" class="toast-dismiss-btn" onclick="this.closest('.toast-item').remove()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="toast-progress-bar" style="background: ${t.color};"></div>
            `;
            wrap.appendChild(el);

            setTimeout(function() {
                el.style.opacity = '0';
                el.style.transform = 'translateX(30px)';
                setTimeout(function() { el.remove(); }, 350);
            }, 4500);
        };
    })();
</script>
