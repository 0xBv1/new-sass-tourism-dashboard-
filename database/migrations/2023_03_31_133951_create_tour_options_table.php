<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tour_options', function (Blueprint $table) {
            $table->id();
            $table->float('adult_price')->default(0);
            $table->float('child_price')->default(0);
            $table->longText('pricing_groups')->nullable();
            $table->dateTime('translated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('tour_option_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('tour_option_id')->constrained('tour_options')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unique(['tour_option_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_option_translations');
        Schema::dropIfExists('tour_options');
    }
};
