@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Trip Bookings">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.trips.index') }}">Trips</a></li>
            <li class="breadcrumb-item active">Bookings</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert />
                    
                    <div class="card">
                        <div class="card-header">
                            <h5>Bookings for Trip: {{ $trip->departure_city }} → {{ $trip->arrival_city }} ({{ $trip->travel_date->format('M d, Y') }})</h5>
                            <div class="card-header-right">
                                <a href="{{ route('dashboard.trips.show', $trip) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i> View Trip
                                </a>
                                <a href="{{ route('dashboard.trips.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($trip->bookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Booking Ref</th>
                                                <th>Passenger</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Passengers</th>
                                                <th>Total Price</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trip->bookings as $booking)
                                            <tr>
                                                <td>{{ $booking->booking_reference }}</td>
                                                <td>{{ $booking->passenger_name }}</td>
                                                <td>{{ $booking->passenger_email }}</td>
                                                <td>{{ $booking->passenger_phone }}</td>
                                                <td>{{ $booking->adults_count }} Adults, {{ $booking->children_count }} Children</td>
                                                <td>{{ number_format($booking->total_price, 2) }} EGP</td>
                                                <td>
                                                    <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }}">
                                                        {{ $booking->status_label }}
                                                    </span>
                                                </td>
                                                <td>{{ $booking->created_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('dashboard.trip-bookings.show', $booking) }}" class="btn btn-sm btn-info" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('dashboard.trip-bookings.edit', $booking) }}" class="btn btn-sm btn-primary" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
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
        <!-- Container-fluid Ends-->
    </div>
@endsection 