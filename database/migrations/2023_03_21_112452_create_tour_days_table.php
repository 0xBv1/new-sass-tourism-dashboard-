<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tour_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->dateTime('translated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tour_day_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_day_id')->constrained('tour_days')->cascadeOnDelete();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->unique(['tour_day_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_day_translations');
        Schema::dropIfExists('tour_days');
    }
};
