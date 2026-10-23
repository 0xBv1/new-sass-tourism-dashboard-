@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <x-dashboard.partials.breadcrumb title="Appointment Details">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.appointments.index') }}">Appointments</a>
            </li>
            <li class="breadcrumb-item active">Details</li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Appointment Information</h5>
                            <span class="badge bg-{{ $appointment->status === 'pending' ? 'warning' : ($appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'contacted' ? 'info' : 'danger')) }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Name</th>
                                    <td>{{ $appointment->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><a href="mailto:{{ $appointment->email }}">{{ $appointment->email }}</a></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><a href="tel:{{ $appointment->formatted_phone }}">{{ $appointment->formatted_phone }}</a></td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</td>
                                </tr>
                                <tr>
                                    <th>Guests</th>
                                    <td>
                                        {{ $appointment->adults }} Adults,
                                        {{ $appointment->children }} Children,
                                        {{ $appointment->infants }} Infants
                                        <strong>({{ $appointment->total_guests }} Total)</strong>
                                    </td>
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
                                    <th>Duration</th>
                                    <td>{{ $appointment->duration_in_days }} days</td>
                                </tr>
                                @if($appointment->nationality)
                                <tr>
                                    <th>Nationality</th>
                                    <td>{{ $appointment->nationality }}</td>
                                </tr>
                                @endif
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
                                @if($appointment->notes)
                                <tr>
                                    <th>Notes</th>
                                    <td>{!! nl2br(e($appointment->notes)) !!}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Submitted At</th>
                                    <td>{{ $appointment->created_at->format('F j, Y g:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Update Status</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.appointments.update-status', $appointment) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        @foreach(\App\Models\Appointment::getStatuses() as $value => $label)
                                            <option value="{{ $value }}" {{ $appointment->status === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Update Status</button>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="mailto:{{ $appointment->email }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fa fa-envelope me-2"></i> Send Email
                            </a>
                            <a href="tel:{{ $appointment->phone }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fa fa-phone me-2"></i> Call
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $appointment->phone) }}" 
                               target="_blank" class="btn btn-outline-success w-100">
                                <i class="fab fa-whatsapp me-2"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

