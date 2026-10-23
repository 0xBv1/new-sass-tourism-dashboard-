@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.trips.store') }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Trip" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.trips.index') }}">Trips</a>
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
                            :tabs="['trip_details', 'schedule', 'amenities']"
                            tab-id="trip-tabs">
                            
                            <!-- Trip Details Tab -->
                            <div class="tab-pane fade active show"
                                 id="{{ 'trip-tabs-0' }}" role="tabpanel"
                                 aria-labelledby="{{ 'trip-tabs-0' }}-tab">

                                <x-dashboard.form.input-select 
                                    id="trip_type"
                                    name="trip_type"
                                    labelTitle="Trip Type"
                                    :options="$tripTypes"
                                    errorKey="trip_type"
                                    required="true"
                                    :value="old('trip_type')"
                                />

                                <x-dashboard.form.input-select 
                                    id="departure_city_id"
                                    name="departure_city_id"
                                    labelTitle="Departure City"
                                    :options="$cities"
                                    errorKey="departure_city_id"
                                    required="true"
                                    :value="old('departure_city_id')"
                                />

                                <x-dashboard.form.input-select 
                                    id="arrival_city_id"
                                    name="arrival_city_id"
                                    labelTitle="Arrival City"
                                    :options="$cities"
                                    errorKey="arrival_city_id"
                                    required="true"
                                    :value="old('arrival_city_id')"
                                />

                                <x-dashboard.form.input-number 
                                    id="seat_price"
                                    name="seat_price"
                                    labelTitle="Seat Price (EGP)"
                                    value="{{ old('seat_price') }}"
                                    errorKey="seat_price"
                                />

                                <x-dashboard.form.input-number 
                                    id="total_seats"
                                    name="total_seats"
                                    labelTitle="Total Seats"
                                    value="{{ old('total_seats') }}"
                                    errorKey="total_seats"
                                />

                                <x-dashboard.form.input-checkbox 
                                    id="enabled"
                                    name="enabled"
                                    labelTitle="Enabled"
                                    :value="true"
                                    resourceName="trip"
                                    resourceDesc="Enable"
                                    errorKey="enabled"
                                />

                            </div>

                            <!-- Schedule Tab -->
                            <div class="tab-pane fade"
                                 id="{{ 'trip-tabs-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'trip-tabs-1' }}-tab">

                                <x-dashboard.form.input-date 
                                    id="travel_date"
                                    name="travel_date"
                                    labelTitle="Travel Date"
                                    value="{{ old('travel_date') }}"
                                    errorKey="travel_date"
                                    required="true"
                                />

                                <x-dashboard.form.input-date 
                                    id="return_date"
                                    name="return_date"
                                    labelTitle="Return Date"
                                    value="{{ old('return_date') }}"
                                    errorKey="return_date"
                                    class="return-date-field"
                                    style="display: none;"
                                />

                                <x-dashboard.form.input-time 
                                    id="departure_time"
                                    name="departure_time"
                                    labelTitle="Departure Time"
                                    value="{{ old('departure_time') }}"
                                    errorKey="departure_time"
                                    required="true"
                                />

                                <x-dashboard.form.input-time 
                                    id="arrival_time"
                                    name="arrival_time"
                                    labelTitle="Arrival Time"
                                    value="{{ old('arrival_time') }}"
                                    errorKey="arrival_time"
                                    required="true"
                                />

                            </div>

                            <!-- Amenities Tab -->
                            <div class="tab-pane fade"
                                 id="{{ 'trip-tabs-2' }}" role="tabpanel"
                                 aria-labelledby="{{ 'trip-tabs-2' }}-tab">

                                <x-dashboard.form.input-select
                                    id="amenities"
                                    name="amenities[]"
                                    labelTitle="Amenities"
                                    :options="$amenities"
                                    errorKey="amenities"
                                    multible="true"
                                    :value="old('amenities', [])"
                                />

                                <x-dashboard.form.input-textarea 
                                    id="additional_notes"
                                    name="additional_notes"
                                    labelTitle="Additional Notes"
                                    value="{{ old('additional_notes') }}"
                                    errorKey="additional_notes"
                                    rows="4"
                                />

                            </div>

                        </x-dashboard.form.multi-tab-card>

                        <x-dashboard.form.submit-button 
                            title="Create Trip"
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
            const tripTypeSelect = document.getElementById('trip_type');
            const returnDateField = document.querySelector('.return-date-field');

            function toggleReturnDate() {
                if (tripTypeSelect.value === 'round_trip') {
                    returnDateField.style.display = 'block';
                    returnDateField.querySelector('input').required = true;
                } else {
                    returnDateField.style.display = 'none';
                    returnDateField.querySelector('input').required = false;
                    returnDateField.querySelector('input').value = '';
                }
            }

            tripTypeSelect.addEventListener('change', toggleReturnDate);
            toggleReturnDate(); // Initial call

            // Time input enhancements
            const departureTimeInput = document.getElementById('departure-time');
            const arrivalTimeInput = document.getElementById('arrival-time');

            // Set min/max times for better UX
            departureTimeInput.min = '00:00';
            departureTimeInput.max = '23:59';
            arrivalTimeInput.min = '00:00';
            arrivalTimeInput.max = '23:59';

            // Auto-validate arrival time is after departure time
            function validateTimes() {
                const departureTime = departureTimeInput.value;
                const arrivalTime = arrivalTimeInput.value;
                
                if (departureTime && arrivalTime && departureTime >= arrivalTime) {
                    arrivalTimeInput.setCustomValidity('Arrival time must be after departure time');
                } else {
                    arrivalTimeInput.setCustomValidity('');
                }
            }

            departureTimeInput.addEventListener('change', validateTimes);
            arrivalTimeInput.addEventListener('change', validateTimes);

            // Debug: Log form values before submission
            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('Form submission debug:');
                console.log('Trip Type:', document.getElementById('trip_type').value);
                console.log('Departure City ID:', document.getElementById('departure_city_id').value);
                console.log('Arrival City ID:', document.getElementById('arrival_city_id').value);
                console.log('Enabled:', document.getElementById('enabled').checked);
            });

            // Initialize select2
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    width: '100%',
                    placeholder: '--Select Option--'
                });
            }
        });
    </script>
@endsection 