@extends('layouts.main')

@section('title', 'Communication Settings | JSHB')

@section('content')
    <div class="card">
        <div class="card-head">
            <div>
                <div class="card-title">Communication Settings</div>
                <div class="card-subtitle">Manage notification channels for different user types</div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" style="margin: 20px 20px 0; padding: 12px; background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; border-radius: 6px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.communication-settings.update') }}" method="POST">
            @csrf
            <div class="table-container" style="padding: 20px;">
                <table class="custom-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid #eee; text-align: left;">
                            <th style="padding: 12px 10px;">User Role</th>
                            <th style="padding: 12px 10px; text-align: center;">Email</th>
                            <th style="padding: 12px 10px; text-align: center;">SMS</th>
                            <th style="padding: 12px 10px; text-align: center;">WhatsApp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px 10px; font-weight: 500;">{{ $role->name }}</td>
                                <td style="padding: 12px 10px; text-align: center;">
                                    <input type="checkbox" 
                                           name="settings[{{ $role->id }}][email]" 
                                           value="1" 
                                           style="width: 18px; height: 18px; cursor: pointer;"
                                           {{ isset($settings[$role->id]) && $settings[$role->id]->is_email_enabled ? 'checked' : '' }}>
                                </td>
                                <td style="padding: 12px 10px; text-align: center;">
                                    <input type="checkbox" 
                                           name="settings[{{ $role->id }}][sms]" 
                                           value="1" 
                                           style="width: 18px; height: 18px; cursor: pointer;"
                                           {{ isset($settings[$role->id]) && $settings[$role->id]->is_sms_enabled ? 'checked' : '' }}>
                                </td>
                                <td style="padding: 12px 10px; text-align: center;">
                                    <input type="checkbox" 
                                           name="settings[{{ $role->id }}][whatsapp]" 
                                           value="1" 
                                           style="width: 18px; height: 18px; cursor: pointer;"
                                           {{ isset($settings[$role->id]) && $settings[$role->id]->is_whatsapp_enabled ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 20px; border-top: 1px solid #eee; text-align: right;">
                <button type="submit" class="btn-primary" style="padding: 10px 24px; font-size: 14px; border-radius: 6px; border: none; cursor: pointer; background: #0d6efd; color: white;">
                    <i class="fa-solid fa-save"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection
