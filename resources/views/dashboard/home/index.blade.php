@extends('layouts.dashboard.app')

@section('content')

    <style>
        .table-row-hover:hover {
            background-color: #f8f9fa !important;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        .widget-cards a {
            color: inherit;
        }
        .widget-cards a:hover {
            text-decoration: none;
        }
    </style>

    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Dashboard">
            <li class="breadcrumb-item active">Dashboard</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Cards -->
        <div class="row">
            <x-dashboard.partials.box-card permission="users.list" title="Users" :count="\App\Models\User::count()" icon="users"
                                           color="danger" :link="route('dashboard.users.index')"/>

            <x-dashboard.partials.box-card permission="tours.list" title="Tours" :count="\App\Models\Tour::count()" icon="grid"
                                           color="secondary" :link="route('dashboard.tours.index')"/>

            <x-dashboard.partials.box-card permission="destinations.list" title="Destinations" :count="\App\Models\Destination::count()" icon="globe"
                                           color="warning" :link="route('dashboard.destinations.index')"/>

            <x-dashboard.partials.box-card permission="categories.list" title="Categories" :count="\App\Models\Category::count()" icon="book-open"
                                           color="primary" :link="route('dashboard.categories.index')"/>

            <x-dashboard.partials.box-card permission="tour-options.list" title="Tour Options" :count="\App\Models\TourOption::count()"
                                           icon="check-square" color="primary" :link="route('dashboard.tour-options.index')"/>

            <x-dashboard.partials.box-card permission="bookings.list" title="New Bookings"
                                           :count="\App\Models\Booking::whereDate('created_at', today())->count()"
                                           icon="edit-3" color="warning" :link="route('dashboard.bookings.index')"/>

            <x-dashboard.partials.box-card permission="car-rentals.list" title="New Car Rentals"
                                           :count="\App\Models\CarRental::whereDate('created_at', today())->count()"
                                           icon="activity" color="secondary" :link="route('dashboard.car-rentals.index')"/>

            <x-dashboard.partials.box-card permission="custom-trips.list" title="New Custom Trips"
                                           :count="\App\Models\CustomTrip::whereDate('created_at', today())->count()"
                                           icon="command" color="danger" :link="route('dashboard.custom-trips.index')"/>
        </div>

        <!-- Tables -->
        <div class="row">
            @canany(['bookings.index' , 'bookings.show'])
                <div class="col-xl-6 xl-100">
                    <div class="card">
                        <div class="card-header">
                            <h5>Latest Bookings</h5>
                        </div>
                        <div class="card-body">
                            <div class="user-status table-responsive latest-order-table">
                                <table class="table table-bordernone">
                                    <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Created At</th>
                                        @can('bookings.show')
                                            <th scope="col"></th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse(\App\Models\Booking::with('currency')->latest('id')->limit(5)->get() as $booking)
                                        <tr @can('bookings.show') onclick="window.location='{{ route('dashboard.bookings.show', $booking) }}'" style="cursor: pointer;" class="table-row-hover" @endcan>
                                            <td>{{  $booking->first_name . ' ' . $booking->last_name }}</td>
                                            <td class="digits">{{ ($booking->currency?->symbol ?? '$') . number_format($booking->total_price * ($booking->currency_exchange_rate ?? 1), 2) }}</td>
                                            <td class="digits">{{ $booking->created_at->format('M Y, d') }}</td>
                                            @can('bookings.show')
                                                <td class="digits"><a
                                                        href="{{ route('dashboard.bookings.show', $booking) }}" onclick="event.stopPropagation();">View</a>
                                                </td>
                                            @endcan
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">No Bookings Found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>

                                <a href="{{ route('dashboard.bookings.index') }}" class="btn btn-primary mt-4">View All
                                    Bookings</a>
                            </div>

                        </div>
                    </div>
                </div>
            @endcanany



            @canany(['car-rentals.index' , 'car-rentals.show'])
                <div class="col-xl-6 xl-100">
                    <div class="card">
                        <div class="card-header">
                            <h5>Latest Rentals</h5>
                        </div>
                        <div class="card-body">
                            <div class="user-status table-responsive latest-order-table">
                                <table class="table table-bordernone">
                                    <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Created At</th>
                                        @can('car-rentals.show')
                                            <th scope="col"></th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse(\App\Models\CarRental::latest('id')->limit(5)->get() as $carRental)
                                        <tr @can('car-rentals.show') onclick="window.location='{{ route('dashboard.car-rentals.show', $carRental) }}'" style="cursor: pointer;" class="table-row-hover" @endcan>
                                            <td>{{  $carRental->name }}</td>
                                            <td class="digits">
                                                {{ $carRental->currency?->symbol }}{{ number_format($carRental->currency_exchange_rate * ($carRental->car_route_price + $carRental->stops->sum('price')) ,2) }}
                                            </td>
                                            <td class="digits">{{ $carRental->created_at->format('M Y, d') }}</td>
                                            @can('car-rentals.show')
                                                <td class="digits">
                                                    <a href="{{ route('dashboard.car-rentals.show', $carRental) }}" onclick="event.stopPropagation();">View</a>
                                                </td>
                                            @endcan
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">No Rentals Found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>

                                <a href="{{ route('dashboard.car-rentals.index') }}" class="btn btn-primary mt-4">View All Rentals</a>
                            </div>

                        </div>
                    </div>
                </div>
            @endcanany


        </div>
    </div>
@endsection
