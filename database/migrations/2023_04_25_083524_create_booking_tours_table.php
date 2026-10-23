<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->float('adult_price');
            $table->float('child_price');
            $table->float('infant_price');
            $table->unsignedInteger('adults');
            $table->unsignedInteger('children');
            $table->unsignedInteger('infants');
            $table->longText('options')->nullable();
            $table->date('start_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_tours');
    }
};
