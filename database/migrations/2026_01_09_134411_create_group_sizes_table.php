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
        if (!Schema::hasTable('group_sizes')) {
        Schema::create('group_sizes', function (Blueprint $table) {
            $table->id();
                $table->string('slug')->unique();
                $table->unsignedSmallInteger('min_people')->nullable();
                $table->unsignedSmallInteger('max_people')->nullable();
                $table->unsignedSmallInteger('display_order')->default(0);
                $table->boolean('enabled')->default(true);
                $table->dateTime('translated_at')->nullable();
                $table->softDeletes();
            $table->timestamps();
        });
        }

        if (!Schema::hasTable('group_size_translations')) {
            Schema::create('group_size_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('group_size_id')
                    ->constrained('group_sizes')
                    ->cascadeOnDelete()
                    ->name('group_size_trans_size_id_foreign');
                $table->string('locale', 10)->index()->default(config('app.locale'));
                $table->string('name');
                $table->unique(['group_size_id', 'locale'], 'group_size_trans_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_size_translations');
        Schema::dropIfExists('group_sizes');
    }
};
