<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tour_durations', function (Blueprint $table) {
            $table->foreignId('duration_id')->constrained('durations')->cascadeOnDelete();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->primary([
                'duration_id',
                'tour_id',
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_durations');
    }
}; 
