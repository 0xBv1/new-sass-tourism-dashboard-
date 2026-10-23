<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pickup_location_id')->constrained('locations');
            $table->foreignId('destination_id')->constrained('locations');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_routes');
    }
};
