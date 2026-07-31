@extends('layouts.main')
@section('title', 'My Assets')

@section('content')
<style>
    .asset-wrapper {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 20px;
        margin-top: 15px;
    }
    .compact-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #eef0f2;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .compact-card-header {
        padding: 16px 20px;
        font-size: 16px;
        font-weight: 600;
        border-bottom: 1px solid #eaeaea;
        display: flex;
        justify-content: space-between;
        align-items: center;
        letter-spacing: 0.3px;
    }
    .header-blue { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #0d47a1; border-bottom-color: #90caf9; }
    .header-dark { background: linear-gradient(135deg, #f8f9fa, #e9ecef); color: #333; border-bottom-color: #dee2e6; }
    
    .compact-card-body {
        padding: 20px;
        flex-grow: 1;
        color: #444;
    }
    
    .asset-upload-zone {
        border: 2px dashed #b9d5f0;
        border-radius: 10px;
        padding: 30px 20px;
        text-align: center;
        background: #f8fbff;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 15px;
    }
    .asset-upload-zone:hover {
        background: #eff6ff;
        border-color: #60a5fa;
    }
    .asset-upload-zone i {
        font-size: 36px;
        color: #60a5fa;
        margin-bottom: 12px;
    }
    .asset-upload-zone p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }
    
    .custom-select-box {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px 15px;
        font-size: 14px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
    }
    .custom-btn-primary {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .custom-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
    }

    .asset-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 20px;
    }
    .asset-item {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        position: relative;
    }
    .asset-item:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transform: translateY(-4px);
        border-color: #cbd5e1;
    }
    .asset-preview {
        height: 120px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        border-bottom: 1px solid #eaeaea;
    }
    .asset-preview img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
    }
    .asset-info {
        padding: 12px 15px;
        text-align: center;
    }
    .asset-type-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #e0e7ff;
        color: #3730a3;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .asset-delete-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #fff;
        color: #ef4444;
        border: 1px solid #fee2e2;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .asset-item:hover .asset-delete-btn {
        opacity: 1;
    }
    .asset-delete-btn:hover {
        background: #ef4444;
        color: #fff;
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1" style="font-weight: 700; color: #1e293b;">My Digital Assets</h4>
                    <p class="text-muted mb-0" style="font-size: 14px;">Manage your official signatures, stamps, and seals.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px; border-left: 4px solid #10b981;">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="asset-wrapper">
            <!-- Upload Column -->
            <div style="grid-column: span 4;">
                <div class="compact-card">
                    <div class="compact-card-header header-blue">
                        <span><i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload New Asset</span>
                    </div>
                    <div class="compact-card-body">
                        <form action="{{ route('coassistant.assets.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label style="font-weight: 600; font-size: 13px; color: #475569; margin-bottom: 8px; display: block;">Asset Category</label>
                                <select name="asset_type" class="form-select custom-select-box" required>
                                    <option value="signature">Signature</option>
                                    <option value="stamp">Official Stamp</option>
                                    <option value="other">Other Image</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label style="font-weight: 600; font-size: 13px; color: #475569; margin-bottom: 8px; display: block;">Select Image File</label>
                                <label class="asset-upload-zone" for="assetFileInput" style="display: block;">
                                    <i class="fa-solid fa-file-image"></i>
                                    <p id="fileNameDisplay">Click to browse or drag image here</p>
                                    <span style="font-size: 11px; color: #94a3b8; display: block; margin-top: 5px;">Max 2MB (JPG, PNG)</span>
                                </label>
                                <input type="file" name="image" id="assetFileInput" accept="image/png, image/jpeg" required style="display: none;" onchange="document.getElementById('fileNameDisplay').textContent = this.files[0] ? this.files[0].name : 'Click to browse or drag image here';">
                            </div>

                            <button type="submit" class="btn btn-primary custom-btn-primary w-100 text-white">
                                Save Asset to Library
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Gallery Column -->
            <div style="grid-column: span 8;">
                <div class="compact-card">
                    <div class="compact-card-header header-dark">
                        <span><i class="fa-solid fa-images me-2"></i> Your Asset Library</span>
                        <span class="badge bg-secondary rounded-pill">{{ $assets->count() }} Assets</span>
                    </div>
                    <div class="compact-card-body" style="background: #f8fafc;">
                        
                        @if($assets->isEmpty())
                            <div style="text-align: center; padding: 50px 20px;">
                                <div style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                    <i class="fa-solid fa-box-open" style="font-size: 32px; color: #94a3b8;"></i>
                                </div>
                                <h5 style="color: #475569; font-weight: 600;">Your library is empty</h5>
                                <p style="color: #64748b; font-size: 14px; max-width: 300px; margin: 0 auto;">Upload your signature or official stamp to quickly insert them into your noting sheets later.</p>
                            </div>
                        @else
                            <div class="asset-grid">
                                @foreach($assets as $asset)
                                    <div class="asset-item">
                                        <div class="asset-preview">
                                            <img src="{{ asset($asset->file_path) }}" alt="{{ $asset->asset_type }}">
                                        </div>
                                        <div class="asset-info">
                                            <span class="asset-type-badge">
                                                @if($asset->asset_type == 'signature') <i class="fa-solid fa-signature"></i> 
                                                @elseif($asset->asset_type == 'stamp') <i class="fa-solid fa-stamp"></i> 
                                                @else <i class="fa-solid fa-paperclip"></i> @endif 
                                                {{ $asset->asset_type }}
                                            </span>
                                            <div style="font-size: 12px; color: #64748b; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $asset->original_name }}">
                                                {{ $asset->original_name }}
                                            </div>
                                        </div>
                                        
                                        <!-- Delete Button Overlay -->
                                        <form action="{{ route('coassistant.assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Delete this asset permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="asset-delete-btn" title="Delete Asset">
                                                <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
