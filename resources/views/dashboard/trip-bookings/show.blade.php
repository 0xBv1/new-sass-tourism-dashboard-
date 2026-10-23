@extends('layouts.dashboard.app')

@section('content')
    <!-- Container-fluid starts-->
    <x-dashboard.partials.breadcrumb title="Trip Booking Details" :hideFirst="true">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.trip-bookings.index') }}">Trip Bookings</a>
        </li>
    </x-dashboard.partials.breadcrumb>
    <!-- Container-fluid Ends-->

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <x-dashboard.partials.message-alert/>

            <!-- Booking Information Card -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>
                            Booking Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Booking Reference:</strong></td>
                                        <td><span class="badge bg-primary">{{ $tripBooking->booking_reference }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if($tripBooking->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($tripBooking->status === 'confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                            @elseif($tripBooking->status === 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @elseif($tripBooking->status === 'completed')
                                                <span class="badge bg-info">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $tripBooking->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td>{{ $tripBooking->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Total Passengers:</strong></td>
                                        <td>{{ $tripBooking->number_of_passengers }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Adults:</strong></td>
                                        <td>{{ $tripBooking->adults_count }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Children:</strong></td>
                                        <td>{{ $tripBooking->children_count }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Price:</strong></td>
                                        <td><span class="text-primary font-weight-bold">{{ $tripBooking->formatted_total_price }}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trip Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-route me-2"></i>
                            Trip Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>From:</strong></td>
                                        <td>{{ $tripBooking->trip->departure_city_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>To:</strong></td>
                                        <td>{{ $tripBooking->trip->arrival_city_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date:</strong></td>
                                        <td>{{ $tripBooking->trip->travel_date->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Time:</strong></td>
                                        <td>{{ $tripBooking->trip->formatted_departure_time }} - {{ $tripBooking->trip->formatted_arrival_time }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Trip Type:</strong></td>
                                        <td>{{ $tripBooking->trip->trip_type_label }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Seat Price:</strong></td>
                                        <td>{{ $tripBooking->trip->formatted_price }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Available Seats:</strong></td>
                                        <td>{{ $tripBooking->trip->available_seats }}</td>
                                    </tr>
                                    @if($tripBooking->trip->amenities)
                                        <tr>
                                            <td><strong>Amenities:</strong></td>
                                            <td>
                                                @foreach($tripBooking->trip->amenities as $amenity)
                                                    <span class="badge bg-secondary me-1">{{ $amenity }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        @if($tripBooking->trip->additional_notes)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <strong>Additional Notes:</strong>
                                    <p class="text-muted mb-0">{{ $tripBooking->trip->additional_notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Passenger Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>
                            Passenger Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $tripBooking->passenger_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><a href="mailto:{{ $tripBooking->passenger_email }}">{{ $tripBooking->passenger_email }}</a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td><a href="tel:{{ $tripBooking->passenger_phone }}">{{ $tripBooking->passenger_phone }}</a></td>
                                    </tr>
                                    @if($tripBooking->client)
                                        <tr>
                                            <td><strong>Client:</strong></td>
                                            <td>{{ $tripBooking->client->name }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        @if($tripBooking->notes)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <strong>Special Requests / Notes:</strong>
                                    <p class="text-muted mb-0">{{ $tripBooking->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @can('trip-bookings.edit')
                                <a href="{{ route('dashboard.trip-bookings.edit', $tripBooking) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>
                                    Edit Booking
                                </a>
                            @endcan

                            @can('trip-bookings.toggle-status')
                                @if($tripBooking->status !== 'cancelled')
                                    <form action="{{ route('dashboard.trip-bookings.toggle-status', $tripBooking) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-check me-2"></i>
                                            {{ $tripBooking->status === 'confirmed' ? 'Mark as Pending' : 'Confirm Booking' }}
                                        </button>
                                    </form>
                                @endif
                            @endcan

                            @can('trip-bookings.cancel')
                                @if($tripBooking->status !== 'cancelled')
                                    <form action="{{ route('dashboard.trip-bookings.cancel', $tripBooking) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning w-100" 
                                                onclick="return confirm('Are you sure you want to cancel this booking?')">
                                            <i class="fas fa-times me-2"></i>
                                            Cancel Booking
                                        </button>
                                    </form>
                                @endif
                            @endcan

                            @can('trip-bookings.delete')
                                <form action="{{ route('dashboard.trip-bookings.destroy', $tripBooking) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" 
                                            onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.')">
                                        <i class="fas fa-trash me-2"></i>
                                        Delete Booking
                                    </button>
                                </form>
                            @endcan

                            <a href="{{ route('dashboard.trip-bookings.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            Price Breakdown
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td>Adults ({{ $tripBooking->adults_count }}):</td>
                                <td class="text-end">{{ $tripBooking->adults_count }} × {{ $tripBooking->trip->formatted_price }}</td>
                            </tr>
                            <tr>
                                <td>Children ({{ $tripBooking->children_count }}):</td>
                                <td class="text-end">{{ $tripBooking->children_count }} × {{ $tripBooking->trip->formatted_price }}</td>
                            </tr>
                            <tr class="table-active">
                                <td><strong>Total:</strong></td>
                                <td class="text-end"><strong>{{ $tripBooking->formatted_total_price }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Quick Stats
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-0">{{ $tripBooking->number_of_passengers }}</h4>
                                    <small class="text-muted">Passengers</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-0">{{ $tripBooking->trip->available_seats }}</h4>
                                <small class="text-muted">Available Seats</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection 