<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('trip_name')->nullable()->after('trip_type');
        });

        // Populate trip_name for existing records
        $trips = \App\Models\Trip::with(['departureCity', 'arrivalCity'])->get();
        
        foreach ($trips as $trip) {
            $tripName = $trip->departureCity->name . ' to ' . $trip->arrivalCity->name;
            if ($trip->trip_type === 'round_trip') {
                $tripName .= ' (Round Trip)';
            } elseif ($trip->trip_type === 'special_discount') {
                $tripName .= ' (Special Discount)';
            }
            
            $trip->update(['trip_name' => $tripName]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('trip_name');
        });
    }
};
