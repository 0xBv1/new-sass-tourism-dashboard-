@component('mail::message')
# Hello {{ $operator?->name ?? 'Admin'   }}

<x-mail::panel>
    You have a new custom trip request.
</x-mail::panel>

<table border="0" cellpadding="0" cellspacing="0" align="left" style="width: 100%;margin-bottom: 50px;">
    <tbody>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Type</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->type_name }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Name</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->name }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Email</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->email }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Nationality</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->nationality }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Phone</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->phone }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Destination</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->destination_name }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px"># Days</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->days }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Adults</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->adults }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Children</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->children }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Infants</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->infants }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Budget</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->budget }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Flight Offer</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{  $trip->flight_offer? 'Yes' : 'No' }}</td>
        </tr>
        <tr align="left" >
            <td style="border-bottom: 1px solid #0003; padding: 8px">Additional Notes</td>
            <td style="border-bottom: 1px solid #0003; padding: 8px;">{{ $trip->additional_notes }}</td>
        </tr>
    </tbody>
</table>

@if(is_null($operator))
@component('mail::button', ['url' => route('dashboard.custom-trips.show', $trip)])
    Assign Operator
@endcomponent
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
