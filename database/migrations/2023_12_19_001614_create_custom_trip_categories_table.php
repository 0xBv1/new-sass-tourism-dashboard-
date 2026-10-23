<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customized_trip_categories', function (Blueprint $table) {
            $table->id();
        });
        Schema::create('customized_trip_category_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->unsignedBigInteger('customized_trip_category_id');
            $table->foreign('customized_trip_category_id','customized_trip_category_translation_id')
                ->references('id')
                ->on('customized_trip_categories')
                ->cascadeOnDelete();
            $table->unique(['customized_trip_category_id', 'locale'],'trip_category_translation_unique');
            $table->string('title')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customized_trip_category_translations');
        Schema::dropIfExists('customized_trip_categories');
    }
};
