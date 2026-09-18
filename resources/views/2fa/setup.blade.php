@extends('layouts.main')

@section('content')
<div class="container-fluid py-3" style="min-height: calc(100vh - 100px); background: #f8fafc; display: flex; align-items: center; justify-content: center;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-7">
            <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
                <!-- Header -->
                <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #1f7b4d 0%, #279f64 100%); border-bottom: none;">
                    <i class="fa-solid fa-shield-halved mb-1" style="font-size: 1.8rem; opacity: 0.9;"></i>
                    <h5 class="mb-0 fw-bold" style="letter-spacing: 0.5px;">Two-Factor Authentication (2FA)</h5>
                </div>

                <div class="card-body p-3 p-md-4 bg-white">
                    
                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center mb-3 py-2 px-3" style="border-radius: 8px; font-weight: 500; font-size: 0.9rem;">
                            <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @if ($user->google2fa_enabled)
                        <div class="text-center py-2">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3" style="width: 60px; height: 60px;">
                                <i class="fa-solid fa-check fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">2FA is currently ENABLED</h6>
                            <p class="text-muted mb-4 px-md-5 small">Your account is highly secure. You will be required to enter a code from your authenticator app each time you log in.</p>
                            
                            <form method="POST" action="{{ route('2fa.disable') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger fw-bold rounded-pill px-4 py-1" onclick="return confirm('Are you sure you want to disable 2FA? This will make your account significantly less secure.');">
                                    <i class="fa-solid fa-power-off me-2"></i> Disable 2FA
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info d-flex align-items-center mb-3 py-2 px-3" style="border-radius: 8px; background-color: #f0f9ff; border-color: #bde0fe; color: #0284c7;">
                            <i class="fa-solid fa-circle-info me-2 fs-5"></i>
                            <div style="font-size: 0.85rem;">
                                <strong>2FA is DISABLED.</strong> We recommend setting it up to protect your account.
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <!-- Left Column: QR Code -->
                            <div class="col-md-6 text-center border-end-md pb-3 pb-md-0 px-md-3">
                                <div class="badge bg-light text-dark border rounded-pill px-3 py-1 mb-2 shadow-sm">
                                    <i class="fa-solid fa-1 text-primary me-1"></i> Step 1: Scan QR Code
                                </div>
                                <p class="text-muted mb-2" style="font-size: 0.8rem;">Open Authenticator app and scan the code below.</p>
                                
                                <div class="p-2 bg-white d-inline-block rounded shadow-sm border mb-3" style="transition: transform 0.3s; cursor: crosshair;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    <div style="width: 140px; height: 140px;">
                                        {!! $qrCodeSvg !!}
                                    </div>
                                </div>
                                
                                <div>
                                    <p class="text-muted fw-bold mb-1" style="font-size: 0.75rem;">Can't scan? Use setup key:</p>
                                    <code class="d-block p-2 bg-light border rounded text-dark font-monospace" style="letter-spacing: 1px; font-size: 0.85rem;">{{ $user->google2fa_secret }}</code>
                                </div>
                            </div>

                            <!-- Right Column: Verification -->
                            <div class="col-md-6 text-center px-md-3 mt-3 mt-md-0">
                                <div class="badge bg-light text-dark border rounded-pill px-3 py-1 mb-2 shadow-sm">
                                    <i class="fa-solid fa-2 text-primary me-1"></i> Step 2: Verify
                                </div>
                                <p class="text-muted mb-3" style="font-size: 0.8rem;">Enter the 6-digit code from your app.</p>
                                
                                <form method="POST" action="{{ route('2fa.enable') }}">
                                    @csrf
                                    <div class="mb-3 text-start">
                                        <label for="totp" class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Authenticator Code</label>
                                        <input type="text" 
                                               class="form-control text-center" 
                                               id="totp" 
                                               name="totp" 
                                               required 
                                               maxlength="6" 
                                               autocomplete="off" 
                                               placeholder="000000" 
                                               style="letter-spacing: 6px; font-size: 1.2rem; font-weight: 600; border-radius: 8px; background: #f8fafc; border: 2px solid #e2e8f0; color: #334155; transition: border-color 0.3s; padding: 0.5rem;"
                                               onfocus="this.style.borderColor='#279f64'; this.style.boxShadow='0 0 0 0.2rem rgba(39, 159, 100, 0.25)'"
                                               onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);"
                                        >
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold text-uppercase" style="background-color: #279f64; border: none; font-size: 0.85rem; letter-spacing: 1px; box-shadow: 0 3px 5px rgba(39, 159, 100, 0.3); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(39, 159, 100, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 5px rgba(39, 159, 100, 0.3)'">
                                        Verify & Enable 2FA
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('admin.dashboard') }}" class="text-muted text-decoration-none fw-bold small" style="transition: color 0.3s;" onmouseover="this.style.color='#1f7b4d'" onmouseout="this.style.color='#6c757d'">
                    <i class="fa-solid fa-arrow-left me-1"></i> Return to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #e2e8f0;
        }
    }
    svg {
        max-width: 100%;
        height: auto;
    }
</style>
@endsection
