<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('durations', function (Blueprint $table) {
            $table->id();
            $table->text('slug')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('durations')->nullOnDelete();
            $table->unsignedMediumInteger('display_order')->default(0)->index();
            $table->boolean('enabled')->default(true);
            $table->boolean('featured')->default(false);
            $table->string('banner')->nullable();
            $table->string('featured_image')->nullable();
            $table->text('gallery')->nullable();
            $table->unsignedInteger('tours_count')->default(0);
            $table->integer('days')->nullable(); // Number of days for this duration
            $table->integer('nights')->nullable(); // Number of nights for this duration
            $table->string('duration_type')->default('days'); // days, hours, weeks, months
            $table->dateTime('translated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('duration_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('locale')->index()->default(config('app.locale'));
            $table->foreignId('duration_id')->constrained('durations')->cascadeOnDelete();
            $table->unique(['duration_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('duration_translations');
        Schema::dropIfExists('durations');
    }
}; 
