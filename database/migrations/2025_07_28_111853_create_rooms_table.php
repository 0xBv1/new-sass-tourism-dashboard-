<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->string('slug');
            $table->string('featured_image')->nullable();
            $table->string('banner')->nullable();
            $table->longText('gallery')->nullable();
            $table->boolean('enabled')->default(false);
            $table->unsignedMediumInteger('bed_count')->default(1);
            $table->unsignedMediumInteger('max_capacity')->default(1);
            $table->string('room_type')->comment('single,deluxe,suite');
            $table->string('bed_types')->comment('single,double,queen,king');
            $table->decimal('night_price')->default(0);
            $table->timestamps();
        });

        Schema::create('room_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unique(['room_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_translations');
        Schema::dropIfExists('rooms');
    }
};
