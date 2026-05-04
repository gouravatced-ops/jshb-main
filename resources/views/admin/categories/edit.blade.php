@extends('layouts.main')

@section('title', 'Edit Property Category | JSHB')

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
                    <h4>Edit Category</h4>
                    <p>Update category information and save the latest changes.</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back
                </a>
            </div>

            <form action="{{ route('admin.categories.update', $categories) }}" method="POST" id="categoryForm">
                @method('PUT')
                @include('admin.categories._form', ['submitLabel' => 'Update Category'])
            </form>
        </div>
    </div>
</div>
@endsection
