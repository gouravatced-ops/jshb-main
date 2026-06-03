@extends('layouts.main')

@section('title', 'Scheme Unit Quota | ' . $scheme->scheme_name)

@section('content')
<style>
    .quota-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;
    }
    
    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #6366f1;
    }
    
    .summary-label {
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .summary-value {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.2;
    }
    
    .summary-sub {
        font-size: 12px;
        color: #6b7280;
        margin-top: 8px;
    }
    
    .progress-section {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 8px;
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
    
    .quotas-table-wrapper {
        overflow-x: auto;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: white;
    }
    
    .quotas-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    
    .quotas-table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .quotas-table th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .quotas-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    
    .quotas-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .quota-code {
        display: inline-block;
        background: #e0e7ff;
        color: #4338ca;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-right: 8px;
    }
    
    .quota-name {
        font-weight: 500;
        color: #1f2937;
    }
    
    .unit-input {
        width: 100px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        text-align: center;
        transition: all 0.2s;
    }
    
    .unit-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .unit-input.invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }
    
    .remaining-units {
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        min-width: 50px;
        text-align: center;
    }
    
    .remaining-units.success {
        color: #059669;
        background: #d1fae5;
    }
    
    .remaining-units.warning {
        color: #d97706;
        background: #fef3c7;
    }
    
    .remaining-units.danger {
        color: #dc2626;
        background: #fee2e2;
    }
    
    .table-footer {
        background: #f8fafc;
        font-weight: 600;
    }
    
    .table-footer td {
        padding: 12px 16px;
        border-top: 1px solid #e5e7eb;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    
    .btn-save {
        background: #10b981;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-save:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    
    .btn-reset-quota {
        background: #f3f4f6;
        color: #6b7280;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-reset-quota:hover {
        background: #e5e7eb;
    }
</style>

<div class="card">
    @if(session('success'))
        <div class="alert alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            {{ session('success') }}
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

    <!-- Scheme Summary Cards -->
    <div style="padding: 20px 20px 0 20px;">
        <div class="quota-summary">
            <div class="summary-card">
                <div class="summary-label">SCHEME NAME</div>
                <div class="summary-value">{{ $scheme->scheme_name }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">TOTAL UNITS</div>
                <div class="summary-value">{{ number_format($scheme->total_units) }}</div>
                <div class="summary-sub">Available for allocation</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">ALLOCATED UNITS</div>
                <div class="summary-value" id="totalAllocatedDisplay">{{ number_format($totalAllocatedUnits) }}</div>
                <div class="summary-sub">Across all quotas</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">REMAINING UNITS</div>
                <div class="summary-value" id="totalRemainingDisplay">{{ number_format($remainingUnits) }}</div>
                <div class="summary-sub">Not yet allocated</div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    @php
        $percentage = $scheme->total_units > 0 ? ($totalAllocatedUnits / $scheme->total_units) * 100 : 0;
        $progressClass = $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : '');
    @endphp

    <div style="padding: 0 20px;">
        <div class="progress-section">
            <div class="progress-header">
                <span class="progress-title">
                    <i class="fa-solid fa-chart-simple"></i> Allocation Progress
                </span>
                <span class="progress-stats">
                    <span id="progressStats">{{ number_format($totalAllocatedUnits) }}</span> / {{ number_format($scheme->total_units) }} units allocated
                </span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill {{ $progressClass }}" id="progressFill" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    </div>

    <div class="card-head">
        <div>
            <div class="card-title">Scheme Unit Quota Management &nbsp;&nbsp; <span class="custom-badge badge-info">({{ $scheme->scheme_code }})</span></div>
            <div class="card-subtitle">Configure quota distribution for this scheme</div>
        </div>
        <div class="card-actions">
            <a class="btn-warning" href="{{ route('admin.schemes.index') }}">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div style="padding: 20px;">
        <form action="{{ route('admin.schemes.quotas.bulk-update', $scheme) }}" method="POST" id="quotaBulkForm">
            @csrf
            @method('PUT')
            
            <div class="table-responsive">
                <table class="ep-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Quota Category</th>
                            <th style="width: 150px;">Total Units</th>
                            <th style="width: 150px;">Allotted Units</th>
                            <th style="width: 150px;">Remaining Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotaTypes as $index => $quotaType)
                            @php
                                $existingQuota = $quotas->firstWhere('quota_type_id', $quotaType->id);
                                $totalUnits = $existingQuota ? $existingQuota->total_units : 0;
                                $allottedUnits = $existingQuota ? $existingQuota->allotted_units : 0;
                                $remaining = $totalUnits - $allottedUnits;
                                $quotaId = $existingQuota ? $existingQuota->id : null;
                            @endphp
                            <tr data-quota-type-id="{{ $quotaType->id }}" data-quota-id="{{ $quotaId }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <input type="hidden" name="quotas[{{ $index }}][quota_type_id]" value="{{ $quotaType->id }}">
                                    @if($quotaId)
                                        <input type="hidden" name="quotas[{{ $index }}][id]" value="{{ $quotaId }}">
                                    @endif
                                    <span>{{ $quotaType->name }}</span>
                                </td>
                                <td>
                                    <input type="number" 
                                           name="quotas[{{ $index }}][total_units]" 
                                           class="unit-input total-units" 
                                           value="{{ old('quotas.' . $index . '.total_units', $totalUnits) }}" 
                                           min="0" 
                                           max="{{ $scheme->total_units }}"
                                           step="1"
                                           data-quota-index="{{ $index }}">
                                </td>
                                <td>
                                    <input type="number" 
                                           name="quotas[{{ $index }}][allotted_units]" 
                                           class="unit-input allotted-units" 
                                           value="{{ old('quotas.' . $index . '.allotted_units', $allottedUnits) }}" 
                                           min="0" 
                                           step="1"
                                           data-quota-index="{{ $index }}">
                                </td>
                                <td>
                                    <span class="remaining-units success" data-remaining-index="{{ $index }}">
                                        {{ number_format($remaining) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-footer">
                        <tr>
                            <td colspan="2" style="text-align: right;"><strong>Total:</strong></td>
                            <td><strong id="totalUnitsSum">{{ number_format($quotas->sum('total_units')) }}</strong></td>
                            <td><strong id="totalAllottedSum">{{ number_format($quotas->sum('allotted_units')) }}</strong></td>
                            <td><strong id="totalRemainingSum">{{ number_format($quotas->sum('total_units') - $quotas->sum('allotted_units')) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="action-buttons" style="margin-top: 24px;">
                <button type="button" class="btn-reset-quota" onclick="resetForm()">
                    <i class="fa-solid fa-undo"></i> Reset
                </button>
                <button type="submit" class="btn-save" id="saveBtn">
                    <i class="fa-solid fa-save"></i> Save All Quotas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const schemeTotalUnits = {{ $scheme->total_units }};
    let currentTotal = {{ $totalAllocatedUnits }};
    
    // Calculate remaining units for a quota
    function calculateRemaining(totalUnits, allottedUnits) {
        return Math.max(0, totalUnits - allottedUnits);
    }
    
    // Update all calculations
    function updateCalculations() {
        let grandTotalUnits = 0;
        let grandTotalAllotted = 0;
        
        document.querySelectorAll('tbody tr').forEach(row => {
            const totalInput = row.querySelector('.total-units');
            const allottedInput = row.querySelector('.allotted-units');
            const remainingSpan = row.querySelector('.remaining-units');
            
            if (totalInput && allottedInput) {
                let total = parseInt(totalInput.value) || 0;
                let allotted = parseInt(allottedInput.value) || 0;
                
                // Validate allotted doesn't exceed total
                if (allotted > total) {
                    allotted = total;
                    allottedInput.value = total;
                }
                
                const remaining = calculateRemaining(total, allotted);
                remainingSpan.textContent = formatNumber(remaining);
                
                // Add color based on remaining
                if (remaining === 0) {
                    remainingSpan.className = 'remaining-units danger';
                } else if (remaining < total / 2) {
                    remainingSpan.className = 'remaining-units warning';
                } else {
                    remainingSpan.className = 'remaining-units success';
                }
                
                grandTotalUnits += total;
                grandTotalAllotted += allotted;
            }
        });
        
        // Update footer totals
        document.getElementById('totalUnitsSum').textContent = formatNumber(grandTotalUnits);
       
        // Update progress bar
        const percentage = schemeTotalUnits > 0 ? (grandTotalUnits / schemeTotalUnits) * 100 : 0;
        document.getElementById('progressFill').style.width = percentage + '%';
        document.getElementById('progressStats').textContent = formatNumber(grandTotalUnits);
        
        // Change progress bar color based on percentage
        const progressFill = document.getElementById('progressFill');
        if (percentage >= 90) {
            progressFill.className = 'progress-bar-fill danger';
        } else if (percentage >= 70) {
            progressFill.className = 'progress-bar-fill warning';
        } else {
            progressFill.className = 'progress-bar-fill';
        }
        
        // Validate total units doesn't exceed scheme total
        if (grandTotalUnits > schemeTotalUnits) {
            document.getElementById('saveBtn').disabled = true;
            document.getElementById('saveBtn').title = 'Total units cannot exceed scheme capacity';
            showError('Total allocated units (' + grandTotalUnits + ') exceeds scheme capacity (' + schemeTotalUnits + ')');
        } else {
            document.getElementById('saveBtn').disabled = false;
            document.getElementById('saveBtn').title = '';
            hideError();
        }
    }
    
    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    // Show error message
    function showError(message) {
        let errorDiv = document.getElementById('quotaError');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'quotaError';
            errorDiv.className = 'alert alert-error';
            errorDiv.style.margin = '0 0 20px 0';
            document.querySelector('.action-buttons').parentNode.insertBefore(errorDiv, document.querySelector('.action-buttons'));
        }
        errorDiv.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> ' + message;
        errorDiv.style.display = 'flex';
    }
    
    function hideError() {
        const errorDiv = document.getElementById('quotaError');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }
    
    // Reset form to original values
    function resetForm() {
        if (confirm('Are you sure you want to reset all changes? This will revert to the last saved values.')) {
            location.reload();
        }
    }
    
    // Add event listeners to all inputs
    document.querySelectorAll('.total-units, .allotted-units').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            const totalInput = row.querySelector('.total-units');
            const allottedInput = row.querySelector('.allotted-units');
            
            let total = parseInt(totalInput.value) || 0;
            let allotted = parseInt(allottedInput.value) || 0;
            
            // Validate total doesn't exceed scheme total
            let currentTotalUnits = 0;
            document.querySelectorAll('.total-units').forEach(t => {
                currentTotalUnits += parseInt(t.value) || 0;
            });
            
            if (currentTotalUnits > schemeTotalUnits) {
                totalInput.classList.add('invalid');
                showError('Total units across all quotas cannot exceed ' + schemeTotalUnits);
            } else {
                totalInput.classList.remove('invalid');
                hideError();
            }
            
            // Validate allotted doesn't exceed total
            if (allotted > total) {
                allottedInput.classList.add('invalid');
                allottedInput.value = total;
                allotted = total;
            } else {
                allottedInput.classList.remove('invalid');
            }
            
            updateCalculations();
        });
        
        input.addEventListener('blur', function() {
            let value = parseInt(this.value) || 0;
            if (value < 0) this.value = 0;
            updateCalculations();
        });
    });
    
    // Form submission validation
    document.getElementById('quotaBulkForm').addEventListener('submit', function(e) {
        let grandTotalUnits = 0;
        document.querySelectorAll('.total-units').forEach(input => {
            grandTotalUnits += parseInt(input.value) || 0;
        });
        
        if (grandTotalUnits > schemeTotalUnits) {
            e.preventDefault();
            alert('Total units (' + grandTotalUnits + ') exceeds scheme capacity (' + schemeTotalUnits + '). Please adjust the values.');
            return false;
        }
        
        if (!confirm('Are you sure you want to save all quota changes?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Initial calculation
    updateCalculations();
    
    // Auto-save indicator (optional)
    let saveTimeout;
    function autoSaveIndicator() {
        const saveBtn = document.getElementById('saveBtn');
        saveBtn.style.opacity = '0.7';
        setTimeout(() => {
            saveBtn.style.opacity = '1';
        }, 500);
    }
    
    document.querySelectorAll('.total-units, .allotted-units').forEach(input => {
        input.addEventListener('change', autoSaveIndicator);
    });
</script>
@endsection