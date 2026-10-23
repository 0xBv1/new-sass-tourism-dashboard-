<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tour_destinations', function (Blueprint $table) {
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->primary([
                'destination_id',
                'tour_id',
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_destinations');
    }
};
