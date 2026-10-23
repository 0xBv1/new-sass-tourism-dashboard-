@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.trip-bookings.store') }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Trip Booking" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.trip-bookings.index') }}">Trip Bookings</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.multi-tab-card
                            :tabs="['booking_details', 'passenger_info', 'pricing']"
                            tab-id="booking-tabs">
                            
                            <!-- Booking Details Tab -->
                            <div class="tab-pane fade active show"
                                 id="{{ 'booking-tabs-0' }}" role="tabpanel"
                                 aria-labelledby="{{ 'booking-tabs-0' }}-tab">

                                <x-dashboard.form.input-select 
                                    id="trip_id"
                                    name="trip_id"
                                    labelTitle="Select Trip"
                                    :options="$trips"
                                    errorKey="trip_id"
                                    required="true"
                                    :value="old('trip_id', $selectedTripId)"
                                />

                                <x-dashboard.form.input-select 
                                    id="client_id"
                                    name="client_id"
                                    labelTitle="Client (Optional)"
                                    :options="$clients"
                                    errorKey="client_id"
                                    :value="old('client_id')"
                                />

                                <x-dashboard.form.input-select 
                                    id="status"
                                    name="status"
                                    labelTitle="Booking Status"
                                    :options="$statuses"
                                    errorKey="status"
                                    required="true"
                                    :value="old('status', 'pending')"
                                />

                                <div class="form-group row">
                                    <label class="col-xl-3 col-md-4" for="booking_reference">Booking Reference</label>
                                    <div class="col-xl-8 col-md-7">
                                        <input type="text" 
                                               class="form-control" 
                                               id="booking_reference" 
                                               name="booking_reference" 
                                               value="{{ old('booking_reference', \App\Models\TripBooking::generateBookingReference()) }}"
                                               readonly>
                                        <small class="form-text text-muted">Auto-generated booking reference</small>
                                        @error('booking_reference')
                                            <span class="d-block text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <!-- Passenger Information Tab -->
                            <div class="tab-pane fade"
                                 id="{{ 'booking-tabs-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'booking-tabs-1' }}-tab">

                                <x-dashboard.form.input-text 
                                    id="passenger_name"
                                    name="passenger_name"
                                    labelTitle="Passenger Name"
                                    value="{{ old('passenger_name') }}"
                                    errorKey="passenger_name"
                                    required="true"
                                />

                                <x-dashboard.form.input-text 
                                    id="passenger_email"
                                    name="passenger_email"
                                    labelTitle="Passenger Email"
                                    type="email"
                                    value="{{ old('passenger_email') }}"
                                    errorKey="passenger_email"
                                    required="true"
                                />

                                <x-dashboard.form.input-text 
                                    id="passenger_phone"
                                    name="passenger_phone"
                                    labelTitle="Passenger Phone"
                                    value="{{ old('passenger_phone') }}"
                                    errorKey="passenger_phone"
                                    required="true"
                                />

                                <x-dashboard.form.input-date 
                                    id="booking_date"
                                    name="booking_date"
                                    labelTitle="Booking Date"
                                    value="{{ old('booking_date', now()->format('Y-m-d')) }}"
                                    errorKey="booking_date"
                                    required="true"
                                />

                                <x-dashboard.form.input-number 
                                    id="number_of_passengers"
                                    name="number_of_passengers"
                                    labelTitle="Number of Passengers"
                                    value="{{ old('number_of_passengers', 1) }}"
                                    errorKey="number_of_passengers"
                                    required="true"
                                    min="1"
                                    max="50"
                                />

                                <div class="form-group row">
                                    <label class="col-xl-3 col-md-4"></label>
                                    <div class="col-xl-8 col-md-7">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Note:</strong> All passengers pay the same price per seat.
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Pricing Tab -->
                            <div class="tab-pane fade"
                                 id="{{ 'booking-tabs-2' }}" role="tabpanel"
                                 aria-labelledby="{{ 'booking-tabs-2' }}-tab">

                                <div class="form-group row">
                                    <label class="col-xl-3 col-md-4">Trip Information</label>
                                    <div class="col-xl-8 col-md-7">
                                        <div id="trip-info" class="alert alert-info" style="display: none;">
                                            <!-- Trip info will be populated via JavaScript -->
                                        </div>
                                    </div>
                                </div>

                                <x-dashboard.form.input-number 
                                    id="total_price"
                                    name="total_price"
                                    labelTitle="Total Price (EGP)"
                                    value="{{ old('total_price') }}"
                                    errorKey="total_price"
                                    required="true"
                                    min="0"
                                    step="0.01"
                                />

                                <div class="form-group row">
                                    <label class="col-xl-3 col-md-4">Price Breakdown</label>
                                    <div class="col-xl-8 col-md-7">
                                        <div id="price-breakdown" class="alert alert-secondary">
                                            <!-- Price breakdown will be populated via JavaScript -->
                                            <p class="mb-0">Select a trip to see price breakdown</p>
                                        </div>
                                    </div>
                                </div>

                                <x-dashboard.form.input-textarea 
                                    id="notes"
                                    name="notes"
                                    labelTitle="Special Requests / Notes"
                                    value="{{ old('notes') }}"
                                    errorKey="notes"
                                    rows="4"
                                />

                            </div>

                        </x-dashboard.form.multi-tab-card>

                        <x-dashboard.form.submit-button 
                            title="Create Booking"
                            class="btn-primary"
                        />

                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get form elements
            const tripSelect = document.getElementById('trip_id');
            const passengersInput = document.getElementById('number_of_passengers');
            const totalPriceInput = document.getElementById('total_price');
            const tripInfoDiv = document.getElementById('trip-info');
            const priceBreakdownDiv = document.getElementById('price-breakdown');

            // Initialize select2
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    width: '100%',
                    placeholder: '--Select Option--'
                });
            }

            // Function to update trip information and pricing
            function updateTripInfo() {
                const tripId = tripSelect.value;
                const passengers = parseInt(passengersInput.value) || 0;

                if (!tripId) {
                    tripInfoDiv.style.display = 'none';
                    priceBreakdownDiv.style.display = 'none';
                    return;
                }

                // Fetch trip details
                fetch(`/api/trips/${tripId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const trip = data.trip;
                            
                            // Update trip info
                            tripInfoDiv.innerHTML = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>From:</strong> ${trip.departure_city_name}<br>
                                        <strong>To:</strong> ${trip.arrival_city_name}<br>
                                        <strong>Date:</strong> ${trip.travel_date}<br>
                                        <strong>Time:</strong> ${trip.departure_time} - ${trip.arrival_time}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Available Seats:</strong> ${trip.available_seats}<br>
                                        <strong>Seat Price:</strong> ${trip.seat_price} EGP<br>
                                        <strong>Trip Type:</strong> ${trip.trip_type_label}
                                    </div>
                                </div>
                            `;
                            tripInfoDiv.style.display = 'block';

                            // Calculate and update pricing
                            const totalPrice = trip.seat_price * passengers;

                            priceBreakdownDiv.innerHTML = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Passengers (${passengers}):</strong> ${passengers} × ${trip.seat_price} EGP = ${totalPrice} EGP
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total Price:</strong> <span class="text-primary font-weight-bold">${totalPrice} EGP</span>
                                    </div>
                                </div>
                            `;

                            // Update total price input
                            totalPriceInput.value = totalPrice;

                            // Validate seat availability
                            if (passengers > trip.available_seats) {
                                priceBreakdownDiv.innerHTML += `
                                    <div class="alert alert-warning mt-2">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Warning:</strong> Only ${trip.available_seats} seats available for this trip.
                                    </div>
                                `;
                            }
                        } else {
                            throw new Error(data.message || 'Failed to load trip details');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching trip details:', error);
                        tripInfoDiv.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                <strong>Error loading trip details:</strong><br>
                                ${error.message}<br>
                                <small>Please try refreshing the page or contact support if the problem persists.</small>
                            </div>
                        `;
                        tripInfoDiv.style.display = 'block';
                    });
            }

            // Event listeners
            tripSelect.addEventListener('change', updateTripInfo);
            passengersInput.addEventListener('input', updateTripInfo);

            // Initial update if values are pre-filled
            if (tripSelect.value) {
                updateTripInfo();
            }

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const tripId = tripSelect.value;
                const passengers = parseInt(passengersInput.value) || 0;

                if (!tripId) {
                    e.preventDefault();
                    alert('Please select a trip');
                    return;
                }

                if (passengers < 1) {
                    e.preventDefault();
                    alert('Number of passengers must be at least 1');
                    return;
                }
            });
        });
    </script>
@endsection 