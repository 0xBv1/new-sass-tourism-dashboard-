<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('stars')->default(1);
            $table->boolean('enabled')->default(false);
            $table->string('featured_image')->nullable();
            $table->string('banner')->nullable();
            $table->longText('gallery')->nullable();
            $table->string('address')->nullable();
            $table->text('map_iframe')->nullable();
            $table->string('slug')->unique();
            $table->string('phone_contact')->nullable();
            $table->string('whatsapp_contact')->nullable();
            $table->timestamps();
        });

        Schema::create('hotel_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unique(['hotel_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_translations');
        Schema::dropIfExists('hotels');
    }
};
