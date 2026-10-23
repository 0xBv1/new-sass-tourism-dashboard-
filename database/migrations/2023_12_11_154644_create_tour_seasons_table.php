<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tour_seasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('start_day');
            $table->unsignedSmallInteger('start_month');
            $table->unsignedSmallInteger('end_day');
            $table->unsignedSmallInteger('end_month');
            $table->longText('pricing_groups');
            $table->boolean('enabled')->default(true);
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_seasons');
    }
};
