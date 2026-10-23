<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BulkDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting bulk data seeder...');
        
        // Get all cities
        $cities = City::all();
        
        if ($cities->isEmpty()) {
            $this->command->error('No cities found. Please run CitySeeder first.');
            return;
        }

        $this->command->info('Found ' . $cities->count() . ' cities. Generating bulk data...');

        // Generate trips for the next 12 months (365 days)
        $this->generateBulkTrips($cities, 365);
        
        $this->command->info('Bulk data seeder completed successfully!');
        $this->command->info('Total trips created: ' . Trip::count());
    }

    private function generateBulkTrips($cities, int $days): void
    {
        $tripTypes = ['one_way', 'round_trip', 'special_discount'];
        $tripTypeWeights = [55, 30, 15]; // Adjusted for more round trips
        
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
            ['Wi-Fi', 'AC', 'Premium_Meals', 'Alcoholic_Beverages'],
            ['Wi-Fi', 'AC', 'Massage_Chair', 'Sleeping_Pod'],
            ['Wi-Fi', 'AC', 'Conference_Room', 'Business_Center'],
            ['Wi-Fi', 'AC', 'Kids_Zone', 'Entertainment_System'],
            ['Wi-Fi', 'AC', 'Pet_Area', 'Pet_Amenities'],
        ];

        $departureTimes = [
            '00:00', '00:15', '00:30', '00:45', '01:00', '01:15', '01:30', '01:45',
            '02:00', '02:15', '02:30', '02:45', '03:00', '03:15', '03:30', '03:45',
            '04:00', '04:15', '04:30', '04:45', '05:00', '05:15', '05:30', '05:45',
            '06:00', '06:15', '06:30', '06:45', '07:00', '07:15', '07:30', '07:45',
            '08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45',
            '10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45',
            '12:00', '12:15', '12:30', '12:45', '13:00', '13:15', '13:30', '13:45',
            '14:00', '14:15', '14:30', '14:45', '15:00', '15:15', '15:30', '15:45',
            '16:00', '16:15', '16:30', '16:45', '17:00', '17:15', '17:30', '17:45',
            '18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30', '19:45',
            '20:00', '20:15', '20:30', '20:45', '21:00', '21:15', '21:30', '21:45',
            '22:00', '22:15', '22:30', '22:45', '23:00', '23:15', '23:30', '23:45',
        ];

        $prices = [
            // Economy prices (25-100)
            25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100,
            // Standard prices (110-300)
            110, 120, 130, 140, 150, 160, 170, 180, 190, 200, 220, 240, 260, 280, 300,
            // Premium prices (350-1000)
            350, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000,
            // Luxury prices (1200-10000)
            1200, 1400, 1600, 1800, 2000, 2500, 3000, 3500, 4000, 5000, 6000, 7000, 8000, 9000, 10000
        ];

        $seatCounts = [8, 12, 15, 18, 20, 22, 25, 28, 30, 32, 35, 38, 40, 42, 45, 48, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100];

        // Generate trips for each day
        $startDate = Carbon::today();
        
        for ($day = 0; $day < $days; $day++) {
            $currentDate = $startDate->copy()->addDays($day);
            
            // Generate more trips per day for bulk data
            $isWeekend = $currentDate->isWeekend();
            $isHoliday = $this->isHoliday($currentDate);
            $isPeakSeason = $this->isPeakSeason($currentDate);
            
            $baseTrips = $isWeekend || $isHoliday ? rand(20, 35) : rand(15, 25);
            $tripsPerDay = $isPeakSeason ? $baseTrips * 2 : $baseTrips;
            
            // Use database transactions for better performance
            \DB::beginTransaction();
            
            try {
                for ($trip = 0; $trip < $tripsPerDay; $trip++) {
                    $this->createBulkTrip($cities, $currentDate, $tripTypes, $tripTypeWeights, $amenities, $departureTimes, $prices, $seatCounts);
                }
                
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                $this->command->error('Error creating trips for day ' . $day . ': ' . $e->getMessage());
            }
            
            // Progress indicator
            if ($day % 30 === 0) {
                $this->command->info("Generated trips for " . $currentDate->format('M Y') . " (Day " . ($day + 1) . "/$days) - Total trips: " . Trip::count());
            }
        }
    }

    private function createBulkTrip($cities, $currentDate, $tripTypes, $tripTypeWeights, $amenities, $departureTimes, $prices, $seatCounts): void
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

        // Calculate arrival time (1-12 hours after departure)
        $departureDateTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $departureTime);
        $duration = rand(1, 12);
        $arrivalDateTime = $departureDateTime->copy()->addHours($duration);

        // For round trips, add return date
        $returnDate = null;
        if ($tripType === 'round_trip') {
            $returnDate = $currentDate->copy()->addDays(rand(1, 30));
        }

        // Apply pricing modifiers
        $price = $this->applyPricingModifiers($price, $tripType, $currentDate);

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
            'additional_notes' => $this->generateBulkNotes($tripType, $price, $currentDate),
            'amenities' => $amenitiesList,
            'enabled' => true,
        ]);
    }

    private function applyPricingModifiers(float $price, string $tripType, $date): float
    {
        // Special discount trips have lower prices
        if ($tripType === 'special_discount') {
            $price = $price * rand(50, 75) / 100; // 25-50% discount
        }

        // Weekend and holiday pricing
        if ($date->isWeekend() || $this->isHoliday($date)) {
            $price = $price * rand(115, 140) / 100; // 15-40% premium
        }

        // Peak season pricing
        if ($this->isPeakSeason($date)) {
            $price = $price * rand(120, 150) / 100; // 20-50% premium
        }

        // Early morning and late night discounts
        $hour = $date->hour;
        if ($hour < 6 || $hour > 22) {
            $price = $price * rand(80, 95) / 100; // 5-20% discount
        }

        return $price;
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

    private function isPeakSeason($date): bool
    {
        $month = $date->month;
        $day = $date->day;
        
        // Peak seasons (summer, winter holidays, spring break)
        $peakSeasons = [
            // Summer (June-August)
            [6, 7, 8],
            // Winter holidays (December-January)
            [12, 1],
            // Spring break (March-April)
            [3, 4],
            // Eid holidays (approximate dates)
            [5, 6, 7], // Ramadan and Eid periods
        ];
        
        foreach ($peakSeasons as $season) {
            if (in_array($month, $season)) {
                return true;
            }
        }
        
        return false;
    }

    private function generateBulkNotes(string $tripType, float $price, $date): ?string
    {
        $notes = [];

        if ($tripType === 'special_discount') {
            $notes[] = 'Limited time offer!';
            $notes[] = 'Special discount applied.';
        }

        if ($price > 2000) {
            $notes[] = 'Ultra-premium luxury service with exclusive amenities.';
        } elseif ($price > 1000) {
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

        if ($this->isPeakSeason($date)) {
            $notes[] = 'Peak season service - high demand expected.';
        }

        if (rand(1, 8) === 1) {
            $notes[] = 'Direct route - no stops.';
        }

        if (rand(1, 12) === 1) {
            $notes[] = 'Pet-friendly vehicle available.';
        }

        if (rand(1, 15) === 1) {
            $notes[] = 'Wheelchair accessible.';
        }

        if (rand(1, 20) === 1) {
            $notes[] = 'Family-friendly service.';
        }

        if (rand(1, 25) === 1) {
            $notes[] = 'Business traveler preferred.';
        }

        if (rand(1, 30) === 1) {
            $notes[] = 'Student discount available.';
        }

        return empty($notes) ? null : implode(' ', $notes);
    }
} 