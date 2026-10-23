<?php

use App\Enums\BlogStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->text('slug')->nullable();
            $table->string('featured_image')->nullable();
            $table->longText('gallery')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('translated_at')->nullable();
            $table->foreignId('published_by_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->enum('status', BlogStatus::all())->default(BlogStatus::PENDING->value)->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('blog_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->text('tags')->nullable();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('blog_id')->constrained('blogs')->cascadeOnDelete();
            $table->unique(['blog_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_translations');
        Schema::dropIfExists('blogs');
    }
};
