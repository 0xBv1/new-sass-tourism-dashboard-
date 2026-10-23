@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <x-dashboard.partials.breadcrumb title="Trip Booking Invoice">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.trip-bookings.index') }}">Trip Bookings</a></li>
            <li class="breadcrumb-item active">Invoice</li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Booking Invoice #{{ $tripBooking->booking_reference }}</h5>
                            <div class="card-header-right">
                                <button onclick="window.print()" class="btn btn-primary btn-sm">
                                    <i class="fa fa-print"></i> Print Invoice
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Booking Details</h6>
                                    <p><strong>Reference:</strong> {{ $tripBooking->booking_reference }}</p>
                                    <p><strong>Passenger:</strong> {{ $tripBooking->passenger_name }}</p>
                                    <p><strong>Email:</strong> {{ $tripBooking->passenger_email }}</p>
                                    <p><strong>Phone:</strong> {{ $tripBooking->passenger_phone }}</p>
                                    <p><strong>Booking Date:</strong> {{ $tripBooking->created_at->format('M d, Y H:i') }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-{{ $tripBooking->status_color }}">{{ $tripBooking->status_label }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Trip Details</h6>
                                    <p><strong>Route:</strong> {{ $tripBooking->trip->departure_city_name }} → {{ $tripBooking->trip->arrival_city_name }}</p>
                                    <p><strong>Travel Date:</strong> {{ $tripBooking->trip->travel_date->format('M d, Y') }}</p>
                                    <p><strong>Departure Time:</strong> {{ $tripBooking->trip->formatted_departure_time }}</p>
                                    <p><strong>Arrival Time:</strong> {{ $tripBooking->trip->formatted_arrival_time }}</p>
                                    <p><strong>Seat Price:</strong> {{ $tripBooking->trip->formatted_price }}</p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Price Breakdown</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Trip Ticket</td>
                                                    <td>{{ $tripBooking->number_of_passengers }}</td>
                                                    <td>{{ $tripBooking->trip->formatted_price }}</td>
                                                    <td><strong>{{ $tripBooking->formatted_total_price }}</strong></td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">Total Amount</th>
                                                    <th>{{ $tripBooking->formatted_total_price }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            @if($tripBooking->notes)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h6>Notes</h6>
                                    <p>{{ $tripBooking->notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .card-header-right {
                display: none !important;
            }
            .page-body {
                margin: 0 !important;
            }
        }
    </style>
@endsection
