@extends('layouts.dashboard.app')

@section('title', 'Create Tour Package')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-12">
            <div class="page-header-left">
                <h3>Create Tour Package</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.tour-packages.index') }}">Tour Packages</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('dashboard.tour-packages.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Package Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Package Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" 
                                   placeholder="Auto-generated if empty">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Package Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Tours in Package</h5>
                    <button type="button" class="btn btn-sm btn-success" id="addTourBtn">
                        <i data-feather="plus"></i> Add Tour
                    </button>
                </div>
                <div class="card-body">
                    <div id="toursContainer">
                        <div class="tour-item border rounded p-3 mb-3">
                            <div class="row align-items-end">
                                <div class="col-md-5 mb-2">
                                    <label class="form-label">Select Tour <span class="text-danger">*</span></label>
                                    <select name="tours[0][tour_id]" class="form-control tour-select" required>
                                        <option value="">Choose a tour...</option>
                                        @foreach($tours as $tour)
                                            <option value="{{ $tour->id }}" data-price="{{ $tour->adult_price }}">
                                                {{ $tour->title }} (${{ number_format($tour->adult_price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="tours[0][quantity]" class="form-control" value="1" min="1">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Custom Price</label>
                                    <input type="number" name="tours[0][individual_price]" class="form-control" 
                                           step="0.01" placeholder="Optional">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-tour" disabled>
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Pricing</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Package Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="package_price" class="form-control @error('package_price') is-invalid @enderror" 
                                   step="0.01" value="{{ old('package_price') }}" required>
                        </div>
                        @error('package_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Original Price (calculated or manual)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="original_price" class="form-control" step="0.01" 
                                   value="{{ old('original_price') }}" placeholder="Auto-calculated">
                        </div>
                        <small class="text-muted">Leave empty to auto-calculate from tour prices</small>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-control">
                                <option value="">None</option>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Discount Value</label>
                            <input type="number" name="discount_value" class="form-control" step="0.01">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Availability</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Valid From</label>
                            <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from') }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Valid Until</label>
                            <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Bookings</label>
                        <input type="number" name="max_bookings" class="form-control" min="1" 
                               value="{{ old('max_bookings') }}" placeholder="Unlimited">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="{{ old('display_order', 0) }}">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="enabled" value="1" class="form-check-input" id="enabledCheck" checked>
                        <label class="form-check-label" for="enabledCheck">Enabled</label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i data-feather="save"></i> Create Package
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    feather.replace();
    
    let tourIndex = 1;
    const toursData = @json($tours->keyBy('id'));
    
    document.getElementById('addTourBtn').addEventListener('click', function() {
        const container = document.getElementById('toursContainer');
        const template = container.querySelector('.tour-item').cloneNode(true);
        
        // Update names
        template.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${tourIndex}]`);
            if (input.tagName === 'SELECT') {
                input.value = '';
            } else {
                input.value = input.type === 'number' && input.name.includes('quantity') ? '1' : '';
            }
        });
        
        // Enable remove button
        template.querySelector('.remove-tour').disabled = false;
        
        container.appendChild(template);
        tourIndex++;
        
        // Re-init feather icons
        feather.replace();
        
        // Update remove buttons state
        updateRemoveButtons();
    });
    
    document.getElementById('toursContainer').addEventListener('click', function(e) {
        if (e.target.closest('.remove-tour')) {
            e.target.closest('.tour-item').remove();
            updateRemoveButtons();
        }
    });
    
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.tour-item');
        items.forEach((item, index) => {
            item.querySelector('.remove-tour').disabled = items.length === 1;
        });
    }
</script>
@endpush

