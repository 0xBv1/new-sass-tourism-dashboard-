@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Car Rentals">
            <li class="breadcrumb-item active">Car Rentals</li>
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

                                    <div class="col-xl-8">
                                        <div class="card-details-title">
                                            <h3>Car Rental Number <span>#{{ $carRental->id }}</span></h3>
                                        </div>

                                        <x-dashboard.car-rental.details :car-rental="$carRental" />

                                    </div>

                                    <div class="col-xl-4">
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <div class="order-success">
                                                    <h4>Summery</h4>
                                                    <ul class="order-details">
                                                        <li>Car Rental ID: {{ $carRental->id }}</li>
                                                        <li>Created At Date: {{ optional($carRental->created_at)->format('F d, Y') }} </li>
                                                        <li>Created At Time: {{ optional($carRental->created_at)->format('h:i:a') }} </li>
                                                        <li>Pickup Date: {{ optional($carRental->pickup_date)->format('F d, Y') }} </li>
                                                        <li>Pickup Time: {{ optional($carRental->pickup_time)->format('h:i:a') }} </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="order-success">
                                                    <h4>Guest Info</h4>
                                                    <ul class="order-details">
                                                        <li>Name: {{ $carRental->name  }}</li>
                                                        <li>Phone: {{ $carRental->phone}}</li>
                                                        <li>E-Mail: {{ $carRental->email}}</li>
                                                        <li>Nationality: {{ $carRental->nationality }}</li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
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


        <!-- Modal -->

    </div>
@endsection
