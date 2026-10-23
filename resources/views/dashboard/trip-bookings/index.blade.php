@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Trip Bookings Management">
            <li class="breadcrumb-item active">Trip Bookings</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert />
                    
                    <!-- Filters Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Filters</h5>
                        </div>
                        <div class="card-body">
                            <form id="filterForm" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="trip_id">Trip</label>
                                        <select name="trip_id" id="trip_id" class="form-control">
                                            <option value="">All Trips</option>
                                            @foreach($trips as $trip)
                                                <option value="{{ $trip->id }}" {{ request('trip_id') == $trip->id ? 'selected' : '' }}>
                                                    {{ $trip->departure_city_name }} → {{ $trip->arrival_city_name }} ({{ $trip->travel_date->format('M d, Y') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">All Statuses</option>
                                            @foreach($statuses as $key => $label)
                                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="date_from">Date From</label>
                                        <input type="date" name="date_from" id="date_from" class="form-control" 
                                               value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="date_to">Date To</label>
                                        <input type="date" name="date_to" id="date_to" class="form-control" 
                                               value="{{ request('date_to') }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('dashboard.trip-bookings.index') }}" class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Bookings Table Card -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Trip Bookings</h5>
                                @can('trip-bookings.create')
                                    <a href="{{ route('dashboard.trip-bookings.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i>
                                        Create Booking
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body order-datatable overflow-x-auto">
                            <div class="">
                                {!! $dataTable->table(['class'=>'display']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            // Auto-submit form on filter change
            $('#filterForm select, #filterForm input[type="date"]').change(function() {
                $('#filterForm').submit();
            });
        });
    </script>
@endpush 