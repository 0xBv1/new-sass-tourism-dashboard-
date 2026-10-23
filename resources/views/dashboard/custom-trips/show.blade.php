@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Custom Trips">
            <li class="breadcrumb-item active">Custom Trips</li>
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
                                            <h3>Custom Trip Number <span>#{{ $customTrip->id }}</span></h3>
                                        </div>

                                        <div class="table-responsive table-details">
                                            <table class="table cart-table table-borderless">
                                                <tbody>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">
                                                        Type
                                                    </td>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">
                                                        {{ $customTrip->type_name }}
                                                    </td>
                                                </tr>

                                                @if($customTrip->type == \App\Enums\CustomTripType::EXACT_TIME->value)
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            Start Date
                                                        </td>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            {{ $customTrip->start_date->format('F d, Y') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            End Date
                                                        </td>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            {{ $customTrip->end_date->format('F d, Y') }}
                                                        </td>
                                                    </tr>
                                                @endif

                                                @if($customTrip->type == \App\Enums\CustomTripType::APPROX_TIME->value)
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            Month
                                                        </td>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            {{ $customTrip->month_name }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            # Days
                                                        </td>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            {{ $customTrip->days }}
                                                        </td>
                                                    </tr>
                                                @endif

                                                @if($customTrip->type == \App\Enums\CustomTripType::NOT_SURE->value)
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            # Days
                                                        </td>
                                                        <td style="border-bottom: 1px solid #00000045 !important;">
                                                            {{ $customTrip->days }}
                                                        </td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">
                                                        Destination
                                                    </td>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">
                                                        {{ $customTrip->destination_name }}
                                                    </td>
                                                </tr>

{{--                                                <tr>--}}
{{--                                                    <td style="border-bottom: 1px solid #00000045 !important;">--}}
{{--                                                        Categories--}}
{{--                                                    </td>--}}
{{--                                                    <td style="border-bottom: 1px solid #00000045 !important;">--}}
{{--                                                        {{ $customTrip->joined_categories }}--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}

                                                <tr>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">Adults</td>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">{{ $customTrip->adults }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">Children</td>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">{{ $customTrip->children }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">Infants</td>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">{{ $customTrip->infants }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">Budget</td>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">{{ $customTrip->budget }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">Flight Offer</td>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">{{  $customTrip->flight_offer? 'Yes' : 'No' }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">Additional Notes</td>
                                                    <td style="border-bottom: 1px solid #00000045 !important;">{{ $customTrip->additional_notes }}</td>
                                                </tr>


                                                </tbody>

                                            </table>
                                        </div>

                                    </div>

                                    <div class="col-xl-4">
                                        <div class="row g-4">
                                            <!-- Button trigger modal -->
                                            @if(!$customTrip->operator || admin()->can('custom-trips.un-assign'))
                                                @canany(['custom-trips.un-assign', 'custom-trips.assign'])

                                                    <form method="post" action="{{ route('dashboard.custom-trips.assign', $customTrip) }}"
                                                          id="change-order-status" class="mt-5">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <select aria-label="Assigned Operator ID"
                                                                    name="assigned_operator_id"
                                                                    id="assigned_operator_id"
                                                                    class="w-100 form-control select2 custom-select">
                                                                @if($users->isNotEmpty())
                                                                    <option value="" selected disabled>Select Operator</option>
                                                                @endif
                                                                @forelse($users as $user)
                                                                    <option @selected($user->id == $customTrip->operator?->id) value="{{$user->id}}">{{ $user->name . ' - '. $user->email }}</option>
                                                                @empty
                                                                    <option value="" disabled>No Available Operators to Assign</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn w-100 btn-primary" data-toggle="modal" data-target="#exampleModal">
                                                            <i class="fa fa-plus"></i> Assign To Operator
                                                        </button>
                                                    </form>
                                                @endcanany
                                            @endif




                                            <div class="col-12">
                                                <div class="order-success">
                                                    <h4>Summery</h4>
                                                    <ul class="order-details">
                                                        <li>Custom Trip ID: {{ $customTrip->id }}</li>
                                                        <li>Custom Trip Status: {{ $customTrip->operator ? 'Assigned' : 'Not Assigned' }}</li>
                                                        <li>Created At Date: {{ $customTrip->created_at->format('F d, Y') }} </li>
                                                        <li>Created At Time: {{ $customTrip->created_at->format('h:i:a') }} </li>
                                                        @if($customTrip->operator)
                                                            <li>Assigned To: {{ $customTrip->operator?->name }} </li>
                                                            <li>Assigned At: {{ $customTrip->assigned_at->format('F d, Y') }} </li>
                                                            <li>Assigned By: {{ $customTrip->assigned_by?->name }} </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="order-success">
                                                    <h4>Guest Info</h4>
                                                    <ul class="order-details">
                                                        <li>Name: {{ $customTrip->name  }}</li>
                                                        <li>Phone: {{ $customTrip->phone}}</li>
                                                        <li>E-Mail: {{ $customTrip->email}}</li>
                                                        <li>Nationality: {{ $customTrip->nationality }}</li>
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



    </div>
@endsection
