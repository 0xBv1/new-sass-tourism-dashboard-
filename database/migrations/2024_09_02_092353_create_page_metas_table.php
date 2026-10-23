<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('meta_key');
        });
        Schema::create('page_meta_translations', function (Blueprint $table) {
            $table->id();
            $table->text('meta_value')->nullable();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('page_meta_id')->constrained('page_metas')->cascadeOnDelete();
            $table->unique(['page_meta_id', 'locale']);
            $table->index(['page_meta_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_meta_translations');
        Schema::dropIfExists('page_metas');
    }
};
