@extends('emails.layouts.booking')
@section('title', 'Booking Receipt')
@section('content')
    <table align="center" border="0" cellpadding="0" cellspacing="0"
           style="padding: 0 30px;background-color: #fff; -webkit-box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);width: 100%;">
        <tbody>
        <tr>
            <td>
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="text-align: left;"
                       width="100%">
                    <tr>
                        <td style="text-align: center;">
                            <img src="{{ logo() }}" alt="" style="margin-bottom: 30px;max-width: 200px">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 14px;"><b>Hi {{ $booking->client?->name ?? $booking->name }},</b></p>
                            <p style="font-size: 14px;">Booking Is Successfully Created, Be Ready for our contact.</p>
                            <p style="font-size: 14px;">Booking Number : {{ $booking->id }}.</p>
                        </td>
                    </tr>
                </table>

                @if($booking->tours->isNotEmpty())
                    <table class="order-detail" border="0" cellpadding="0" cellspacing="0" align="left" style="width: 100%;    margin-bottom: 50px;">
                        <tr align="left">
                            <th></th>
                            <th style="padding-left: 15px;">Tour</th>
                            <th>Start Date</th>
                            <th>Options</th>
                            <th>Members </th>
                            <th>Total </th>
                        </tr>

                        @foreach($booking->tours as $tour)
                            <tr>
                                <td>
                                    <a target="_blank" href="{{ $tour->link }}"><img style="margin-left: 6px" src="{{ $tour->featured_image }}" alt="" width="80"></a>
                                </td>
                                <td valign="top" style="padding-left: 15px; max-width: 100px">
                                    <h5 style="margin-top: 15px;"><a target="_blank" style="color: #000" href="{{ $tour->link }}">{{$tour->title}}</a></h5>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($tour->pivot->start_date)->format('Y-m-d') }}</td>
                                <td valign="top" style="padding-left: 15px;">
                                    @forelse($tour->pivot->options() as $option)
                                        <h5 style="font-size: 14px; color:#444;margin-top:15px;margin-bottom: 0px;">
                                            <span>{{ $option->name }} x {{ ($booking->currency?->symbol ?? '$') }}{{ number_format($option->price * ($booking->currency_exchange_rate ?? 1) , 2)  }}</span>
                                        </h5>
                                    @empty
                                        <span style="margin-top: 13px;display: block;font-size: 12px;">No Selected Options</span>
                                    @endforelse
                                </td>
                                <td valign="top" style="padding-left: 15px;">
                                    <h5 style="font-size: 14px; color:#444;margin-top:15px">
                                        <p style="font-weight: bold"> Adults ({{  $tour->pivot->adults }}) x {{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) *$tour->pivot->adult_price,2) }}</p>
                                        <p style="font-weight: bold"> Children ({{  $tour->pivot->children }}) x {{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) *$tour->pivot->child_price,2) }}</p>
                                    </h5>
                                </td>
                                <td valign="top" style="padding-left: 15px;">
                                    <h5 style="font-size: 14px; color:#444;margin-top:15px">
                                        <b>
                                            {{ ($booking->currency?->symbol ?? '$') }}{{ number_format( ($tour->pivot->adults * ($booking->currency_exchange_rate ?? 1) * $tour->pivot->adult_price) +
                                                  ($tour->pivot->children * ($booking->currency_exchange_rate ?? 1) *$tour->pivot->child_price) +
                                                  (collect($tour->pivot->options)->sum('price') * ($booking->currency_exchange_rate ?? 1) )
                                                ,2)}}
                                        </b>
                                    </h5>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif

                @if($booking->rentals->isNotEmpty())
                    <table class="order-detail" border="0" cellpadding="0" cellspacing="0" align="left" style="width: 100%;    margin-bottom: 50px;">
                        <tr align="left">
                            <th style="padding-left: 15px;">Pickup / Destination</th>
                            <th>Pickup Date</th>
                            <th>Return Date</th>
                            <th>Stops</th>
                            <th>Rental Type</th>
                            <th>Car Type</th>
                            <th>Members</th>
                            <th>Total Price</th>
                        </tr>
                        @foreach($booking->rentals as $carRental)
                            <tr>
                                <td valign="top" style="padding-left: 15px; max-width: 100px">
                                    <h5 style="margin-top: 15px;">{{ $carRental->pickup?->name }} / {{ $carRental->destination?->name }}</h5>
                                </td>
                                <td>{{ $carRental->pickup_date->format('Y-m-d') . ' ' . $carRental->pickup_time->format('h:i A') }}</td>
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
                    </table>
                @endif

                <table>
                    <tr class="pad-left-right-space">
                        <td colspan="2" align="left">
                            <p style="font-size: 14px;">Subtotal :</p>
                        </td>
                        <td colspan="2" align="right">
                            <b> {{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) * $booking->sub_total_price, 2) }}</b>
                        </td>
                    </tr>
                    @if($booking->sub_total_price != $booking->total_price && $booking->coupon)
                        <tr class="pad-left-right-space">
                            <td colspan="2" align="left">
                                <p style="font-size: 14px;">Discount (Coupon: {{$booking->coupon->code}}):</p>
                            </td>
                            <td colspan="2" align="right">
                                <b> {{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) * ($booking->sub_total_price - $booking->total_price), 2) }}</b>
                            </td>
                        </tr>
                    @endif
                    <tr class="pad-left-right-space ">
                        <td class="m-b-5" colspan="2" align="left">
                            <p style="font-size: 14px;">Total :</p>
                        </td>
                        <td class="m-b-5" colspan="2" align="right">
                            <b> {{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) * $booking->total_price, 2) }}</b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

    <table align="center" border="0" cellpadding="0" cellspacing="0"
           style="padding: 0 30px;background-color: #fff; -webkit-box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);width: 100%;">
        <tbody>
        <tr>
            <td>
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="text-align: left;" width="100%">
                    <tr>
                        <td>
                            <p style="font-size: 14px;color: red">
                                <b>
                                    Note: Please find attached PDF files below to review tours programs
                                </b>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

    <table class="main-bg-light text-center top-0" align="center" border="0" cellpadding="0" cellspacing="0"
           width="100%">
        <tr>
            <td style="padding: 30px;">
                <div>
                    <h4 class="title" style="margin:0;text-align: center;">Follow us</h4>
                </div>
                <table border="0" cellpadding="0" cellspacing="0" class="footer-social-icon" align="center"
                       class="text-center" style="margin-top:20px;">
                    <tr>
                        @foreach($social_links as $social_link)
                            <td>
                                <a href="{{ $social_link['url'] }}">
                                    <img style="padding: 5px; border: 1px solid #000;border-radius: 50%;width: 20px;height: 20px"
                                         src="{{ asset('assets/site/images/icons/'.$social_link['type'].'.png') }}"
                                         alt="" />
                                </a>
                            </td>
                        @endforeach
                    </tr>
                </table>
                <div style="border-top: 1px solid #ddd; margin: 20px auto 0;"></div>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 20px auto 0;">
                    <tr>
                        <td>
                            <a href="{{ site_url('/tours') }}" style="font-size:13px">Want to book more tours?</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size:13px; margin:0;">2023 Copy Right by <a href="{{ site_url() }}">{{ config('app.name') }}</a></p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
@endsection
