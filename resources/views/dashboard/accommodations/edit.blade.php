@extends('layouts.dashboard.app')

@section('title', 'Edit Accommodation')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="d-flex align-items-center justify-content-between" style="width: 100%">
        <ol class="breadcrumb my-0 ms-2">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.accommodations.index') }}">Accommodations</a>
            </li>
            <li class="breadcrumb-item active">
                <span>Edit</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Edit Accommodation
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.accommodations.update', $accommodation) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $accommodation->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="hotel_id" class="form-label">Hotel</label>
                    <select name="hotel_id" id="hotel_id" class="form-control">
                        <option value="">Select Hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" @if($accommodation->hotel_id == $hotel->id) selected @endif>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>

                <h5>Pricing</h5>
                <div class="row">
                    @foreach(['Solo', '2–4 Pax', '5–8 Pax', '9–16 Pax'] as $group)
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="price_{{ str_replace(' ', '_', $group) }}" class="form-label">{{ $group }}</label>
                                <input type="number" name="prices[{{ $group }}]" id="price_{{ str_replace(' ', '_', $group) }}" class="form-control"
                                       value="{{ $accommodation->prices->where('group', $group)->first()?->price }}"
                                       step="0.01" required>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@endsection
