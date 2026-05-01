@extends('layouts.main')

@section('title', 'New Requisition | JSHB')

@section('content')
<div class="card">
    @if($errors->any())
        <div class="alert alert-danger" style="margin: 20px 20px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card-head">
        <div>
            <div class="card-title">Guest House Requisition</div>
            <div class="card-subtitle">Submit a stay request for a government guest house visit</div>
        </div>
    </div>

    <form action="{{ route('requisitions.store') }}" method="POST">
        @csrf
        <div class="form-container">
            <div class="form-section">
                <h5 class="section-title">Employee Details</h5>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Employee Name</label>
                        <input type="text" class="form-control" value="{{ $engineer?->employee_name ?: auth()->user()->name }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" class="form-control" value="{{ $engineer?->department?->name ?: '-' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Post Type</label>
                        <input type="text" class="form-control" value="{{ $engineer?->postType?->name ?: '-' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Current Organization</label>
                        <input type="text" class="form-control" value="{{ $engineer?->currentOrganization?->name ?: '-' }}" disabled>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h5 class="section-title">Visit Details</h5>
                <div class="form-grid">
                    <div class="form-group">
                        <label>District <span class="required">*</span></label>
                        <select name="district_id" id="requisitionDistrict" class="form-control" required>
                            <option value="">Select district</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ old('district_id', $engineer?->district_id) == $district->id ? 'selected' : '' }}>
                                    {{ $district->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Block <span class="required">*</span></label>
                        <select name="block_id" id="requisitionBlock" class="form-control" required>
                            <option value="">Select block</option>
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}" data-district-id="{{ $block->district_id }}" {{ old('block_id', $engineer?->block_id) == $block->id ? 'selected' : '' }}>
                                    {{ $block->block_name_eng }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Guest House Name <span class="required">*</span></label>
                        <input type="text" name="guest_house_name" class="form-control" value="{{ old('guest_house_name') }}" placeholder="Enter guest house name" required>
                    </div>

                    <div class="form-group">
                        <label>Total Guests <span class="required">*</span></label>
                        <input type="number" min="1" max="20" name="total_guests" class="form-control" value="{{ old('total_guests', 1) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Stay From <span class="required">*</span></label>
                        <input type="date" name="stay_from" class="form-control" value="{{ old('stay_from') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Stay To <span class="required">*</span></label>
                        <input type="date" name="stay_to" class="form-control" value="{{ old('stay_to') }}" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Purpose of Visit <span class="required">*</span></label>
                        <textarea name="purpose" class="form-control" rows="4" placeholder="Describe the official visit purpose" required>{{ old('purpose') }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Additional notes">{{ old('remarks') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('requisitions.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
            <button type="submit" class="btn-submit">Submit Requisition</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const districtField = document.getElementById('requisitionDistrict');
        const blockField = document.getElementById('requisitionBlock');

        function syncBlocks() {
            const districtId = districtField.value;
            Array.from(blockField.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                option.hidden = districtId && option.dataset.districtId !== districtId;
            });

            const selected = blockField.options[blockField.selectedIndex];
            if (selected && selected.hidden) {
                blockField.value = '';
            }
        }

        syncBlocks();
        districtField.addEventListener('change', syncBlocks);
    });
</script>
@endsection
