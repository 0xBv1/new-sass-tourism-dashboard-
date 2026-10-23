<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->text('slug')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('destinations')->nullOnDelete();
            $table->unsignedMediumInteger('display_order')->default(0)->index();
            $table->boolean('global')->default(false);
            $table->boolean('enabled')->default(true);
            $table->boolean('featured')->default(false);
            $table->string('banner')->nullable();
            $table->string('featured_image')->nullable();
            $table->longText('gallery')->nullable();
            $table->dateTime('translated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('destination_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->unique(['destination_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('destination_translations');
        Schema::dropIfExists('destinations');
    }
};
