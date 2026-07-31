@extends('layouts.main')

@section('title', 'System Reset | JSHB')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3 mt-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">System Reset</h4>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> DANGER ZONE</h5>
                    </div>
                    <div class="card-body text-center p-5">
                        <h4 class="text-danger mb-4">Reset Entire Project</h4>
                        <p class="mb-4">This action will perform a complete reset of the project. It will:</p>
                        <ul class="text-start mb-4" style="display: inline-block; text-align: left;">
                            <li>Truncate all allottee data in <strong>adms_allottees</strong> database.</li>
                            <li>Truncate all application data in <strong>adms_jshb</strong> database.</li>
                            <li>Delete all uploaded documents and folders in <strong>jshb-doc/documents</strong>.</li>
                        </ul>
                        <p class="text-danger fw-bold mb-4">This action cannot be undone!</p>
                        
                        <form action="{{ route('dev.system-reset.process') }}" method="POST" id="systemResetForm">
                            @csrf
                            <button type="button" class="btn btn-danger btn-lg" onclick="confirmReset()">
                                <i class="fas fa-trash-alt me-2"></i> Reset Project Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmReset() {
            if(confirm('Are you absolutely sure you want to reset the entire project? This will erase all data and documents.')) {
                if(confirm('This is your final warning. Type "yes" if you are sure.')) {
                    document.getElementById('systemResetForm').submit();
                }
            }
        }
    </script>
@endsection
