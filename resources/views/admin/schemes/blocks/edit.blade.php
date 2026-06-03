@extends('layouts.main')
@section('title', 'Edit Scheme Block | ' . $scheme->scheme_name)
@section('content')
<div class="form-container">
    <div class="form-wrapper">
        <div class="form-main">
            @if($errors->any())
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="form-header">
                <div>
                    <h4>Edit Scheme Block</h4>
                    <p>Update block information for scheme: <strong>{{ $scheme->scheme_name }}</strong></p>
                    @if($block->block_name)
                        <p class="text-muted mt-1" style="font-size: 13px;">Editing: <strong>{{ $block->block_name }}</strong></p>
                    @endif
                </div>
                <a href="{{ route('admin.schemes.blocks.index', $scheme) }}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back to Blocks
                </a>
            </div>

            <form action="{{ route('admin.schemes.blocks.update', [$scheme, $block]) }}" method="POST" id="schemeBlockForm">
                @include('admin.schemes.blocks._form', ['submitLabel' => 'Update Block'])
            </form>
        </div>
    </div>
</div>
@endsection