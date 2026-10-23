<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\City;

return new class extends Migration
{
    public function up(): void
    {
        // Check if new columns already exist using raw query to avoid MySQL version compatibility issues
        $departureCityIdExists = $this->columnExists('trips', 'departure_city_id');
        $arrivalCityIdExists = $this->columnExists('trips', 'arrival_city_id');

        // Add foreign keys if they don't exist using raw SQL to avoid introspection issues
        if (!$departureCityIdExists) {
            try {
                DB::statement("ALTER TABLE trips ADD COLUMN departure_city_id BIGINT UNSIGNED NULL AFTER trip_type");
                DB::statement("ALTER TABLE trips ADD CONSTRAINT trips_departure_city_id_foreign FOREIGN KEY (departure_city_id) REFERENCES cities(id) ON DELETE CASCADE");
            } catch (\Exception $e) {
                // Column might already exist, ignore
            }
        }

        if (!$arrivalCityIdExists) {
            try {
                DB::statement("ALTER TABLE trips ADD COLUMN arrival_city_id BIGINT UNSIGNED NULL AFTER departure_city_id");
                DB::statement("ALTER TABLE trips ADD CONSTRAINT trips_arrival_city_id_foreign FOREIGN KEY (arrival_city_id) REFERENCES cities(id) ON DELETE CASCADE");
            } catch (\Exception $e) {
                // Column might already exist, ignore
            }
        }

        // Drop the old columns if they exist using raw SQL
        $departureCityExists = $this->columnExists('trips', 'departure_city');
        $arrivalCityExists = $this->columnExists('trips', 'arrival_city');

        if ($departureCityExists) {
            try {
                DB::statement("ALTER TABLE trips DROP COLUMN departure_city");
            } catch (\Exception $e) {
                // Column might not exist or already dropped, ignore
            }
        }

        if ($arrivalCityExists) {
            try {
                DB::statement("ALTER TABLE trips DROP COLUMN arrival_city");
            } catch (\Exception $e) {
                // Column might not exist or already dropped, ignore
            }
        }
    }

    public function down(): void
    {
        // Re-add old columns if they don't exist
        $departureCityExists = $this->columnExists('trips', 'departure_city');
        $arrivalCityExists = $this->columnExists('trips', 'arrival_city');

        if (!$departureCityExists) {
            try {
                Schema::table('trips', function (Blueprint $table) {
                    $table->string('departure_city')->after('trip_type');
                });
            } catch (\Exception $e) {
                // Ignore errors
            }
        }

        if (!$arrivalCityExists) {
            try {
                Schema::table('trips', function (Blueprint $table) {
                    $table->string('arrival_city')->after('departure_city');
                });
            } catch (\Exception $e) {
                // Ignore errors
            }
        }

        // Drop foreign key columns if they exist
        $departureCityIdExists = $this->columnExists('trips', 'departure_city_id');
        $arrivalCityIdExists = $this->columnExists('trips', 'arrival_city_id');

        if ($departureCityIdExists) {
            try {
                Schema::table('trips', function (Blueprint $table) {
                    $table->dropForeign(['departure_city_id']);
                    $table->dropColumn('departure_city_id');
                });
            } catch (\Exception $e) {
                // Ignore errors
            }
        }

        if ($arrivalCityIdExists) {
            try {
                Schema::table('trips', function (Blueprint $table) {
                    $table->dropForeign(['arrival_city_id']);
                    $table->dropColumn('arrival_city_id');
                });
            } catch (\Exception $e) {
                // Ignore errors
            }
        }
    }

    /**
     * Check if column exists using raw query to avoid MySQL version compatibility issues
     * This avoids using Schema::hasColumn() which queries generation_expression (MySQL 5.7.6+)
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
            // If query fails, assume column doesn't exist
            return false;
        }
    }
};
