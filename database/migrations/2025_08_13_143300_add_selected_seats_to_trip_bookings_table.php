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
        $selectedSeatsExists = $this->columnExists('trip_bookings', 'selected_seats');

        if (!$selectedSeatsExists) {
            try {
                DB::statement("ALTER TABLE trip_bookings ADD COLUMN selected_seats TEXT NULL AFTER number_of_passengers");
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
        $selectedSeatsExists = $this->columnExists('trip_bookings', 'selected_seats');

        if ($selectedSeatsExists) {
            try {
                DB::statement("ALTER TABLE trip_bookings DROP COLUMN selected_seats");
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
