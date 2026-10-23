<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripBooking;
use App\Models\Client;
use App\Http\Requests\Dashboard\TripBookingRequest;
use App\DataTables\TripBookingDataTable;
use Illuminate\Http\Request;
use App\Models\TripSeat;

class TripBookingController extends Controller
{
    public function index(TripBookingDataTable $dataTable)
    {
        $this->authorize('trip-bookings.list');
        
        // Get trips for filter dropdown
        $trips = Trip::available()
            ->with(['departureCity', 'arrivalCity'])
            ->orderBy('travel_date')
            ->get();
            
        // Get booking statuses for filter dropdown
        $statuses = TripBooking::getStatuses();
        
        return $dataTable->render('dashboard.trip-bookings.index', compact('trips', 'statuses'));
    }

    public function create()
    {
        $this->authorize('trip-bookings.create');
        
        // Get available trips
        $trips = Trip::available()
            ->with(['departureCity', 'arrivalCity'])
            ->orderBy('travel_date')
            ->get()
            ->mapWithKeys(function($trip) {
                $label = sprintf(
                    '%s → %s | %s | %s | %s EGP | %d seats',
                    $trip->departure_city_name,
                    $trip->arrival_city_name,
                    $trip->travel_date->format('M d, Y'),
                    $trip->formatted_departure_time,
                    $trip->seat_price,
                    $trip->available_seats
                );
                return [$trip->id => $label];
            });

        // Get clients
        $clients = Client::orderBy('name')->pluck('name', 'id');

        // Get booking statuses
        $statuses = TripBooking::getStatuses();

        // Get pre-selected trip if provided
        $selectedTripId = request('trip_id');

        return view('dashboard.trip-bookings.create', compact('trips', 'clients', 'statuses', 'selectedTripId'));
    }

    public function store(TripBookingRequest $request)
    {
        $this->authorize('trip-bookings.create');
        
        $data = $request->validated();
        
        // Generate booking reference if not provided
        if (empty($data['booking_reference'])) {
            $data['booking_reference'] = TripBooking::generateBookingReference();
        }

        // Calculate total price based on selected seats
        $trip = Trip::find($data['trip_id']);
        if (isset($data['selected_seats']) && is_array($data['selected_seats'])) {
            $data['total_price'] = $trip->calculateTotalPriceForSeats($data['selected_seats']);
        } else {
            $data['total_price'] = $trip->calculateTotalPrice($data['number_of_passengers']);
        }

        // Create booking
        $booking = TripBooking::create($data);

        // Book the selected seats if provided
        if (isset($data['selected_seats']) && is_array($data['selected_seats'])) {
            TripSeat::bookSeats($trip->id, $data['selected_seats'], $booking->id);
        }

        // Update trip available seats
        $trip->decrement('available_seats', $data['number_of_passengers']);

        return redirect()->route('dashboard.trip-bookings.index')
            ->with('success', 'Trip booking created successfully.');
    }

    public function show(TripBooking $tripBooking)
    {
        $this->authorize('trip-bookings.view');
        $tripBooking->load(['trip.departureCity', 'trip.arrivalCity', 'client']);
        return view('dashboard.trip-bookings.show', compact('tripBooking'));
    }

    public function edit(TripBooking $tripBooking)
    {
        $this->authorize('trip-bookings.edit');
        
        $trips = Trip::with(['departureCity', 'arrivalCity'])->get();
        $clients = Client::all();
        
        return view('dashboard.trip-bookings.edit', compact('tripBooking', 'trips', 'clients'));
    }

    public function update(TripBookingRequest $request, TripBooking $tripBooking)
    {
        $this->authorize('trip-bookings.edit');
        
        $data = $request->validated();
        
        // Calculate passenger difference for seat management
        $oldPassengers = $tripBooking->number_of_passengers;
        $newPassengers = $data['number_of_passengers'];
        $passengerDifference = $newPassengers - $oldPassengers;
        
        // Calculate total price based on selected seats
        $trip = Trip::find($data['trip_id']);
        if (isset($data['selected_seats']) && is_array($data['selected_seats'])) {
            $data['total_price'] = $trip->calculateTotalPriceForSeats($data['selected_seats']);
        } else {
            $data['total_price'] = $trip->calculateTotalPrice($data['number_of_passengers']);
        }
        
        // Release old seats if they exist
        if ($tripBooking->booking_id) {
            TripSeat::releaseSeats($tripBooking->id);
        }
        
        // Update booking
        $tripBooking->update($data);

        // Book the new selected seats if provided
        if (isset($data['selected_seats']) && is_array($data['selected_seats'])) {
            TripSeat::bookSeats($trip->id, $data['selected_seats'], $tripBooking->id);
        }

        // Update trip available seats if passenger count changed
        if ($passengerDifference != 0) {
            if ($passengerDifference > 0) {
                // More passengers - decrease available seats
                $trip->decrement('available_seats', $passengerDifference);
            } else {
                // Fewer passengers - increase available seats
                $trip->increment('available_seats', abs($passengerDifference));
            }
        }

        return redirect()->route('dashboard.trip-bookings.index')
            ->with('success', 'Trip booking updated successfully.');
    }

