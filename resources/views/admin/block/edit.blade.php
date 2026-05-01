@extends('layouts.main')

@section('title', 'Edit Block | JSHB')

@section('content')
<div class="form-container">
    <div class="form-wrapper">
        <div class="form-main">
            @if($errors->any())
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-header">
                <div>
                    <h4>Edit Block</h4>
                    <p>Update the selected block and district mapping.</p>
                </div>
                <a href="{{ route('admin.blocks.index') }}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back
                </a>
            </div>

            <form action="{{ route('admin.blocks.update', $block) }}" method="POST" id="blockForm" data-loader-message="Updating block...">
                @method('PUT')
                @include('admin.block._form', ['submitLabel' => 'Update Block'])
            </form>
        </div>
    </div>
</div>
@endsection
