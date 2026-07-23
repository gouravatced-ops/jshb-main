@extends('layouts.main')

@section('title', 'Bulk Notifications | JSHB')

@section('content')
    <div class="card">
        @if (session('success'))
            <div class="alert alert-success" style="margin: 20px 20px 0;">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error" style="margin: 20px 20px 0;">
                {{ session('error') }}
            </div>
        @endif

        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3>{{ $pageTitle }}</h3>
                <p class="text-muted">Notify selected users via multiple channels.</p>
            </div>
            <div>
                <a href="{{ $btnLink }}" class="btn btn-info">{{ $btnText }}</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.notifications.send') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                
                <div class="form-group mb-4">
                    <label>Select Users <span class="text-danger">*</span></label>
                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="selectAllUsers">
                            <label class="form-check-label" for="selectAllUsers">
                                <strong>Select All</strong>
                            </label>
                        </div>
                        <hr>
                        @foreach($users as $user)
                            <div class="form-check">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-check-input user-checkbox" id="user_{{ $user->id }}">
                                <label class="form-check-label" for="user_{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->designation ?? $user->user_type }}) - {{ $user->email }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('user_ids') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label>Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control" required placeholder="Enter notification subject">
                    @error('subject') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label>Message <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control" rows="4" required placeholder="Enter your message here..."></textarea>
                    @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label>Channels</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="send_email" class="form-check-input" id="send_email" checked>
                            <label class="form-check-label" for="send_email">Email</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="send_sms" class="form-check-input" id="send_sms">
                            <label class="form-check-label" for="send_sms">SMS</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="send_whatsapp" class="form-check-input" id="send_whatsapp">
                            <label class="form-check-label" for="send_whatsapp">WhatsApp</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        Send Notifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('selectAllUsers').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.user-checkbox');
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>
    @endpush
@endsection
