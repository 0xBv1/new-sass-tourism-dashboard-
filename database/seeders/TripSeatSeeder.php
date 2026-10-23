<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\TripSeat;

class TripSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = Trip::all();

        foreach ($trips as $trip) {
            // Check if seats already exist for this trip
            $existingSeats = TripSeat::where('trip_id', $trip->id)->count();
            
            if ($existingSeats === 0 && $trip->total_seats > 0) {
                TripSeat::generateSeatsForTrip($trip->id, $trip->total_seats);
                $this->command->info("Generated {$trip->total_seats} seats for trip: {$trip->trip_name}");
            }
        }

        $this->command->info('Trip seats seeding completed!');
    }
}
