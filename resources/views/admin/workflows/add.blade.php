@extends('layouts.main')

@section('title', 'Add Workflow | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Create New Workflow</div>
            <div class="card-subtitle">Define application processing stages, assigned roles, and operational permissions</div>
        </div>
        <div class="card-actions">
            <a class="btn-pink" href="{{ route('admin.workflows.index') }}" style="background: #64748b; text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Back to Workflows
            </a>
        </div>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger" style="margin: 20px 20px 0; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 6px;">
            <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please fix the following errors:</div>
            <ul class="mb-0 ps-3" style="font-size: 13.5px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.workflows.store') }}" method="POST" id="workflowForm">
        @include('admin.workflows._form', ['submitLabel' => 'Save & Create Workflow'])
    </form>
</div>
@endsection
