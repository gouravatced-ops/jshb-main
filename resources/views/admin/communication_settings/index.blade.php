@extends('layouts.main')

@section('title', 'Communication Settings | Admin | JSHB')

@section('content')
<div class="card">
    <!-- Header -->
    <div class="card-head">
        <div>
            <div class="card-title">Communication & Gateway Settings</div>
            <div class="card-subtitle">Configure role-based automated alert channels (Email, SMS & WhatsApp) across all workflow triggers</div>
        </div>
        <div class="card-actions">
            <a class="btn-sky" href="{{ route('admin.notifications.index') }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-tower-broadcast"></i>
                <span>Broadcast Notification</span>
            </a>
            <a class="btn-pink" href="{{ route('admin.notices.index') }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Notices Registry</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success" style="margin: 20px 24px 0;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="margin: 20px 24px 0; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 6px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Metrics Cards Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin: 20px 24px 16px;">
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Configured Roles</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ number_format($totalRoles) }} Roles</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-users-gear"></i>
            </div>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #1e40af; font-weight: 700; letter-spacing: 0.5px;">Email Gateway</div>
                <div style="font-size: 20px; font-weight: 800; color: #1d4ed8; margin-top: 2px;">{{ number_format($emailEnabledCount) }} Active</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-envelope"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">SMS Gateway</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ number_format($smsEnabledCount) }} Active</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-comment-sms"></i>
            </div>
        </div>

        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #065f46; font-weight: 700; letter-spacing: 0.5px;">WhatsApp Gateway</div>
                <div style="font-size: 20px; font-weight: 800; color: #047857; margin-top: 2px;">{{ number_format($whatsappEnabledCount) }} Active</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #d1fae5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
        </div>
    </div>

    <!-- Quick Controls & Search Bar -->
    <div style="margin: 0 24px 16px; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <!-- Live Search Field -->
        <div style="flex: 1; min-width: 220px; max-width: 360px; position: relative;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px;"></i>
            <input type="text" id="roleSearchInput" placeholder="Filter roles by name or code..." autocomplete="off"
                style="width: 100%; padding: 7px 12px 7px 32px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12.5px; color: #1e293b; background: #fff; outline: none;">
        </div>

        <!-- Batch Column Toggles -->
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <span style="font-size: 12px; font-weight: 600; color: #64748b;">Batch Toggles:</span>
            <button type="button" class="btn-toggle-col" data-target="email" style="background: #fff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 5px 10px; font-size: 11.5px; font-weight: 600; color: #0284c7; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fa-solid fa-envelope"></i> All Email
            </button>
            <button type="button" class="btn-toggle-col" data-target="sms" style="background: #fff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 5px 10px; font-size: 11.5px; font-weight: 600; color: #16a34a; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fa-solid fa-comment-sms"></i> All SMS
            </button>
            <button type="button" class="btn-toggle-col" data-target="whatsapp" style="background: #fff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 5px 10px; font-size: 11.5px; font-weight: 600; color: #059669; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fa-brands fa-whatsapp"></i> All WhatsApp
            </button>
            <button type="button" id="btnSelectAllChannels" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 5px 10px; font-size: 11.5px; font-weight: 600; color: #334155; cursor: pointer;">
                Enable All
            </button>
        </div>
    </div>

    <!-- Main Settings Form -->
    <form action="{{ route('admin.communication-settings.update') }}" method="POST">
        @csrf

        <!-- Settings Table -->
        <div class="table-responsive">
            <table class="ep-table" id="settingsTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>User Role & Hierarchy</th>
                        <th style="text-align: center; width: 150px;">
                            <div style="display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fa-solid fa-envelope" style="color: #0284c7;"></i>
                                <span>Email</span>
                            </div>
                        </th>
                        <th style="text-align: center; width: 150px;">
                            <div style="display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fa-solid fa-comment-sms" style="color: #16a34a;"></i>
                                <span>SMS</span>
                            </div>
                        </th>
                        <th style="text-align: center; width: 150px;">
                            <div style="display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fa-brands fa-whatsapp" style="color: #059669;"></i>
                                <span>WhatsApp</span>
                            </div>
                        </th>
                        <th style="text-align: center; width: 160px;">Channel Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $index => $role)
                        @php
                            $setting = $settings[$role->id] ?? null;
                            $emailOn = (bool)($setting && $setting->is_email_enabled);
                            $smsOn = (bool)($setting && $setting->is_sms_enabled);
                            $waOn = (bool)($setting && $setting->is_whatsapp_enabled);
                            $activeCount = ($emailOn ? 1 : 0) + ($smsOn ? 1 : 0) + ($waOn ? 1 : 0);
                        @endphp
                        <tr class="role-row" data-name="{{ strtolower($role->name) }}" data-code="{{ strtolower($role->short_name ?? $role->slug) }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 34px; height: 34px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; flex-shrink: 0; border: 1px solid #bae6fd;">
                                        {{ $role->short_name ?: strtoupper(substr($role->slug, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="table-name" style="font-weight: 700; color: #0f172a; font-size: 13.5px;">
                                            {{ $role->name }}
                                        </div>
                                        <div class="table-email" style="font-size: 11.5px; color: #64748b; margin-top: 1px; font-family: monospace;">
                                            {{ $role->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email Toggle -->
                            <td style="text-align: center;">
                                <label class="channel-toggle-wrapper">
                                    <input type="checkbox"
                                           class="channel-cb channel-email"
                                           name="settings[{{ $role->id }}][email]"
                                           value="1"
                                           {{ $emailOn ? 'checked' : '' }}>
                                    <span class="toggle-pill email-pill">
                                        <i class="fa-solid fa-envelope"></i>
                                        <span class="toggle-text">{{ $emailOn ? 'On' : 'Off' }}</span>
                                    </span>
                                </label>
                            </td>

                            <!-- SMS Toggle -->
                            <td style="text-align: center;">
                                <label class="channel-toggle-wrapper">
                                    <input type="checkbox"
                                           class="channel-cb channel-sms"
                                           name="settings[{{ $role->id }}][sms]"
                                           value="1"
                                           {{ $smsOn ? 'checked' : '' }}>
                                    <span class="toggle-pill sms-pill">
                                        <i class="fa-solid fa-comment-sms"></i>
                                        <span class="toggle-text">{{ $smsOn ? 'On' : 'Off' }}</span>
                                    </span>
                                </label>
                            </td>

                            <!-- WhatsApp Toggle -->
                            <td style="text-align: center;">
                                <label class="channel-toggle-wrapper">
                                    <input type="checkbox"
                                           class="channel-cb channel-whatsapp"
                                           name="settings[{{ $role->id }}][whatsapp]"
                                           value="1"
                                           {{ $waOn ? 'checked' : '' }}>
                                    <span class="toggle-pill wa-pill">
                                        <i class="fa-brands fa-whatsapp"></i>
                                        <span class="toggle-text">{{ $waOn ? 'On' : 'Off' }}</span>
                                    </span>
                                </label>
                            </td>

                            <!-- Active Summary Badge -->
                            <td style="text-align: center;">
                                <span class="badge-status role-status-badge {{ $activeCount > 0 ? 'active' : 'inactive' }}">
                                    <i class="fa-solid fa-circle"></i>
                                    <span class="badge-count-text">{{ $activeCount }}/3 Channels</span>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Sticky Footer Action Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-top: 1px solid #f1f5f9; background: #fff; flex-wrap: wrap; gap: 12px;">
            <div style="font-size: 13px; color: #64748b;">
                <i class="fa-solid fa-shield-halved text-primary me-1"></i>
                Settings will be applied immediately across all automatic notification triggers.
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('admin.communication-settings.index') }}" class="btn-secondary" style="padding: 9px 18px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; color: #64748b; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; background: #fff;">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset</span>
                </a>
                <button type="submit" class="btn-pink" style="padding: 10px 24px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; border-radius: 6px; border: none;">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Communication Settings</span>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
/* Modern Channel Toggle Switches */
.channel-toggle-wrapper {
    display: inline-block;
    cursor: pointer;
    margin: 0;
    user-select: none;
}

.channel-toggle-wrapper input[type="checkbox"] {
    display: none;
}

.toggle-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #94a3b8;
    transition: all 0.2s ease;
}

.toggle-pill i {
    font-size: 11px;
}

/* Checked States */
.channel-toggle-wrapper input[type="checkbox"]:checked + .email-pill {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
    box-shadow: 0 1px 3px rgba(37, 99, 235, 0.12);
}

.channel-toggle-wrapper input[type="checkbox"]:checked + .sms-pill {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #15803d;
    box-shadow: 0 1px 3px rgba(22, 163, 74, 0.12);
}

.channel-toggle-wrapper input[type="checkbox"]:checked + .wa-pill {
    background: #ecfdf5;
    border-color: #a7f3d0;
    color: #047857;
    box-shadow: 0 1px 3px rgba(5, 150, 105, 0.12);
}

.toggle-pill:hover {
    transform: translateY(-1px);
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSearchInput = document.getElementById('roleSearchInput');
    const roleRows = document.querySelectorAll('.role-row');
    const channelCheckboxes = document.querySelectorAll('.channel-cb');

    // 1. Update toggle text & row summary badge
    function updateRowBadge(row) {
        if (!row) return;
        const cbs = row.querySelectorAll('.channel-cb');
        let active = 0;
        cbs.forEach(cb => {
            const pill = cb.nextElementSibling;
            const textSpan = pill ? pill.querySelector('.toggle-text') : null;
            if (cb.checked) {
                active++;
                if (textSpan) textSpan.textContent = 'On';
            } else {
                if (textSpan) textSpan.textContent = 'Off';
            }
        });

        const badge = row.querySelector('.role-status-badge');
        const badgeText = row.querySelector('.badge-count-text');
        if (badge && badgeText) {
            badgeText.textContent = active + '/3 Channels';
            if (active > 0) {
                badge.classList.remove('inactive');
                badge.classList.add('active');
            } else {
                badge.classList.remove('active');
                badge.classList.add('inactive');
            }
        }
    }

    channelCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const row = this.closest('.role-row');
            updateRowBadge(row);
        });
    });

    // 2. Live Role Search Filter
    if (roleSearchInput) {
        roleSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            roleRows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const code = row.getAttribute('data-code') || '';
                if (name.includes(query) || code.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // 3. Batch Column Toggles
    document.querySelectorAll('.btn-toggle-col').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const targetCbs = document.querySelectorAll(`.channel-${target}`);
            const allChecked = Array.from(targetCbs).every(cb => cb.checked);
            
            targetCbs.forEach(cb => {
                cb.checked = !allChecked;
                updateRowBadge(cb.closest('.role-row'));
            });
        });
    });

    // 4. Enable All Channels
    const btnSelectAllChannels = document.getElementById('btnSelectAllChannels');
    if (btnSelectAllChannels) {
        btnSelectAllChannels.addEventListener('click', function() {
            const allChecked = Array.from(channelCheckboxes).every(cb => cb.checked);
            channelCheckboxes.forEach(cb => {
                cb.checked = !allChecked;
                updateRowBadge(cb.closest('.role-row'));
            });
            this.textContent = allChecked ? 'Enable All' : 'Disable All';
        });
    }
});
</script>
@endpush
