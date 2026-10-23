<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('active')->default(true);
            $table->foreignId('parent_id')->nullable()
            ->constrained('blog_categories')->nullOnDelete();
            $table->timestamps();
        });
        Schema::create('blog_category_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('blog_category_id')->constrained('blog_categories')->cascadeOnDelete();
            $table->unique(['blog_category_id', 'locale']);
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_category_translations');
        Schema::dropIfExists('blog_categories');
    }
};
