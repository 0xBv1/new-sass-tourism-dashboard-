<?php

namespace Database\Seeders;

use App\Models\TripBooking;
use App\Models\Trip;
use App\Models\Client;
use Illuminate\Database\Seeder;

class TripBookingSeeder extends Seeder
{
    public function run(): void
    {
        // Get some trips and clients for creating bookings
        $trips = Trip::take(5)->get();
        $clients = Client::take(3)->get();

        if ($trips->isEmpty()) {
            $this->command->warn('No trips found. Please run TripSeeder first.');
            return;
        }

        $statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        $passengerNames = [
            'Ahmed Hassan',
            'Fatima Ali',
            'Mohammed Omar',
            'Aisha Khalil',
            'Omar Ibrahim',
            'Layla Ahmed',
            'Youssef Mahmoud',
            'Nour El-Din',
            'Mariam Hassan',
            'Khalil Ali'
        ];

        $passengerEmails = [
            'ahmed.hassan@email.com',
            'fatima.ali@email.com',
            'mohammed.omar@email.com',
            'aisha.khalil@email.com',
            'omar.ibrahim@email.com',
            'layla.ahmed@email.com',
            'youssef.mahmoud@email.com',
            'nour.eldin@email.com',
            'mariam.hassan@email.com',
            'khalil.ali@email.com'
        ];

        $passengerPhones = [
            '+201234567890',
            '+201234567891',
            '+201234567892',
            '+201234567893',
            '+201234567894',
            '+201234567895',
            '+201234567896',
            '+201234567897',
            '+201234567898',
            '+201234567899'
        ];

        for ($i = 0; $i < 20; $i++) {
            $trip = $trips->random();
            $client = $clients->random();
            $passengerIndex = $i % count($passengerNames);
            
            $numberOfPassengers = rand(1, 4);
            
            // Calculate total price
            $totalPrice = $trip->seat_price * $numberOfPassengers;

            TripBooking::create([
                'trip_id' => $trip->id,
                'client_id' => $client->id,
                'passenger_name' => $passengerNames[$passengerIndex],
                'passenger_email' => $passengerEmails[$passengerIndex],
                'passenger_phone' => $passengerPhones[$passengerIndex],
                'booking_date' => now()->addDays(rand(1, 30)),
                'number_of_passengers' => $numberOfPassengers,
                'total_price' => $totalPrice,
                'status' => $statuses[array_rand($statuses)],
                'booking_reference' => TripBooking::generateBookingReference(),
                'notes' => rand(0, 1) ? 'Special request: Window seat preferred' : null,
            ]);
        }

        $this->command->info('Sample trip bookings created successfully!');
    }
}
