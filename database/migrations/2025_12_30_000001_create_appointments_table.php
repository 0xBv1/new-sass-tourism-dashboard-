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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('nickname')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('country_phone_code')->nullable();
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->date('arrival_date');
            $table->date('departure_date');
            $table->text('notes')->nullable();
            $table->string('nationality')->nullable();
            $table->string('hotel_choice')->nullable();
            $table->string('age_range')->nullable();
            $table->string('hear_about_us')->nullable();
            // Nile cruise specific fields
            $table->string('cruise_type')->nullable();
            $table->string('cruise_pick_drop_off')->nullable();
            $table->string('cruise_duration')->nullable();
            // Type and status
            $table->string('type')->default('appointment'); // appointment, nile_cruise, plan_trip
            $table->string('status')->default('pending'); // pending, contacted, confirmed, cancelled
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

