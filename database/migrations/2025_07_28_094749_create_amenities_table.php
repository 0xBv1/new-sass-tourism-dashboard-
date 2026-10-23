<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('icon_name')->nullable();
            $table->timestamps();
        });

        Schema::create('amenity_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->unique(['amenity_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenity_translations');
        Schema::dropIfExists('amenities');
    }
};
