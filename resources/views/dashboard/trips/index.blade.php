@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Trips Management">
            <li class="breadcrumb-item active">Trips</li>
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
                                    <div class="col-md-2">
                                        <label for="trip_type">Trip Type</label>
                                        <select name="trip_type" id="trip_type" class="form-control">
                                            <option value="">All Types</option>
                                            @foreach(\App\Models\Trip::getTripTypes() as $key => $label)
                                                <option value="{{ $key }}" {{ request('trip_type') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="departure_city">From City</label>
                                        <select name="departure_city" id="departure_city" class="form-control">
                                            <option value="">All Cities</option>
                                            @foreach(\App\Models\City::orderBy('name')->get() as $city)
                                                <option value="{{ $city->name }}" {{ request('departure_city') == $city->name ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="arrival_city">To City</label>
                                        <select name="arrival_city" id="arrival_city" class="form-control">
                                            <option value="">All Cities</option>
                                            @foreach(\App\Models\City::orderBy('name')->get() as $city)
                                                <option value="{{ $city->name }}" {{ request('arrival_city') == $city->name ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="travel_date">Travel Date</label>
                                        <input type="date" name="travel_date" id="travel_date" class="form-control" 
                                               value="{{ request('travel_date') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date_from">Date From</label>
                                        <input type="date" name="date_from" id="date_from" class="form-control" 
                                               value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date_to">Date To</label>
                                        <input type="date" name="date_to" id="date_to" class="form-control" 
                                               value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="enabled">Status</label>
                                        <select name="enabled" id="enabled" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="1" {{ request('enabled') === '1' ? 'selected' : '' }}>Enabled</option>
                                            <option value="0" {{ request('enabled') === '0' ? 'selected' : '' }}>Disabled</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="amenities">Amenities</label>
                                        <select name="amenities" id="amenities" class="form-control">
                                            <option value="">All Amenities</option>
                                            <option value="Wi-Fi" {{ request('amenities') === 'Wi-Fi' ? 'selected' : '' }}>Wi-Fi</option>
                                            <option value="Snacks" {{ request('amenities') === 'Snacks' ? 'selected' : '' }}>Snacks</option>
                                            <option value="AC" {{ request('amenities') === 'AC' ? 'selected' : '' }}>Air Conditioning</option>
                                            <option value="TV" {{ request('amenities') === 'TV' ? 'selected' : '' }}>TV</option>
                                            <option value="USB_Charging" {{ request('amenities') === 'USB_Charging' ? 'selected' : '' }}>USB Charging</option>
                                            <option value="Water" {{ request('amenities') === 'Water' ? 'selected' : '' }}>Free Water</option>
                                            <option value="Blanket" {{ request('amenities') === 'Blanket' ? 'selected' : '' }}>Blanket</option>
                                            <option value="Pillow" {{ request('amenities') === 'Pillow' ? 'selected' : '' }}>Pillow</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="price_from">Price From (EGP)</label>
                                        <input type="number" name="price_from" id="price_from" class="form-control" 
                                               value="{{ request('price_from') }}" placeholder="Min Price">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="price_to">Price To (EGP)</label>
                                        <input type="number" name="price_to" id="price_to" class="form-control" 
                                               value="{{ request('price_to') }}" placeholder="Max Price">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="available_seats">Min Available Seats</label>
                                        <input type="number" name="available_seats" id="available_seats" class="form-control" 
                                               value="{{ request('available_seats') }}" placeholder="Min Seats">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                        <a href="{{ route('dashboard.trips.index') }}" class="btn btn-secondary">Clear Filters</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- DataTable Card -->
                    <div class="card">
                        <x-dashboard.partials.table-card-header model="trip" />
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
    {{ $dataTable->scripts() }}
    
    <script>
        $(document).ready(function() {
            // Auto-submit form on select change
            $('#trip_type, #departure_city, #arrival_city, #enabled, #amenities').change(function() {
                $('#filterForm').submit();
            });

            // Auto-submit form on date/number input change
            $('#travel_date, #date_from, #date_to, #price_from, #price_to, #available_seats').change(function() {
                $('#filterForm').submit();
            });

            // Debounced auto-submit for text inputs
            let timeout;
            $('#departure_city, #arrival_city').on('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    $('#filterForm').submit();
                }, 500);
            });
        });
    </script>
@endpush 