<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\City;
use App\Http\Requests\Dashboard\TripRequest;
use App\DataTables\TripDataTable;

class TripController extends Controller
{
    public function index(TripDataTable $dataTable)
    {
        $this->authorize('trips.list');
        return $dataTable->render('dashboard.trips.index');
    }

    public function create()
    {
        $this->authorize('trips.create');
        $tripTypes = Trip::getTripTypes();
        $cities = City::orderBy('name')->get()->mapWithKeys(function($city) {
            return [$city->id => $city->name];
        });
        $amenities = [
            'Wi-Fi' => 'Wi-Fi',
            'Snacks' => 'Snacks',
            'AC' => 'Air Conditioning',
            'TV' => 'TV',
            'USB_Charging' => 'USB Charging',
            'Water' => 'Free Water',
            'Blanket' => 'Blanket',
            'Pillow' => 'Pillow'
        ];

        return view('dashboard.trips.create', compact('tripTypes', 'cities', 'amenities'));
    }

    public function store(TripRequest $request)
    {
        $this->authorize('trips.create');
        
        $data = $request->validated();
        $data['available_seats'] = $data['total_seats'];

        Trip::create($data);

        return redirect()->route('dashboard.trips.index')
            ->with('success', 'Trip created successfully.');
    }

    public function show(Trip $trip)
    {
        $this->authorize('trips.list');
        $trip->load(['bookings.client', 'departureCity', 'arrivalCity']);
        return view('dashboard.trips.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        $this->authorize('trips.edit');
        $tripTypes = Trip::getTripTypes();
        $cities = City::orderBy('name')->get()->mapWithKeys(function($city) {
            return [$city->id => $city->name];
        });
        $amenities = [
            'Wi-Fi' => 'Wi-Fi',
            'Snacks' => 'Snacks',
            'AC' => 'Air Conditioning',
            'TV' => 'TV',
            'USB_Charging' => 'USB Charging',
            'Water' => 'Free Water',
            'Blanket' => 'Blanket',
            'Pillow' => 'Pillow'
        ];

        return view('dashboard.trips.edit', compact('trip', 'tripTypes', 'cities', 'amenities'));
    }

    public function update(TripRequest $request, Trip $trip)
    {
        $this->authorize('trips.edit');
        
        $data = $request->validated();

        // If total_seats changed, adjust available_seats
        if ($data['total_seats'] != $trip->total_seats) {
            $difference = $data['total_seats'] - $trip->total_seats;
            $data['available_seats'] = $trip->available_seats + $difference;
        }

        $trip->update($data);

        return redirect()->route('dashboard.trips.index')
            ->with('success', 'Trip updated successfully.');
    }

    public function destroy(Trip $trip)
    {
        $this->authorize('trips.delete');
        
        // Check if trip has bookings
        if ($trip->bookings()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete trip with existing bookings.');
        }

        $trip->delete();

        return redirect()->route('dashboard.trips.index')
            ->with('success', 'Trip deleted successfully.');
    }

    public function toggleStatus(Trip $trip)
    {
        $this->authorize('trips.toggle-status');
        
        $trip->update(['enabled' => !$trip->enabled]);
        
        $status = $trip->enabled ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "Trip {$status} successfully.");
    }

    public function tripBookings(Trip $trip)
    {
        $this->authorize('trips.view-bookings');
        $trip->load(['bookings.client', 'departureCity', 'arrivalCity']);
        return view('dashboard.trips.bookings', compact('trip'));
    }

    public function getTripDetails(Trip $trip)
    {
        $this->authorize('trips.view');
        
        // Load relationships to ensure they're available
        $trip->load(['departureCity', 'arrivalCity']);
        
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
}
