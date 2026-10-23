<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trip_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->integer('seat_number');
            $table->boolean('is_available')->default(true);
            $table->foreignId('booking_id')->nullable()->constrained('trip_bookings')->onDelete('set null');
            $table->timestamps();

            // Ensure unique seat number per trip
            $table->unique(['trip_id', 'seat_number']);
            
            // Index for performance
            $table->index(['trip_id', 'is_available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_seats');
    }
};
