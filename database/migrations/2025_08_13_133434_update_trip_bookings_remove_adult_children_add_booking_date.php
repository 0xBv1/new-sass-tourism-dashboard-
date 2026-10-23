<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns exist before dropping them using raw query
        $adultsCountExists = $this->columnExists('trip_bookings', 'adults_count');
        $childrenCountExists = $this->columnExists('trip_bookings', 'children_count');
        $bookingDateExists = $this->columnExists('trip_bookings', 'booking_date');

        // Drop columns if they exist using raw SQL
        if ($adultsCountExists) {
            try {
                DB::statement("ALTER TABLE trip_bookings DROP COLUMN adults_count");
            } catch (\Exception $e) {
                // Column might not exist, ignore
            }
        }

        if ($childrenCountExists) {
            try {
                DB::statement("ALTER TABLE trip_bookings DROP COLUMN children_count");
            } catch (\Exception $e) {
                // Column might not exist, ignore
            }
        }

        // Add booking date column if it doesn't exist using raw SQL
        if (!$bookingDateExists) {
            try {
                DB::statement("ALTER TABLE trip_bookings ADD COLUMN booking_date DATE NULL AFTER passenger_phone");
            } catch (\Exception $e) {
                // Column might already exist, ignore
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $bookingDateExists = $this->columnExists('trip_bookings', 'booking_date');
        $adultsCountExists = $this->columnExists('trip_bookings', 'adults_count');
        $childrenCountExists = $this->columnExists('trip_bookings', 'children_count');

        // Remove the booking date column if it exists
        if ($bookingDateExists) {
            try {
                DB::statement("ALTER TABLE trip_bookings DROP COLUMN booking_date");
            } catch (\Exception $e) {
                // Ignore errors
            }
        }

        // Add back the removed columns if they don't exist
        if (!$adultsCountExists) {
            try {
                DB::statement("ALTER TABLE trip_bookings ADD COLUMN adults_count INT DEFAULT 0 AFTER passenger_phone");
            } catch (\Exception $e) {
                // Ignore errors
            }
        }

        if (!$childrenCountExists) {
            try {
                DB::statement("ALTER TABLE trip_bookings ADD COLUMN children_count INT DEFAULT 0 AFTER adults_count");
            } catch (\Exception $e) {
                // Ignore errors
            }
        }
    }

    /**
     * Check if column exists using raw query to avoid MySQL version compatibility issues
     */
    private function columnExists(string $table, string $column): bool
    {
        try {
            $result = DB::selectOne(
                "SELECT COUNT(*) as count FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?",
                [$table, $column]
            );
            return $result && $result->count > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
};
