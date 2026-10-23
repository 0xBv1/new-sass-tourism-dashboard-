@extends('layouts.dashboard.app')

@section('title', 'Create Accommodation')

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
                <span>Create</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Create Accommodation
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.accommodations.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="hotel_id" class="form-label">Hotel</label>
                    <select name="hotel_id" id="hotel_id" class="form-control">
                        <option value="">Select Hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>

                <h5>Pricing</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="price_solo" class="form-label">Solo</label>
                            <input type="number" name="prices[solo]" id="price_solo" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="price_2_4" class="form-label">2–4 Pax</label>
                            <input type="number" name="prices[2–4 Pax]" id="price_2_4" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="price_5_8" class="form-label">5–8 Pax</label>
                            <input type="number" name="prices[5–8 Pax]" id="price_5_8" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="price_9_16" class="form-label">9–16 Pax</label>
                            <input type="number" name="prices[9–16 Pax]" id="price_9_16" class="form-control" step="0.01" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
