<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->boolean('extra_bed_available')->default(false)->after('night_price');
            $table->decimal('extra_bed_price', 10, 2)->default(0)->after('extra_bed_available');
            $table->unsignedMediumInteger('max_extra_beds')->default(1)->after('extra_bed_price');
            $table->text('extra_bed_description')->nullable()->after('max_extra_beds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn([
                'extra_bed_available',
                'extra_bed_price',
                'max_extra_beds',
                'extra_bed_description'
            ]);
        });
    }
};
