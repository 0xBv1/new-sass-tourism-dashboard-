@if($booking->tours->isNotEmpty())
    <div class="table-responsive table-details">
        <h3>Tours</h3>
        <table class="table cart-table table-bbookingless">
            <thead>
            <tr>
                <th></th>
                <th style="padding-left: 15px;">Tour</th>
                <th>Start Date</th>
                <th>Options</th>
                <th>Members</th>
                <th>Total</th>
            </tr>
            </thead>

            <tbody>

            @foreach($booking->tours as $tour)
                <tr>
                    <td>
                        <a target="_blank" href="{{ $tour->link }}"><img
                                style="margin-left: 6px"
                                src="{{ $tour->featured_image }}"
                                alt="" width="80"></a>
                    </td>
                    <td valign="top" style="padding-left: 15px; max-width: 100px">
                        <h5 style="margin-top: 15px;"><a target="_blank"
                                                         style="color: #000"
                                                         href="{{ $tour->link }}">{{$tour->title}}</a>
                        </h5>
                    </td>
                    {{-- <td>{{ \Carbon\Carbon::parse($tour->pivot->start_date)->format('Y-m-d') }}</td> --}}
                    <td>{{ $tour->pivot->start_date->format('F,d, Y') }}</td>
                    <td valign="top" style="padding-left: 15px;">
                        @php
                            $options = 0;
                        @endphp
                        {{-- @forelse($tour->pivot->options() as $option)
                            <h5 style="font-size: 14px; color:#444;margin-top:15px;margin-bottom: 0px;">
                                <span>{{ $option->name }}(Adults) x {{ $booking->currency->symbol }}{{ number_format($tour->pivot->adults * $option->adult_price * $booking->currency_exchange_rate , 2)  }}</span>
                                <span>{{ $option->name }}(Children) x {{ $booking->currency->symbol }}{{ number_format($tour->pivot->children * $option->child_price * $booking->currency_exchange_rate , 2)  }}</span>
                                @php
                                    $options += ($tour->pivot->children * $option->child_price) + ($tour->pivot->adults * $option->adult_price)
                                @endphp
                            </h5>
                        @empty
                            <span>No Selected Options</span>
                        @endforelse --}}



                        @forelse($tour->pivot->options() as $option)
                        @php
                            $adultPrice = $option->calcAdultPrice($tour->pivot->adults);
                            $childPrice = $option->calcChildPrice($tour->pivot->adults);
                        @endphp
                        <h5 style="font-size: 14px; color:#444;margin-top:15px;margin-bottom: 0px;">
                            <span>{{ $option->name }}(Adults) x {{ ($booking->currency?->symbol ?? '$') }}{{ number_format($tour->pivot->adults * $adultPrice * ($booking->currency_exchange_rate ?? 1), 2) }}<span>
                            <br>
                            <span>{{ $option->name }}(Children) x {{ ($booking->currency?->symbol ?? '$') }}{{ number_format($tour->pivot->children * $childPrice * ($booking->currency_exchange_rate ?? 1),2) }}</span>
                        </h5>
                        @php
                            $options += ($tour->pivot->children * $childPrice) + ($tour->pivot->adults * $adultPrice);
                        @endphp
                        @empty
                            <span>No Selected Options</span>
                        @endforelse





                    </td>
                    <td valign="top" style="padding-left: 15px;">
                        <h5 style="font-size: 14px; color:#444;margin-top:15px">
                            <p style="font-weight: bold"> Adults
                                ({{  $tour->pivot->adults }})
                                x {{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) *$tour->pivot->adult_price,2) }}</p>
                            <p style="font-weight: bold"> Children
                                ({{  $tour->pivot->children }})
                                x {{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) *$tour->pivot->child_price,2) }}</p>
                            <p style="font-weight: bold"> Infants
                                ({{  $tour->pivot->infants }})
                                x {{ ($booking->currency?->symbol ?? '$') }}{{ number_format(($booking->currency_exchange_rate ?? 1) *$tour->pivot->infant_price,2) }}</p>

                        </h5>
                    </td>
                    <td valign="top" style="padding-left: 15px;">
                        <h5 style="font-size: 14px; color:#444;margin-top:15px">
                            <b>
                                {{ ($booking->currency?->symbol ?? '$') }}{{ number_format( ($tour->pivot->adults * ($booking->currency_exchange_rate ?? 1) * $tour->pivot->adult_price) +
                                                  ($tour->pivot->children * ($booking->currency_exchange_rate ?? 1) *$tour->pivot->child_price) +
                                                  ($tour->pivot->infants * ($booking->currency_exchange_rate ?? 1) *$tour->pivot->infant_price) +
                                                  ($options * ($booking->currency_exchange_rate ?? 1) )
                                                ,2)}}
                            </b>
                        </h5>
                    </td>
                </tr>
            @endforeach


            </tbody>


        </table>
    </div>

@endif
