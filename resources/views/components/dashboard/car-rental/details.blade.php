<div class="table-responsive table-details">
    <table class="table cart-table table-borderless">
        <tbody>

        <tr>
            <td style="border-bottom: 1px solid #00000045 !important;">
                Pickup
            </td>
            <td style="border-bottom: 1px solid #00000045 !important;">
                {{ $carRental->pickup?->name }}
            </td>
        </tr>


        <tr>
            <td style="border-bottom: 1px solid #00000045 !important;">
                Destination
            </td>
            <td style="border-bottom: 1px solid #00000045 !important;">
                {{ $carRental->destination?->name }}
            </td>
        </tr>

        @if ($carRental->stops->isNotEmpty())
            <tr>
                <td style="border-bottom: 1px solid #00000045 !important;">
                    Stops
                </td>
                <td style="border-bottom: 1px solid #00000045 !important;">
                    <ul>
                        @forelse($carRental->stops->pluck('location.name')->toArray() as $name)
                            <li class="d-block">{{ $name }}</li>
                        @empty
                            No selected stops
                        @endforelse
                    </ul>
                </td>
            </tr>
        @endif
        <tr>
            <td style="border-bottom: 1px solid #00000045 !important;">
                Type
            </td>
            <td style="border-bottom: 1px solid #00000045 !important;">
                {{ $carRental->rental_type }}
            </td>
        </tr>

        <tr>
            <td style="border-bottom: 1px solid #00000045 !important;">
                Car Type
            </td>
            <td style="border-bottom: 1px solid #00000045 !important;">
                {{ $carRental->car_type }}
            </td>
        </tr>

        <tr>
            <td style="border-bottom: 1px solid #00000045 !important;">
                Members
            </td>
            <td style="border-bottom: 1px solid #00000045 !important;">
                Adults x ({{ $carRental->adults }}), Children x ({{ $carRental->children }})
            </td>
        </tr>

        <tr>
            <td style="border-bottom: 1px solid #00000045 !important;">
                Total Price ({{ $carRental->currency?->name }})
            </td>
            <td style="border-bottom: 1px solid #00000045 !important;">
                {{ $carRental->currency?->symbol }}{{ number_format($carRental->currency_exchange_rate * ($carRental->car_route_price + $carRental->stops->sum('price')) ,2) }}
            </td>
        </tr>


        </tbody>

    </table>
</div>
