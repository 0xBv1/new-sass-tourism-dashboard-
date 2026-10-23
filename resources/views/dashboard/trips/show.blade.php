@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Trip Details">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.trips.index') }}">Trips</a></li>
            <li class="breadcrumb-item active">Details</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert />
                    
                    <div class="card">
                        <div class="card-header">
                            <h5>Trip Details</h5>
                            <div class="card-header-right">
                                @can('trip-bookings.create')
                                    <a href="{{ route('dashboard.trip-bookings.create') }}?trip_id={{ $trip->id }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Create Booking
                                    </a>
                                @endcan
                                <a href="{{ route('dashboard.trips.edit', $trip) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('dashboard.trips.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="150">Trip Type:</th>
                                            <td>{{ $trip->trip_type_label }}</td>
                                        </tr>
                                        <tr>
                                            <th>From:</th>
                                            <td>{{ $trip->departure_city_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>To:</th>
                                            <td>{{ $trip->arrival_city_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Travel Date:</th>
                                            <td>{{ $trip->travel_date->format('M d, Y') }}</td>
                                        </tr>
                                        @if($trip->return_date)
                                        <tr>
                                            <th>Return Date:</th>
                                            <td>{{ $trip->return_date->format('M d, Y') }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>Departure Time:</th>
                                            <td>{{ $trip->formatted_departure_time }}</td>
                                        </tr>
                                        <tr>
                                            <th>Arrival Time:</th>
                                            <td>{{ $trip->formatted_arrival_time }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Seat Price:</th>
                                            <td>{{ $trip->formatted_price }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Seats:</th>
                                            <td>{{ $trip->total_seats }}</td>
                                        </tr>
                                        <tr>
                                            <th>Available Seats:</th>
                                            <td>{{ $trip->available_seats }}</td>
                                        </tr>
                                        <tr>
                                            <th>Booked Seats:</th>
                                            <td>{{ $trip->booked_seats }}</td>
                                        </tr>
                                        <tr>
                                            <th>Occupancy Rate:</th>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-{{ $trip->occupancy_rate >= 90 ? 'danger' : ($trip->occupancy_rate >= 75 ? 'warning' : ($trip->occupancy_rate >= 50 ? 'info' : 'success')) }}" 
                                                         style="width: {{ $trip->occupancy_rate }}%">
                                                        {{ $trip->occupancy_rate }}%
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $trip->occupancy_status }}</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total Revenue:</th>
                                            <td>{{ $trip->formatted_total_price }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if($trip->enabled)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td>{{ $trip->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if(!empty($trip->amenities))
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h6>Amenities:</h6>
                                    <div class="row">
                                        @foreach($trip->amenities as $amenity)
                                            <div class="col-md-3">
                                                <span class="badge badge-info">{{ $amenity }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($trip->additional_notes)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h6>Additional Notes:</h6>
                                    <p>{{ $trip->additional_notes }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6>Bookings ({{ $trip->bookings->count() }})</h6>
                                    @if($trip->bookings->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Booking Ref</th>
                                                        <th>Passenger</th>
                                                        <th>Passengers</th>
                                                        <th>Total Price</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($trip->bookings as $booking)
                                                    <tr>
                                                        <td>{{ $booking->booking_reference }}</td>
                                                        <td>{{ $booking->passenger_name }}</td>
                                                        <td>{{ $booking->adults_count }} Adults, {{ $booking->children_count }} Children</td>
                                                        <td>{{ number_format($booking->total_price, 2) }} EGP</td>
                                                        <td>
                                                            <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }}">
                                                                {{ $booking->status_label }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $booking->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No bookings for this trip yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection 