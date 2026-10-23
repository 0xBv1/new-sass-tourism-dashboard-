<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seos', function (Blueprint $table) {
            $table->id();
            $table->morphs('seo');
            $table->text('og_image')->nullable();
            $table->string('og_type')->default('article')->nullable();
            $table->string('viewport', 255)->nullable()->default('width=device-width, initial-scale=1');
            $table->string('robots', 255)->nullable()->default('index, follow');
            $table->string('twitter_card')->nullable();
            $table->string('twitter_image')->nullable();
            $table->string('twitter_creator')->nullable();
            $table->timestamps();
        });
        Schema::create('seo_translations', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->longText('og_description')->nullable();
            $table->string('canonical')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->text('structure_schema')->nullable();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('seo_id')->constrained('seos')->cascadeOnDelete();
            $table->unique(['seo_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_translations');
        Schema::dropIfExists('seos');
    }
};
