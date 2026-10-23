<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Appointment Request</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        .alert {
            background-color: #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
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
            width: 35%;
            color: #666;
        }
        .notes {
            background-color: #e8f4f8;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New 
                @if($appointment->type === 'nile_cruise')
                    Nile Cruise Inquiry
                @elseif($appointment->type === 'plan_trip')
                    Trip Planning Request
                @else
                    Appointment Request
                @endif
            </h1>
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>⚡ New Lead!</strong> A new request has been submitted and requires attention.
            </div>
            
            <div class="details">
                <h3>Customer Information</h3>
                <table>
                    <tr>
                        <th>Name</th>
                        <td><strong>{{ $appointment->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a href="mailto:{{ $appointment->email }}">{{ $appointment->email }}</a></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><a href="tel:{{ $appointment->phone }}">{{ $appointment->formatted_phone }}</a></td>
                    </tr>
                    @if($appointment->nationality)
                    <tr>
                        <th>Nationality</th>
                        <td>{{ $appointment->nationality }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <div class="details">
                <h3>Trip Details</h3>
                <table>
                    <tr>
                        <th>Type</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</td>
                    </tr>
                    <tr>
                        <th>Arrival</th>
                        <td>{{ $appointment->arrival_date->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Departure</th>
                        <td>{{ $appointment->departure_date->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Duration</th>
                        <td>{{ $appointment->duration_in_days }} days</td>
                    </tr>
                    <tr>
                        <th>Guests</th>
                        <td>
                            {{ $appointment->adults }} Adults,
                            {{ $appointment->children }} Children,
                            {{ $appointment->infants }} Infants
                        </td>
                    </tr>
                    @if($appointment->hotel_choice)
                    <tr>
                        <th>Hotel Choice</th>
                        <td>{{ $appointment->hotel_choice }}</td>
                    </tr>
                    @endif
                    @if($appointment->cruise_type)
                    <tr>
                        <th>Cruise Type</th>
                        <td>{{ $appointment->cruise_type }}</td>
                    </tr>
                    @endif
                    @if($appointment->cruise_duration)
                    <tr>
                        <th>Cruise Duration</th>
                        <td>{{ $appointment->cruise_duration }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            @if($appointment->notes)
            <div class="notes">
                <h3>Customer Notes</h3>
                <p>{!! nl2br(e($appointment->notes)) !!}</p>
            </div>
            @endif
            
            <p style="text-align: center;">
                <a href="{{ route('dashboard.appointments.show', $appointment) }}" class="btn">
                    View in Dashboard
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}</p>
            <p>Submitted on: {{ $appointment->created_at->format('F j, Y g:i A') }}</p>
        </div>
    </div>
</body>
</html>

