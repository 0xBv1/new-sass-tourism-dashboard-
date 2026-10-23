@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Bookings">
            <li class="breadcrumb-item active"><a href="{{ route('dashboard.bookings.index') }}">Bookings</a></li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">

                <x-dashboard.partials.message-alert/>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="bg-inner cart-section order-details-table">
                                <div class="row g-4">
                                    <div class="col-xl-12">

                                        <div class="row g-4">
                                            <div class="card-details-title">
                                                <h3>Booking Number <span>#{{ $booking->id }}</span></h3>
                                            </div>
                                            <!-- Button trigger modal -->

                                            @can('bookings.edit')
                                                <button type="button" class="btn btn-primary update-booking"
                                                        data-toggle="modal"
                                                        data-target="#exampleModal">
                                                    Update Booking
                                                </button>
                                            @endcan

                                            <div class="col-4">
                                                <div class="order-success">
                                                    <h4>Summery</h4>
                                                    <ul class="order-details">
                                                        <li>Booking ID: {{ $booking->id }}</li>
                                                        <li>Pick Up Location: {{ $booking->pickup_location }}</li>
                                                        <li>Client: {{$booking?->client?->name ?? $booking->name}}</li>
                                                        <li>Country: {{$booking->country}}</li>
                                                        <li>State: {{$booking->state}}</li>
                                                        <li>Street Address: {{$booking->street_address}}</li>
                                                        <li>Booking
                                                            Status: {{ Str::of($booking->status)->headline() }}</li>
                                                        @foreach($booking->tours as $tour)
                                                            <li>Traveling Date: {{ $tour->pivot->start_date->format('F,d, Y') }}</li>
                                                        @endforeach
                                                        <li>Booking
                                                            Total: {{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) * $booking->total_price, 2) }}</li>
                                                        <li>Issued Date: {{ $booking->created_at->format('F,d, Y') }} </li>
                                                        <li>Client Notes: {{ $booking->notes }}</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="order-success">
                                                    <h4>Client Basic Info</h4>
                                                    <ul class="order-details">
                                                        <li>Is Registered: {{ $booking->client ? 'Yes' :'No'}}</li>
                                                        <li>Name: {{ $booking?->client?->name ?? $booking->name}}</li>
                                                        <li>
                                                            Phone: {{ $booking?->client?->phone ?? $booking->phone}}</li>
                                                        <li>
                                                            E-Mail: {{ $booking?->client?->email ?? $booking->email}}</li>
                                                        <li>
                                                            Nationality: {{ $booking?->client?->nationality ?? $booking->country}}</li>
                                                        <li>
                                                            Birthdate: {{ optional($booking?->client?->birthdate)->toDateString() ?? 'N/A' }}</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="order-success">
                                                    <div class="payment-mode">
                                                        <h4>Payment</h4>
                                                        <p>Gateway: {{ Str::headline($booking->payment_method) }}</p>
                                                        <p>Statue: {{ Str::headline($booking->payment_status) }}</p>
                                                        <p>Currency: {{ $booking->currency?->name ?? 'N/A' }}</p>

                                                        @isset($booking->payment->transaction_verification['payment_method'])
                                                            <p>
                                                                Method: {{ $booking->payment->transaction_verification['payment_method'] }}</p>
                                                        @endisset
                                                        @isset($booking->payment->transaction_verification['invoice_key'])
                                                            <p>
                                                                Reference: {{ $booking->payment->transaction_verification['invoice_key'] }}</p>
                                                        @endisset
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xl-12">


                                        <x-dashboard.booking.tours-list :booking="$booking"/>

                                        @if($booking->rentals->isNotEmpty())
                                            <div class="table-responsive table-details">
                                                <h3>Car Rentals</h3>
                                                <table class="table cart-table table-bbookingless">
                                                    <thead>
                                                    <tr>
                                                        <th style="padding-left: 15px;">Pickup / Destination</th>
                                                        <th>Pick Up Date</th>
                                                        <th>Return Date</th>
                                                        <th>Stops</th>
                                                        <th>Rental Type</th>
                                                        <th>Car Type</th>
                                                        <th>Members</th>
                                                        <th>Total Price</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($booking->rentals as $carRental)
                                                        <tr>
                                                            <td valign="top" style="padding-left: 15px; max-width: 100px">
                                                                <h5 style="margin-top: 15px;">{{ $carRental->pickup?->name }} / {{ $carRental->destination?->name }}</h5>
                                                            </td>
                                                            <td>{{ optional($carRental->pickup_date)->format('Y-m-d') . ' ' . optional($carRental->pickup_time)->format('h:i A') }}</td>
                                                            <td>{{ $carRental->oneway ? '-' : optional($carRental->return_date)->format('Y-m-d') . ' ' . optional($carRental->return_time)->format('h:i A') }}</td>
                                                            <td valign="top" style="padding-left: 15px;">
                                                                {{ $carRental->stops->pluck('location.name')->implode(', ') ?? '-' }}
                                                            </td>
                                                            <td valign="top" style="padding-left: 15px;">
                                                                {{ $carRental->rental_type }}
                                                            </td>
                                                            <td valign="top" style="padding-left: 15px;">
                                                                {{ $carRental->car_type }}
                                                            </td>
                                                            <td valign="top" style="padding-left: 15px;">
                                                                Adults x ({{ $carRental->adults }}), Children x
                                                                ({{ $carRental->children }})
                                                            </td>
                                                            <td valign="top" style="padding-left: 15px;">
                                                                {{ $carRental->currency?->symbol }}{{ number_format($carRental->currency_exchange_rate * ($carRental->car_route_price + $carRental->stops->sum('price')) ,2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif

                                        <table class="table cart-table table-bbookingless">
                                            <tfoot>
                                            <tr class="table-booking">
                                                <td style="border-bottom: 1px solid #00000038" colspan="4">
                                                    <h5>Subtotal :</h5>
                                                </td>
                                                <td style="border-bottom: 1px solid #00000038">
                                                    <h4>{{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) * $booking->sub_total_price, 2) }}</h4>
                                                </td>
                                            </tr>

                                            @if($booking->sub_total_price != $booking->total_price && $booking->coupon)
                                                <tr class="table-booking">
                                                    <td style="border-bottom: 1px solid #00000038" colspan="4">
                                                        <h5>Discount (Coupon: {{$booking->coupon->code}}):</h5>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #00000038">
                                                        <h4> {{
                                                                    $booking->coupon->discount_type == \App\Enums\CouponType::PERCENTAGE->value ?
                                                                    $booking->coupon->value.'%' :
                                                                    ($booking->currency?->symbol ?? '$') . number_format(($booking->currency_exchange_rate ?? 1) * $booking->coupon->value,2)
                                                              }}
                                                        </h4>
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr class="table-booking">
                                                <td colspan="4">
                                                    <h4 class="theme-color fw-bold">Total Price :</h4>
                                                </td>
                                                <td>
                                                    <h4 class="theme-color fw-bold">
                                                        @if($booking->sub_total_price != $booking->total_price )
                                                            <del>{{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) * $booking->sub_total_price, 2) }}</del>
                                                        @endif
                                                        <b>{{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) * $booking->total_price, 2) }}</b>
                                                    </h4>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>


                                </div>
                            </div>
                            <!-- section end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->


        @can('bookings.edit')
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Booking Status</h5>
                        </div>
                        <div class="modal-body">

                            <form method="post" action="{{ route('dashboard.bookings.update', $booking) }}"
                                  id="change-booking-status" class="row">
                                @csrf
                                @method('PUT')

                                <x-dashboard.form.input-select :options="\App\Enums\BookingStatus::all()"
                                                               value="{{ $booking->status }}"
                                                               error-key="status"
                                                               name="status"
                                                               id="status"
                                                               label-title="Status"/>

                                <x-dashboard.form.input-select :options="\App\Enums\PaymentStatus::all()"
                                                               value="{{ $booking->payment_status }}"
                                                               error-key="payment_status"
                                                               name="payment_status"
                                                               id="payment_status"
                                                               label-title="Payment Status"/>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" form="change-booking-status" class="btn btn-primary">Save changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

    </div>
@endsection

@push('css')
    <style>
        .order-success {
            height: 100% !important;
        }
        @media print {
            /* styling goes here */
            footer, .page-header, .page-main-header, .update-booking, .page-sidebar {
                display: none !important;
            }

            .page-body {
                margin-top: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
@endpush
