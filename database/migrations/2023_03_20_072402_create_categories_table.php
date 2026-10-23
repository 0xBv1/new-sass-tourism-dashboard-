<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->text('slug')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->unsignedMediumInteger('display_order')->default(0);
            $table->boolean('enabled')->default(true);
            $table->boolean('featured')->default(false);
            $table->string('banner')->nullable();
            $table->string('featured_image')->nullable();
            $table->longText('gallery')->nullable();
            $table->dateTime('translated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->unique(['category_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('categories');
    }
};
