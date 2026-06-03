@extends('layouts.main')
@section('title', 'Scheme Blocks | ' . $scheme->scheme_name)
@section('content')
<style>
    .scheme-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .scheme-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 2px;
        color: #097a2b;
    }

    .scheme-subtitle {
        font-size: 16px;
        color: rgb(4, 60, 170);
        margin-bottom: 16px;
        font-weight: 600;
    }

    .simple-scheme-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
    }

    .scheme-item {
        background: #f9fafb;
        border-radius: 8px;
        padding: 12px 14px;
        border: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }

    .scheme-item:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .scheme-label {
        font-size: 12px;
        color: #2a2a2c;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .scheme-value {
        font-size: 15px;
        font-weight: 600;
        color: #111827;
    }

    .blocks-progress {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        border: 1px solid #e5e7eb;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .progress-title {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

    .progress-stats {
        font-size: 13px;
        color: #6b7280;
    }

    .progress-bar-container {
        height: 8px;
        background: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #6366f1, #818cf8);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .progress-bar-fill.warning {
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
    }

    .progress-bar-fill.danger {
        background: linear-gradient(90deg, #ef4444, #f87171);
    }

    .blocks-limit-warning {
        margin-top: 12px;
        background: #fef3c7;
        border-left: 3px solid #f59e0b;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 13px;
        color: #92400e;
        display: none;
    }

    .blocks-limit-warning.show {
        display: block;
    }

    .blocks-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .block-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }

    .block-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .block-header {
        padding: 16px 20px;
        background: #fafbfc;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .block-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .block-sno {
        width: 36px;
        height: 36px;
        background: #6366f1;
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }

    .block-name-section h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .block-type {
        font-size: 12px;
        color: #10b981;
        font-weight: 500;
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-status.active {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-status.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .block-body {
        padding: 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #f0f0f0;
    }

    .info-label {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .dimensions-section {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }

    .dimensions-title {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 12px;
        text-transform: uppercase;
    }

    .dimensions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .dimension-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 8px;
    }

    .dimension-label {
        color: #ffffff;
        font-weight: 600;
    }

    .dimension-value {
        font-weight: 600;
        color: #ffffff;
    }

    .boundary-dimensions {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }

    .boundary-title {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 12px;
        text-transform: uppercase;
    }

    .boundary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .boundary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 8px;
    }

    .boundary-label {
        color: #ffffff;
        font-weight: 600;
    }

    .boundary-value {
        font-weight: 600;
        color: #ffffff;
    }

    .boundary-dimensions {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }

    .block-footer {
        padding: 14px 20px;
        background: #fafbfc;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        background: #f3f4f6;
        color: #4b5563;
        text-decoration: none;
    }

    .action-btn:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }

    .action-btn.edit:hover {
        background: #e0e7ff;
        color: #4338ca;
    }

    .action-btn.delete:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-pink.disabled {
        opacity: 0.5;
        pointer-events: none;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .blocks-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card" style="padding: 10px;">


    <!-- Scheme Information Header -->
    <div class="scheme-card">
        <div class="scheme-title">
            {{ $scheme->scheme_name }}
        </div>

        @if($scheme->scheme_name_hindi)
        <div class="scheme-subtitle">
            {{ $scheme->scheme_name_hindi }}
        </div>
        @endif

        <div class="simple-scheme-grid">

            <div class="scheme-item">
                <div class="scheme-label">Scheme Code</div>
                <div class="scheme-value">{{ $scheme->scheme_code }}</div>
            </div>

            <div class="scheme-item">
                <div class="scheme-label">Total Units</div>
                <div class="scheme-value">{{ number_format($scheme->total_units) }}</div>
            </div>

            <div class="scheme-item">
                <div class="scheme-label">Scheme Value</div>
                <div class="scheme-value">
                    ₹{{ number_format($scheme->total_units * 500000, 2) }}
                </div>
            </div>

            @if($scheme->lease_period)
            <div class="scheme-item">
                <div class="scheme-label">Lease Period</div>
                <div class="scheme-value">{{ $scheme->lease_period }} Years</div>
            </div>
            @endif

        </div>

    </div>

    @if(session('success'))
    <div class="alert alert-success" style="margin: 20px 20px 0;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger" style="margin: 20px 20px 0;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Blocks Limit Progress -->
    @php
    $maxBlocks = 10;
    $currentBlocks = $blocks->total();
    $remainingBlocks = $maxBlocks - $currentBlocks;
    $percentage = ($currentBlocks / $maxBlocks) * 100;
    $progressClass = $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : '');
    @endphp

    <div style="padding: 10px;">
        <div class="blocks-progress">
            <div class="progress-header">
                <span class="progress-title">
                    <i class="fa-solid fa-cubes"></i> Existing Blocks
                    <span style="color: #6366f1; font-weight: 700;">({{ $currentBlocks }}/{{ $maxBlocks }})</span>
                </span>
                <span class="progress-stats">
                    @if($remainingBlocks > 0)
                    {{ $remainingBlocks }} block slots remaining
                    @else
                    Maximum limit reached
                    @endif
                </span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill {{ $progressClass }}" style="width: {{ $percentage }}%"></div>
            </div>
            <div class="blocks-limit-warning {{ $remainingBlocks <= 2 ? 'show' : '' }}">
                <i class="fa-solid fa-triangle-exclamation"></i>
                @if($remainingBlocks == 0)
                Maximum limit of {{ $maxBlocks }} blocks reached. Cannot add more blocks.
                @elseif($remainingBlocks == 1)
                Only {{ $remainingBlocks }} block slot remaining!
                @elseif($remainingBlocks == 2)
                Only {{ $remainingBlocks }} block slots remaining!
                @endif
            </div>
        </div>
    </div>

    <div class="card-head">
        <div>
            <div class="card-title">Scheme Blocks</div>
            <div class="card-subtitle">Manage blocks and their dimensions for this scheme</div>
        </div>
        <div class="card-actions">
            <form method="GET" action="{{ route('admin.schemes.blocks.index', $scheme) }}" class="search-box" onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="blocksSearchInput" name="search" value="{{ $search ?? '' }}" placeholder="Search by block name or type..." autocomplete="off">
            </form>
            <a class="btn-pink {{ $currentBlocks >= $maxBlocks ? 'disabled' : '' }}"
                href="{{ $currentBlocks >= $maxBlocks ? 'javascript:void(0)' : route('admin.schemes.blocks.create', $scheme) }}"
                onclick="{{ $currentBlocks >= $maxBlocks ? 'showMaxBlocksAlert()' : '' }}">
                <i class="fa-solid fa-plus"></i> Add Block
            </a>
            <a class="btn-warning" href="{{ route('admin.schemes.index') }}">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div style="padding: 20px;">
        <div class="blocks-grid" id="blocksContainer">
            @forelse($blocks as $index => $block)
            <div class="block-card" data-block-id="{{ $block->id }}">
                <div class="block-header">
                    <div class="block-title">
                        <div class="block-sno">{{ $blocks->firstItem() + $index }}</div>
                        <div class="block-name-section">
                            <h4>{{ $block->block_name }}</h4>
                            @if($block->scheme_property_type)
                            <div class="block-type">
                                <i class="fa-solid fa-building"></i> {{ $block->scheme_property_type }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <span class="badge-status {{ $block->status ? 'active' : 'inactive' }}">
                        <i class="fa-solid fa-circle"></i>
                        {{ $block->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="block-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fa-solid fa-chart-line"></i> Area (sq.ft.)</span>
                        <span class="info-value">{{ number_format($block->area_sqft, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fa-solid fa-landmark"></i> Land Share (sq.ft.)</span>
                        <span class="info-value">{{ number_format($block->undivided_land_share, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fa-solid fa-building"></i> Total Buildup (sq.ft.)</span>
                        <span class="info-value">{{ number_format($block->total_buildup, 2) }}</span>
                    </div>
                    @if($block->total_area_of_construction)
                    <div class="info-row">
                        <span class="info-label"><i class="fa-solid fa-draw-polygon"></i> Construction Area (sq.ft.)</span>
                        <span class="info-value">{{ number_format($block->total_area_of_construction, 2) }}</span>
                    </div>
                    @endif

                    <div class="dimensions-section">
                        <div class="dimensions-title">
                            <i class="fa-solid fa-arrows-up-down-left-right"></i> Dimensions
                        </div>
                        <div class="dimensions-grid">
                            <div class="dimension-item tag-primary">
                                <span class="dimension-label">East:</span>
                                <span class="dimension-value">{{ $block->dimension_east ?? '-' }}</span>
                            </div>
                            <div class="dimension-item tag-success">
                                <span class="dimension-label">West:</span>
                                <span class="dimension-value">{{ $block->dimension_west ?? '-' }}</span>
                            </div>
                            <div class="dimension-item tag-warning">
                                <span class="dimension-label" style="color:black;">North:</span>
                                <span class="dimension-value" style="color:black;">{{ $block->dimension_north ?? '-' }}</span>
                            </div>
                            <div class="dimension-item tag-danger">
                                <span class="dimension-label">South:</span>
                                <span class="dimension-value">{{ $block->dimension_south ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    @if($block->arm_east_west_north || $block->arm_east_west_south || $block->arm_north_south_east || $block->arm_north_south_west)
                    <div class="boundary-dimensions">
                        <div class="boundary-title">
                            <i class="fa-solid fa-border-all"></i> Boundary Dimensions
                        </div>
                        <div class="boundary-grid">
                            @if($block->arm_east_west_north)
                            <div class="boundary-item tag-primary">
                                <span class="boundary-label">E/W-N:</span>
                                <span class="boundary-value">{{ $block->arm_east_west_north ?? '-' }}</span>
                            </div>
                            @endif
                            @if($block->arm_east_west_south)
                            <div class="boundary-item tag-success">
                                <span class="boundary-label">E/W-S:</span>
                                <span class="boundary-value">{{ $block->arm_east_west_south ?? '-' }}</span>
                            </div>
                            @endif
                            @if($block->arm_north_south_east)
                            <div class="boundary-item tag-warning">
                                <span class="boundary-label" style="color:black;">N/S-E:</span>
                                <span class="boundary-value" style="color:black;">{{ $block->arm_north_south_east ?? '-' }}</span>
                            </div>
                            @endif
                            @if($block->arm_north_south_west)
                            <div class="boundary-item tag-danger">
                                <span class="boundary-label">N/S-W:</span>
                                <span class="boundary-value">{{ $block->arm_north_south_west ?? '-' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="block-footer">
                    <a class="action-btn edit" href="{{ route('admin.schemes.blocks.edit', [$scheme, $block]) }}" title="Edit Block">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.schemes.blocks.destroy', [$scheme, $block]) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                        @csrf
                        <button class="action-btn delete" type="submit" title="Delete Block">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:60px 20px; grid-column: 1/-1;">
                <i class="fa-solid fa-cubes" style="font-size: 64px; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
                <h3 style="color: #6b7280;">No blocks found</h3>
                <p style="color: #9ca3af;">Click "Add Block" to create blocks for this scheme.</p>
            </div>
            @endforelse
        </div>
    </div>

    @if(method_exists($blocks, 'total') && $blocks->total() > 0 && $blocks->lastPage() > 1)
    <div class="table-pagination" id="blocksPagination">
        <span>
            Showing <strong>{{ $blocks->firstItem() }}</strong> to <strong>{{ $blocks->lastItem() }}</strong> of <strong>{{ number_format($blocks->total()) }}</strong> blocks
        </span>
        <div class="pagination-btns">
            {!! $blocks->links() !!}
        </div>
    </div>
    @endif
</div>

<script>
    let searchTimeout;
    let currentSearchAbortController = null;
    const MAX_BLOCKS = {
        {
            $maxBlocks
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('blocksSearchInput');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => performSearch(), 300);
            });
        }
    });

    function showMaxBlocksAlert() {
        alert('Maximum limit of ' + MAX_BLOCKS + ' blocks reached for this scheme. Cannot add more blocks.');
    }

    function performSearch() {
        const searchInput = document.getElementById('blocksSearchInput');
        const keyword = searchInput ? searchInput.value.trim() : '';
        const schemeId = {
            {
                $scheme - > id
            }
        };

        if (currentSearchAbortController) {
            currentSearchAbortController.abort();
        }

        currentSearchAbortController = new AbortController();

        const url = keyword === '' ?
            '{{ route("admin.schemes.blocks.index", $scheme) }}' :
            '/admin/schemes/' + schemeId + '/blocks/search?search=' + encodeURIComponent(keyword);

        if (keyword === '') {
            window.location.href = '{{ route("admin.schemes.blocks.index", $scheme) }}';
            return;
        }

        showSecondaryLoader('Searching blocks...');

        fetch(url, {
                signal: currentSearchAbortController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(result => {
                updateBlocksWithSearchResults(result.data || []);
                const pagination = document.getElementById('blocksPagination');
                if (pagination) pagination.style.display = 'none';
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Search error:', error);
                    updateBlocksWithSearchResults([]);
                }
            })
            .finally(() => {
                hideSecondaryLoader();
                currentSearchAbortController = null;
            });
    }

    function updateBlocksWithSearchResults(blocks) {
        const container = document.getElementById('blocksContainer');

        if (!container) return;

        if (!blocks || blocks.length === 0) {
            container.innerHTML = `
                <div style="text-align:center;padding:60px 20px; grid-column: 1/-1;">
                    <i class="fa-solid fa-search" style="font-size: 64px; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
                    <h3 style="color: #6b7280;">No blocks found</h3>
                    <p style="color: #9ca3af;">Try a different search term.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = blocks.map((block, idx) => `
            <div class="block-card" data-block-id="${block.id}">
                <div class="block-header">
                    <div class="block-title">
                        <div class="block-sno">${idx + 1}</div>
                        <div class="block-name-section">
                            <h4>${escapeHtml(block.block_name)}</h4>
                            ${block.scheme_property_type ? `<div class="block-type"><i class="fa-solid fa-building"></i> ${escapeHtml(block.scheme_property_type)}</div>` : ''}
                        </div>
                    </div>
                    <span class="badge-status ${block.status ? 'active' : 'inactive'}">
                        <i class="fa-solid fa-circle"></i>
                        ${block.status ? 'Active' : 'Inactive'}
                    </span>
                </div>
                
                <div class="block-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fa-solid fa-chart-line"></i> Area (sq.ft.)</span>
                        <span class="info-value">${formatNumber(block.area_sqft)}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fa-solid fa-landmark"></i> Land Share (sq.ft.)</span>
                        <span class="info-value">${formatNumber(block.undivided_land_share)}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fa-solid fa-building"></i> Total Buildup (sq.ft.)</span>
                        <span class="info-value">${formatNumber(block.total_buildup)}</span>
                    </div>
                    ${block.total_area_of_construction ? `
                    <div class="info-row">
                        <span class="info-label"><i class="fa-solid fa-draw-polygon"></i> Construction Area</span>
                        <span class="info-value">${formatNumber(block.total_area_of_construction)} sq.ft.</span>
                    </div>
                    ` : ''}
                    
                    <div class="dimensions-section">
                        <div class="dimensions-title">
                            <i class="fa-solid fa-arrows-up-down-left-right"></i> Dimensions
                        </div>
                        <div class="dimensions-grid">
                            <div class="dimension-item">
                                <span class="dimension-label">East:</span>
                                <span class="dimension-value">${escapeHtml(block.dimension_east || '-')}</span>
                            </div>
                            <div class="dimension-item">
                                <span class="dimension-label">West:</span>
                                <span class="dimension-value">${escapeHtml(block.dimension_west || '-')}</span>
                            </div>
                            <div class="dimension-item">
                                <span class="dimension-label">North:</span>
                                <span class="dimension-value">${escapeHtml(block.dimension_north || '-')}</span>
                            </div>
                            <div class="dimension-item">
                                <span class="dimension-label">South:</span>
                                <span class="dimension-value">${escapeHtml(block.dimension_south || '-')}</span>
                            </div>
                        </div>
                    </div>

                    ${block.arm_east_west_north || block.arm_east_west_south || block.arm_north_south_east || block.arm_north_south_west ? `
                    <div class="boundary-dimensions">
                        <div class="boundary-title">
                            <i class="fa-solid fa-border-all"></i> Boundary Dimensions
                        </div>
                        <div class="boundary-grid">
                            ${block.arm_east_west_north ? `<div><span style="color:#6b7280;">E/W-N:</span> <strong>${escapeHtml(block.arm_east_west_north)}</strong></div>` : ''}
                            ${block.arm_east_west_south ? `<div><span style="color:#6b7280;">E/W-S:</span> <strong>${escapeHtml(block.arm_east_west_south)}</strong></div>` : ''}
                            ${block.arm_north_south_east ? `<div><span style="color:#6b7280;">N/S-E:</span> <strong>${escapeHtml(block.arm_north_south_east)}</strong></div>` : ''}
                            ${block.arm_north_south_west ? `<div><span style="color:#6b7280;">N/S-W:</span> <strong>${escapeHtml(block.arm_north_south_west)}</strong></div>` : ''}
                        </div>
                    </div>
                    ` : ''}
                </div>
                
                <div class="block-footer">
                    <a class="action-btn edit" href="${escapeHtml(block.edit_url)}" title="Edit Block">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="${escapeHtml(block.delete_url)}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="action-btn delete" type="submit" title="Delete Block">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        `).join('');
    }

    function confirmDelete(event) {
        if (!confirm('Are you sure you want to delete this block? This action cannot be undone.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatNumber(num) {
        if (!num) return '0';
        return new Intl.NumberFormat('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(num);
    }
</script>
@endsection