    public function destroy(TripBooking $tripBooking)
    {
        $this->authorize('trip-bookings.delete');
        
        // Release seats
        if ($tripBooking->booking_id) {
            TripSeat::releaseSeats($tripBooking->id);
        }
        
        // Restore available seats
        $trip = $tripBooking->trip;
        $trip->increment('available_seats', $tripBooking->number_of_passengers);
        
        $tripBooking->delete();

        return redirect()->route('dashboard.trip-bookings.index')
            ->with('success', 'Trip booking deleted successfully.');
    }

    public function toggleStatus(TripBooking $tripBooking)
    {
        $this->authorize('trip-bookings.toggle-status');
        
        $newStatus = $tripBooking->status === 'confirmed' ? 'pending' : 'confirmed';
        $tripBooking->update(['status' => $newStatus]);
        
        $status = $newStatus === 'confirmed' ? 'confirmed' : 'pending';
        return redirect()->back()->with('success', "Booking {$status} successfully.");
    }

    public function cancel(TripBooking $tripBooking)
    {
        $this->authorize('trip-bookings.cancel');
        
        if ($tripBooking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Booking is already cancelled.');
        }
        
        // Restore available seats
        $trip = $tripBooking->trip;
        $trip->increment('available_seats', $tripBooking->number_of_passengers);
        
        $tripBooking->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }

    public function getTripDetails(Trip $trip)
    {
        $this->authorize('trip-bookings.create');
        
        return response()->json([
            'success' => true,
            'trip' => [
                'id' => $trip->id,
                'departure_city_name' => $trip->departure_city_name,
                'arrival_city_name' => $trip->arrival_city_name,
                'travel_date' => $trip->travel_date->format('M d, Y'),
                'departure_time' => $trip->formatted_departure_time,
                'arrival_time' => $trip->formatted_arrival_time,
                'seat_price' => (float) $trip->seat_price,
                'available_seats' => $trip->available_seats,
                'trip_type_label' => $trip->trip_type_label,
                'amenities' => $trip->amenities ?? [],
                'additional_notes' => $trip->additional_notes,
            ]
        ]);
    }

    public function export(Request $request)
    {
        $this->authorize('trip-bookings.export');
        
        $format = $request->get('format', 'csv');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $status = $request->get('status');

        $query = TripBooking::with(['trip.departureCity', 'trip.arrivalCity', 'client']);

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        $filename = 'trip_bookings_' . date('Y-m-d_H-i-s') . '.' . $format;

        if ($format === 'csv') {
            return $this->exportToCsv($bookings, $filename);
        } elseif ($format === 'excel') {
            return $this->exportToExcel($bookings, $filename);
        }

        return redirect()->back()->with('error', 'Invalid export format.');
    }

    public function invoice(TripBooking $tripBooking)
    {
        $this->authorize('trip-bookings.view');
        
        $tripBooking->load(['trip.departureCity', 'trip.arrivalCity', 'client']);
        
        return view('dashboard.trip-bookings.invoice', compact('tripBooking'));
    }

    private function exportToCsv($bookings, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Booking Reference',
                'Passenger Name',
                'Email',
                'Phone',
                'Trip',
                'From',
                'To',
                'Date',
                'Adults',
                'Children',
                'Total Passengers',
                'Total Price',
                'Status',
                'Created At'
            ]);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_reference,
                    $booking->passenger_name,
                    $booking->passenger_email,
                    $booking->passenger_phone,
                    $booking->trip->trip_type_label,
                    $booking->trip->departure_city_name,
                    $booking->trip->arrival_city_name,
                    $booking->trip->travel_date->format('Y-m-d'),
                    $booking->adults_count,
                    $booking->children_count,
                    $booking->number_of_passengers,
                    $booking->total_price,
                    $booking->status_label,
                    $booking->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($bookings, $filename)
    {
        // This would require a package like PhpSpreadsheet
        // For now, redirect to CSV export
        return redirect()->back()->with('error', 'Excel export not implemented yet.');
    }
}
