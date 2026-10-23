<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')
                ->nullable()
                ->constrained('bookings')
                ->nullOnDelete();
            $table->foreignId('pickup_location_id')->constrained('locations');
            $table->foreignId('destination_id')->constrained('locations');
            $table->float('car_route_price');
            $table->string('car_type')->nullable();
            $table->integer('adults');
            $table->integer('children')->default(0);
            $table->boolean('oneway')->default(true);
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->date('return_date')->nullable();
            $table->time('return_time')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('nationality')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->float('currency_exchange_rate')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_rentals');
    }
};
