@extends('layouts.main')
@section('title', 'Scheme List | JSHB')
@section('content')
<style>
    .schemes-grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .scheme-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }

    .scheme-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .scheme-header {
        padding: 18px 24px;
        background: #fafbfc;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .scheme-title {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .scheme-sno {
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

    .scheme-name-section h3 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .scheme-name-hindi {
        font-size: 14px;
        color: #068d23;
        margin: 0;
        font-weight: 700;
    }

    .scheme-code {
        font-size: 14px;
        color: white;
        font-family: monospace;
        background: #10b981;
        padding: 4px 12px;
        border-radius: 6px;
        margin-top: 6px;
        font-weight: 600;
        display: inline-block;
    }

    .scheme-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
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

    .badge-lease {
        background: #e0e7ff;
        color: #3730a3;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .scheme-body {
        padding: 20px 24px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #f0f0f0;
    }

    .info-label {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .price-value {
        color: #059669;
        font-size: 14px;
        font-weight: 700;
    }

    .down-price-value {
        color: #cc050f;
        font-size: 14px;
        font-weight: 700;
    }

    .emi-price-value {
        color: #084ee7;
        font-size: 14px;
        font-weight: 700;
    }

    .scheme-footer {
        padding: 14px 24px;
        background: #fafbfc;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        background: #f3f4f6;
        color: #4b5563;
    }

    .action-btn:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }

    .action-btn.edit:hover {
        background: #e0e7ff;
        color: #4338ca;
    }

    .action-btn.blocks {
        background: #6366f1;
        color: white;
        font-weight: 600;
        width: auto;
        padding: 0 16px;
        gap: 6px;
    }

    .action-btn.quota {
        background: #252525;
        font-weight: 600;
        color: white;
        width: auto;
        padding: 0 16px;
        gap: 6px;
    }

    .action-btn.blocks:hover {
        background: #4f46e5;
    }
    .action-btn.quota:hover {
        background: #1b1b1b;
    }

    @media (max-width: 768px) {
        .scheme-body {
            grid-template-columns: 1fr;
        }

        .scheme-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="card">
    @if(session('success'))
    <div class="alert alert-success" style="margin: 20px 20px 0;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="card-head">
        <div>
            <div class="card-title">Scheme Management</div>
            <div class="card-subtitle">Manage all property schemes from the admin panel</div>
        </div>
        <div class="card-actions">
            <form method="GET" action="{{ route('admin.schemes.index') }}" class="search-box" onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="schemesSearchInput" name="search" value="{{ $search ?? '' }}" placeholder="Search by scheme name or code..." autocomplete="off">
            </form>
            <a class="btn-pink" href="{{ route('admin.schemes.create') }}">
                <i class="fa-solid fa-plus"></i> Add Scheme
            </a>
        </div>
    </div>


    <div style="padding: 12px;">
        <div class="schemes-grid" id="schemesContainer">
            @forelse($schemes as $index => $scheme)
            <div class="scheme-card" data-scheme-id="{{ $scheme->id }}">
                <div class="scheme-header">
                    <div class="scheme-title">
                        <div class="scheme-sno">{{ $schemes->firstItem() + $index }}</div>
                        <div class="scheme-name-section">
                            <h3>{{ $scheme->scheme_name }}</h3>
                            @if($scheme->scheme_name_hindi)
                            <p class="scheme-name-hindi">{{ $scheme->scheme_name_hindi }}</p>
                            @endif
                            <div class="scheme-code">
                                {{ $scheme->scheme_code }}
                            </div>
                        </div>
                    </div>
                    <div class="scheme-badges">
                        <span class="badge-status {{ $scheme->status ?? 'active' }}">
                            <i class="fa-solid fa-circle"></i>
                            {{ ($scheme->status ?? 1) ? 'Active' : 'Inactive' }}
                        </span>
                        @if($scheme->lease_period)
                        <span class="badge-lease">
                            <i class="fa-regular fa-clock"></i> {{ $scheme->lease_period }} Years
                        </span>
                        @endif
                    </div>
                </div>

                <div class="scheme-body">
                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-building"></i> Division</span>
                            <span class="info-value">{{ $scheme->division->name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-layer-group"></i> Sub Division</span>
                            <span class="info-value">{{ $scheme->subDivision->name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-tag"></i> Category</span>
                            <span class="info-value">{{ $scheme->propertyCategory->name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-cube"></i> Type</span>
                            <span class="info-value">{{ $scheme->propertyType->name ?? '-' }} / {{ $scheme->propertySubType->name ?? '-' }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-chart-line"></i> Total Units</span>
                            <span class="info-value">{{ number_format($scheme->total_units) }} units</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-indian-rupee-sign"></i> Est. Price</span>
                            <span class="info-value price-value">₹{{ number_format($scheme->financial->property_total_cost, 2) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"> <i class="fa-solid fa-indian-rupee-sign"></i> Down Payment <span style="font-size: 12px !important; color:red !important; font-weight:600 !important;">({{ $scheme->financial->down_payment_percentage }}%)</span></span>
                            <span class="downpayment down-price-value">₹{{ number_format($scheme->financial->down_payment_amount, 2) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-indian-rupee-sign"></i> EMI</span>
                            <span class="info-value emi-price-value">₹{{ number_format($scheme->financial->emi_without_penalty, 2) }} × {{ $scheme->financial->emi_count }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-calendar"></i> Start Date</span>
                            <span class="info-value">{{ optional($scheme->scheme_start_date)->format('M d, Y') ?: '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-calendar-check"></i> End Date</span>
                            <span class="info-value">{{ optional($scheme->scheme_end_date)->format('M d, Y') ?: '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-clock"></i> Initiation Year</span>
                            <span class="info-value">{{ $scheme->initiation_year ?: '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-user"></i> Quarter Type</span>
                            <span class="info-value">{{ $scheme->quarterType->quarter_code ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="scheme-footer">
                    <div class="created-info">
                        <i class="fa-regular fa-calendar-alt"></i> Created: {{ optional($scheme->created_at)->format('M d, Y') ?: '-' }}
                        @if($scheme->creator)
                        <span style="margin-left: 12px;"><i class="fa-regular fa-user"></i> by: {{ $scheme->creator->name }}</span>
                        @endif
                    </div>
                    <div class="action-btns">
                        <a class="action-btn edit" href="{{ route('admin.schemes.edit', $scheme) }}" title="Edit Scheme">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn blocks" href="{{ route('admin.schemes.blocks.index', $scheme) }}" title="View Blocks">
                            <i class="fa-solid fa-layer-group"></i> Blocks
                        </a>
                        <a class="action-btn quota" href="{{ route('admin.schemes.quotas.index', $scheme) }}" title="View Quota">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Quota</span>
                        </a>
                        <form action="{{ route('admin.schemes.destroy', $scheme) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button class="action-btn" style="background:#fee2e2; color:#dc2626;" type="submit" title="Delete Scheme">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:60px 20px;">
                <i class="fa-solid fa-building" style="font-size: 64px; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
                <h3 style="color: #6b7280;">No schemes found</h3>
                <p style="color: #9ca3af;">Click "Add Scheme" to create your first scheme.</p>
            </div>
            @endforelse
        </div>
    </div>

    @if(method_exists($schemes, 'total') && $schemes->total() > 0)
    <div class="table-pagination" id="schemesPagination">
        <span>
            Showing <strong>{{ $schemes->firstItem() }}</strong> to <strong>{{ $schemes->lastItem() }}</strong> of <strong>{{ number_format($schemes->total()) }}</strong> schemes
        </span>
        <div class="pagination-btns">
            {!! $schemes->links() !!}
        </div>
    </div>
    @endif
</div>

<script>
    let searchTimeout;
    let currentSearchAbortController = null;

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('schemesSearchInput');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => performSearch(), 300);
            });
        }
    });

    function performSearch() {
        const searchInput = document.getElementById('schemesSearchInput');
        const keyword = searchInput ? searchInput.value.trim() : '';

        if (currentSearchAbortController) {
            currentSearchAbortController.abort();
        }

        currentSearchAbortController = new AbortController();

        const url = keyword === '' ?
            '{{ route("admin.schemes.index") }}' :
            '{{ route("admin.schemes.search") }}?search=' + encodeURIComponent(keyword);

        if (keyword === '') {
            showSecondaryLoader('Searching schemes...');
            window.location.href = url;
            return;
        }

        showSecondaryLoader('Searching schemes...');

        fetch(url, {
                signal: currentSearchAbortController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(result => {
                updateSchemesWithSearchResults(result.data || []);
                const pagination = document.getElementById('schemesPagination');
                if (pagination) pagination.style.display = 'none';
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Search error:', error);
                }
            })
            .finally(() => {
                hideSecondaryLoader();
                currentSearchAbortController = null;
            });
    }

    function updateSchemesWithSearchResults(schemes) {
        const container = document.getElementById('schemesContainer');

        if (!container) return;

        if (!schemes || schemes.length === 0) {
            container.innerHTML = `
                <div style="text-align:center;padding:60px 20px;">
                    <i class="fa-solid fa-search" style="font-size: 64px; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
                    <h3 style="color: #6b7280;">No schemes found</h3>
                    <p style="color: #9ca3af;">Try a different search term.</p>
                </div>
            `;
            return;
        }
        console.log(schemes);
        container.innerHTML = schemes.map((scheme, idx) => `
            <div class="scheme-card" data-scheme-id="${scheme.id}">
                <div class="scheme-header">
                    <div class="scheme-title">
                        <div class="scheme-sno">${idx + 1}</div>
                        <div class="scheme-name-section">
                            <h3>${escapeHtml(scheme.scheme_name)}</h3>
                            ${scheme.scheme_name_hindi ? `<p class="scheme-name-hindi">${escapeHtml(scheme.scheme_name_hindi)}</p>` : ''}
                            <div class="scheme-code">
                                ${escapeHtml(scheme.scheme_code)}
                            </div>
                        </div>
                    </div>
                    <div class="scheme-badges">
                        <span class="badge-status active">
                            <i class="fa-solid fa-circle"></i> Active
                        </span>
                        <span class="badge-lease">
                            <i class="fa-regular fa-clock"></i> ${scheme.lease_period || 90} Years
                        </span>
                    </div>
                </div>
                
                <div class="scheme-body">
                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-building"></i> Division</span>
                            <span class="info-value">${escapeHtml(scheme.division)}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-layer-group"></i> Sub Division</span>
                            <span class="info-value">${escapeHtml(scheme.sub_division)}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-tag"></i> Category</span>
                            <span class="info-value">${escapeHtml(scheme.property_category)}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-cube"></i> Type</span>
                            <span class="info-value">${escapeHtml(scheme.property_type)}</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-chart-line"></i> Total Units</span>
                            <span class="info-value">${formatNumber(scheme.total_units)} units</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-indian-rupee-sign"></i> Est. Price</span>
                            <span class="info-value price-value">₹${formatNumber(scheme.property_total_cost)}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-indian-rupee-sign"></i> Down Payment <span style="font-size: 12px !important; color:red !important; font-weight:600 !important;">(${formatNumber(scheme.down_payment_percentage)}%)</span></span>
                            <span class="info-value down-price-value">₹${formatNumber(scheme.down_payment_amount)}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-indian-rupee-sign"></i> EMI</span>
                            <span class="info-value emi-price-value">₹${formatNumber(scheme.emi_without_penalty)} * ${scheme.emi_count}</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-calendar"></i> Start Date</span>
                            <span class="info-value">${escapeHtml(scheme.scheme_start_date)}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-calendar-check"></i> End Date</span>
                            <span class="info-value">${escapeHtml(scheme.scheme_end_date)}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-clock"></i> Initiation Year</span>
                            <span class="info-value">${escapeHtml(scheme.initiation_year) || '—'}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-user"></i> Quarter Type</span>
                            <span class="info-value">${escapeHtml(scheme.quarter_code) || '—'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="scheme-footer">
                    <div class="created-info">
                        <i class="fa-regular fa-calendar-alt"></i> Created: ${escapeHtml(scheme.created_at)}
                    </div>
                    <div class="action-btns">
                        <a class="action-btn edit" href="${escapeHtml(scheme.edit_url)}" title="Edit Scheme">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn blocks" href="${escapeHtml(scheme.block_url)}" title="View Blocks">
                            <i class="fa-solid fa-layer-group"></i> Blocks
                        </a>
                        <a class="action-btn quota" href="${escapeHtml(scheme.quota_url)}" title="View Quota">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Quota</span>
                        </a>
                        <form action="${escapeHtml(scheme.delete_url)}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="action-btn" style="background:#fee2e2; color:#dc2626;" type="submit" title="Delete Scheme">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function confirmDelete(event) {
        if (!confirm('Are you sure you want to delete this scheme? This action cannot be undone.')) {
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
            maximumFractionDigits: 0
        }).format(num);
    }
</script>
@endsection