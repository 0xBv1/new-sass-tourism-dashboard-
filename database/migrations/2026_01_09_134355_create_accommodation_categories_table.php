<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('accommodation_categories')) {
        Schema::create('accommodation_categories', function (Blueprint $table) {
            $table->id();
                $table->string('slug')->unique();
                $table->unsignedSmallInteger('display_order')->default(0);
                $table->boolean('enabled')->default(true);
                $table->dateTime('translated_at')->nullable();
                $table->softDeletes();
            $table->timestamps();
        });
        }

        if (!Schema::hasTable('accommodation_category_translations')) {
            Schema::create('accommodation_category_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('accommodation_category_id')
                    ->constrained('accommodation_categories')
                    ->cascadeOnDelete()
                    ->name('acc_cat_trans_cat_id_foreign');
                $table->string('locale', 10)->index()->default(config('app.locale'));
                $table->string('name');
                $table->unique(['accommodation_category_id', 'locale'], 'acc_cat_trans_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_category_translations');
        Schema::dropIfExists('accommodation_categories');
    }
};
