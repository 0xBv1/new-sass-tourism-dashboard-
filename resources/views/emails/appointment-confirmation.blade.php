<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Received</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th, .details td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .details th {
            width: 40%;
            color: #666;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .reference {
            background-color: #11998e;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        
        <div class="content">
            <h2>Thank You, {{ $appointment->name }}!</h2>
            
            <p>We have received your 
                @if($appointment->type === 'nile_cruise')
                    Nile Cruise inquiry
                @elseif($appointment->type === 'plan_trip')
                    trip planning request
                @else
                    appointment request
                @endif
                and will get back to you shortly.</p>
            
            <div class="reference">
                Reference: {{ strtoupper(substr($appointment->type, 0, 3)) }}-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
            </div>
            
            <div class="details">
                <h3>Your Request Details</h3>
                <table>
                    <tr>
                        <th>Name</th>
                        <td>{{ $appointment->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $appointment->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $appointment->formatted_phone }}</td>
                    </tr>
                    <tr>
                        <th>Arrival Date</th>
                        <td>{{ $appointment->arrival_date->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Departure Date</th>
                        <td>{{ $appointment->departure_date->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Guests</th>
                        <td>
                            {{ $appointment->adults }} Adults
                            @if($appointment->children > 0), {{ $appointment->children }} Children @endif
                            @if($appointment->infants > 0), {{ $appointment->infants }} Infants @endif
                        </td>
                    </tr>
                    @if($appointment->cruise_type)
                    <tr>
                        <th>Cruise Type</th>
                        <td>{{ $appointment->cruise_type }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <p>Our team will contact you within 24-48 hours to discuss your request in detail.</p>
            
            <p>If you have any urgent questions, please don't hesitate to contact us.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

