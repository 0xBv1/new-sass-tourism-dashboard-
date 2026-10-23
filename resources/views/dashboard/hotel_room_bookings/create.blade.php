@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.hotel_room_bookings.store') }}" method="POST" class="page-body">
        @csrf
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Add Hotel Room Booking" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.hotel_room_bookings.index') }}">Hotel Room Bookings</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>
                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <div class="row">
                            <div class="row">
                                <x-dashboard.partials.message-alert/>
                
                
                                <div class="card">
                                    <div class="card-body">
                                        <x-dashboard.form.input-text error-key="name" name="name" id="name" label-title="Name"/>
                
                                        <x-dashboard.form.input-text error-key="email" name="email" id="email" label-title="Email"/>
                
                
                                        <x-dashboard.form.input-text error-key="phone" name="phone" id="phone" label-title="Phone"/>
                
                                        <x-dashboard.form.input-text error-key="nationality" name="nationality" id="nationality"
                                                                     label-title="Nationality"/>
                
                
                
                                        
                                        <x-dashboard.form.hotel-room-selector 
                                                    :hotels="$hotels"
                                                    :rooms="$rooms ?? []"
                                                    :data-url="route('dashboard.hotels.rooms', ['hotel' => 'HOTEL_ID'])"
                                                    :selectedHotel="$booking->hotel_id ?? null"
                                                    :selectedRoom="$booking->room_id ?? null"
                                                />
                                    
                                                <x-dashboard.form.input-date
                                                name="start_date"
                                                id="start_date"
                                                label-title="Check In"
                                                error-key="start_date"
                                                :value="old('start_date')"
                                            />
                                            
                                            <x-dashboard.form.input-date
                                                name="end_date"
                                                id="end_date"
                                                label-title="Check Out"
                                                error-key="end_date"
                                                :value="old('end_date')"
                                            />
                                            
                                            <x-dashboard.form.input-number
                                            name="guests_count"
                                            id="guests_count"
                                            label-title="Guests Count"
                                        />

                                        <!-- Extra Bed Section -->
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="mb-3">Extra Bed Options</h6>
                                            </div>
                                        </div>

                                        <x-dashboard.form.input-number
                                            name="extra_beds_count"
                                            id="extra_beds_count"
                                            label-title="Extra Beds Count"
                                            min="0"
                                            max="5"
                                            value="0"
                                        />

                                        <div id="extra-bed-info" class="alert alert-info" style="display: none;">
                                            <strong>Extra Bed Information:</strong>
                                            <div id="extra-bed-details"></div>
                                        </div>

                                        <div id="price-breakdown" class="alert alert-success" style="display: none;">
                                            <strong>Price Breakdown:</strong>
                                            <div id="price-details"></div>
                                        </div>

           <x-dashboard.form.input-select
                                            :options="[['id'=>'pending','name'=>'Pending'],['id'=>'confirmed','name'=>'Confirmed']]"
                                            name="status"
                                            id="status"
                                            track-by="id"
                                            option-lable="name"
                                            label-title="Status"
                                        />
                                            


                                            <x-dashboard.form.submit-button/>

                                        </div>
                                </div>
                
                
                            </div>

                    </div>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </form>
@endsection 

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const hotelSelect = document.getElementById('hotel_id');
    const roomSelect = document.getElementById('room_id');
    const extraBedsCount = document.getElementById('extra_beds_count');
    const extraBedInfo = document.getElementById('extra-bed-info');
    const extraBedDetails = document.getElementById('extra-bed-details');
    const priceBreakdown = document.getElementById('price-breakdown');
    const priceDetails = document.getElementById('price-details');

    function updateExtraBedInfo() {
        const selectedRoom = roomSelect.value;
        if (selectedRoom) {
            const selectedOption = roomSelect.querySelector(`option[value="${selectedRoom}"]`);
            if (selectedOption) {
                const extraBedAvailable = selectedOption.dataset.extraBedAvailable === 'true';
                const extraBedPrice = parseFloat(selectedOption.dataset.extraBedPrice) || 0;
                const maxExtraBeds = parseInt(selectedOption.dataset.maxExtraBeds) || 0;
                
                if (extraBedAvailable) {
                    extraBedInfo.style.display = 'block';
                    extraBedDetails.innerHTML = `
                        <div>Extra Bed Price: $${extraBedPrice.toFixed(2)} per night</div>
                        <div>Maximum Extra Beds: ${maxExtraBeds}</div>
                    `;
                } else {
                    extraBedInfo.style.display = 'none';
                }
            }
        } else {
            extraBedInfo.style.display = 'none';
            priceBreakdown.style.display = 'none';
        }
    }

    function calculatePrice() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const extraBeds = parseInt(extraBedsCount.value) || 0;
        const selectedRoom = roomSelect.value;
        
        if (startDate && endDate && selectedRoom) {
            const selectedOption = roomSelect.querySelector(`option[value="${selectedRoom}"]`);
            if (selectedOption) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                const nightPrice = parseFloat(selectedOption.dataset.nightPrice) || 0;
                const extraBedPrice = parseFloat(selectedOption.dataset.extraBedPrice) || 0;
                
                if (nights > 0) {
                    const basePrice = nightPrice * nights;
                    const extraBedsPrice = extraBedPrice * extraBeds * nights;
                    const totalPrice = basePrice + extraBedsPrice;
                    
                    priceBreakdown.style.display = 'block';
                    priceDetails.innerHTML = `
                        <div>Nights: ${nights}</div>
                        <div>Base Price: $${basePrice.toFixed(2)}</div>
                        <div>Extra Beds: ${extraBeds} × $${extraBedPrice.toFixed(2)} × ${nights} nights = $${extraBedsPrice.toFixed(2)}</div>
                        <div><strong>Total: $${totalPrice.toFixed(2)}</strong></div>
                    `;
                }
            }
        }
    }

    hotelSelect.addEventListener('change', function () {
        const hotelId = this.value;

        const urlTemplate = this.dataset.url; // get from data-url attribute
        const url = urlTemplate.replace('HOTEL_ID', hotelId); // replace placeholder

        fetch(url)
            .then(response => response.json())
            .then(data => {
                roomSelect.innerHTML = '<option value="">Select Room</option>';

                data.rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = room.name;
                    option.dataset.extraBedAvailable = room.extra_bed_available || false;
                    option.dataset.extraBedPrice = room.extra_bed_price || 0;
                    option.dataset.maxExtraBeds = room.max_extra_beds || 0;
                    option.dataset.nightPrice = room.night_price || 0;
                    option.dataset.maxCapacity = room.max_capacity || 0;
                    roomSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching rooms:', error);
            });
    });

    roomSelect.addEventListener('change', function() {
        updateExtraBedInfo();
        calculatePrice();
    });
    extraBedsCount.addEventListener('change', calculatePrice);
    document.getElementById('start_date').addEventListener('change', calculatePrice);
    document.getElementById('end_date').addEventListener('change', calculatePrice);

    hotelSelect.dispatchEvent(new Event('change'));
});
    </script>
@endsection