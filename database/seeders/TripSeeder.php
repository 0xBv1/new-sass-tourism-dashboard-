<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting trip seeder...');
        
        // Get all cities
        $cities = City::all();
        
        if ($cities->isEmpty()) {
            $this->command->error('No cities found. Please run CitySeeder first.');
            return;
        }

        $this->command->info('Found ' . $cities->count() . ' cities. Generating trips...');

        // Generate trips for the next 6 months (180 days)
        $this->generateTrips($cities, 180);
        
        $this->command->info('Trip seeder completed successfully!');
        $this->command->info('Total trips created: ' . Trip::count());
    }

    private function generateTrips($cities, int $days): void
    {
        $tripTypes = ['one_way', 'round_trip', 'special_discount'];
        $tripTypeWeights = [60, 25, 15]; // Percentage distribution
        
        $amenities = [
            ['Wi-Fi', 'AC', 'Water'],
            ['Wi-Fi', 'AC', 'TV', 'USB_Charging'],
            ['Wi-Fi', 'AC', 'Snacks', 'Blanket'],
            ['Wi-Fi', 'AC', 'Water', 'Pillow'],
            ['Wi-Fi', 'AC', 'TV', 'USB_Charging', 'Snacks'],
            ['Wi-Fi', 'AC', 'Premium_Seating', 'Refreshments'],
            ['Wi-Fi', 'AC', 'Entertainment_System', 'Comfort_Plus'],
            ['Wi-Fi', 'AC', 'Business_Class', 'Priority_Boarding'],
            ['Wi-Fi', 'AC', 'First_Class', 'Luxury_Amenities'],
            ['Wi-Fi', 'AC', 'VIP_Service', 'Exclusive_Lounge'],
        ];

        $departureTimes = [
            '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30',
            '21:00', '21:30', '22:00', '22:30', '23:00', '23:30', '00:00', '00:30', '01:00', '01:30',
            '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30'
        ];

        $prices = [
            // Economy prices
            25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100,
            // Standard prices
            110, 120, 130, 140, 150, 160, 170, 180, 190, 200, 220, 240, 260, 280, 300,
            // Premium prices
            350, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000,
            // Luxury prices
            1200, 1400, 1600, 1800, 2000, 2500, 3000, 3500, 4000, 5000
        ];

        $seatCounts = [12, 15, 18, 20, 22, 25, 28, 30, 32, 35, 38, 40, 42, 45, 48, 50, 55, 60, 65, 70, 75, 80];

        // Generate trips for each day
        $startDate = Carbon::today();
        
        for ($day = 0; $day < $days; $day++) {
            $currentDate = $startDate->copy()->addDays($day);
            
            // Generate 5-15 trips per day (more on weekends and holidays)
            $isWeekend = $currentDate->isWeekend();
            $isHoliday = $this->isHoliday($currentDate);
            
            $tripsPerDay = $isWeekend || $isHoliday ? rand(12, 20) : rand(8, 15);
            
            for ($trip = 0; $trip < $tripsPerDay; $trip++) {
                $this->createTrip($cities, $currentDate, $tripTypes, $tripTypeWeights, $amenities, $departureTimes, $prices, $seatCounts);
            }
            
            // Progress indicator
            if ($day % 30 === 0) {
                $this->command->info("Generated trips for " . $currentDate->format('M Y') . " (Day " . ($day + 1) . "/$days)");
            }
        }
    }

    private function createTrip($cities, $currentDate, $tripTypes, $tripTypeWeights, $amenities, $departureTimes, $prices, $seatCounts): void
    {
        $departureCity = $cities->random();
        $arrivalCity = $cities->random();
        
        // Ensure departure and arrival cities are different
        while ($departureCity->id === $arrivalCity->id) {
            $arrivalCity = $cities->random();
        }

        // Select trip type based on weights
        $tripType = $this->weightedRandom($tripTypes, $tripTypeWeights);
        
        $departureTime = $departureTimes[array_rand($departureTimes)];
        $price = $prices[array_rand($prices)];
        $totalSeats = $seatCounts[array_rand($seatCounts)];
        $availableSeats = rand(5, $totalSeats);
        $amenitiesList = $amenities[array_rand($amenities)];

        // Calculate arrival time (1-8 hours after departure)
        $departureDateTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $departureTime);
        $duration = rand(1, 8);
        $arrivalDateTime = $departureDateTime->copy()->addHours($duration);

        // For round trips, add return date
        $returnDate = null;
        if ($tripType === 'round_trip') {
            $returnDate = $currentDate->copy()->addDays(rand(1, 14));
        }

        // Special discount trips have lower prices
        if ($tripType === 'special_discount') {
            $price = $price * rand(60, 80) / 100; // 20-40% discount
        }

        // Weekend and holiday pricing
        if ($currentDate->isWeekend() || $this->isHoliday($currentDate)) {
            $price = $price * rand(110, 130) / 100; // 10-30% premium
        }

        // Create the trip
        Trip::create([
            'trip_type' => $tripType,
            'trip_name' => $departureCity->name . ' to ' . $arrivalCity->name,
            'departure_city_id' => $departureCity->id,
            'arrival_city_id' => $arrivalCity->id,
            'travel_date' => $currentDate,
            'return_date' => $returnDate,
            'seat_price' => round($price, 2),
            'available_seats' => $availableSeats,
            'total_seats' => $totalSeats,
            'departure_time' => $departureDateTime,
            'arrival_time' => $arrivalDateTime,
            'additional_notes' => $this->generateAdditionalNotes($tripType, $price, $currentDate),
            'amenities' => $amenitiesList,
            'enabled' => true,
        ]);
    }

    private function weightedRandom($items, $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($items as $index => $item) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $item;
            }
        }
        
        return $items[0]; // Fallback
    }

    private function isHoliday($date): bool
    {
        $month = $date->month;
        $day = $date->day;
        
        // Egyptian holidays and peak seasons
        $holidays = [
            '01-01', // New Year
            '01-07', // Coptic Christmas
            '04-25', // Sinai Liberation Day
            '05-01', // Labor Day
            '07-23', // Revolution Day
            '09-11', // Coptic New Year
            '10-06', // Armed Forces Day
            '12-25', // Christmas
        ];
        
        $dateString = sprintf('%02d-%02d', $month, $day);
        return in_array($dateString, $holidays);
    }

    private function generateAdditionalNotes(string $tripType, float $price, $date): ?string
    {
        $notes = [];

        if ($tripType === 'special_discount') {
            $notes[] = 'Limited time offer!';
            $notes[] = 'Special discount applied.';
        }

        if ($price > 1000) {
            $notes[] = 'Premium luxury service with exclusive amenities.';
        } elseif ($price > 500) {
            $notes[] = 'Premium service with extra comfort.';
        } elseif ($price > 200) {
            $notes[] = 'Standard service with modern amenities.';
        } else {
            $notes[] = 'Economy option for budget travelers.';
        }

        if ($date->isWeekend()) {
            $notes[] = 'Weekend service - popular route.';
        }

        if ($this->isHoliday($date)) {
            $notes[] = 'Holiday service - advance booking recommended.';
        }

        if (rand(1, 10) === 1) {
            $notes[] = 'Direct route - no stops.';
        }

        if (rand(1, 15) === 1) {
            $notes[] = 'Pet-friendly vehicle available.';
        }

        if (rand(1, 20) === 1) {
            $notes[] = 'Wheelchair accessible.';
        }

        if (rand(1, 25) === 1) {
            $notes[] = 'Family-friendly service.';
        }

        return empty($notes) ? null : implode(' ', $notes);
    }
}
