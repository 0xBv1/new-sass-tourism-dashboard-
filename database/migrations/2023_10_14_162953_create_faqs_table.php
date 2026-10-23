<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->string('tag')->nullable()->default('general');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('faq_translations', function (Blueprint $table) {
            $table->id();
            $table->text('question')->nullable();
            $table->longText('answer')->nullable();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('faq_id')->constrained('faqs')->cascadeOnDelete();
            $table->unique(['faq_id', 'locale']);
            $table->index(['faq_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
