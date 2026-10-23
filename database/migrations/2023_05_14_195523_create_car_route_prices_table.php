<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_route_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_route_id')->constrained('car_routes')->cascadeOnDelete();
            $table->string('car_type')->nullable();
            $table->unsignedInteger('from');
            $table->unsignedInteger('to');
            $table->float('oneway_price');
            $table->float('rounded_price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_route_prices');
    }
};
