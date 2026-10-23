<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('display_order')->default(0)->index();
            $table->string('slug')->nullable();
            $table->string('code')->nullable();
            $table->float('rates')->default(0);
            $table->integer('reviews_number')->default(0);
            $table->float('adult_price');
            $table->float('child_price');
            $table->float('infant_price')->default(0);
            $table->longText('pricing_groups')->nullable();
            $table->boolean('enabled')->default(true);
            $table->boolean('featured')->default(false);
            $table->string('featured_image')->nullable();
            $table->longText('gallery')->nullable();
            $table->unsignedInteger('duration_in_days')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tour_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->string('title')->nullable();
            $table->string('duration')->nullable();
            $table->string('type')->nullable();
            $table->string('run')->nullable();
            $table->string('pickup_time')->nullable();
            $table->longText('overview')->nullable();
            $table->longText('highlights')->nullable();
            $table->longText('included')->nullable();
            $table->longText('excluded')->nullable();
            $table->unique(['tour_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_translations');
        Schema::dropIfExists('tours');
    }
};
