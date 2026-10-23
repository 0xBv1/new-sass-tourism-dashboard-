<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->dateTime('translated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('location_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            $table->unique(['location_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_translations');
        Schema::dropIfExists('locations');
    }
};
