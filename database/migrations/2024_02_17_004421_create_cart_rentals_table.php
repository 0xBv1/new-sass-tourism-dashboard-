<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cart_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('pickup_location_id')->constrained('locations')->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained('locations')->cascadeOnDelete();
            $table->longText('stops')->nullable();
            $table->float('car_route_price');
            $table->string('car_type')->nullable();
            $table->integer('adults');
            $table->integer('children')->default(0);
            $table->boolean('oneway')->default(true);
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->date('return_date')->nullable();
            $table->time('return_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_rentals');
    }
};
