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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->enum('trip_type', ['one_way', 'round_trip', 'special_discount']);
            $table->string('departure_city');
            $table->string('arrival_city');
            $table->date('travel_date');
            $table->date('return_date')->nullable();
            $table->decimal('seat_price', 10, 2);
            $table->integer('total_seats');
            $table->integer('available_seats');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->text('additional_notes')->nullable();
            $table->text('amenities')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['trip_type', 'enabled']);
            //$table->index(['departure_city', 'arrival_city'], 'dept_arr_city_idx'); too long index on server
            $table->index('travel_date');
            $table->index('arrival_city');
            $table->index('departure_city');
            $table->index('available_seats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
